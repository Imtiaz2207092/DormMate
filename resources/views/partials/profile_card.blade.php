@php
    // compute send/request state if parent scopes provide these arrays
    $hasPending = isset($requestedIds) && (is_array($requestedIds) ? in_array($student->id, $requestedIds) : false) || (isset($incomingPendingIds) && is_array($incomingPendingIds) ? in_array($student->id, $incomingPendingIds) : false);
    $hasAccepted = isset($matchedIds) && is_array($matchedIds) ? in_array($student->id, $matchedIds) : false;
    $canSend = ! $hasPending && ! $hasAccepted;
@endphp

<div class="card rounded-card shadow-sm hover-shadow profile-card-fixed h-100">
    <div class="card-body profile-card-body">
        <div class="profile-avatar">
            @if(optional($student->studentProfile)->profile_photo)
                <img src="{{ asset('storage/' . $student->studentProfile->profile_photo) }}" alt="Profile photo" class="rounded-circle profile-photo-lg mb-3">
            @else
                <div class="rounded-circle bg-primary-solid d-flex align-items-center justify-content-center profile-photo-lg mb-3">
                    <span class="fs-3">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                </div>
            @endif
        </div>

        <div class="profile-meta mt-3">
            <h5 class="mb-1 text-truncate" style="max-width:220px;">{{ $student->name }}</h5>
            <p class="text-muted small mb-2 text-truncate" style="max-width:220px;">{{ optional($student->studentProfile)->department ? strtoupper(optional($student->studentProfile)->department) : 'Department not set' }}</p>

            <div class="d-flex gap-2 justify-content-center flex-wrap mb-3 w-100">
                <span class="badge bg-info text-dark">ID: {{ optional($student->studentProfile)->student_id ?? 'N/A' }}</span>
                <span class="badge bg-secondary">Batch: {{ optional($student->studentProfile)->batch ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="profile-compat mb-3 w-100">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small text-muted">Compatibility</span>
                <strong>{{ $student->compatibility_score !== null ? $student->compatibility_score . '%' : 'N/A' }}</strong>
            </div>
            <div class="progress" style="height: 14px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $student->compatibility_score ?? 0 }}%;" aria-valuenow="{{ $student->compatibility_score ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>

        <div class="profile-bio text-truncate-2 text-muted mb-3 w-100">{{ optional($student->studentProfile)->bio ?? 'No bio provided yet.' }}</div>

        <div class="d-flex gap-2 w-100 mt-auto profile-card-actions">
            <a href="{{ route('students.show', ['id' => $student->id]) }}" class="btn btn-primary flex-grow-1">View Profile</a>
            <form method="POST" action="{{ route('messages.open') }}" class="m-0">
                @csrf
                <input type="hidden" name="other_user_id" value="{{ $student->id }}">
                <button type="submit" class="btn btn-outline-primary" style="padding: 0.65rem 0.95rem;" title="Chat"><i class="bi bi-chat-dots"></i></button>
            </form>
        </div>
        @if(empty($hideConnect))
        <div class="w-100 mt-2">
            @if($canSend)
                <button type="button" class="btn btn-soft-primary w-100" data-bs-toggle="modal" data-bs-target="#sendRequestModal-{{ $student->id }}"><i class="bi bi-person-plus me-1"></i> Connect</button>
            @else
                <button type="button" class="btn btn-soft-secondary w-100" disabled style="opacity: 0.8;">
                    @if($hasAccepted)
                        <i class="bi bi-people me-1"></i> Connected
                    @else
                        <i class="bi bi-clock me-1"></i> Pending
                    @endif
                </button>
            @endif
        </div>
        @endif
    </div>

    @if($canSend)
        <div class="modal fade" id="sendRequestModal-{{ $student->id }}" tabindex="-1" aria-labelledby="sendRequestModalLabel-{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 shadow-soft border-0">
                    <div class="modal-header border-bottom-0">
                        <h5 class="modal-title" id="sendRequestModalLabel-{{ $student->id }}">Send Roommate Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('roommate-requests.send') }}">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $student->id }}">
                        <div class="modal-body">
                            <p class="text-muted">Send a message with your roommate request to {{ $student->name }}.</p>
                            <div class="mb-3">
                                <label class="form-label">Message</label>
                                <textarea name="message" class="form-control" rows="4" maxlength="250" placeholder="Write a note (optional)"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-soft-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Send Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
