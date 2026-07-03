@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
            <div>
                <h2 class="mb-1">Roommate Requests</h2>
                <p class="text-muted mb-0">Manage incoming, outgoing, accepted, rejected and cancelled roommate requests.</p>
            </div>
            <div class="text-md-end d-flex flex-column flex-md-row gap-2">
                <a href="{{ route('roommate-requests.history') }}" class="btn btn-outline-secondary">Request History</a>
                <a href="{{ route('students.index') }}" class="btn btn-primary">Find Roommates</a>
            </div>
        </div>

        <ul class="nav nav-pills mb-4" id="requestTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="incoming-tab" data-bs-toggle="tab" data-bs-target="#incoming" type="button" role="tab">Incoming</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="outgoing-tab" data-bs-toggle="tab" data-bs-target="#outgoing" type="button" role="tab">Outgoing</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="accepted-tab" data-bs-toggle="tab" data-bs-target="#accepted" type="button" role="tab">Accepted</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab">Rejected</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab">Cancelled</button>
            </li>
        </ul>

        <div class="tab-content" id="requestTabsContent">
            <div class="tab-pane fade show active" id="incoming" role="tabpanel">
                @if($incoming->isEmpty())
                    <div class="alert alert-info">No incoming requests at the moment.</div>
                @else
                    <div class="row g-4">
                        @foreach($incoming as $request)
                            @php $other = $request->sender; @endphp
                            <div class="col-md-6">
                                <div class="card rounded-card card-soft shadow-soft h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start gap-3 mb-3">
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
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small text-muted">Compatibility</span>
                                                <strong>{{ $compatibility->calculateScore(auth()->user(), $other) }}%</strong>
                                            </div>
                                            <div class="progress progress-xs rounded-pill overflow-hidden">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $compatibility->calculateScore(auth()->user(), $other) }}%;" aria-valuenow="{{ $compatibility->calculateScore(auth()->user(), $other) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">Request sent</small>
                                            <div>{{ $request->created_at?->format('F j, Y • h:i A') }}</div>
                                        </div>

                                        @if($request->message)
                                            <div class="mb-3">
                                                <small class="text-uppercase text-muted">Message</small>
                                                <div class="border rounded-3 p-3 bg-white">{{ $request->message }}</div>
                                            </div>
                                        @endif

                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <a href="{{ route('students.show', ['id' => $other->id]) }}" class="btn btn-outline-primary w-100">View Profile</a>
                                            <form method="POST" action="{{ route('messages.open') }}" class="w-100">
                                                @csrf
                                                <input type="hidden" name="other_user_id" value="{{ $other->id }}">
                                                <button type="submit" class="btn btn-outline-secondary w-100">Message</button>
                                            </form>
                                            <form method="POST" action="{{ route('messages.open') }}" class="w-100">
                                                @csrf
                                                <input type="hidden" name="other_user_id" value="{{ $other->id }}">
                                                <button type="submit" class="btn btn-outline-secondary w-100">Message</button>
                                            </form>
                                            <form method="POST" action="{{ route('messages.open') }}" class="w-100">
                                                @csrf
                                                <input type="hidden" name="other_user_id" value="{{ $other->id }}">
                                                <button type="submit" class="btn btn-outline-secondary w-100">Message</button>
                                            </form>
                                            <form method="POST" action="{{ route('roommate-requests.accept', ['id' => $request->id]) }}" class="flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-gradient-primary w-100">Accept</button>
                                            </form>
                                            <form method="POST" action="{{ route('roommate-requests.reject', ['id' => $request->id]) }}" class="flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-gradient-danger w-100">Reject</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $incoming->links() }}</div>
                @endif
            </div>

            <div class="tab-pane fade" id="outgoing" role="tabpanel">
                @if($outgoing->isEmpty())
                    <div class="alert alert-info">No outgoing requests at the moment.</div>
                @else
                    <div class="row g-4">
                        @foreach($outgoing as $request)
                            @php $other = $request->receiver; @endphp
                            <div class="col-md-6">
                                <div class="card rounded-card card-soft shadow-soft h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start gap-3 mb-3">
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
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small text-muted">Compatibility</span>
                                                <strong>{{ $compatibility->calculateScore(auth()->user(), $other) }}%</strong>
                                            </div>
                                            <div class="progress progress-xs rounded-pill overflow-hidden">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $compatibility->calculateScore(auth()->user(), $other) }}%;" aria-valuenow="{{ $compatibility->calculateScore(auth()->user(), $other) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">Request sent</small>
                                            <div>{{ $request->created_at?->format('F j, Y • h:i A') }}</div>
                                        </div>

                                        @if($request->message)
                                            <div class="mb-3">
                                                <small class="text-uppercase text-muted">Message</small>
                                                <div class="border rounded-3 p-3 bg-light">{{ $request->message }}</div>
                                            </div>
                                        @endif

                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <a href="{{ route('students.show', ['id' => $other->id]) }}" class="btn btn-outline-primary w-100">View Profile</a>
                                            <form method="POST" action="{{ route('roommate-requests.cancel', ['id' => $request->id]) }}" class="flex-grow-1">
                                                @csrf
                                                <button type="submit" class="btn btn-gradient-danger w-100">Cancel Request</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $outgoing->links() }}</div>
                @endif
            </div>

            <div class="tab-pane fade" id="accepted" role="tabpanel">
                @if($accepted->isEmpty())
                    <div class="alert alert-info">No accepted requests found.</div>
                @else
                    <div class="row g-4">
                        @foreach($accepted as $request)
                            @php
                                $other = $request->sender_id === auth()->id() ? $request->receiver : $request->sender;
                            @endphp
                            <div class="col-md-6">
                                <div class="card rounded-card card-soft shadow-soft h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start gap-3 mb-3">
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
                                        </div>

                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="small text-muted">Compatibility</span>
                                                <strong>{{ $compatibility->calculateScore(auth()->user(), $other) }}%</strong>
                                            </div>
                                            <div class="progress progress-xs rounded-pill overflow-hidden">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $compatibility->calculateScore(auth()->user(), $other) }}%;" aria-valuenow="{{ $compatibility->calculateScore(auth()->user(), $other) }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <small class="text-muted">Request date</small>
                                            <div>{{ $request->created_at?->format('F j, Y • h:i A') }}</div>
                                        </div>

                                        @if($request->message)
                                            <div class="mb-3">
                                                <small class="text-uppercase text-muted">Message</small>
                                                <div class="border rounded-3 p-3 bg-white">{{ $request->message }}</div>
                                            </div>
                                        @endif

                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <a href="{{ route('students.show', ['id' => $other->id]) }}" class="btn btn-outline-primary w-100">View Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $accepted->links() }}</div>
                @endif
            </div>

            <div class="tab-pane fade" id="rejected" role="tabpanel">
                @if($rejected->isEmpty())
                    <div class="alert alert-info">No rejected requests found.</div>
                @else
                    <div class="row g-4">
                        @foreach($rejected as $request)
                            @php
                                $other = $request->sender_id === auth()->id() ? $request->receiver : $request->sender;
                            @endphp
                            <div class="col-md-6">
                                <div class="card rounded-card card-soft shadow-soft h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start gap-3 mb-3">
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

                                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-center">
                                            <span class="badge bg-danger text-uppercase">Rejected</span>
                                            <a href="{{ route('students.show', ['id' => $other->id]) }}" class="btn btn-outline-primary w-100">View Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $rejected->links() }}</div>
                @endif
            </div>

            <div class="tab-pane fade" id="cancelled" role="tabpanel">
                @if($cancelled->isEmpty())
                    <div class="alert alert-info">No cancelled requests found.</div>
                @else
                    <div class="row g-4">
                        @foreach($cancelled as $request)
                            @php
                                $other = $request->sender_id === auth()->id() ? $request->receiver : $request->sender;
                            @endphp
                            <div class="col-md-6">
                                <div class="card rounded-card card-soft shadow-soft h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start gap-3 mb-3">
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

                                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-center">
                                            <span class="badge bg-secondary text-uppercase">Cancelled</span>
                                            <a href="{{ route('students.show', ['id' => $other->id]) }}" class="btn btn-outline-primary w-100">View Profile</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">{{ $cancelled->links() }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection
