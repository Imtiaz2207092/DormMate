@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="card dashboard-hero-full mb-4 border-0">
            <div class="card-body p-0" style="position: relative; z-index: 1;">
                <div class="row align-items-center g-4">
                    <div class="col-lg-8">
                        <p class="text-uppercase text-white-50 fw-semibold mb-1 small" style="letter-spacing: 0.08em; font-family: var(--font-display);">Recommended roommates</p>
                        <h1 class="h3 text-white mb-2 fw-extrabold" style="font-family: var(--font-display); letter-spacing: -0.01em;">Welcome back, {{ explode(' ', trim($user->name))[0] ?? $user->name }}</h1>
                        <p class="mb-0" style="color: rgba(255, 255, 255, 0.85); font-size: 1.05rem; line-height: 1.6;">Please complete your student profile and lifestyle preferences first to unlock roommate compatibility matches and start connecting.</p>
                    </div>
                    <div class="col-lg-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="rounded-4 p-3 border border-white border-opacity-10 shadow-sm" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-white-50 mb-1 small">Profile completion</p>
                                            <h3 class="mb-0 fw-bold text-white">{{ $profileCompletion }}%</h3>
                                        </div>
                                        <a href="{{ $profile ? route('profile.edit') : route('profile.create') }}" class="btn btn-light btn-sm rounded-3 px-3 fw-bold text-primary shadow-sm">Edit Profile</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="rounded-4 p-3 border border-white border-opacity-10 shadow-sm" style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-white-50 mb-1 small">Preferences completion</p>
                                            <h3 class="mb-0 fw-bold text-white">{{ $preferenceCompletion }}%</h3>
                                        </div>
                                        <a href="{{ $preference ? route('preferences.edit') : route('preferences.create') }}" class="btn btn-light btn-sm rounded-3 px-3 fw-bold text-primary shadow-sm">Edit Preferences</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Roommate Advisor Card -->
        <div class="card rounded-4 border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
            <div class="card-body p-4 text-white">
                <div class="row align-items-center g-3">
                    <div class="col-md-8 col-lg-9">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="rounded-circle bg-white bg-opacity-10 p-2 d-flex align-items-center justify-content-center" style="width: 46px; height: 46px;">
                                <i class="bi bi-robot text-info fs-3"></i>
                            </div>
                            <div>
                                <h4 class="h5 fw-bold mb-0 text-white" style="font-family: var(--font-display);">🤖 AI Roommate Advisor</h4>
                                <span class="badge bg-info text-dark font-sans" style="font-size: 0.72rem; padding: 0.25em 0.6em;">Gemini AI Powered</span>
                            </div>
                        </div>
                        <p class="mb-0 text-white-50 small" style="max-width: 700px; font-size: 0.92rem; line-height: 1.5;">
                            Get an AI explanation of your best roommate match based on your lifestyle preferences.
                        </p>
                    </div>
                    <div class="col-md-4 col-lg-3 text-md-end">
                        <button type="button" class="btn btn-info text-dark fw-bold px-4 py-2.5 rounded-3 w-100 shadow-sm" id="btnAnalyzeBestMatch" data-best-match-id="{{ $bestMatch ? $bestMatch->id : '' }}" data-best-name="{{ $bestMatch ? $bestMatch->name : '' }}" data-best-score="{{ $bestMatch ? data_get($bestMatch, 'compatibility_score', 0) : '' }}">
                            ✨ Analyze My Best Match
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card rounded-4 shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
                                <div>
                                    <h5 class="mb-1 fw-bold" style="font-family: var(--font-display);">Current Roommate</h5>
                                    <p class="text-muted mb-0 small">Your active roommate is shown here. End the match when you're ready to find a new roommate.</p>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('roommate-match.index') }}" class="btn btn-outline-secondary btn-sm">My Roommate</a>
                                    <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm">Find Roommates</a>
                                </div>
                            </div>

                            @if(isset($currentMatches) && $currentMatches->isNotEmpty())
                                <div class="d-flex flex-column gap-3 mt-4">
                                    @foreach($currentMatches as $match)
                                        @php
                                            $roommate = $match->otherStudent($user);
                                        @endphp
                                        <div class="d-flex flex-column flex-lg-row align-items-center gap-4 p-3 rounded-4 border border-secondary border-opacity-10 bg-light bg-opacity-10 w-100">
                                            <div class="d-flex align-items-center gap-3">
                                                @if(optional($roommate->studentProfile)->profile_photo)
                                                    <img src="{{ asset('storage/' . $roommate->studentProfile->profile_photo) }}" alt="{{ $roommate->name }}" class="rounded-circle" style="width:72px;height:72px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary-solid d-flex align-items-center justify-content-center" style="width:72px;height:72px;">
                                                        <span class="fs-4">{{ strtoupper(substr($roommate->name, 0, 1)) }}</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h5 class="mb-1 fw-bold">{{ $roommate->name }}</h5>
                                                    <p class="text-secondary mb-1">{{ optional($roommate->studentProfile)->department ? strtoupper(optional($roommate->studentProfile)->department) : 'Department not set' }}</p>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge bg-secondary">ID: {{ optional($roommate->studentProfile)->student_id ?? 'N/A' }}</span>
                                                        <span class="badge bg-secondary">Hall: {{ optional($roommate->studentProfile)->hall ?? 'N/A' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center gap-3 ms-lg-auto">
                                                <div class="text-center">
                                                    <div class="fw-semibold fs-4 text-primary">{{ $match->compatibility_score ?? 0 }}%</div>
                                                    <div class="small text-secondary">Compatibility</div>
                                                </div>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <a href="{{ route('students.show', $roommate->id) }}" class="btn btn-outline-primary btn-sm">View Profile</a>
                                                    <form method="POST" action="{{ route('messages.open') }}" class="d-inline-block">
                                                        @csrf
                                                        <input type="hidden" name="other_user_id" value="{{ $roommate->id }}">
                                                        <button type="submit" class="btn btn-primary btn-sm">Message</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info mb-0 mt-3">No roommate assigned yet. Search for roommates to create a match or check your pending requests.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card rounded-4 shadow-sm h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0 fw-bold" style="font-family: var(--font-display);">Top Matches</h5>
                                <span class="badge bg-primary">Top 3</span>
                            </div>

                            @php
                                $medals = ['🥇', '🥈', '🥉'];
                            @endphp

                            <div class="d-flex flex-column gap-3">
                                @forelse($topMatches->take(3) as $index => $match)
                                    <a href="{{ route('students.show', $match->id) }}" class="text-decoration-none text-dark">
                                        <div class="d-flex align-items-center justify-content-between py-2 {{ !$loop->last ? 'border-bottom border-secondary border-opacity-10' : '' }}">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="text-center" style="width:30px">
                                                    <span class="fs-5">{{ $medals[$index] ?? '•' }}</span>
                                                </div>

                                                @if(optional($match->studentProfile)->profile_photo)
                                                    <img src="{{ asset('storage/' . $match->studentProfile->profile_photo) }}" alt="{{ $match->name }}" class="rounded-circle" style="width:38px;height:38px;object-fit:cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary-solid d-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                                                        <span class="fs-6 fw-semibold">{{ strtoupper(substr($match->name,0,1)) }}</span>
                                                    </div>
                                                @endif

                                                <div>
                                                    <div class="fw-semibold small text-truncate" style="max-width: 120px;">{{ $match->name }}</div>
                                                    <div class="x-small text-secondary" style="font-size: 0.75rem;">{{ optional($match->studentProfile)->department ? strtoupper(optional($match->studentProfile)->department) : 'N/A' }}</div>
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <div class="fw-bold small text-primary">{{ data_get($match, 'compatibility_score', 0) }}%</div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="text-secondary small">Complete your profile and preferences to see matches.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card rounded-4 border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h5 class="fw-bold mb-1" style="font-family: var(--font-display);">Find Roommates</h5>
                                <p class="text-muted small mb-0">Search and filter students directly using the roommate finder filters.</p>
                            </div>
                        </div>

                        <form method="GET" action="{{ route('students.index') }}">
                            <div class="row g-3 align-items-center">
                                <div class="col-lg-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                                        <input type="search" name="q" class="form-control border-start-0 ps-0" placeholder="Search by name, student ID, department, hall or batch..." value="{{ request('q') }}">
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <select name="sort_by" class="form-select">
                                        <option value="compatibility_desc" {{ request('sort_by') === 'compatibility_desc' ? 'selected' : '' }}>Highest Compatibility</option>
                                        <option value="compatibility_asc" {{ request('sort_by') === 'compatibility_asc' ? 'selected' : '' }}>Lowest Compatibility</option>
                                        <option value="newest" {{ request('sort_by') === 'newest' ? 'selected' : '' }}>Newest</option>
                                        <option value="oldest" {{ request('sort_by') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                                        <option value="name_asc" {{ request('sort_by') === 'name_asc' ? 'selected' : '' }}>Name A-Z</option>
                                        <option value="name_desc" {{ request('sort_by') === 'name_desc' ? 'selected' : '' }}>Name Z-A</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 d-flex gap-2">
                                    <button class="btn btn-soft-primary flex-grow-1" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
                                        <i class="bi bi-funnel"></i> Filters
                                    </button>
                                    <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i> Search</button>
                                </div>
                            </div>

                            @php
                                $hasActiveFilters = request()->filled('department') || request()->filled('batch') || request()->filled('hall') || request()->filled('gender') || request()->filled('sleep_schedule') || request()->filled('study_habit') || request()->filled('cleanliness') || request()->filled('smoking') || request()->filled('noise_tolerance') || request()->filled('room_temperature') || request()->filled('music_preference') || request()->filled('introvert_extrovert');
                            @endphp
                            <div class="collapse {{ $hasActiveFilters ? 'show' : '' }} mt-4 pt-3 border-top border-secondary border-opacity-10" id="advancedFilters">
                                <div class="row g-3">
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Department</label>
                                        <select name="department" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($departments as $department)
                                                <option value="{{ $department }}" {{ request('department') === $department ? 'selected' : '' }}>{{ strtoupper($department) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Batch</label>
                                        <select name="batch" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($batches as $batch)
                                                <option value="{{ $batch }}" {{ request('batch') === $batch ? 'selected' : '' }}>{{ $batch }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Hall</label>
                                        <select name="hall" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($halls as $hall)
                                                <option value="{{ $hall }}" {{ request('hall') === $hall ? 'selected' : '' }}>{{ ucwords($hall) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($genders as $gender)
                                                <option value="{{ $gender }}" {{ request('gender') === $gender ? 'selected' : '' }}>{{ ucfirst($gender) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Sleep</label>
                                        <select name="sleep_schedule" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($sleepSchedules as $sleepSchedule)
                                                <option value="{{ $sleepSchedule }}" {{ request('sleep_schedule') === $sleepSchedule ? 'selected' : '' }}>{{ ucwords($sleepSchedule) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Study Habit</label>
                                        <select name="study_habit" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($studyHabits as $studyHabit)
                                                <option value="{{ $studyHabit }}" {{ request('study_habit') === $studyHabit ? 'selected' : '' }}>{{ ucwords($studyHabit) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Cleanliness</label>
                                        <select name="cleanliness" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($cleanlinessLevels as $cleanliness)
                                                <option value="{{ $cleanliness }}" {{ request('cleanliness') === $cleanliness ? 'selected' : '' }}>{{ ucwords($cleanliness) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Smoking</label>
                                        <select name="smoking" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            <option value="yes" {{ request('smoking') === 'yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="no" {{ request('smoking') === 'no' ? 'selected' : '' }}>No</option>
                                            <option value="occasional" {{ request('smoking') === 'occasional' ? 'selected' : '' }}>Occasional</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Noise</label>
                                        <select name="noise_tolerance" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($noiseLevels as $noiseLevel)
                                                <option value="{{ $noiseLevel }}" {{ request('noise_tolerance') === $noiseLevel ? 'selected' : '' }}>{{ ucwords($noiseLevel) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Temperature</label>
                                        <select name="room_temperature" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($roomTemperatures as $roomTemperature)
                                                <option value="{{ $roomTemperature }}" {{ request('room_temperature') === $roomTemperature ? 'selected' : '' }}>{{ ucwords($roomTemperature) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Music</label>
                                        <select name="music_preference" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($musicPreferences as $musicPreference)
                                                <option value="{{ $musicPreference }}" {{ request('music_preference') === $musicPreference ? 'selected' : '' }}>{{ ucwords($musicPreference) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-6 col-md-4 col-lg-2">
                                        <label class="form-label">Personality</label>
                                        <select name="introvert_extrovert" class="form-select form-select-sm">
                                            <option value="">Any</option>
                                            @foreach($personalities as $personality)
                                                <option value="{{ $personality }}" {{ request('introvert_extrovert') === $personality ? 'selected' : '' }}>{{ ucfirst($personality) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 text-end mt-3 border-top border-secondary border-opacity-10 pt-3">
                                        <a href="{{ route('dashboard') }}" class="btn btn-soft-secondary btn-sm me-2">Clear Filters</a>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">Apply Filters</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                        <div>
                            <div class="d-flex flex-row overflow-auto gap-3 pb-3" style="scroll-behavior: smooth; -webkit-overflow-scrolling: touch;">
                                @forelse($recommendedUsers as $student)
                                    <div class="flex-shrink-0" style="width: 270px;">
                                        @include('partials.profile_card', ['student' => $student, 'hideConnect' => true])
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

    <!-- AI Roommate Advisor Modal -->
    <div class="modal fade" id="aiDashboardAdvisorModal" tabindex="-1" aria-labelledby="aiDashboardAdvisorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content rounded-4 shadow border-0">
                <div class="modal-header border-bottom border-secondary border-opacity-10 py-3">
                    <h5 class="modal-title fw-bold text-dark" id="aiDashboardAdvisorModalLabel">
                        <i class="bi bi-robot text-primary me-2"></i> 🤖 AI Roommate Analysis
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Loading State -->
                    <div id="aiAdvisorLoading" class="text-center py-5 d-none">
                        <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h6 class="fw-semibold text-secondary">Analyzing your roommate compatibility...</h6>
                        <p class="text-muted small mb-0">Re-evaluating roommate synergy and habits via Gemini AI.</p>
                    </div>

                    <!-- No Match State -->
                    <div id="aiAdvisorNoMatch" class="text-center py-5 d-none">
                        <i class="bi bi-people-fill text-muted mb-3" style="font-size: 3rem;"></i>
                        <h6 class="fw-semibold text-secondary">No Match Found</h6>
                        <p class="text-muted small mb-0 px-4">No compatible roommate found yet. Complete your profile and preferences to receive AI analysis.</p>
                    </div>

                    <!-- Error State -->
                    <div id="aiAdvisorError" class="alert alert-danger d-none" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <span id="aiAdvisorErrorMessage">Unable to generate AI analysis at the moment.</span>
                    </div>

                    <!-- Content State -->
                    <div id="aiAdvisorContent" class="d-none">
                        <div class="p-3 bg-light rounded-4 border border-secondary border-opacity-10 mb-4">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <small class="text-uppercase text-muted fw-bold d-block mb-1">Best Match</small>
                                    <h5 class="fw-bold text-dark mb-0" id="aiAdvisorMatchName"></h5>
                                </div>
                                <div class="col-4 text-end">
                                    <small class="text-uppercase text-muted fw-bold d-block mb-1">Compatibility</small>
                                    <span class="badge bg-success fs-6 fw-bold px-3 py-1.5" id="aiAdvisorMatchScore"></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold text-secondary text-uppercase small tracking-wider mb-2">Overall Summary</h6>
                            <p class="text-dark fs-6" id="aiAdvisorSummary" style="line-height: 1.6;"></p>
                        </div>

                        <hr class="my-4 border-secondary border-opacity-10">

                        <h6 class="fw-bold text-secondary text-uppercase small tracking-wider mb-3">
                            <i class="bi bi-arrow-left-right text-primary me-2"></i> Side-by-Side Comparison
                        </h6>

                        <div class="card border border-secondary border-opacity-10 shadow-sm overflow-hidden mb-4">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" style="min-width: 600px; font-size: 0.9rem;">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 25%;" class="ps-3">Preference / Habit</th>
                                            <th scope="col" style="width: 20%;">You</th>
                                            <th scope="col" style="width: 20%;" id="aiAdvisorBestMatchHeaderName">{{ $bestMatch ? $bestMatch->name : 'Best Match' }}</th>
                                            <th scope="col" style="width: 35%;" class="pe-3">AI Verdict</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sleep Schedule -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-moon-stars text-primary me-2"></i>Sleep Schedule
                                            </td>
                                            <td>{{ $preference->sleep_schedule ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->sleep_schedule ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-sleep_schedule">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Wake Up Time -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-alarm text-primary me-2"></i>Wake Up Time
                                            </td>
                                            <td>{{ $preference ? \Carbon\Carbon::parse($preference->wake_up_time)->format('h:i A') : 'N/A' }}</td>
                                            <td>{{ (optional($bestMatch)->studentPreference) ? \Carbon\Carbon::parse(optional($bestMatch)->studentPreference->wake_up_time)->format('h:i A') : 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-wake_up_time">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Study Habit -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-book text-primary me-2"></i>Study Habit
                                            </td>
                                            <td>{{ $preference->study_habit ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->study_habit ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-study_habit">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Cleanliness -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-brush text-primary me-2"></i>Cleanliness
                                            </td>
                                            <td>{{ $preference->cleanliness ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->cleanliness ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-cleanliness">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Smoking -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-capsule text-primary me-2"></i>Smoking
                                            </td>
                                            <td>{{ isset($preference->smoking) ? ($preference->smoking ? 'Smoker' : 'Non-smoker') : 'N/A' }}</td>
                                            <td>{{ isset(optional($bestMatch)->studentPreference->smoking) ? (optional($bestMatch)->studentPreference->smoking ? 'Smoker' : 'Non-smoker') : 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-smoking">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Noise Tolerance -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-volume-up text-primary me-2"></i>Noise Tolerance
                                            </td>
                                            <td>{{ $preference->noise_tolerance ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->noise_tolerance ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-noise_tolerance">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Room Temperature -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-thermometer-half text-primary me-2"></i>Temperature
                                            </td>
                                            <td>{{ $preference->room_temperature ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->room_temperature ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-room_temperature">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Music Preference -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-music-note-beamed text-primary me-2"></i>Music Preference
                                            </td>
                                            <td>{{ $preference->music_preference ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->music_preference ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-music_preference">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Lights Preference -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-lightbulb text-primary me-2"></i>Lights Preference
                                            </td>
                                            <td>{{ $preference->lights_preference ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->lights_preference ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-lights_preference">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Introvert Extrovert -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-people text-primary me-2"></i>Personality Type
                                            </td>
                                            <td>{{ $preference->introvert_extrovert ?? 'N/A' }}</td>
                                            <td>{{ optional($bestMatch)->studentPreference->introvert_extrovert ?? 'N/A' }}</td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-introvert_extrovert">
                                                Loading...
                                            </td>
                                        </tr>

                                        <!-- Hobbies -->
                                        <tr>
                                            <td class="ps-3 fw-semibold text-muted">
                                                <i class="bi bi-controller text-primary me-2"></i>Hobbies / Interests
                                            </td>
                                            <td><div class="text-truncate" style="max-width: 120px;" title="{{ $preference->hobbies ?? 'N/A' }}">{{ $preference->hobbies ?? 'N/A' }}</div></td>
                                            <td><div class="text-truncate" style="max-width: 120px;" title="{{ optional($bestMatch)->studentPreference->hobbies ?? 'N/A' }}">{{ optional($bestMatch)->studentPreference->hobbies ?? 'N/A' }}</div></td>
                                            <td class="pe-3 text-muted small" id="ai-advisor-insight-hobbies">
                                                Loading...
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr class="my-4 border-secondary border-opacity-10">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-bold text-success text-uppercase small tracking-wider mb-3">
                                    <i class="bi bi-plus-circle-fill me-2"></i> Key Strengths
                                </h6>
                                <ul class="list-unstyled d-flex flex-column gap-2 small text-dark" id="aiAdvisorStrengths">
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold text-warning text-uppercase small tracking-wider mb-3">
                                    <i class="bi bi-dash-circle-fill me-2"></i> Possible Differences
                                </h6>
                                <ul class="list-unstyled d-flex flex-column gap-2 small text-dark" id="aiAdvisorDifferences">
                                </ul>
                            </div>
                        </div>

                        <hr class="my-4 border-secondary border-opacity-10">

                        <div class="mb-4">
                            <h6 class="fw-bold text-primary text-uppercase small tracking-wider mb-3">
                                <i class="bi bi-lightbulb-fill me-2"></i> Recommendations & Suggestions
                            </h6>
                            <ul class="list-unstyled d-flex flex-column gap-2 small text-dark" id="aiAdvisorSuggestions">
                            </ul>
                        </div>

                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10 d-flex align-items-center justify-content-between">
                            <span class="small fw-semibold text-muted">Final Recommendation</span>
                            <span class="badge fs-6 fw-bold px-3 py-2" id="aiAdvisorRecommendationBadge"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary px-4 btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnAnalyze = document.getElementById('btnAnalyzeBestMatch');
        if (!btnAnalyze) return;

        const bestMatchId = btnAnalyze.getAttribute('data-best-match-id');
        const modalElement = document.getElementById('aiDashboardAdvisorModal');
        const modalInstance = new bootstrap.Modal(modalElement);

        const loadingState = document.getElementById('aiAdvisorLoading');
        const noMatchState = document.getElementById('aiAdvisorNoMatch');
        const errorState = document.getElementById('aiAdvisorError');
        const errorMessage = document.getElementById('aiAdvisorErrorMessage');
        const contentState = document.getElementById('aiAdvisorContent');

        const matchNameEl = document.getElementById('aiAdvisorMatchName');
        const matchScoreEl = document.getElementById('aiAdvisorMatchScore');
        const summaryEl = document.getElementById('aiAdvisorSummary');
        const strengthsEl = document.getElementById('aiAdvisorStrengths');
        const differencesEl = document.getElementById('aiAdvisorDifferences');
        const suggestionsEl = document.getElementById('aiAdvisorSuggestions');
        const badgeEl = document.getElementById('aiAdvisorRecommendationBadge');

        btnAnalyze.addEventListener('click', function() {
            // Reset states
            loadingState.classList.add('d-none');
            noMatchState.classList.add('d-none');
            errorState.classList.add('d-none');
            contentState.classList.add('d-none');
            modalInstance.show();

            if (!bestMatchId) {
                // No match logic
                noMatchState.classList.remove('d-none');
                return;
            }

            // Reset table cells to Loading...
            const keys = ['sleep_schedule', 'wake_up_time', 'study_habit', 'cleanliness', 'smoking', 'noise_tolerance', 'room_temperature', 'music_preference', 'lights_preference', 'introvert_extrovert', 'hobbies'];
            keys.forEach(key => {
                const cell = document.getElementById(`ai-advisor-insight-${key}`);
                if (cell) {
                    cell.innerHTML = `<span class="text-secondary opacity-75">Loading...</span>`;
                }
            });

            // Loading state
            loadingState.classList.remove('d-none');
            btnAnalyze.disabled = true;
            btnAnalyze.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Analyzing...';

            // Post AJAX Request to existing explain compatibility endpoint
            fetch(`/students/${bestMatchId}/explain-compatibility`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || 'Server error'); });
                }
                return response.json();
            })
            .then(data => {
                const bestName = btnAnalyze.getAttribute('data-best-name') || 'Student';
                const bestScore = btnAnalyze.getAttribute('data-best-score') || '0';

                matchNameEl.textContent = bestName;
                matchScoreEl.textContent = `${bestScore}%`;

                const headerName = document.getElementById('aiAdvisorBestMatchHeaderName');
                if (headerName) {
                    headerName.textContent = bestName;
                }

                // Populate side-by-side table cells with AI comments
                if (data.comparison) {
                    Object.keys(data.comparison).forEach(key => {
                        const cell = document.getElementById(`ai-advisor-insight-${key}`);
                        if (cell) {
                            cell.innerHTML = `<span class="text-dark fw-semibold"><i class="bi bi-robot text-primary me-1"></i> ${data.comparison[key]}</span>`;
                        }
                    });
                }

                // Render content
                summaryEl.textContent = data.summary || 'No summary generated.';

                // Render Strengths
                strengthsEl.innerHTML = '';
                if (Array.isArray(data.strengths)) {
                    data.strengths.forEach(str => {
                        strengthsEl.innerHTML += `<li class="d-flex align-items-start gap-2"><i class="bi bi-check-lg text-success"></i> <span>${str}</span></li>`;
                    });
                }

                // Render Differences
                differencesEl.innerHTML = '';
                if (Array.isArray(data.differences)) {
                    data.differences.forEach(diff => {
                        differencesEl.innerHTML += `<li class="d-flex align-items-start gap-2"><i class="bi bi-x-lg text-warning"></i> <span>${diff}</span></li>`;
                    });
                }

                // Render Suggestions
                suggestionsEl.innerHTML = '';
                if (Array.isArray(data.suggestions)) {
                    data.suggestions.forEach(sug => {
                        suggestionsEl.innerHTML += `<li class="d-flex align-items-start gap-2"><i class="bi bi-arrow-right-short text-primary fs-5 leading-none"></i> <span>${sug}</span></li>`;
                    });
                }

                // Render recommendation badge
                const rec = (data.recommendation || 'Neutral').trim();
                badgeEl.textContent = rec;
                badgeEl.className = 'badge fs-6 fw-bold px-3 py-2'; // Reset

                if (rec === 'Highly Recommended') {
                    badgeEl.classList.add('bg-success');
                } else if (rec === 'Recommended') {
                    badgeEl.classList.add('bg-primary');
                } else if (rec === 'Not Recommended') {
                    badgeEl.classList.add('bg-danger');
                } else {
                    badgeEl.classList.add('bg-secondary');
                }

                // Switch display states
                loadingState.classList.add('d-none');
                contentState.classList.remove('d-none');
            })
            .catch(error => {
                errorMessage.textContent = 'Unable to generate AI analysis at the moment.';
                loadingState.classList.add('d-none');
                errorState.classList.remove('d-none');
            })
            .finally(() => {
                btnAnalyze.disabled = false;
                btnAnalyze.innerHTML = '✨ Analyze My Best Match';
            });
        });
    });
    </script>
@endsection
