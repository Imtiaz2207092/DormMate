@extends('layouts.app')

@section('content')
    <div class="py-4">
        <!-- Profile Header Section -->
        <div class="row g-4">
            <!-- Left Side: Profile Details -->
            <div class="col-lg-8">
                <div class="card rounded-4 shadow-sm border-0 mb-4 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex flex-column flex-md-row align-items-center gap-4 border-bottom border-secondary border-opacity-10 pb-4 mb-4">
                            <div class="profile-avatar-wrapper">
                                @if($profile->profile_photo)
                                    <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="photo" class="rounded-circle img-thumbnail shadow-sm" style="width: 110px; height: 110px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary-solid d-flex align-items-center justify-content-center text-white font-weight-bold shadow-sm" style="width: 110px; height: 110px; font-size: 2.5rem;">
                                        {{ strtoupper(substr($profile->user->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="text-center text-md-start flex-grow-1">
                                <h3 class="fw-bold mb-1" style="font-family: var(--font-display);">{{ $profile->user->name }}</h3>
                                <p class="text-muted mb-2">{{ $profile->user->email }}</p>
                                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                                    <span class="badge bg-primary">ID: {{ $profile->student_id }}</span>
                                    <span class="badge bg-secondary">Batch: {{ $profile->batch }}</span>
                                </div>
                            </div>
                            <div class="ms-md-auto mt-3 mt-md-0">
                                <a href="{{ route('profile.edit') }}" class="btn btn-soft-primary px-4 btn-sm"><i class="bi bi-pencil-square"></i> Edit Profile</a>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <span class="text-secondary small d-block mb-1">Department</span>
                                    <span class="fw-semibold text-dark">{{ strtoupper($profile->department) }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <span class="text-secondary small d-block mb-1">Residential Hall</span>
                                    <span class="fw-semibold text-dark">{{ ucwords($profile->hall) }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <span class="text-secondary small d-block mb-1">Phone Number</span>
                                    <span class="fw-semibold text-dark">{{ $profile->phone }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                    <span class="text-secondary small d-block mb-1">Gender</span>
                                    <span class="fw-semibold text-dark">{{ ucfirst($profile->gender) }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10 mb-4">
                                    <span class="text-secondary small d-block mb-2">Bio / About Me</span>
                                    <p class="text-dark mb-0" style="line-height: 1.6;">{{ $profile->bio ?? 'No bio has been added yet.' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Lifestyle & Preferences Section -->
                        <div class="border-top border-secondary border-opacity-10 pt-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="fw-bold mb-0" style="font-family: var(--font-display);">Lifestyle & Preferences</h5>
                                @if($profile->user->studentPreference)
                                    <a href="{{ route('preferences.edit') }}" class="btn btn-soft-primary btn-sm"><i class="bi bi-pencil-square"></i> Edit Preferences</a>
                                @endif
                            </div>

                            @if($profile->user->studentPreference)
                                @php
                                    $pref = $profile->user->studentPreference;
                                @endphp
                                <div class="row g-3">
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Sleep Schedule</span>
                                            <span class="fw-semibold text-dark">{{ ucwords($pref->sleep_schedule) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Wake Up Time</span>
                                            <span class="fw-semibold text-dark">{{ $pref->wake_up_time ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Study Habit</span>
                                            <span class="fw-semibold text-dark">{{ ucwords($pref->study_habit) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Cleanliness</span>
                                            <span class="fw-semibold text-dark">{{ ucwords($pref->cleanliness) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Smoking</span>
                                            <span class="fw-semibold text-dark">{{ ucfirst($pref->smoking) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Noise Tolerance</span>
                                            <span class="fw-semibold text-dark">{{ ucwords($pref->noise_tolerance) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Room Temperature</span>
                                            <span class="fw-semibold text-dark">{{ ucwords($pref->room_temperature) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Music Preference</span>
                                            <span class="fw-semibold text-dark">{{ ucwords($pref->music_preference) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-4">
                                        <div class="p-3 bg-light rounded-3 border border-secondary border-opacity-10">
                                            <span class="text-secondary small d-block mb-1">Personality</span>
                                            <span class="fw-semibold text-dark">{{ ucfirst($pref->introvert_extrovert) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info small mb-0 d-flex align-items-center justify-content-between">
                                    <span>No lifestyle preferences set yet.</span>
                                    <a href="{{ route('preferences.create') }}" class="btn btn-primary btn-sm">Add Preferences</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Current Roommate Match -->
            <div class="col-lg-4">
                <div class="card rounded-4 shadow-sm border-0 mb-4 h-100">
                    <div class="card-body p-4 d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-2" style="font-family: var(--font-display);">Current Roommate</h5>
                            <p class="text-muted small mb-4">Manage your active roommate match from your profile page.</p>

                            @if($currentRoommate)
                                <div class="text-center py-3 border border-secondary border-opacity-10 rounded-4 bg-light mb-4">
                                    @if(optional($currentRoommate->studentProfile)->profile_photo)
                                        <img src="{{ asset('storage/' . $currentRoommate->studentProfile->profile_photo) }}" alt="{{ $currentRoommate->name }}" class="rounded-circle shadow-sm mb-3" style="width: 80px; height: 80px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-primary-solid d-flex align-items-center justify-content-center text-white mx-auto shadow-sm mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
                                            {{ strtoupper(substr($currentRoommate->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <h6 class="fw-bold text-dark mb-1">{{ $currentRoommate->name }}</h6>
                                    <p class="text-secondary small mb-2">{{ optional($currentRoommate->studentProfile)->department ? strtoupper(optional($currentRoommate->studentProfile)->department) : 'Department not set' }}</p>
                                    <div class="d-flex justify-content-center gap-2">
                                        <span class="badge bg-secondary">ID: {{ optional($currentRoommate->studentProfile)->student_id ?? 'N/A' }}</span>
                                        <span class="badge bg-secondary">Hall: {{ optional($currentRoommate->studentProfile)->hall ?? 'N/A' }}</span>
                                    </div>
                                </div>

                                <div class="d-flex flex-column gap-2 mt-auto">
                                    <a href="{{ route('students.show', $currentRoommate->id) }}" class="btn btn-soft-primary btn-sm w-100">View Profile</a>
                                    <form method="POST" action="{{ route('roommate-match.end') }}" class="w-100">
                                        @csrf
                                        <input type="hidden" name="match_id" value="{{ $currentMatch->id }}">
                                        <button type="submit" class="btn btn-gradient-danger btn-sm w-100">End Match</button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-info small mb-0">No active roommate match found. Browse students to find a roommate or review your pending requests.</div>
                                <div class="mt-4">
                                    <a href="{{ route('students.index') }}" class="btn btn-primary btn-sm w-100">Find Roommates</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
