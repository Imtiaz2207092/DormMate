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

        <div class="card rounded-card shadow-sm mb-4" id="student-search-card">
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
                        <div class="col-12">
                            <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                                <label class="form-label mb-0">Sort by</label>
                                <select name="sort_by" class="form-select form-select-sm w-auto">
                                    <option value="compatibility_desc" {{ ($sortBy ?? '') === 'compatibility_desc' ? 'selected' : '' }}>Highest Compatibility</option>
                                    <option value="compatibility_asc" {{ ($sortBy ?? '') === 'compatibility_asc' ? 'selected' : '' }}>Lowest Compatibility</option>
                                    <option value="newest" {{ ($sortBy ?? '') === 'newest' ? 'selected' : '' }}>Newest Students</option>
                                    <option value="oldest" {{ ($sortBy ?? '') === 'oldest' ? 'selected' : '' }}>Oldest Students</option>
                                    <option value="name_asc" {{ ($sortBy ?? '') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                    <option value="name_desc" {{ ($sortBy ?? '') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                </select>
                                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
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

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($users as $student)
                <div class="col">
                    @include('partials.profile_card', ['student' => $student])
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No students found matching your criteria.</div>
                </div>
            @endforelse
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const openSearch = params.has('open_search') || params.has('q');
            if (openSearch) {
                const card = document.getElementById('student-search-card');
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    const firstField = card.querySelector('input, select');
                    if (firstField) {
                        firstField.focus();
                    }
                }
            }
        });
    </script>
@endsection
