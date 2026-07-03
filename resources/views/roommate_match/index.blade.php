@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">My Roommate</h2>
                <p class="text-muted mb-0">View your active roommate match and manage the relationship.</p>
            </div>
            <div class="text-md-end d-flex flex-column flex-md-row gap-2">
                <a href="{{ route('roommate-match.history') }}" class="btn btn-outline-secondary">Match History</a>
                <a href="{{ route('students.index') }}" class="btn btn-primary">Find Roommates</a>
            </div>
        </div>

        @if($currentMatch && $otherStudent)
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card rounded-card shadow-sm text-center p-4">
                        <div class="card-body">
                            @if(optional($otherStudent->studentProfile)->profile_photo)
                                <img src="{{ asset('storage/' . $otherStudent->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle profile-photo-lg mb-3">
                            @else
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center profile-photo-lg profile-avatar-large mb-3">
                                    {{ strtoupper(substr($otherStudent->name, 0, 1)) }}
                                </div>
                            @endif

                            <h4 class="mb-1">{{ $otherStudent->name }}</h4>
                            <p class="text-muted mb-3">{{ optional($otherStudent->studentProfile)->department ?? 'Department not set' }}</p>

                            <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                                <span class="badge bg-info text-dark">ID: {{ optional($otherStudent->studentProfile)->student_id ?? 'N/A' }}</span>
                                <span class="badge bg-secondary">Batch: {{ optional($otherStudent->studentProfile)->batch ?? 'N/A' }}</span>
                            </div>

                            <div class="mb-3 text-center">
                                <span class="badge bg-success text-uppercase">Active</span>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small text-muted">Compatibility</span>
                                    <strong>{{ $currentMatch->compatibility_score }}%</strong>
                                </div>
                                <div class="progress progress-sm rounded-pill overflow-hidden">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $currentMatch->compatibility_score }}%;" aria-valuenow="{{ $currentMatch->compatibility_score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="text-muted small">Matched since {{ $currentMatch->matched_at->format('F j, Y') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="card rounded-card shadow-sm p-4">
                        <div class="card-body">
                            <h5 class="mb-3">Match Details</h5>
                            <dl class="row gy-3 mb-4">
                                <dt class="col-4 text-muted">Hall</dt>
                                <dd class="col-8">{{ optional($otherStudent->studentProfile)->hall ?? 'N/A' }}</dd>

                                <dt class="col-4 text-muted">Matched Date</dt>
                                <dd class="col-8">{{ $currentMatch->matched_at->format('F j, Y') }}</dd>

                                <dt class="col-4 text-muted">Match Status</dt>
                                <dd class="col-8 text-capitalize">{{ $currentMatch->status }}</dd>

                                <dt class="col-4 text-muted">Department</dt>
                                <dd class="col-8">{{ optional($otherStudent->studentProfile)->department ?? 'N/A' }}</dd>

                                <dt class="col-4 text-muted">Batch</dt>
                                <dd class="col-8">{{ optional($otherStudent->studentProfile)->batch ?? 'N/A' }}</dd>

                                <dt class="col-4 text-muted">Bio</dt>
                                <dd class="col-8">{{ optional($otherStudent->studentProfile)->bio ?? 'No bio provided.' }}</dd>
                            </dl>

                            <h6 class="mb-3">Lifestyle Summary</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Sleep</small>
                                        <div class="mt-2">{{ optional($otherStudent->studentPreference)->sleep_schedule ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Study</small>
                                        <div class="mt-2">{{ optional($otherStudent->studentPreference)->study_habit ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Cleanliness</small>
                                        <div class="mt-2">{{ optional($otherStudent->studentPreference)->cleanliness ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Noise</small>
                                        <div class="mt-2">{{ optional($otherStudent->studentPreference)->noise_tolerance ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Room Temp</small>
                                        <div class="mt-2">{{ optional($otherStudent->studentPreference)->room_temperature ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Personality</small>
                                        <div class="mt-2">{{ optional($otherStudent->studentPreference)->introvert_extrovert ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <a href="{{ route('roommate-match.show', ['id' => $currentMatch->id]) }}" class="btn btn-outline-primary me-2">View Match</a>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#endMatchModal">End Match</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="endMatchModal" tabindex="-1" aria-labelledby="endMatchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow-soft border-0">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title" id="endMatchModalLabel">End Roommate Match</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('roommate-match.end') }}">
                            @csrf
                            <input type="hidden" name="match_id" value="{{ $currentMatch->id }}">
                            <div class="modal-body">
                                <p class="text-muted">Are you sure you want to end this roommate match? This will allow both students to send and receive new roommate requests again.</p>
                            </div>
                            <div class="modal-footer border-top-0">
                                <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">End Match</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info">You do not currently have an active roommate match. Search for a roommate to get started.</div>
        @endif
    </div>
@endsection
