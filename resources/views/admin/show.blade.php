@extends('layouts.admin')

@section('admin_content')
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
        <h2 class="mb-1">User Details: {{ $user->name }}</h2>
        <p class="text-muted">Detailed account information and student roommate preferences.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card rounded-4 border-0 shadow-sm p-4 text-center">
                @if(optional($user->studentProfile)->profile_photo)
                    <img src="{{ asset('storage/' . $user->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle mb-3 mx-auto" style="width:130px;height:130px;object-fit:cover;">
                @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-3 mx-auto fw-bold" style="width:130px;height:130px;font-size:2.5rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    @if($user->user_type === 'admin')
                        <span class="badge bg-danger">Admin Account</span>
                    @else
                        <span class="badge bg-secondary">Student Account</span>
                    @endif

                    @if($user->active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-warning text-dark">Suspended</span>
                    @endif
                </div>
                <div class="d-grid gap-2 mt-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-pencil"></i> Edit Account
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card rounded-4 border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Student Profile</h5>
                    @if($user->studentProfile)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Department</small>
                                <strong>{{ $user->studentProfile->department ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Batch</small>
                                <strong>{{ $user->studentProfile->batch ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Student ID</small>
                                <strong>{{ $user->studentProfile->student_id ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Hall</small>
                                <strong>{{ ucwords($user->studentProfile->hall ?? 'N/A') }}</strong>
                            </div>
                            <div class="col-12">
                                <small class="text-muted d-block">Bio</small>
                                <p class="mb-0 text-secondary">{{ $user->studentProfile->bio ?? 'No bio provided.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-muted small">This user hasn't created a student profile yet.</div>
                    @endif
                </div>
            </div>

            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold border-bottom pb-2 mb-3">Lifestyle Preferences</h5>
                    @if($user->studentPreference)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted d-block">Sleep Schedule</small>
                                <strong>{{ ucwords($user->studentPreference->sleep_schedule ?? 'N/A') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Wake Up Time</small>
                                <strong>{{ $user->studentPreference->wake_up_time ?? 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Study Habit</small>
                                <strong>{{ ucwords($user->studentPreference->study_habit ?? 'N/A') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Cleanliness</small>
                                <strong>{{ ucwords($user->studentPreference->cleanliness ?? 'N/A') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Smoking</small>
                                <strong>{{ isset($user->studentPreference->smoking) ? ($user->studentPreference->smoking ? 'Yes (Smoker)' : 'No (Non-smoker)') : 'N/A' }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Noise Tolerance</small>
                                <strong>{{ ucwords($user->studentPreference->noise_tolerance ?? 'N/A') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Room Temperature</small>
                                <strong>{{ ucwords($user->studentPreference->room_temperature ?? 'N/A') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Music Preference</small>
                                <strong>{{ ucwords($user->studentPreference->music_preference ?? 'N/A') }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block">Personality</small>
                                <strong>{{ ucwords($user->studentPreference->introvert_extrovert ?? 'N/A') }}</strong>
                            </div>
                        </div>
                    @else
                        <div class="text-muted small">This user hasn't set their roommate lifestyle preferences yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
