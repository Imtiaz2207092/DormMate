<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->apiKey = env('GROQ_API_KEY', '');$this->model = env('GROQ_MODEL', 'llama-3.3-70b-versatile');
    }

    public function generateExplanation(User $user, User $matchedUser, int$score): ?array
    {
        if (empty($this->apiKey)) {
            Log::error('Groq API key is not configured.');
            return null;
        }

        $prompt = $this->buildPrompt($user, $matchedUser,$score);

        try {
            // Groq API Endpoint
            $url = "https://api.groq.com/openai/v1/chat/completions";

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post($url, [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.2,
            ]);

            if ($response->failed()) {$errorBody = $response->json();$errorMsg = $errorBody['error']['message'] ?? $response->body();
                throw new \Exception("Groq API request failed: " . $errorMsg);
            }

            $result =$response->json();
            $text =$result['choices'][0]['message']['content'] ?? null;

            if (!$text) {
                throw new \Exception("Groq API returned an empty or invalid content structure.");
            }

            $cleanText = trim($text);$cleanText = preg_replace('/^```json\s*/i', '', $cleanText);
            $cleanText = preg_replace('/^```\s*/i', '', $cleanText);$cleanText = preg_replace('/```$/', '', $cleanText);
            $cleanText = trim($cleanText);

            $explanation = json_decode($cleanText, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $firstBracket = strpos($cleanText, '{');
                $lastBracket = strrpos($cleanText, '}');
                
                if ($firstBracket !== false && $lastBracket !== false) {
                    $cleanText = substr($cleanText, $firstBracket, ($lastBracket - $firstBracket) + 1);
                    $explanation = json_decode($cleanText, true);
                }
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception("Failed to parse Groq response as JSON: " . $text);
                }
            }

            return [
                'summary' => $explanation['summary'] ?? 'No summary generated.',
                'strengths' => is_array($explanation['strengths'] ?? null) ? $explanation['strengths'] : [],
                'differences' => is_array($explanation['differences'] ?? null) ? $explanation['differences'] : [],
                'suggestions' => is_array($explanation['suggestions'] ?? null) ? $explanation['suggestions'] : [],
                'recommendation' => $explanation['recommendation'] ?? 'Neutral',
                'comparison' => is_array($explanation['comparison'] ?? null) ? $explanation['comparison'] : [],
            ];

        } catch (\Exception $e) {
            Log::error('Error calling Groq API: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function buildPrompt(User $user, User $matchedUser, int $score): string
    {
        $profile = $user->studentProfile;
        $pref = $user->studentPreference;

        $mProfile = $matchedUser->studentProfile;
        $mPref = $matchedUser->studentPreference;

        return "You are a professional university roommate matching assistant. 
Based on the calculated compatibility score of {$score}%, write a friendly and helpful explanation of why the two students have this compatibility score.

Current Student Details:
- Department: " . ($profile->department ?? 'N/A') . "
- Hall: " . ($profile->hall ?? 'N/A') . "
- Sleep Schedule: " . ($pref->sleep_schedule ?? 'N/A') . "
- Wake Up Time: " . ($pref->wake_up_time ?? 'N/A') . "
- Study Habit: " . ($pref->study_habit ?? 'N/A') . "
- Cleanliness: " . ($pref->cleanliness ?? 'N/A') . "
- Smoking Preference: " . ($pref->smoking ? 'Yes (Smoker)' : 'No (Non-smoker)') . "
- Noise Tolerance: " . ($pref->noise_tolerance ?? 'N/A') . "
- Room Temperature: " . ($pref->room_temperature ?? 'N/A') . "
- Music Preference: " . ($pref->music_preference ?? 'N/A') . "
- Personality: " . ($pref->introvert_extrovert ?? 'N/A') . "
- Hobbies: " . ($pref->hobbies ?? 'N/A') . "

Matched Student (Name: {$matchedUser->name}) Details:
- Department: " . ($mProfile->department ?? 'N/A') . "
- Hall: " . ($mProfile->hall ?? 'N/A') . "
- Sleep Schedule: " . ($mPref->sleep_schedule ?? 'N/A') . "
- Wake Up Time: " . ($mPref->wake_up_time ?? 'N/A') . "
- Study Habit: " . ($mPref->study_habit ?? 'N/A') . "
- Cleanliness: " . ($mPref->cleanliness ?? 'N/A') . "
- Smoking Preference: " . ($mPref->smoking ? 'Yes (Smoker)' : 'No (Non-smoker)') . "
- Noise Tolerance: " . ($mPref->noise_tolerance ?? 'N/A') . "
- Room Temperature: " . ($mPref->room_temperature ?? 'N/A') . "
- Music Preference: " . ($mPref->music_preference ?? 'N/A') . "
- Personality: " . ($mPref->introvert_extrovert ?? 'N/A') . "
- Hobbies: " . ($mPref->hobbies ?? 'N/A') . "

Calculated Compatibility Score: {$score}%

Instructions:
1. DO NOT calculate another score. Always explain based on the provided score of {$score}%.
2. Write in clean, professional, and friendly English.
3. Structure your response EXACTLY as a JSON object matching this schema:
{
  \"summary\": \"A short paragraph summarizing the overall compatibility and synergy.\",
  \"strengths\": [
    \"Bullet point explaining matching traits, shared habits, or values\",
    \"Another bullet point...\"
  ],
  \"differences\": [
    \"Bullet point explaining key differences or minor conflicts\",
    \"Another bullet point...\"
  ],
  \"suggestions\": [
    \"Actionable suggestion to resolve differences\",
    \"Another recommendation...\"
  ],
  \"recommendation\": \"Choose one of: Highly Recommended / Recommended / Neutral / Not Recommended based on {$score}% compatibility.\",
  \"comparison\": {
    \"sleep_schedule\": \"Short feedback line comparing their sleep schedules (max 15 words)\",
    \"wake_up_time\": \"Short feedback line comparing their wake up times (max 15 words)\",
    \"study_habit\": \"Short feedback line comparing their study habits (max 15 words)\",
    \"cleanliness\": \"Short feedback line comparing their cleanliness levels (max 15 words)\",
    \"smoking\": \"Short feedback line comparing their smoking preferences (max 15 words)\",
    \"noise_tolerance\": \"Short feedback line comparing their noise tolerance levels (max 15 words)\",
    \"room_temperature\": \"Short feedback line comparing their room temperature preferences (max 15 words)\",
    \"music_preference\": \"Short feedback line comparing their music preferences (max 15 words)\",
    \"introvert_extrovert\": \"Short feedback line comparing their personalities/extroversion (max 15 words)\",
    \"hobbies\": \"Short feedback line comparing their hobbies and shared interests (max 15 words)\"
  }
}

Do not wrap the JSON output in markdown tags (like ```json ... ```) or any other text. Output ONLY the raw JSON string.";
    }
}
