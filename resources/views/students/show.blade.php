@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Student Profile</h2>
                <p class="text-muted mb-0">Public profile view for roommate discovery.</p>
            </div>
            <div class="text-md-end">
                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Back to search</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card rounded-card shadow-sm text-center py-4">
                    <div class="card-body">
                        @if(optional($student->studentProfile)->profile_photo)
                            <img src="{{ asset('storage/' . $student->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle profile-photo-lg mb-3">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center profile-photo-lg profile-avatar-large mb-3">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                        @endif

                        <h4 class="mb-1">{{ $student->name }}</h4>
                        <p class="text-muted mb-2">{{ optional($student->studentProfile)->department ?? 'Department not set' }}</p>
                        <p class="text-muted mb-0">{{ optional($student->studentProfile)->hall ?? 'Hall not set' }} • Batch {{ optional($student->studentProfile)->batch ?? 'N/A' }}</p>

                        @if($score !== null)
                            <div class="mt-4 text-start">
                                <h6 class="mb-2">Compatibility</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $score }}%</strong>
                                    <span class="badge bg-success">Live</span>
                                </div>
                                <div class="progress" style="height:12px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $score }}%;" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endif

                        <button type="button" class="btn btn-outline-secondary w-100 mt-4" disabled>Send Roommate Request</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card rounded-card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">About</h5>
                        <p class="text-muted">{{ optional($student->studentProfile)->bio ?? 'No bio available.' }}</p>

                        <div class="row g-3 mt-4">
                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Student ID</small>
                                    <div class="mt-2">{{ optional($student->studentProfile)->student_id ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Department</small>
                                    <div class="mt-2">{{ optional($student->studentProfile)->department ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Batch</small>
                                    <div class="mt-2">{{ optional($student->studentProfile)->batch ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Hall</small>
                                    <div class="mt-2">{{ optional($student->studentProfile)->hall ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mt-5 mb-3">Lifestyle Preferences</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Sleep Schedule</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->sleep_schedule ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Wake Up Time</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->wake_up_time ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Study Habit</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->study_habit ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Cleanliness</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->cleanliness ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Smoking</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->smoking ? 'Yes' : 'No' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Noise Tolerance</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->noise_tolerance ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Room Temperature</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->room_temperature ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Music Preference</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->music_preference ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Lights Preference</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->lights_preference ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3">
                                    <small class="text-uppercase text-muted">Personality</small>
                                    <div class="mt-2">{{ optional($student->studentPreference)->introvert_extrovert ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
