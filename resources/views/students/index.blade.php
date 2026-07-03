@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Find Roommates</h2>
                <p class="text-muted mb-0">Search and filter students, then review compatibility scores instantly.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
        </div>

        <div class="card rounded-card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('students.index') }}">
                    <div class="row g-3">
                        <div class="col-lg-2">
                            <label class="form-label">Department</label>
                            <select name="department" class="form-select">
                                <option value="">Any</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department }}" {{ (isset($filters['department']) && $filters['department'] === $department) ? 'selected' : '' }}>{{ strtoupper($department) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Batch</label>
                            <select name="batch" class="form-select">
                                <option value="">Any</option>
                                @foreach($batches as $batch)
                                    <option value="{{ $batch }}" {{ (isset($filters['batch']) && $filters['batch'] === $batch) ? 'selected' : '' }}>{{ $batch }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Hall</label>
                            <select name="hall" class="form-select">
                                <option value="">Any</option>
                                @foreach($halls as $hall)
                                    <option value="{{ $hall }}" {{ (isset($filters['hall']) && $filters['hall'] === $hall) ? 'selected' : '' }}>{{ ucwords($hall) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Any</option>
                                @foreach($genders as $gender)
                                    <option value="{{ $gender }}" {{ (isset($filters['gender']) && $filters['gender'] === $gender) ? 'selected' : '' }}>{{ ucfirst($gender) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Sleep</label>
                            <select name="sleep_schedule" class="form-select">
                                <option value="">Any</option>
                                @foreach($sleepSchedules as $sleepSchedule)
                                    <option value="{{ $sleepSchedule }}" {{ (isset($filters['sleep_schedule']) && $filters['sleep_schedule'] === $sleepSchedule) ? 'selected' : '' }}>{{ ucwords($sleepSchedule) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Study Habit</label>
                            <select name="study_habit" class="form-select">
                                <option value="">Any</option>
                                @foreach($studyHabits as $studyHabit)
                                    <option value="{{ $studyHabit }}" {{ (isset($filters['study_habit']) && $filters['study_habit'] === $studyHabit) ? 'selected' : '' }}>{{ ucwords($studyHabit) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Cleanliness</label>
                            <select name="cleanliness" class="form-select">
                                <option value="">Any</option>
                                @foreach($cleanlinessLevels as $cleanliness)
                                    <option value="{{ $cleanliness }}" {{ (isset($filters['cleanliness']) && $filters['cleanliness'] === $cleanliness) ? 'selected' : '' }}>{{ ucwords($cleanliness) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Smoking</label>
                            <select name="smoking" class="form-select">
                                <option value="">Any</option>
                                <option value="yes" {{ (isset($filters['smoking']) && $filters['smoking'] === 'yes') ? 'selected' : '' }}>Yes</option>
                                <option value="no" {{ (isset($filters['smoking']) && $filters['smoking'] === 'no') ? 'selected' : '' }}>No</option>
                                <option value="occasional" {{ (isset($filters['smoking']) && $filters['smoking'] === 'occasional') ? 'selected' : '' }}>Occasional</option>
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Noise</label>
                            <select name="noise_tolerance" class="form-select">
                                <option value="">Any</option>
                                @foreach($noiseLevels as $noiseLevel)
                                    <option value="{{ $noiseLevel }}" {{ (isset($filters['noise_tolerance']) && $filters['noise_tolerance'] === $noiseLevel) ? 'selected' : '' }}>{{ ucwords($noiseLevel) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Temperature</label>
                            <select name="room_temperature" class="form-select">
                                <option value="">Any</option>
                                @foreach($roomTemperatures as $roomTemperature)
                                    <option value="{{ $roomTemperature }}" {{ (isset($filters['room_temperature']) && $filters['room_temperature'] === $roomTemperature) ? 'selected' : '' }}>{{ ucwords($roomTemperature) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Music</label>
                            <select name="music_preference" class="form-select">
                                <option value="">Any</option>
                                @foreach($musicPreferences as $musicPreference)
                                    <option value="{{ $musicPreference }}" {{ (isset($filters['music_preference']) && $filters['music_preference'] === $musicPreference) ? 'selected' : '' }}>{{ ucwords($musicPreference) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Personality</label>
                            <select name="introvert_extrovert" class="form-select">
                                <option value="">Any</option>
                                @foreach($personalities as $personality)
                                    <option value="{{ $personality }}" {{ (isset($filters['introvert_extrovert']) && $filters['introvert_extrovert'] === $personality) ? 'selected' : '' }}>{{ ucfirst($personality) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label">Sort by</label>
                            <select name="sort_by" class="form-select">
                                <option value="compatibility_desc" {{ ($sortBy ?? '') === 'compatibility_desc' ? 'selected' : '' }}>Highest Compatibility</option>
                                <option value="compatibility_asc" {{ ($sortBy ?? '') === 'compatibility_asc' ? 'selected' : '' }}>Lowest Compatibility</option>
                                <option value="newest" {{ ($sortBy ?? '') === 'newest' ? 'selected' : '' }}>Newest Students</option>
                                <option value="oldest" {{ ($sortBy ?? '') === 'oldest' ? 'selected' : '' }}>Oldest Students</option>
                                <option value="name_asc" {{ ($sortBy ?? '') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                <option value="name_desc" {{ ($sortBy ?? '') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary me-2">Reset</a>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-3">
            <div>
                <h5 class="mb-1">Search Results</h5>
                <p class="text-muted mb-0">Showing {{ $users->total() }} students matching your criteria.</p>
            </div>
            <span class="badge bg-secondary">Page {{ $users->currentPage() }} of {{ $users->lastPage() }}</span>
        </div>

        <div class="row g-4">
            @forelse($users as $student)
                <div class="col-lg-4">
                    <div class="card rounded-card shadow-sm h-100 hover-shadow">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                @if(optional($student->studentProfile)->profile_photo)
                                    <img src="{{ asset('storage/' . $student->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle profile-photo">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center profile-photo">
                                        <span class="fs-4">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">{{ $student->name }}</h5>
                                    <small class="text-muted">{{ optional($student->studentProfile)->department ? strtoupper(optional($student->studentProfile)->department) : 'Department not set' }}</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <span class="badge bg-info">ID: {{ optional($student->studentProfile)->student_id ?? 'N/A' }}</span>
                                <span class="badge bg-secondary">Batch: {{ optional($student->studentProfile)->batch ?? 'N/A' }}</span>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">Compatibility</span>
                                    <strong>{{ $student->compatibility_score !== null ? $student->compatibility_score . '%' : 'N/A' }}</strong>
                                </div>
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $student->compatibility_score ?? 0 }}%;" aria-valuenow="{{ $student->compatibility_score ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <p class="text-truncate-2 text-muted mb-4">{{ optional($student->studentProfile)->bio ?? 'No bio provided yet.' }}</p>

                            <div class="mt-auto">
                                <a href="{{ route('students.show', ['id' => $student->id]) }}" class="btn btn-primary w-100 mb-2">View Profile</a>
                                <button type="button" class="btn btn-outline-secondary w-100" disabled>Send Roommate Request</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No students found matching your criteria.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
@endsection
