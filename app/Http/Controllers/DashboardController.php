<?php

namespace App\Http\Controllers;

use App\Services\CompatibilityService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, CompatibilityService $compatibility)
    {
        $user = $request->user();
        $profile = $user->studentProfile;
        $preference = $user->studentPreference;
        $topMatches = collect();

        if ($user->studentProfile && $user->studentPreference) {
            $topMatches = $compatibility->getBestMatches($user, 3);
        }

        $profileCompletedFields = [
            'student_id' => $profile?->student_id,
            'department' => $profile?->department,
            'batch' => $profile?->batch,
            'hall' => $profile?->hall,
            'phone' => $profile?->phone,
            'gender' => $profile?->gender,
            'bio' => $profile?->bio,
            'profile_photo' => $profile?->profile_photo,
        ];

        $preferenceCompletedFields = [
            'sleep_schedule' => $preference?->sleep_schedule,
            'wake_up_time' => $preference?->wake_up_time,
            'study_habit' => $preference?->study_habit,
            'cleanliness' => $preference?->cleanliness,
            'smoking' => isset($preference->smoking) ? $preference->smoking : null,
            'noise_tolerance' => $preference?->noise_tolerance,
            'room_temperature' => $preference?->room_temperature,
            'music_preference' => $preference?->music_preference,
            'introvert_extrovert' => $preference?->introvert_extrovert,
            'hobbies' => $preference?->hobbies,
            'languages' => $preference?->languages,
        ];

        $allCompletionFields = array_merge($profileCompletedFields, $preferenceCompletedFields);

        $completedCount = 0;
        $missingFields = [];

        foreach ($allCompletionFields as $key => $value) {
            $isComplete = ! is_null($value) && $value !== '';
            if ($key === 'smoking') {
                $isComplete = $value !== null;
            }

            if ($isComplete) {
                $completedCount++;
            } else {
                $missingFields[] = str_replace('_', ' ', ucwords($key));
            }
        }

        $completionPercentage = $allCompletionFields
            ? (int) round(($completedCount / count($allCompletionFields)) * 100)
            : 0;

        $missingFields = array_values(array_filter($missingFields));

        return view('dashboard', [
            'user' => $user,
            'profile' => $profile,
            'preference' => $preference,
            'completionPercentage' => $completionPercentage,
            'missingFields' => $missingFields,
            'profileStatus' => $profile ? 'Completed' : 'Incomplete',
            'preferenceStatus' => $preference ? 'Completed' : 'Incomplete',
            'dashboardStatus' => 'Completed',
            'topMatches' => $topMatches,
        ]);
    }
}
