<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class CompatibilityService
{
    protected array $weights = [
        'sleep_schedule' => 0.20,
        'study_habit' => 0.15,
        'cleanliness' => 0.15,
        'smoking' => 0.15,
        'noise_tolerance' => 0.10,
        'wake_up_time' => 0.05,
        'room_temperature' => 0.05,
        'music_preference' => 0.05,
        'lights_preference' => 0.05,
        'introvert_extrovert' => 0.05,
    ];

    public function calculateScore(User $user, User $otherUser): int
    {
        if ($user->id === $otherUser->id) {
            return 0;
        }

        $preference = $user->studentPreference;
        $otherPreference = $otherUser->studentPreference;

        if (! $preference || ! $otherPreference || ! $user->studentProfile || ! $otherUser->studentProfile) {
            return 0;
        }

        $similarities = [
            'sleep_schedule' => $this->tokenSimilarity($preference->sleep_schedule, $otherPreference->sleep_schedule),
            'study_habit' => $this->tokenSimilarity($preference->study_habit, $otherPreference->study_habit),
            'cleanliness' => $this->ordinalSimilarity($preference->cleanliness, $otherPreference->cleanliness),
            'smoking' => $this->smokingSimilarity($preference->smoking, $otherPreference->smoking),
            'noise_tolerance' => $this->ordinalSimilarity($preference->noise_tolerance, $otherPreference->noise_tolerance),
            'wake_up_time' => $this->wakeUpTimeSimilarity($preference->wake_up_time, $otherPreference->wake_up_time),
            'room_temperature' => $this->ordinalSimilarity($preference->room_temperature, $otherPreference->room_temperature),
            'music_preference' => $this->tokenSimilarity($preference->music_preference, $otherPreference->music_preference),
            'lights_preference' => $this->ordinalSimilarity($preference->lights_preference, $otherPreference->lights_preference),
            'introvert_extrovert' => $this->ordinalSimilarity($preference->introvert_extrovert, $otherPreference->introvert_extrovert),
        ];

        $score = 0.0;
        foreach ($this->weights as $field => $weight) {
            $score += ($similarities[$field] ?? 0.0) * $weight;
        }

        return $this->getCompatibilityPercentage($score * 100);
    }

    public function getBestMatches(User $user, int $limit = 10): Collection
    {
        if (! $user->studentProfile || ! $user->studentPreference) {
            return collect();
        }

        return User::with(['studentProfile', 'studentPreference'])
            ->where('id', '!=', $user->id)
            ->whereHas('studentProfile')
            ->whereHas('studentPreference')
            ->get()
            ->map(function (User $candidate) use ($user) {
                $candidate->compatibility_score = $this->calculateScore($user, $candidate);
                return $candidate;
            })
            ->sortByDesc('compatibility_score')
            ->values()
            ->take($limit);
    }

    public function getCompatibilityPercentage(float $score): int
    {
        $percentage = max(0, min(100, (int) round($score)));
        return $percentage;
    }

    protected function normalizeText(?string $value): string
    {
        if (! $value) {
            return '';
        }

        $value = mb_strtolower($value);
        $value = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    protected function tokens(string $value): array
    {
        $value = $this->normalizeText($value);
        if ($value === '') {
            return [];
        }

        return array_values(array_filter(explode(' ', $value)));
    }

    protected function tokenSimilarity(?string $a, ?string $b): float
    {
        if (empty($a) && empty($b)) {
            return 1.0;
        }

        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $ta = $this->tokens($a);
        $tb = $this->tokens($b);

        if (empty($ta) || empty($tb)) {
            return 0.0;
        }

        $intersect = array_intersect($ta, $tb);
        $union = array_unique(array_merge($ta, $tb));

        return count($intersect) / max(1, count($union));
    }

    protected function mapSimilarity(?string $a, ?string $b): float
    {
        if (empty($a) && empty($b)) {
            return 1.0;
        }

        if (empty($a) || empty($b)) {
            return 0.0;
        }

        return $this->normalizeText($a) === $this->normalizeText($b) ? 1.0 : 0.0;
    }

    protected function normalizeBooleanPreference($value): ?string
    {
        if (is_bool($value)) {
            return $value ? 'yes' : 'no';
        }

        return $this->normalizeText((string) $value);
    }

    protected function smokingSimilarity($a, $b): float
    {
        if ($a === null && $b === null) {
            return 1.0;
        }

        if ($a === null || $b === null) {
            return 0.0;
        }

        $na = $this->normalizeSmoking($a);
        $nb = $this->normalizeSmoking($b);

        if ($na === $nb) {
            return 1.0;
        }

        if ($na === 'occasional' || $nb === 'occasional') {
            return 0.5;
        }

        return 0.0;
    }

    protected function normalizeSmoking($value): string
    {
        if (is_bool($value)) {
            return $value ? 'yes' : 'no';
        }

        $value = $this->normalizeText((string) $value);

        if (preg_match('/\b(no|non|never|none|nonsmoker|non smoker|non-smoker)\b/', $value)) {
            return 'no';
        }

        if (preg_match('/\b(occasional|sometimes|social)\b/', $value)) {
            return 'occasional';
        }

        if (preg_match('/\b(yes|smoke|daily|regular|true)\b/', $value)) {
            return 'yes';
        }

        return $value;
    }

    protected function wakeUpTimeSimilarity(?string $a, ?string $b): float
    {
        $aKey = $this->normalizeWakeUpTime($a);
        $bKey = $this->normalizeWakeUpTime($b);

        if (empty($aKey) && empty($bKey)) {
            return 1.0;
        }

        if (empty($aKey) || empty($bKey)) {
            return 0.0;
        }

        if ($aKey === $bKey) {
            return 1.0;
        }

        $order = ['early', 'mid', 'late'];
        $distance = abs(array_search($aKey, $order, true) - array_search($bKey, $order, true));
        return max(0.0, 1.0 - ($distance / (count($order) - 1)));
    }

    protected function normalizeWakeUpTime(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = $this->normalizeText($value);

        if (preg_match('/\b(\d{1,2})(?::\d{2})?\s*(am|pm)?\b/', $value, $matches)) {
            $hour = (int) $matches[1];
            $period = $matches[2] ?? null;
            if ($period === 'pm' && $hour < 12) {
                $hour += 12;
            }
            if ($period === 'am' && $hour === 12) {
                $hour = 0;
            }

            if ($hour < 10) {
                return 'early';
            }

            if ($hour < 14) {
                return 'mid';
            }

            return 'late';
        }

        if (str_contains($value, 'early')) {
            return 'early';
        }

        if (str_contains($value, 'mid') || str_contains($value, 'noon') || str_contains($value, 'morning')) {
            return 'mid';
        }

        if (str_contains($value, 'late') || str_contains($value, 'afternoon') || str_contains($value, 'evening') || str_contains($value, 'night')) {
            return 'late';
        }

        return 'mid';
    }

    protected function ordinalSimilarity(?string $a, ?string $b): float
    {
        if (empty($a) && empty($b)) {
            return 1.0;
        }

        if (empty($a) || empty($b)) {
            return 0.0;
        }

        $aText = $this->normalizeText($a);
        $bText = $this->normalizeText($b);

        if ($aText === $bText) {
            return 1.0;
        }

        $aOrder = $this->mapOrder($aText);
        $bOrder = $this->mapOrder($bText);

        if ($aOrder === null || $bOrder === null) {
            return $this->mapSimilarity($aText, $bText);
        }

        $distance = abs($aOrder - $bOrder);
        return max(0.0, 1.0 - ($distance / 2));
    }

    protected function mapOrder(string $value): ?int
    {
        $order = [
            'low' => 0,
            'quiet' => 0,
            'minimal' => 0,
            'medium' => 1,
            'moderate' => 1,
            'average' => 1,
            'high' => 2,
            'loud' => 2,
            'noisy' => 2,
            'cold' => 0,
            'cool' => 0,
            'chilly' => 0,
            'neutral' => 1,
            'comfortable' => 1,
            'warm' => 2,
            'hot' => 2,
            'dark' => 0,
            'dim' => 0,
            'bright' => 2,
            'light' => 2,
            'introvert' => 0,
            'ambivert' => 1,
            'extrovert' => 2,
        ];

        foreach ($order as $term => $ordinal) {
            if (str_contains($value, $term)) {
                return $ordinal;
            }
        }

        return null;
    }
}
