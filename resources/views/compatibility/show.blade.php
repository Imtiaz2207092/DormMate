@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Compatibility Detail</h2>
                <p class="text-muted mb-0">Compare your preferences with {{ $match->name }}.</p>
            </div>
            <div class="text-end">
                <a href="{{ route('compatibility.index') }}" class="btn btn-outline-secondary">Back to Matches</a>
            </div>
        </div>

        <div class="card shadow-sm rounded-4">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="text-center">
                            @if(optional($match->studentProfile)->profile_photo)
                                <img src="{{ asset('storage/' . $match->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle mb-3" style="width:128px; height:128px; object-fit:cover;">
                            @else
                                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3" style="width:128px; height:128px; font-size:2rem;">
                                    {{ strtoupper(substr($match->name, 0, 1)) }}
                                </div>
                            @endif
                            <h4 class="mb-1">{{ $match->name }}</h4>
                            <p class="text-muted mb-0">{{ optional($match->studentProfile)->department ?? 'Department not set' }}</p>
                            <p class="text-muted">{{ optional($match->studentProfile)->hall ?? 'Hall not set' }}</p>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h5 class="mb-0">Compatibility Score</h5>
                                    <p class="text-muted mb-0">Instantly calculated from the latest preferences.</p>
                                </div>
                                <span class="badge bg-success fs-5">{{ $score }}%</span>
                            </div>
                            <div class="progress" style="height: 18px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $score }}%;" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Sleep Schedule</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->sleep_schedule ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Wake Up Time</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->wake_up_time ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Study Habit</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->study_habit ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Cleanliness</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->cleanliness ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Smoking</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->smoking ? 'Yes' : 'No' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Noise Tolerance</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->noise_tolerance ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Room Temperature</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->room_temperature ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Music Preference</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->music_preference ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Lights Preference</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->lights_preference ?? 'Not set' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <small class="text-uppercase text-muted">Personality</small>
                                    <div class="mt-2">{{ optional($match->studentPreference)->introvert_extrovert ?? 'Not set' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
