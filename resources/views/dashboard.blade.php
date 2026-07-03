@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Dashboard</h2>
                <p class="text-muted mb-0">Your DormMate control center for profile, preferences, and roommate discovery.</p>
            </div>
            <div class="text-md-end">
                <span class="badge bg-secondary">{{ now()->format('F j, Y') }}</span>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card rounded-card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted">Profile Status</h6>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <div>
                                <h4 class="mb-1">{{ $profile ? 'Completed' : 'Incomplete' }}</h4>
                                <p class="text-muted mb-0">Student profile completion status.</p>
                            </div>
                            <span class="badge {{ $profile ? 'bg-success' : 'bg-secondary' }}">{{ $profile ? 'Ready' : 'Setup' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded-card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted">Preference Status</h6>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <div>
                                <h4 class="mb-1">{{ $preference ? 'Completed' : 'Incomplete' }}</h4>
                                <p class="text-muted mb-0">Lifestyle preferences readiness.</p>
                            </div>
                            <span class="badge {{ $preference ? 'bg-success' : 'bg-secondary' }}">{{ $preference ? 'Ready' : 'Update' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card rounded-card shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted">Match Readiness</h6>
                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <div>
                                <h4 class="mb-1">{{ $completionPercentage }}%</h4>
                                <p class="text-muted mb-0">Overall completion across profile and preferences.</p>
                            </div>
                            <span class="badge bg-primary">Dashboard</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (! $profile)
            <div class="alert alert-warning rounded-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>You have not completed your profile yet.</strong>
                        <div class="mt-1">
                            <a href="{{ route('profile.create') }}" class="btn btn-sm btn-primary">Complete Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if (! $preference)
            <div class="alert alert-info rounded-4">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i>
                    <div>
                        <strong>You have not completed your lifestyle preferences.</strong>
                        <div class="mt-1">
                            <a href="{{ route('preferences.create') }}" class="btn btn-sm btn-outline-primary">Complete Preferences</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-xl-4">
                <div class="card shadow-sm rounded-4 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center" style="width:64px; height:64px;">
                                <i class="bi bi-person-fill fs-3"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Good {{ now()->hour < 12 ? 'Morning' : (now()->hour < 18 ? 'Afternoon' : 'Evening') }}, {{ $user->name }}</h5>
                                <p class="text-muted mb-0">Welcome back to your DormMate dashboard.</p>
                            </div>
                        </div>

                        <div class="row gy-2">
                            <div class="col-6">
                                <small class="text-uppercase text-muted">Department</small>
                                <div>{{ $profile?->department ?? 'Not available' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase text-muted">Batch</small>
                                <div>{{ $profile?->batch ?? 'Not available' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase text-muted">Hall</small>
                                <div>{{ $profile?->hall ?? 'Not available' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-uppercase text-muted">Email</small>
                                <div>{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm rounded-4 mt-4 h-100">
                    <div class="card-body">
                        <h5 class="card-title">Profile Completion</h5>
                        <div class="mb-3">
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionPercentage }}%;" aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <p class="mb-2">Completion: <strong>{{ $completionPercentage }}%</strong></p>
                        @if(count($missingFields))
                            <div class="small text-muted">Missing fields:</div>
                            <ul class="list-unstyled mb-0">
                                @foreach($missingFields as $field)
                                    <li class="text-danger">• {{ $field }}</li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-success">Your profile and preferences are complete.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="row g-4">
                    <div class="col-lg-12">
                        <div class="card shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <h5 class="card-title">Profile Summary</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><strong>Student ID</strong><div>{{ $profile?->student_id ?? 'Not provided' }}</div></div>
                                    <div class="col-md-6 mb-3"><strong>Department</strong><div>{{ $profile?->department ?? 'Not provided' }}</div></div>
                                    <div class="col-md-6 mb-3"><strong>Batch</strong><div>{{ $profile?->batch ?? 'Not provided' }}</div></div>
                                    <div class="col-md-6 mb-3"><strong>Hall</strong><div>{{ $profile?->hall ?? 'Not provided' }}</div></div>
                                    <div class="col-md-6 mb-3"><strong>Email</strong><div>{{ $user->email }}</div></div>
                                    <div class="col-md-6 mb-3"><strong>Phone</strong><div>{{ $profile?->phone ?? 'Not provided' }}</div></div>
                                    <div class="col-md-6 mb-3"><strong>Gender</strong><div>{{ $profile?->gender ? ucfirst($profile->gender) : 'Not provided' }}</div></div>
                                    <div class="col-md-6 mb-3"><strong>Blood Group</strong><div>{{ $profile?->blood_group ?? 'Not available' }}</div></div>
                                    <div class="col-12 mb-3"><strong>Bio</strong><div>{{ $profile?->bio ?? 'Not provided' }}</div></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <h5 class="card-title">Preference Summary</h5>
                                @if($preference)
                                    <div class="row">
                                        <div class="col-md-6 mb-3"><strong>Sleep Schedule</strong><div>{{ $preference->sleep_schedule }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Wake Up Time</strong><div>{{ $preference->wake_up_time }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Study Habit</strong><div>{{ $preference->study_habit }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Cleanliness</strong><div>{{ $preference->cleanliness }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Smoking</strong><div>{{ $preference->smoking ? 'Yes' : 'No' }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Noise Tolerance</strong><div>{{ $preference->noise_tolerance }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Room Temperature</strong><div>{{ $preference->room_temperature }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Music Preference</strong><div>{{ $preference->music_preference }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Personality</strong><div>{{ $preference->introvert_extrovert }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Hobbies</strong><div>{{ $preference->hobbies }}</div></div>
                                        <div class="col-md-6 mb-3"><strong>Languages</strong><div>{{ $preference->languages }}</div></div>
                                    </div>
                                @else
                                    <div class="text-muted">No lifestyle preference data available yet.</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <h5 class="card-title">Quick Actions</h5>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('profile.show') }}" class="btn btn-outline-primary">My Profile</a>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                                    <a href="{{ route('preferences.index') }}" class="btn btn-outline-primary">Preferences</a>
                                    <a href="{{ route('preferences.edit') }}" class="btn btn-primary">Edit Preferences</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <h5 class="card-title">Account Information</h5>
                                <dl class="row mb-0">
                                    <dt class="col-5">Member Since</dt>
                                    <dd class="col-7">{{ $user->created_at?->format('F j, Y') ?? 'Unknown' }}</dd>
                                    <dt class="col-5">Last Updated</dt>
                                    <dd class="col-7">{{ $user->updated_at?->format('F j, Y') ?? 'Unknown' }}</dd>
                                    <dt class="col-5">Email Verified</dt>
                                    <dd class="col-7">{{ $user->email_verified_at ? 'Verified' : 'Not verified' }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm rounded-4 h-100">
                            <div class="card-body">
                                <h5 class="card-title">System Status</h5>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Profile Status</span>
                                            <span class="badge {{ $profile ? 'bg-success' : 'bg-secondary' }}">{{ $profileStatus }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Preferences Status</span>
                                            <span class="badge {{ $preference ? 'bg-success' : 'bg-secondary' }}">{{ $preferenceStatus }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Dashboard Status</span>
                                            <span class="badge bg-success">{{ $dashboardStatus }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card shadow-sm rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1">Top Compatible Students</h5>
                                        <p class="text-muted small mb-0">Top 3 matches are shown here. Tap to view the full top 10 list.</p>
                                    </div>
                                    <a href="{{ route('students.index', ['sort_by' => 'compatibility_desc']) }}" class="btn btn-sm btn-outline-primary">See Top 10</a>
                                </div>

                                @if($topMatches->isEmpty())
                                    <div class="alert alert-info mb-0">Complete your profile and preferences to see top matches.</div>
                                @else
                                    <div class="list-group list-group-flush">
                                        @foreach($topMatches->take(3) as $index => $match)
                                            <div class="list-group-item px-0 py-3 border-0">
                                                <div class="d-flex align-items-center justify-content-between gap-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <span class="fs-4">
                                                            @if($index === 0) 🥇 @elseif($index === 1) 🥈 @else 🥉 @endif
                                                        </span>
                                                        <div>
                                                            <h6 class="mb-0">{{ $match->name }}</h6>
                                                            <small class="text-muted">{{ optional($match->studentProfile)->department ?? 'Department not set' }}</small>
                                                        </div>
                                                    </div>
                                                    <span class="badge bg-success rounded-pill">{{ $match->compatibility_score }}%</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-3 text-end">
                                        <a href="{{ route('students.index', ['sort_by' => 'compatibility_desc']) }}" class="btn btn-sm btn-primary">View Top 10 Matches</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="row g-3">
                            @foreach([ 'Compatibility Score', 'Find Roommates', 'Roommate Requests', 'Notifications', 'Matches' ] as $item)
                                <div class="col-sm-6 col-xl-4">
                                    <div class="card shadow-sm rounded-4 h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center justify-content-between mb-3">
                                                <div>
                                                    <h6 class="mb-1">{{ $item }}</h6>
                                                    <p class="text-muted small mb-0">Coming soon</p>
                                                </div>
                                                <span class="badge bg-warning text-dark">Coming Soon</span>
                                            </div>
                                            <p class="small text-muted mb-0">This feature will be available in the next version.</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
