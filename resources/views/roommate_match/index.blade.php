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

        @if(isset($activeMatches) && $activeMatches->isNotEmpty())
            <div class="row g-4">
                @foreach($activeMatches as $match)
                    @php
                        $otherStudent = $match->otherStudent($user);
                    @endphp
                    <div class="col-md-6 col-lg-4">
                        <div class="card rounded-card shadow-sm h-100 p-4 border border-secondary border-opacity-10">
                            <div class="card-body d-flex flex-column text-center h-100">
                                <div class="profile-avatar mb-3">
                                    @if(optional($otherStudent->studentProfile)->profile_photo)
                                        <img src="{{ asset('storage/' . $otherStudent->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle profile-photo-lg mb-3" style="width:90px;height:90px;object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center profile-photo-lg mx-auto mb-3" style="width:90px;height:90px;background-color:var(--primary);">
                                            <span class="fs-2">{{ strtoupper(substr($otherStudent->name, 0, 1)) }}</span>
                                        </div>
                                    @endif
                                </div>

                                <h4 class="mb-1 fw-bold">{{ $otherStudent->name }}</h4>
                                <p class="text-muted mb-3">{{ optional($otherStudent->studentProfile)->department ? strtoupper(optional($otherStudent->studentProfile)->department) : 'Department not set' }}</p>

                                <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                                    <span class="badge bg-info text-dark">ID: {{ optional($otherStudent->studentProfile)->student_id ?? 'N/A' }}</span>
                                    <span class="badge bg-secondary">Batch: {{ optional($otherStudent->studentProfile)->batch ?? 'N/A' }}</span>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="small text-muted">Compatibility</span>
                                        <strong>{{ $match->compatibility_score }}%</strong>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $match->compatibility_score }}%;" aria-valuenow="{{ $match->compatibility_score }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <div class="text-muted small mb-4">Matched since {{ $match->matched_at->format('F j, Y') }}</div>

                                <div class="mt-auto d-flex gap-2">
                                    <a href="{{ route('students.show', $otherStudent->id) }}" class="btn btn-outline-primary btn-sm flex-grow-1">Profile</a>
                                    <form method="POST" action="{{ route('messages.open') }}" class="m-0">
                                        @csrf
                                        <input type="hidden" name="other_user_id" value="{{ $otherStudent->id }}">
                                        <button type="submit" class="btn btn-primary btn-sm px-3" title="Message"><i class="bi bi-chat-dots"></i></button>
                                    </form>
                                    <button type="button" class="btn btn-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#endMatchModal-{{ $match->id }}" title="End Match"><i class="bi bi-person-x"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="endMatchModal-{{ $match->id }}" tabindex="-1" aria-labelledby="endMatchModalLabel-{{ $match->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content rounded-4 shadow-soft border-0">
                                <div class="modal-header border-bottom-0">
                                    <h5 class="modal-title" id="endMatchModalLabel-{{ $match->id }}">End Roommate Match</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="{{ route('roommate-match.end') }}">
                                    @csrf
                                    <input type="hidden" name="match_id" value="{{ $match->id }}">
                                    <div class="modal-body text-start">
                                        <p class="text-muted">Are you sure you want to end your roommate match with <span class="fw-semibold">{{ $otherStudent->name }}</span>? This will free up slot for a new roommate match.</p>
                                    </div>
                                    <div class="modal-footer border-top-0">
                                        <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">End Match</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-info">You do not currently have any active roommate matches. Search for roommates to get started.</div>
        @endif
    </div>
@endsection
