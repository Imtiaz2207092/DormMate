<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DormMate') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .hover-shadow {
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }
        .hover-shadow:hover {
            transform: translateY(-4px);
            box-shadow: 0 1.25rem 2rem rgba(0, 0, 0, 0.08);
        }
        .profile-photo {
            width: 72px;
            height: 72px;
            object-fit: cover;
        }
        .profile-photo-lg {
            width: 110px;
            height: 110px;
            object-fit: cover;
        }
        .rounded-card {
            border-radius: 1.25rem;
        }
        .shadow-soft {
            box-shadow: 0 1rem 2rem rgba(99, 102, 241, 0.08);
        }
        .btn-gradient-primary {
            background: linear-gradient(135deg, #60a5fa 0%, #93c5fd 100%);
            border: none;
            color: #fff;
        }
        .btn-gradient-primary:hover,
        .btn-gradient-primary:focus {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: #fff;
        }
        .btn-gradient-secondary {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border: none;
            color: #1e3a8a;
        }
        .btn-gradient-secondary:hover,
        .btn-gradient-secondary:focus {
            background: linear-gradient(135deg, #bfdbfe 0%, #93c5fd 100%);
            color: #1e3a8a;
        }
        .btn-gradient-danger {
            background: linear-gradient(135deg, #fb7185 0%, #f97316 100%);
            border: none;
            color: #fff;
        }
        .btn-gradient-danger:hover,
        .btn-gradient-danger:focus {
            background: linear-gradient(135deg, #f43f5e 0%, #ea580c 100%);
            color: #fff;
        }
        .btn-soft-secondary {
            background: rgba(108, 117, 125, 0.12);
            border: 1px solid rgba(108, 117, 125, 0.18);
            color: #495057;
        }
        .btn-soft-secondary:hover,
        .btn-soft-secondary:focus {
            background: rgba(108, 117, 125, 0.16);
        }
        .btn-soft-primary {
            background: rgba(59, 130, 246, 0.14);
            border: 1px solid rgba(59, 130, 246, 0.25);
            color: #2563eb;
        }
        .btn-soft-primary:hover,
        .btn-soft-primary:focus {
            background: rgba(59, 130, 246, 0.22);
            color: #1d4ed8;
        }
        .card-soft {
            background: #f8fafc;
            border: none;
        }
        .profile-card-block {
            border-radius: 1.6rem;
            border: 1px solid rgba(37, 99, 235, 0.16);
            box-shadow: 0 1.2rem 2rem rgba(37, 99, 235, 0.12);
            background: #ffffff;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .profile-card-block:hover {
            transform: translateY(-4px);
            box-shadow: 0 1.6rem 2.8rem rgba(37, 99, 235, 0.16);
            border-color: rgba(37, 99, 235, 0.24);
        }
        .dashboard-hero-full {
            width: 100%;
            border-radius: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        }
        .dashboard-hero-full .premium-dashboard-hero {
            border-radius: 0;
            background: transparent;
        }
        .premium-dashboard-hero {
            background: transparent;
        }
        .profile-card-block .card-body {
            padding: 1.6rem;
        }
        .profile-card-block .badge {
            border-radius: 0.85rem;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .progress-xs {
            height: 10px;
        }
        .progress-sm {
            height: 12px;
        }
        .profile-avatar-large {
            font-size: 2.5rem;
        }
        .bg-dashboard-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        }
        .premium-dashboard-card {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }
        .premium-dashboard-hero {
            background: linear-gradient(135deg, rgba(30, 64, 175, 0.95), rgba(37, 99, 235, 0.95));
        }
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            white-space: nowrap;
        }
        .brand-logo .brand-mark {
            width: 2rem;
            height: 2rem;
            min-width: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.85rem;
            background: linear-gradient(135deg, #60a5fa 0%, #8b5cf6 100%);
            color: #fff;
            font-size: 0.78rem;
            font-weight: 800;
            text-transform: uppercase;
            box-shadow: 0 0.75rem 1.75rem rgba(59, 130, 246, 0.18);
        }
        .brand-logo .brand-text {
            display: inline-block;
        }
        .brand-logo-inline {
            gap: 0.3rem;
        }
        .brand-logo-inline .brand-mark {
            width: 1.6rem;
            height: 1.6rem;
            min-width: 1.6rem;
            font-size: 0.72rem;
        }
        .premium-dashboard-avatar {
            width: 88px;
            height: 88px;
            font-size: 1.8rem;
        }
        .premium-status-pill {
            border-radius: 999px;
            padding: 0.7rem 1rem;
            font-size: 0.82rem;
            font-weight: 600;
        }
        .progress-pill {
            background: rgba(255,255,255,0.2);
        }
        .dashboard-stat-card {
            background: rgba(248, 250, 252, 0.95);
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 1.5rem;
            box-shadow: 0 1.2rem 2rem rgba(15, 23, 42, 0.05);
        }
        /* Messaging & profile card fixed sizing for student cards */
        .profile-card-fixed {
            min-height: 520px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .profile-card-fixed .profile-card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 1.5rem;
            height: 100%;
            box-sizing: border-box;
        }

        .profile-card-fixed .profile-avatar {
            flex: 0 0 auto;
        }

        .profile-card-fixed .profile-meta {
            flex: 0 0 auto;
        }

        .profile-card-fixed .profile-compat {
            flex: 0 0 auto;
            width: 100%;
        }

        .profile-card-fixed .profile-bio {
            flex: 1 1 auto; /* allow bio area to take remaining space but not expand card */
            width: 100%;
            overflow: hidden;
        }

        .profile-card-actions {
            margin-top: auto;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <span class="brand-logo">
                <span class="brand-mark">DM</span>
                <span class="brand-text">DormMate</span>
            </span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">
            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Find Roommates</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">My Profile</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('preferences.*') ? 'active' : '' }}" href="{{ route('preferences.index') }}">Preferences</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('roommate-requests.*') ? 'active' : '' }}" href="{{ route('roommate-requests.index') }}">Requests</a></li>
                    <li class="nav-item">
                        <?php
                            $unreadCount = 0;
                            if(auth()->check()) {
                                $userId = auth()->id();
                                $unreadCount = \App\Models\Conversation::where(function($q) use ($userId) {
                                    $q->where('user_one_id', $userId)->orWhere('user_two_id', $userId);
                                })->get()->sum(function($c) use ($userId) {
                                    return $c->messages()->where('is_read', false)->where('sender_id', '!=', $userId)->count();
                                });
                            }
                        ?>
                        <a class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}" href="{{ route('messages.index') }}">
                            Chats @if($unreadCount) <span class="badge bg-primary ms-1">{{ $unreadCount }}</span>@endif
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        @php
                            $notificationCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
                            $notificationItems = auth()->check() ? auth()->user()->notifications()->latest()->take(5)->get() : collect();
                        @endphp
                        <a class="nav-link dropdown-toggle position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            @if($notificationCount)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">{{ $notificationCount }}</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 320px;">
                            <li class="dropdown-header">Notifications</li>
                            @forelse($notificationItems as $notification)
                                @php
                                    $data = $notification->data;
                                    $icon = match (data_get($data, 'type')) {
                                        'roommate_request' => 'bi-person-plus',
                                        'request_accepted' => 'bi-person-check',
                                        'request_rejected' => 'bi-person-x',
                                        'roommate_removed' => 'bi-person-dash',
                                        'new_message' => 'bi-chat-dots',
                                        default => 'bi-bell',
                                    };
                                @endphp
                                <li>
                                    <a class="dropdown-item d-flex align-items-start gap-3 {{ $notification->read_at ? '' : 'bg-light' }}" href="{{ route('notifications.redirect', $notification->id) }}">
                                        <i class="bi {{ $icon }} fs-5"></i>
                                        <div class="flex-grow-1">
                                            <div class="small fw-semibold mb-1">{{ data_get($data, 'title') }}</div>
                                            <div class="small text-muted">{{ data_get($data, 'message') }}</div>
                                            <div class="small text-secondary">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    </a>
                                </li>
                            @empty
                                <li><div class="dropdown-item text-muted">No notifications yet.</div></li>
                            @endforelse
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">View all notifications</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('preferences.index') }}">Preferences</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <div class="container">
        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
