@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <h2 class="mb-1">Notifications</h2>
                <p class="text-muted mb-0">Review your latest notifications, mark them read, or delete items you no longer need.</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <form method="POST" action="{{ route('notifications.markAllAsRead') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary btn-sm">Mark All as Read</button>
                </form>
                <a href="{{ route('notifications.index') }}" class="btn btn-secondary btn-sm">Refresh</a>
            </div>
        </div>

        <div class="card rounded-4 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('notifications.index') }}" class="row g-3 mb-4">
                    <div class="col-md-5">
                        <input name="search" class="form-control" type="search" placeholder="Search by title..." value="{{ $search }}">
                    </div>
                    <div class="col-md-4">
                        <select name="filter" class="form-select">
                            <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All</option>
                            <option value="unread" {{ $filter === 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ $filter === 'read' ? 'selected' : '' }}>Read</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button class="btn btn-primary">Search</button>
                    </div>
                </form>

                @if($notifications->isEmpty())
                    <div class="alert alert-info mb-0">No notifications found.</div>
                @else
                    <div class="list-group">
                        @foreach($notifications as $notification)
                            @php
                                $data = $notification->data;
                                $type = data_get($data, 'type');
                                $icon = match ($type) {
                                    'roommate_request' => 'bi-person-plus',
                                    'request_accepted' => 'bi-person-check',
                                    'request_rejected' => 'bi-person-x',
                                    'roommate_removed' => 'bi-person-dash',
                                    'new_message' => 'bi-chat-dots',
                                    default => 'bi-bell',
                                };
                            @endphp
                            <div class="list-group-item d-flex flex-column gap-3 p-4 {{ $notification->read_at ? '' : 'bg-light' }}">
                                <div class="d-flex align-items-start justify-content-between gap-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                            <i class="bi {{ $icon }}"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('notifications.redirect', $notification->id) }}" class="stretched-link text-decoration-none text-dark">
                                                <h5 class="mb-1">{{ data_get($data, 'title') }}</h5>
                                            </a>
                                            <p class="mb-0 text-muted">{{ data_get($data, 'message') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        <div class="mt-2">
                                            @if($notification->read_at)
                                                <span class="badge bg-secondary">Read</span>
                                            @else
                                                <span class="badge bg-primary">Unread</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if(! $notification->read_at)
                                        <form method="POST" action="{{ route('notifications.markAsRead', $notification->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Mark as Read</button>
                                        </form>
                                    @endif
                                    <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" onsubmit="return confirm('Delete this notification?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
