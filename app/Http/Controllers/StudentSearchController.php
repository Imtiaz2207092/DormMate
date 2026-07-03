<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentSearchRequest;
use App\Models\StudentProfile;
use App\Services\CompatibilityService;
use Illuminate\Http\Request;

class StudentSearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(StudentSearchRequest $request, CompatibilityService $compat)
    {
        $data = $request->validated();

        $query = StudentProfile::with('user');

        // filters
        $query->when($data['department'] ?? null, fn($q, $v) => $q->filterDepartment($v));
        $query->when($data['hall'] ?? null, fn($q, $v) => $q->filterHall($v));
        $query->when($data['gender'] ?? null, fn($q, $v) => $q->filterGender($v));
        $query->when($data['sleep_schedule'] ?? null, fn($q, $v) => $q->filterSleep($v));
        $query->when($data['study_habit'] ?? null, fn($q, $v) => $q->filterStudy($v));
        $query->when($data['smoking'] ?? null, fn($q, $v) => $q->filterSmoking($v));
        $query->when($data['cleanliness'] ?? null, fn($q, $v) => $q->filterCleanliness($v));
        $query->when($data['noise_tolerance'] ?? null, fn($q, $v) => $q->filterNoise($v));

        // batch maps to user.year
        if (! empty($data['batch'] ?? null)) {
            $batch = $data['batch'];
            $query->whereHas('user', fn($q) => $q->where('year', $batch));
        }

        // keyword search
        $query->when($data['q'] ?? null, fn($q, $v) => $q->searchKeyword($v));

        // exclude current user
        $query->where('user_id', '!=', $request->user()->id);

        $profiles = $query->paginate(10)->withQueryString();

        // compute compatibility scores for current user
        $meProfile = $request->user()->studentProfile;
        $scores = [];
        if ($meProfile) {
            foreach ($profiles as $p) {
                $scores[$p->id] = $compat->scoreProfiles($meProfile, $p);
            }
        }

        return view('students.index', [
            'profiles' => $profiles,
            'scores' => $scores,
            'filters' => $data,
        ]);
    }
}
