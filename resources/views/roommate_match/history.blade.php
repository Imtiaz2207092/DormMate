@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Match History</h2>
                <p class="text-muted mb-0">Review your roommate match history, including active and ended matches.</p>
            </div>
            <div class="text-md-end d-flex flex-column flex-md-row gap-2">
                <a href="{{ route('roommate-match.index') }}" class="btn btn-outline-secondary">My Roommate</a>
                <a href="{{ route('students.index') }}" class="btn btn-primary">Find Roommates</a>
            </div>
        </div>

        @if($matches->isEmpty())
            <div class="alert alert-info">No roommate matches found yet.</div>
        @else
            <div class="timeline">
                @foreach($matches as $match)
                    @php
                        $other = auth()->id() === $match->student_one_id ? $match->studentTwo : $match->studentOne;
                        $statusClass = $match->status === 'active' ? 'bg-success' : 'bg-secondary';
                    @endphp
                    <div class="card rounded-card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $other->name }}</h5>
                                    <p class="text-muted mb-1">{{ optional($other->studentProfile)->department ?? 'N/A' }} • Batch {{ optional($other->studentProfile)->batch ?? 'N/A' }}</p>
                                    <div class="text-muted small">Matched on {{ $match->matched_at->format('F j, Y') }}</div>
                                </div>
                                <span class="badge {{ $statusClass }} text-uppercase">{{ $match->status }}</span>
                            </div>

                            <div class="row gy-3">
                                <div class="col-md-4">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Compatibility</small>
                                        <div class="mt-2 fw-semibold">{{ $match->compatibility_score }}%</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">Start Date</small>
                                        <div class="mt-2">{{ $match->matched_at->format('F j, Y') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded-3 p-3">
                                        <small class="text-uppercase text-muted">End Date</small>
                                        <div class="mt-2">{{ $match->ended_at?->format('F j, Y') ?? 'Ongoing' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 text-end">
                                <a href="{{ route('roommate-match.show', ['id' => $match->id]) }}" class="btn btn-outline-primary">View Match</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">{{ $matches->links() }}</div>
        @endif
    </div>
@endsection
