<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentSearchRequest;
use App\Models\StudentPreference;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(StudentSearchRequest $request, CompatibilityService $compatibility)
    {
        $user = $request->user();
        $data = $request->validated();
        $perPage = 12;

        $query = User::with(['studentProfile', 'studentPreference'])
            ->where('id', '!=', $user->id)
            ->whereHas('studentProfile');


        $query->when($data['department'] ?? null, fn($q, $v) => $q->whereHas('studentProfile', fn($q2) => $q2->where('department', 'like', "%{$v}%")));
        $query->when($data['batch'] ?? null, fn($q, $v) => $q->whereHas('studentProfile', fn($q2) => $q2->where('batch', 'like', "%{$v}%")));
        $query->when($data['hall'] ?? null, fn($q, $v) => $q->whereHas('studentProfile', fn($q2) => $q2->where('hall', 'like', "%{$v}%")));
        $query->when($data['gender'] ?? null, fn($q, $v) => $q->whereHas('studentProfile', fn($q2) => $q2->where('gender', $v)));

        $query->when($data['sleep_schedule'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('sleep_schedule', 'like', "%{$v}%")));
        $query->when($data['study_habit'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('study_habit', 'like', "%{$v}%")));
        $query->when($data['cleanliness'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('cleanliness', 'like', "%{$v}%")));
        $query->when($data['smoking'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('smoking', $v)));
        $query->when($data['noise_tolerance'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('noise_tolerance', 'like', "%{$v}%")));
        $query->when($data['room_temperature'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('room_temperature', 'like', "%{$v}%")));
        $query->when($data['music_preference'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('music_preference', 'like', "%{$v}%")));
        $query->when($data['introvert_extrovert'] ?? null, fn($q, $v) => $q->whereHas('studentPreference', fn($q2) => $q2->where('introvert_extrovert', $v)));

        $query->when($data['q'] ?? null, function ($q, $value) {
            $q->where(function ($search) use ($value) {
                $search->where('name', 'like', "%{$value}%")
                    ->orWhereHas('studentProfile', fn($q2) => $q2->where('student_id', 'like', "%{$value}%")
                        ->orWhere('department', 'like', "%{$value}%")
                        ->orWhere('hall', 'like', "%{$value}%")
                        ->orWhere('batch', 'like', "%{$value}%"));
            });
        });

        $sortBy = $data['sort_by'] ?? 'compatibility_desc';

        $departments = collect(['cse', 'eee', 'me', 'civil', 'bme', 'mte', 'mse', 'becm', 'arch']);
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

        if (in_array($sortBy, ['compatibility_desc', 'compatibility_asc'], true)) {
            $users = $query->get()->map(function (User $candidate) use ($user, $compatibility) {
                $candidate->compatibility_score = $this->calculateCompatibility($user, $candidate, $compatibility);
                return $candidate;
            });

            if ($sortBy === 'compatibility_asc') {
                $users = $users->sortBy('compatibility_score');
            } else {
                $users = $users->sortByDesc('compatibility_score');
            }

            $users = $users->values();
            $page = LengthAwarePaginator::resolveCurrentPage();
            $paginated = new LengthAwarePaginator(
                $users->forPage($page, $perPage),
                $users->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );
        } else {
            match ($sortBy) {
                'newest' => $query->orderBy('created_at', 'desc'),
                'oldest' => $query->orderBy('created_at', 'asc'),
                'name_asc' => $query->orderBy('name', 'asc'),
                'name_desc' => $query->orderBy('name', 'desc'),
                default => $query->orderBy('name', 'asc'),
            };

            $paginated = $query->paginate($perPage)->withQueryString();
            $paginated->getCollection()->transform(function (User $candidate) use ($user, $compatibility) {
                $candidate->compatibility_score = $this->calculateCompatibility($user, $candidate, $compatibility);
                return $candidate;
            });
        }

        return view('students.index', [
            'users' => $paginated,
            'filters' => $data,
            'sortBy' => $sortBy,
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
        ]);
    }

    public function show(Request $request, CompatibilityService $compatibility, $id)
    {
        $student = User::with(['studentProfile', 'studentPreference'])
            ->where('id', '!=', $request->user()->id)
            ->whereHas('studentProfile')
            ->findOrFail($id);

        $score = null;
        if ($request->user()->studentProfile && $request->user()->studentPreference && $student->studentPreference) {
            $score = $compatibility->calculateScore($request->user(), $student);
        }

        return view('students.show', [
            'student' => $student,
            'score' => $score,
        ]);
    }

    protected function calculateCompatibility(User $user, User $candidate, CompatibilityService $compatibility): ?int
    {
        if (! $user->studentProfile || ! $user->studentPreference || ! $candidate->studentPreference) {
            return null;
        }

        return $compatibility->calculateScore($user, $candidate);
    }
}
