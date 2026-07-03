@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="card rounded-4 shadow-sm mb-4 overflow-hidden">
            <div class="bg-primary text-white p-4 p-md-5">
                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-4">
                    <div>
                        <p class="text-uppercase text-white-50 mb-2 small">Recommended roommates</p>
                        <h1 class="h3 text-white mb-3">Welcome back, {{ explode(' ', trim($user->name))[0] ?? $user->name }}</h1>
                        <p class="text-white-75 mb-4">Your profile readiness and preference match score help find the best roommates fast. Keep your details updated for stronger results.</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="rounded-4 bg-white bg-opacity-10 p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-white-75 mb-1 small">Profile completion</p>
                                            <h3 class="mb-0">{{ $profileCompletion }}%</h3>
                                        </div>
                                        <a href="{{ $profile ? route('profile.edit') : route('profile.create') }}" class="btn btn-light btn-sm rounded-3 px-4">Edit Profile</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="rounded-4 bg-white bg-opacity-10 p-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-white-75 mb-1 small">Preferences completion</p>
                                            <h3 class="mb-0">{{ $preferenceCompletion }}%</h3>
                                        </div>
                                        <a href="{{ $preference ? route('preferences.edit') : route('preferences.create') }}" class="btn btn-light btn-sm rounded-3 px-4">Edit Preferences</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3 align-items-stretch">
                        <div class="rounded-4 bg-white p-3 text-dark shadow-sm" style="min-height: 300px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h6 class="mb-0">Top Compatible Students</h6>
                                <span class="badge bg-primary text-white">Top 3</span>
                            </div>

                            @php
                                $medals = ['🥇', '🥈', '🥉'];
                            @endphp

                            @forelse($topMatches->take(3) as $index => $match)
                                <a href="{{ route('students.show', $match->id) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex align-items-center justify-content-between py-3 border-bottom border-secondary border-opacity-10">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="text-center" style="width:44px">
                                                <div class="fs-5">{{ $medals[$index] ?? '•' }}</div>
                                            </div>

                                            @if(optional($match->studentProfile)->profile_photo)
                                                <img src="{{ asset('storage/' . $match->studentProfile->profile_photo) }}" alt="{{ $match->name }}" class="rounded-circle" style="width:48px;height:48px;object-fit:cover;">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                                                    <span class="fs-5">{{ strtoupper(substr($match->name,0,1)) }}</span>
                                                </div>
                                            @endif

                                            <div>
                                                <div class="fw-semibold fs-6">{{ $match->name }}</div>
                                                <div class="small text-secondary">{{ optional($match->studentProfile)->department ? strtoupper(optional($match->studentProfile)->department) : 'Department not set' }}</div>
                                            </div>
                                        </div>

                                        <div class="text-end ms-3">
                                            <div class="fw-bold fs-5">{{ data_get($match, 'compatibility_score', 0) }}%</div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-secondary small">Complete your profile and preferences to see top match recommendations here.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
                            <div>
                                <h5 class="mb-1">Current Roommate</h5>
                                <p class="text-muted mb-0">Your active roommate is shown here. End the match when you're ready to find a new roommate.</p>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('roommate-match.index') }}" class="btn btn-outline-secondary btn-sm">My Roommate</a>
                                <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm">Find Roommates</a>
                            </div>
                        </div>

                        @if($currentRoommate)
                            <div class="d-flex flex-column flex-lg-row align-items-center gap-4">
                                <div class="d-flex align-items-center gap-3">
                                    @if(optional($currentRoommate->studentProfile)->profile_photo)
                                        <img src="{{ asset('storage/' . $currentRoommate->studentProfile->profile_photo) }}" alt="{{ $currentRoommate->name }}" class="rounded-circle" style="width:72px;height:72px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:72px;height:72px;">
                                            <span class="fs-4">{{ strtoupper(substr($currentRoommate->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="mb-1">{{ $currentRoommate->name }}</h5>
                                        <p class="text-secondary mb-1">{{ optional($currentRoommate->studentProfile)->department ? strtoupper(optional($currentRoommate->studentProfile)->department) : 'Department not set' }}</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-secondary">ID: {{ optional($currentRoommate->studentProfile)->student_id ?? 'N/A' }}</span>
                                            <span class="badge bg-secondary">Hall: {{ optional($currentRoommate->studentProfile)->hall ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <div class="text-center">
                                        <div class="fw-semibold fs-4">{{ $currentMatch->compatibility_score ?? 0 }}%</div>
                                        <div class="small text-secondary">Compatibility</div>
                                    </div>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="{{ route('students.show', $currentRoommate->id) }}" class="btn btn-outline-primary btn-sm">View Profile</a>
                                        <form method="POST" action="{{ route('messages.open') }}" class="d-inline-block">
                                            @csrf
                                            <input type="hidden" name="other_user_id" value="{{ $currentRoommate->id }}">
                                            <button type="submit" class="btn btn-primary btn-sm">Message</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info mb-0">No roommate assigned yet. Search for roommates to create a match or check your pending requests.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="mb-4">
                            <h5 class="card-title mb-1">Find Roommates</h5>
                            <p class="text-muted mb-3">Search and filter students directly from the dashboard using the same options as the full roommate finder.</p>
                            <form method="GET" action="{{ route('students.index') }}">
                                <div class="row g-3">
                                    <div class="col-lg-2">
                                        <label class="form-label">Department</label>
                                        <select name="department" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department }}" {{ request('department') === $department ? 'selected' : '' }}>{{ strtoupper($department) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Batch</label>
                                        <select name="batch" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($batches as $batch)
                                                <option value="{{ $batch }}" {{ request('batch') === $batch ? 'selected' : '' }}>{{ $batch }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Hall</label>
                                        <select name="hall" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($halls as $hall)
                                                <option value="{{ $hall }}" {{ request('hall') === $hall ? 'selected' : '' }}>{{ ucwords($hall) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($genders as $gender)
                                                <option value="{{ $gender }}" {{ request('gender') === $gender ? 'selected' : '' }}>{{ ucfirst($gender) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Sleep</label>
                                        <select name="sleep_schedule" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($sleepSchedules as $sleepSchedule)
                                                <option value="{{ $sleepSchedule }}" {{ request('sleep_schedule') === $sleepSchedule ? 'selected' : '' }}>{{ ucwords($sleepSchedule) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Study Habit</label>
                                        <select name="study_habit" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($studyHabits as $studyHabit)
                                                <option value="{{ $studyHabit }}" {{ request('study_habit') === $studyHabit ? 'selected' : '' }}>{{ ucwords($studyHabit) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Cleanliness</label>
                                        <select name="cleanliness" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($cleanlinessLevels as $cleanliness)
                                                <option value="{{ $cleanliness }}" {{ request('cleanliness') === $cleanliness ? 'selected' : '' }}>{{ ucwords($cleanliness) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Smoking</label>
                                        <select name="smoking" class="form-select">
                                            <option value="">Any</option>
                                            <option value="yes" {{ request('smoking') === 'yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="no" {{ request('smoking') === 'no' ? 'selected' : '' }}>No</option>
                                            <option value="occasional" {{ request('smoking') === 'occasional' ? 'selected' : '' }}>Occasional</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Noise</label>
                                        <select name="noise_tolerance" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($noiseLevels as $noiseLevel)
                                                <option value="{{ $noiseLevel }}" {{ request('noise_tolerance') === $noiseLevel ? 'selected' : '' }}>{{ ucwords($noiseLevel) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Temperature</label>
                                        <select name="room_temperature" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($roomTemperatures as $roomTemperature)
                                                <option value="{{ $roomTemperature }}" {{ request('room_temperature') === $roomTemperature ? 'selected' : '' }}>{{ ucwords($roomTemperature) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Music</label>
                                        <select name="music_preference" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($musicPreferences as $musicPreference)
                                                <option value="{{ $musicPreference }}" {{ request('music_preference') === $musicPreference ? 'selected' : '' }}>{{ ucwords($musicPreference) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2">
                                        <label class="form-label">Personality</label>
                                        <select name="introvert_extrovert" class="form-select">
                                            <option value="">Any</option>
                                            @foreach($personalities as $personality)
                                                <option value="{{ $personality }}" {{ request('introvert_extrovert') === $personality ? 'selected' : '' }}>{{ ucfirst($personality) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-lg-8">
                                                <input type="search" name="q" class="form-control" placeholder="Search by name, student ID, department, hall or batch" value="{{ request('q') }}">
                                            </div>
                                            <div class="col-auto">
                                                <label class="form-label mb-0">Sort by</label>
                                            </div>
                                            <div class="col-lg-2">
                                                <select name="sort_by" class="form-select">
                                                    <option value="compatibility_desc" {{ request('sort_by') === 'compatibility_desc' ? 'selected' : '' }}>Highest Compatibility</option>
                                                    <option value="compatibility_asc" {{ request('sort_by') === 'compatibility_asc' ? 'selected' : '' }}>Lowest Compatibility</option>
                                                    <option value="newest" {{ request('sort_by') === 'newest' ? 'selected' : '' }}>Newest</option>
                                                    <option value="oldest" {{ request('sort_by') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                                    <option value="name_asc" {{ request('sort_by') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                                    <option value="name_desc" {{ request('sort_by') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                                </select>
                                            </div>
                                            <div class="col-auto d-flex gap-2">
                                                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Reset</a>
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-4 g-4">
                                @forelse($recommendedUsers as $student)
                                    <div class="col">
                                        @include('partials.profile_card', ['student' => $student])
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-muted">No available student profiles at the moment.</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
