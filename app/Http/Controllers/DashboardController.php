<?php

namespace App\Http\Controllers;

use App\Models\RoommateMatch;
use App\Models\RoommateRequest;
use App\Models\StudentPreference;
use App\Models\User;
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
        $recommendedUsers = collect();
        $bestMatch = null;

        if ($user->studentProfile && $user->studentPreference) {
            $bestMatch = $compatibility->getBestMatches($user, 1)->first();
            $topMatches = $compatibility->getBestMatches($user, 3);

            // If there are fewer than 3 compatibility matches (e.g., small seed data),
            // fill the remaining slots with latest students who have profiles.
            if ($topMatches->count() < 3) {
                $needed = 3 - $topMatches->count();
                $existingIds = $topMatches->pluck('id')->toArray();
                $fillers = User::with('studentProfile')
                    ->where('id', '!=', $user->id)
                    ->whereHas('studentProfile')
                    ->whereNotIn('id', $existingIds)
                    ->latest()
                    ->take($needed)
                    ->get()
                    ->map(function (User $u) use ($compatibility, $user) {
                        $u->compatibility_score = $compatibility->calculateScore($user, $u);
                        return $u;
                    });

                $topMatches = $topMatches->concat($fillers)->values();
            }

            $recommendedUsers = $compatibility->getBestMatches($user, 8);
        } else {
            $recommendedUsers = User::with('studentProfile')
                ->where('id', '!=', $user->id)
                ->whereHas('studentProfile')
                ->latest()
                ->take(8)
                ->get();
        }

        $studentIds = User::with('studentProfile')
            ->where('id', '!=', $user->id)
            ->whereHas('studentProfile')
            ->get()
            ->map(fn($student) => optional($student->studentProfile)->student_id ?? 'ID ' . $student->id);

        $departments = collect(['eee', 'cse', 'ece', 'bme', 'mse', 'me', 'iem', 'le', 'te', 'ese', 'ce', 'urp', 'becm', 'arch', 'math', 'chem', 'phy', 'hum']);
        $batches = collect(array_map(fn($year) => '2k' . substr((string) $year, 2), range(2018, 2028)));
        $halls = collect(['amar ekushey hall', 'lalon shah hall', 'fajlul haq hall', 'khan jahan ali hall', 'rashid hall', 'rokeya hall']);
        $genders = collect(['male', 'female', 'other']);
        $sleepSchedules = StudentPreference::query()
            ->whereNotNull('sleep_schedule')
            ->where('sleep_schedule', '!=', '')
            ->distinct()
            ->orderBy('sleep_schedule')
            ->pluck('sleep_schedule');
        $studyHabits = collect(['loud', 'silent', 'group study']);
        $cleanlinessLevels = collect(['low', 'medium', 'high']);
        $noiseLevels = StudentPreference::query()
            ->whereNotNull('noise_tolerance')
            ->where('noise_tolerance', '!=', '')
            ->distinct()
            ->orderBy('noise_tolerance')
            ->pluck('noise_tolerance');
        $roomTemperatures = collect(['hot', 'cold']);
        $musicPreferences = StudentPreference::query()
            ->whereNotNull('music_preference')
            ->where('music_preference', '!=', '')
            ->distinct()
            ->orderBy('music_preference')
            ->pluck('music_preference');
        $personalities = collect(['introvert', 'ambivert', 'extrovert']);

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

        $profileCompletedCount = 0;
        foreach ($profileCompletedFields as $key => $value) {
            $isComplete = ! is_null($value) && $value !== '';
            if ($key === 'smoking') {
                $isComplete = $value !== null;
            }
            if ($isComplete) {
                $profileCompletedCount++;
            }
        }

        $preferenceCompletedCount = 0;
        foreach ($preferenceCompletedFields as $key => $value) {
            $isComplete = ! is_null($value) && $value !== '';
            if ($key === 'smoking') {
                $isComplete = $value !== null;
            }
            if ($isComplete) {
                $preferenceCompletedCount++;
            }
        }

        $profileCompletion = $profileCompletedFields
            ? (int) round(($profileCompletedCount / count($profileCompletedFields)) * 100)
            : 0;

        $preferenceCompletion = $preferenceCompletedFields
            ? (int) round(($preferenceCompletedCount / count($preferenceCompletedFields)) * 100)
            : 0;

        $missingFields = array_values(array_filter($missingFields));

        $pendingIncoming = RoommateRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $pendingOutgoing = RoommateRequest::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->count();

        $currentMatches = RoommateMatch::with(['studentOne.studentProfile', 'studentTwo.studentProfile'])
            ->active()
            ->forUser($user->id)
            ->get();

        $currentMatch = $currentMatches->first();
        $currentRoommate = $currentMatch ? $currentMatch->otherStudent($user) : null;

        $requestedIds = RoommateRequest::where('sender_id', $user->id)
            ->where('status', 'pending')
            ->pluck('receiver_id')
            ->toArray();

        $incomingPendingIds = RoommateRequest::where('receiver_id', $user->id)
            ->where('status', 'pending')
            ->pluck('sender_id')
            ->toArray();

        $matchedIds = RoommateMatch::active()
            ->forUser($user->id)
            ->get()
            ->flatMap(fn($match) => [$match->student_one_id, $match->student_two_id])
            ->unique()
            ->filter(fn($id) => $id !== $user->id)
            ->values()
            ->toArray();

        $notifications = $user->notifications()->latest()->take(5)->get();

        $latestRequest = RoommateRequest::with(['sender.studentProfile', 'receiver.studentProfile'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
            })
            ->latest('created_at')
            ->first();

        return view('dashboard', [
            'user' => $user,
            'profile' => $profile,
            'preference' => $preference,
            'completionPercentage' => $completionPercentage,
            'profileCompletion' => $profileCompletion,
            'preferenceCompletion' => $preferenceCompletion,
            'missingFields' => $missingFields,
            'profileStatus' => $profile ? 'Completed' : 'Incomplete',
            'preferenceStatus' => $preference ? 'Completed' : 'Incomplete',
            'dashboardStatus' => 'Completed',
            'topMatches' => $topMatches,
            'recommendedUsers' => $recommendedUsers,
            'bestMatch' => $bestMatch,
            'studentIds' => $studentIds,
            'departments' => $departments,
            'batches' => $batches,
            'halls' => $halls,
            'genders' => $genders,
            'sleepSchedules' => $sleepSchedules,
            'studyHabits' => $studyHabits,
            'cleanlinessLevels' => $cleanlinessLevels,
            'noiseLevels' => $noiseLevels,
            'roomTemperatures' => $roomTemperatures,
            'musicPreferences' => $musicPreferences,
            'personalities' => $personalities,
            'pendingIncoming' => $pendingIncoming,
            'pendingOutgoing' => $pendingOutgoing,
            'currentRoommate' => $currentRoommate,
            'currentMatch' => $currentMatch,
            'currentMatches' => $currentMatches,
            'latestRequest' => $latestRequest,
            'notifications' => $notifications,
            'requestedIds' => $requestedIds,
            'incomingPendingIds' => $incomingPendingIds,
            'matchedIds' => $matchedIds,
        ]);
    }
}
