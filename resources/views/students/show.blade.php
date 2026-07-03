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

        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card rounded-card shadow-sm p-4">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            <div class="card rounded-card shadow-soft h-100 p-4 text-center">
                                @if(optional($student->studentProfile)->profile_photo)
                                    <img src="{{ asset('storage/' . $student->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle mb-4" style="width:170px;height:170px;object-fit:cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-4" style="width:170px;height:170px;font-size:2.75rem;">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                @endif

                                <h2 class="h4 mb-1">{{ $student->name }}</h2>
                                <p class="text-muted mb-1">{{ optional($student->studentProfile)->department ?? 'Department not set' }}</p>
                                <p class="text-muted mb-3">{{ optional($student->studentProfile)->hall ?? 'Hall not set' }}</p>
                                <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                                    <span class="badge bg-info text-dark">Batch {{ optional($student->studentProfile)->batch ?? 'N/A' }}</span>
                                    <span class="badge bg-secondary">ID {{ optional($student->studentProfile)->student_id ?? 'N/A' }}</span>
                                </div>

                                @if($score !== null)
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="fw-bold">{{ $score }}%</strong>
                                            <span class="badge bg-success">Live</span>
                                        </div>
                                        <div class="progress rounded-pill overflow-hidden" style="height: 16px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $score }}%;" aria-valuenow="{{ $score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-grid gap-3">
                                    <form method="POST" action="{{ route('messages.open') }}">
                                        @csrf
                                        <input type="hidden" name="other_user_id" value="{{ $student->id }}">
                                        <button type="submit" class="btn btn-primary btn-lg">Chat</button>
                                    </form>

                                    @if($requestStatus === 'accepted')
                                        <button type="button" class="btn btn-success btn-lg">Roommate Match Accepted</button>
                                    @elseif($requestStatus === 'pending')
                                        <button type="button" class="btn btn-warning text-dark btn-lg">Request Pending</button>
                                    @elseif($requestStatus === 'rejected')
                                        <button type="button" class="btn btn-gradient-danger btn-lg">Request Rejected</button>
                                    @elseif($canSendRequest)
                                        <button type="button" class="btn btn-gradient-primary btn-lg" data-bs-toggle="modal" data-bs-target="#profileRequestModal">Send Roommate Request</button>
                                    @else
                                        <button type="button" class="btn btn-soft-secondary btn-lg" disabled>Roommate Match Unavailable</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <div>
                                    <h3 class="h5 mb-2">About</h3>
                                    <p class="text-muted mb-0">{{ optional($student->studentProfile)->bio ?? 'No bio available.' }}</p>
                                </div>
                                <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">Back to search</a>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Department</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->department ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Hall</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->hall ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Batch</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->batch ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Student ID</small>
                                        <div class="mt-2">{{ optional($student->studentProfile)->student_id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-5 mb-3">Lifestyle Preferences</h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Sleep Schedule</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->sleep_schedule ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Wake Up Time</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->wake_up_time ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Study Habit</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->study_habit ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Cleanliness</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->cleanliness ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Smoking</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->smoking ? 'Yes' : 'No' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Noise Tolerance</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->noise_tolerance ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Room Temperature</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->room_temperature ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Music Preference</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->music_preference ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <small class="text-uppercase text-muted">Lights Preference</small>
                                        <div class="mt-2">{{ optional($student->studentPreference)->lights_preference ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
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

        <div class="modal fade" id="profileRequestModal" tabindex="-1" aria-labelledby="profileRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-soft border-0">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="profileRequestModalLabel">Send Roommate Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('roommate-requests.send') }}">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $student->id }}">
                        <div class="modal-body">
                            <p class="text-muted">Send your request directly to {{ $student->name }}.</p>
                            <div class="mb-3">
                                <label class="form-label">Optional Message</label>
                                <textarea name="message" class="form-control" rows="4" maxlength="250" placeholder="Write a short note (optional)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-gradient-primary">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
