@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Roommate Request History</h2>
                <p class="text-muted mb-0">All roommate request activity, including pending, accepted, rejected, and cancelled requests.</p>
            </div>
            <div class="text-md-end">
                <a href="{{ route('roommate-requests.index') }}" class="btn btn-outline-secondary">Back to Requests</a>
            </div>
        </div>

        @if($history->isEmpty())
            <div class="alert alert-info">You have no roommate request history yet.</div>
        @else
            <div class="row g-4">
                @foreach($history as $request)
                    @php
                        $other = $request->sender_id === auth()->id() ? $request->receiver : $request->sender;
                        $statusClass = match ($request->status) {
                            'accepted' => 'bg-success',
                            'pending' => 'bg-warning text-dark',
                            'rejected' => 'bg-danger',
                            'cancelled' => 'bg-secondary',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <div class="col-md-6">
                        <div class="card rounded-card card-soft shadow-soft h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    @if(optional($other->studentProfile)->profile_photo)
                                        <img src="{{ asset('storage/' . $other->studentProfile->profile_photo) }}" alt="Profile" class="rounded-circle" style="width:60px; height:60px; object-fit:cover;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:60px; height:60px;">
                                            {{ strtoupper(substr($other->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1">{{ $other->name }}</h5>
                                        <div class="text-muted small">{{ optional($other->studentProfile)->department ?? 'Department not set' }}</div>
                                        <div class="text-muted small">Batch {{ optional($other->studentProfile)->batch ?? 'N/A' }} • {{ optional($other->studentProfile)->hall ?? 'Hall not set' }}</div>
                                    </div>
                                    <span class="badge {{ $statusClass }} text-uppercase">{{ $request->status }}</span>
                                </div>

                                <div class="mb-3">
                                    <small class="text-muted">Request date</small>
                                    <div>{{ $request->created_at?->format('F j, Y • h:i A') }}</div>
                                </div>

                                @if($request->message)
                                    <div class="mb-3">
                                        <small class="text-uppercase text-muted">Message</small>
                                        <div class="border rounded-3 p-3 bg-light">{{ $request->message }}</div>
                                    </div>
                                @endif

                                @if($request->status === 'accepted')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="small text-muted">Compatibility</span>
                                            <strong>{{ $compatibility->calculateScore(auth()->user(), $other) }}%</strong>
                                        </div>
                                        <div class="progress progress-xs rounded-pill overflow-hidden">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $compatibility->calculateScore(auth()->user(), $other) }}%;" aria-valuenow="{{ $compatibility->calculateScore(auth()->user(), $other) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                @endif

                                <a href="{{ route('students.show', ['id' => $other->id]) }}" class="btn btn-gradient-primary w-100">View Student</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $history->links() }}</div>
        @endif
    </div>
@endsection
