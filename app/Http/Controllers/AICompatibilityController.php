<?php

namespace App\Http\Controllers;

use App\Models\CompatibilityExplanation;
use App\Models\User;
use App\Services\CompatibilityService;
use App\Services\GeminiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AICompatibilityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function explain(
        Request $request,
        $matchedUserId,
        CompatibilityService $compatibilityService,
        GeminiService $geminiService
    ): JsonResponse {
        $user = $request->user();

        // 1. Security & Validation checks
        if ($user->id == $matchedUserId) {
            return response()->json(['error' => 'You cannot match with yourself.'], 422);
        }

        $matchedUser = User::with(['studentProfile', 'studentPreference'])->find($matchedUserId);

        if (!$matchedUser || !$matchedUser->studentProfile || !$matchedUser->studentPreference) {
            return response()->json(['error' => 'The target student profile or preferences are incomplete.'], 404);
        }

        if (!$user->studentProfile || !$user->studentPreference) {
            return response()->json(['error' => 'Please complete your own profile and preferences first.'], 400);
        }

        // 2. Compute compatibility score using the existing algorithm
        $score = $compatibilityService->calculateScore($user, $matchedUser);

        // 3. Cache Check: Check if an explanation already exists for this pair with the exact same score
        $cachedExplanation = CompatibilityExplanation::where('user_id', $user->id)
            ->where('matched_user_id', $matchedUser->id)
            ->first();

        if ($cachedExplanation && $cachedExplanation->compatibility_score === $score) {
            return response()->json($cachedExplanation->ai_explanation);
        }

        // 4. Generate new explanation using Gemini Service
        try {
            $explanationData = $geminiService->generateExplanation($user, $matchedUser, $score);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

        // 5. Store/Update cached explanation in database
        $savedRecord = CompatibilityExplanation::updateOrCreate(
            [
                'user_id' => $user->id,
                'matched_user_id' => $matchedUser->id
            ],
            [
                'compatibility_score' => $score,
                'ai_explanation' => $explanationData
            ]
        );

        return response()->json($savedRecord->ai_explanation);
    }
}
