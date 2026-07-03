@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Roommate Match Details</h2>
                <p class="text-muted mb-0">Review your active or past roommate match details and manage the connection.</p>
            </div>
            <div class="text-md-end d-flex flex-column flex-md-row gap-2">
                <a href="{{ route('roommate-match.index') }}" class="btn btn-outline-secondary">My Roommate</a>
                <a href="{{ route('roommate-match.history') }}" class="btn btn-outline-secondary">Match History</a>
                <a href="{{ route('students.index') }}" class="btn btn-primary">Find Roommates</a>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="card rounded-card shadow-sm text-center p-4 mx-auto">
                    <div class="card-body">
                        @if(optional($otherStudent->studentProfile)->profile_photo)
                            <img src="{{ asset('storage/' . $otherStudent->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle profile-photo-xl mb-3" style="width:140px;height:140px;object-fit:cover;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-3" style="width:140px;height:140px;font-size:2rem;">
                                {{ strtoupper(substr($otherStudent->name, 0, 1)) }}
                            </div>
                        @endif

                        <h2 class="mb-1">{{ $otherStudent->name }}</h2>
                        <p class="text-muted mb-2">{{ optional($otherStudent->studentProfile)->department ?? 'Department not set' }}</p>
                        <p class="text-muted mb-3">{{ optional($otherStudent->studentProfile)->hall ?? 'Hall not set' }} • Batch {{ optional($otherStudent->studentProfile)->batch ?? 'N/A' }}</p>

                        <div class="d-flex justify-content-center flex-wrap gap-2 mb-4">
                            <span class="badge bg-secondary">ID: {{ optional($otherStudent->studentProfile)->student_id ?? 'N/A' }}</span>
                            <span class="badge bg-secondary">Hall: {{ optional($otherStudent->studentProfile)->hall ?? 'N/A' }}</span>
                            <span class="badge bg-{{ $match->status === 'active' ? 'success' : 'secondary' }} text-uppercase">{{ $match->status }}</span>
                        </div>

                        <div class="mb-4 mx-auto" style="max-width: 540px;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Compatibility</span>
                                <strong>{{ $match->compatibility_score }}%</strong>
                            </div>
                            <div class="progress progress-sm rounded-pill overflow-hidden">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $match->compatibility_score }}%;" aria-valuenow="{{ $match->compatibility_score }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <div class="text-muted small">Matched on {{ $match->matched_at->format('F j, Y') }}</div>
                        @if($match->ended_at)
                            <div class="text-muted small">Ended on {{ $match->ended_at->format('F j, Y') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card rounded-card shadow-sm p-4">
                    <div class="card-body">
                        <h5 class="mb-3 text-center">Match Summary</h5>
                        <p class="text-muted text-center mb-4">Here are the details for your roommate match. Use this page to track compatibility, shared preferences, and match history.</p>

                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-4 mb-4">
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Student ID</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentProfile)->student_id ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Department</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentProfile)->department ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Batch</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentProfile)->batch ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Hall</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentProfile)->hall ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 text-center">Lifestyle Overview</h5>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-4">
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Sleep Schedule</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentPreference)->sleep_schedule ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Study Habit</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentPreference)->study_habit ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Cleanliness</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentPreference)->cleanliness ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Noise Tolerance</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentPreference)->noise_tolerance ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Room Temperature</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentPreference)->room_temperature ?? 'N/A' }}</div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="border rounded-3 p-4 text-center h-100" style="min-height:170px;">
                                    <small class="text-muted d-block mb-2">Personality</small>
                                    <div class="fs-5">{{ optional($otherStudent->studentPreference)->introvert_extrovert ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        @if($match->status === 'active')
                            <div class="mt-4 text-center">
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#endMatchModal">End Match</button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($match->status === 'active')
            <div class="modal fade" id="endMatchModal" tabindex="-1" aria-labelledby="endMatchModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content rounded-4 shadow-soft border-0">
                        <div class="modal-header border-bottom-0">
                            <h5 class="modal-title" id="endMatchModalLabel">End Roommate Match</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="{{ route('roommate-match.end') }}">
                            @csrf
                            <input type="hidden" name="match_id" value="{{ $match->id }}">
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
        @endif
    </div>
@endsection
