<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'DormMate') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-sans: 'Inter', sans-serif;
            --font-display: 'Plus Jakarta Sans', sans-serif;
            --primary: #1d4ed8;
            --primary-light: #2563eb;
            --primary-dark: #1e3a8a;
            --primary-glow: rgba(37, 99, 235, 0.08);
            --secondary: #475569;
            --secondary-light: #64748b;
            --success: #059669;
            --success-glow: rgba(5, 150, 105, 0.12);
            --warning: #d97706;
            --danger: #dc2626;
            --dark: #0f172a;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-300: #cbd5e1;
            --slate-600: #475569;
            --slate-700: #334155;
            --bg-app: #f8fafc;
            --card-bg: #ffffff;
            --card-border: rgba(15, 23, 42, 0.06);
            --card-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.02), 0 2px 4px -2px rgba(15, 23, 42, 0.02);
            --card-shadow-hover: 0 12px 20px -8px rgba(15, 23, 42, 0.06), 0 4px 10px -4px rgba(15, 23, 42, 0.04);
            --radius-sm: 0.5rem;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
            --radius-xl: 1.5rem;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-app);
            color: var(--dark);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--dark);
        }

        /* Float/Sticky Navbar with Glassmorphic Blur */
        .navbar {
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.85) !important;
            border-bottom: 1px solid rgba(15, 23, 42, 0.05);
            padding: 0.95rem 0;
            transition: all 0.3s ease;
            position: relative;
            z-index: 1050 !important;
        }

        /* Remove caret arrow from dropdowns */
        .navbar .dropdown-toggle::after {
            display: none !important;
        }

        /* Apple-style Control Center module button / waterdrop */
        .apple-module-btn {
            background-color: rgba(15, 23, 42, 0.05) !important;
            border: none !important;
            border-radius: 50% !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .apple-module-btn:hover {
            background-color: rgba(15, 23, 42, 0.09) !important;
            transform: scale(1.05);
        }
        .apple-module-btn:active {
            background-color: rgba(15, 23, 42, 0.15) !important;
            transform: scale(0.95);
        }

        /* Apple-style profile capsule pill */
        .apple-profile-pill {
            background-color: rgba(15, 23, 42, 0.05) !important;
            border-radius: 20px !important;
            padding: 4px 12px 4px 6px !important;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }
        .apple-profile-pill:hover {
            background-color: rgba(15, 23, 42, 0.09) !important;
            transform: scale(1.03);
        }
        .apple-profile-pill:active {
            background-color: rgba(15, 23, 42, 0.14) !important;
            transform: scale(0.97);
        }

        .navbar-brand {
            font-family: var(--font-display);
        }

        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            font-weight: 800;
            letter-spacing: -0.01em;
            font-size: 1.35rem;
        }

        .brand-logo .brand-mark {
            width: 2.2rem;
            height: 2.2rem;
            min-width: 2.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--secondary-light) 100%);
            color: #ffffff;
            font-size: 0.9rem;
            font-weight: 800;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.15);
        }

        .brand-text {
            background: linear-gradient(135deg, var(--dark) 30%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-link {
            font-family: var(--font-sans);
            font-weight: 500;
            font-size: 0.92rem;
            color: var(--slate-600) !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
            background-color: var(--primary-glow);
        }

        .nav-link.active {
            font-weight: 600;
        }

        /* Card Overrides */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: var(--radius-lg);
            box-shadow: var(--card-shadow);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.3s ease, border-color 0.3s ease;
            overflow: hidden;
        }

        .hover-shadow:hover {
            transform: translateY(-6px);
            box-shadow: var(--card-shadow-hover);
            border-color: rgba(37, 99, 235, 0.15);
        }

        /* Form Controls */
        .form-control, .form-select {
            font-family: var(--font-sans);
            font-size: 0.95rem;
            padding: 0.7rem 1rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--slate-200);
            background-color: #ffffff;
            color: var(--dark);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02) inset;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px var(--primary-glow);
            outline: 0;
            background-color: #ffffff;
        }

        .form-label {
            font-family: var(--font-sans);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--slate-600);
            margin-bottom: 0.5rem;
        }

        /* Buttons styling */
        .btn {
            font-family: var(--font-sans);
            font-weight: 600;
            font-size: 0.92rem;
            padding: 0.65rem 1.25rem;
            border-radius: var(--radius-md);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.45rem 1rem;
            font-size: 0.82rem;
            border-radius: var(--radius-sm);
        }

        .btn-lg {
            padding: 0.85rem 1.75rem;
            font-size: 1.05rem;
            border-radius: var(--radius-lg);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
        }

        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
            color: #ffffff;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981 0%, var(--success) 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
        }

        .btn-success:hover, .btn-success:focus {
            background: linear-gradient(135deg, var(--success) 0%, #047857 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(16, 185, 129, 0.35);
            color: #ffffff;
        }

        .btn-success:active {
            transform: translateY(0);
        }

        .btn-outline-primary {
            border: 1.5px solid var(--primary-light);
            color: var(--primary-light);
            background: transparent;
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--primary-light);
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.15);
        }

        .btn-soft-primary {
            background-color: rgba(37, 99, 235, 0.06);
            color: var(--primary);
            border: 1px solid rgba(37, 99, 235, 0.12);
        }

        .btn-soft-primary:hover, .btn-soft-primary:focus {
            background-color: rgba(37, 99, 235, 0.12);
            color: var(--primary-dark);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .btn-soft-secondary {
            background-color: var(--slate-100);
            color: var(--slate-700);
            border: 1px solid var(--slate-200);
        }

        .btn-soft-secondary:hover, .btn-soft-secondary:focus {
            background-color: var(--slate-200);
            color: var(--dark);
        }

        .btn-gradient-danger {
            background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);
            border: none;
            color: #ffffff;
            box-shadow: 0 4px 10px rgba(244, 63, 94, 0.2);
        }

        .btn-gradient-danger:hover {
            background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(244, 63, 94, 0.3);
            color: #ffffff;
        }

        /* Progress Bars */
        .progress {
            height: 10px;
            background-color: var(--slate-100);
            border-radius: 999px;
            overflow: hidden;
            border: 1px solid rgba(15, 23, 42, 0.03);
        }

        .progress-bar {
            border-radius: 999px;
            background: linear-gradient(90deg, var(--primary-light) 0%, var(--primary) 100%) !important;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.15);
        }

        /* Custom Badge System */
        .badge {
            font-family: var(--font-sans);
            font-weight: 600;
            padding: 0.45rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            letter-spacing: 0.01em;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .bg-primary {
            background-color: rgba(37, 99, 235, 0.08) !important;
            color: var(--primary-light) !important;
        }

        .bg-primary-solid {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%) !important;
            color: #ffffff !important;
        }

        .bg-secondary {
            background-color: var(--slate-100) !important;
            color: var(--slate-700) !important;
        }

        .bg-secondary-solid {
            background-color: var(--slate-600) !important;
            color: #ffffff !important;
        }

        .bg-info {
            background-color: rgba(56, 189, 248, 0.1) !important;
            color: #0369a1 !important;
        }

        .bg-success {
            background-color: rgba(16, 185, 129, 0.1) !important;
            color: #047857 !important;
        }

        .bg-danger {
            background-color: rgba(239, 68, 68, 0.1) !important;
            color: #b91c1c !important;
        }

        .bg-warning {
            background-color: rgba(245, 158, 11, 0.1) !important;
            color: #b45309 !important;
        }

        /* Hero / Header Section */
        .dashboard-hero-full {
            border-radius: var(--radius-xl);
            background: linear-gradient(135deg, #09090b 0%, #171725 60%, #1e1b4b 100%);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
            padding: 3rem 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .dashboard-hero-full::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        .dashboard-hero-full::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Custom scrollbar for premium feel */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-app);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--slate-300);
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--slate-600);
        }

        /* Profile details */
        .profile-photo-lg {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 4px solid #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            border-radius: 50%;
        }

        .profile-photo {
            width: 64px;
            height: 64px;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            border-radius: 50%;
        }

        /* Modals style */
        .modal-content {
            border-radius: var(--radius-lg);
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            background: #ffffff;
        }

        .modal-header {
            padding: 1.5rem 1.5rem 0.5rem;
            border-bottom: none;
        }

        .modal-footer {
            padding: 0.5rem 1.5rem 1.5rem;
            border-top: none;
        }
        
        /* Layout fixes */
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
            width: 100%;
        }

        .profile-card-fixed .profile-compat {
            flex: 0 0 auto;
            width: 100%;
        }

        .profile-card-fixed .profile-bio {
            flex: 1 1 auto;
            width: 100%;
            overflow: hidden;
        }

        .profile-card-actions {
            margin-top: auto;
        }

        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
                    @if(auth()->check() && auth()->user()->is_admin)
                        <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}" href="{{ route('students.index') }}">Find Roommates</a></li>
                    <li class="nav-item">
                        @php
                            $unreadRequestsCount = 0;
                            if(auth()->check()) {
                                $unreadRequestsCount = auth()->user()->unreadNotifications->filter(function($n) {
                                    $type = data_get($n->data, 'type');
                                    return in_array($type, ['roommate_request', 'request_accepted', 'request_rejected', 'roommate_removed']);
                                })->count();
                            }
                        @endphp
                        <a class="nav-link {{ request()->routeIs('roommate-requests.*') ? 'active' : '' }}" href="{{ route('roommate-requests.index') }}">
                            Requests <span id="navRequestsBadge" class="badge bg-primary ms-1 {{ $unreadRequestsCount ? '' : 'd-none' }}">{{ $unreadRequestsCount }}</span>
                        </a>
                    </li>
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
                            Chats <span id="navChatsBadge" class="badge bg-primary ms-1 {{ $unreadCount ? '' : 'd-none' }}">{{ $unreadCount }}</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        @php
                            $notificationCount = auth()->check() ? auth()->user()->unreadNotifications()->count() : 0;
                            $notificationItems = auth()->check() ? auth()->user()->notifications()->latest()->take(5)->get() : collect();
                            $userProfile = auth()->user()->studentProfile;
                        @endphp
                        <a class="nav-link dropdown-toggle position-relative d-flex align-items-center justify-content-center apple-module-btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="width: 38px; height: 38px;">
                            <i class="bi bi-bell-fill text-dark" style="font-size: 1.1rem;"></i>
                            <span id="navNotificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger {{ $notificationCount ? '' : 'd-none' }}" style="font-size: 0.72rem; padding: 0.25em 0.5em; transform: translate(-30%, -10%) !important;">{{ $notificationCount }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 rounded-3" style="min-width: 360px; max-width: 380px;">
                            <li class="px-3 py-2 d-flex align-items-center justify-content-between border-bottom border-secondary border-opacity-10 mb-2">
                                <span class="fw-bold text-dark fs-5" style="font-family: var(--font-display);">Notifications</span>
                                <span id="navMarkAllReadWrapper" class="{{ $notificationCount ? '' : 'd-none' }}">
                                    <form method="POST" action="{{ route('notifications.markAllAsRead') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="btn btn-link btn-sm p-0 text-decoration-none text-primary fw-semibold small" style="font-size: 0.82rem;">Mark all as read</button>
                                    </form>
                                </span>
                            </li>
                            <div id="navNotificationListContainer">
                            @forelse($notificationItems as $notification)
                                @php
                                    $data = $notification->data;
                                    $icon = match (data_get($data, 'type')) {
                                        'roommate_request' => 'bi-person-plus-fill',
                                        'request_accepted' => 'bi-person-check-fill',
                                        'request_rejected' => 'bi-person-x-fill',
                                        'roommate_removed' => 'bi-person-dash-fill',
                                        'new_message' => 'bi-chat-dots-fill',
                                        'login' => 'bi-shield-lock-fill',
                                        'top_matches_changed' => 'bi-arrow-left-right',
                                        default => 'bi-bell-fill',
                                    };
                                    $iconBg = match (data_get($data, 'type')) {
                                        'roommate_request' => 'bg-primary',
                                        'request_accepted' => 'bg-success',
                                        'request_rejected' => 'bg-danger',
                                        'roommate_removed' => 'bg-warning',
                                        'new_message' => 'bg-info',
                                        'login' => 'bg-primary',
                                        'top_matches_changed' => 'bg-success',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-3 p-3 position-relative {{ $notification->read_at ? '' : 'bg-light bg-opacity-50' }}" href="{{ route('notifications.redirect', $notification->id) }}" style="border-radius: 8px; margin: 2px 8px; transition: background-color 0.2s ease;">
                                        <div class="rounded-circle {{ $iconBg }} text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; flex-shrink: 0; font-size: 1.25rem;">
                                            <i class="bi {{ $icon }}"></i>
                                        </div>
                                        <div class="flex-grow-1" style="min-width: 0;">
                                            <div class="small fw-semibold text-dark text-truncate mb-1">{{ data_get($data, 'title') }}</div>
                                            <div class="small text-muted text-wrap" style="font-size: 0.85rem; line-height: 1.4;">{{ data_get($data, 'message') }}</div>
                                            <div class="small text-secondary mt-1" style="font-size: 0.78rem;">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                        @if(!$notification->read_at)
                                            <span class="bg-primary rounded-circle" style="width: 8px; height: 8px; flex-shrink: 0; align-self: center;" title="Unread"></span>
                                        @endif
                                    </a>
                                </li>
                            @empty
                                <li><div class="dropdown-item text-muted text-center py-4">No notifications yet.</div></li>
                            @endforelse
                            </div>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center fw-semibold text-primary py-2" href="{{ route('notifications.index') }}" style="font-size: 0.88rem;">View all notifications</a></li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 apple-profile-pill" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            @if($userProfile && $userProfile->profile_photo)
                                <img src="{{ asset('storage/' . $userProfile->profile_photo) }}" alt="{{ auth()->user()->name }}" class="rounded-circle shadow-sm" style="width: 30px; height: 30px; object-fit: cover; border: 1px solid rgba(15, 23, 42, 0.15);">
                            @else
                                <div class="rounded-circle bg-primary-solid text-white d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 30px; height: 30px; font-size: 0.85rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span class="fw-semibold text-dark">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 rounded-3">
                            <li><a class="dropdown-item py-2" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('preferences.index') }}"><i class="bi bi-gear me-2"></i> Preferences</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> Logout</button>
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

@auth
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1100;" id="globalToastContainer"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navChatsBadge = document.getElementById('navChatsBadge');
    const navRequestsBadge = document.getElementById('navRequestsBadge');
    const navNotificationBadge = document.getElementById('navNotificationBadge');
    const navNotificationListContainer = document.getElementById('navNotificationListContainer');
    const navMarkAllReadWrapper = document.getElementById('navMarkAllReadWrapper');
    const globalToastContainer = document.getElementById('globalToastContainer');

    // Keep track of shown notification IDs so we don't display duplicate toasts for the same notification
    let shownNotificationIds = new Set();

    function showToastNotification(title, message, route) {
        if (!globalToastContainer) return;
        const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-dark border-0 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body p-3">
                        <strong class="d-block mb-1 text-info"><i class="bi bi-bell-fill me-2"></i>${title}</strong>
                        <span style="font-size: 0.9rem;">${message}</span>
                        ${route ? `<a href="${route}" class="btn btn-sm btn-info text-dark mt-2 d-inline-flex px-3 py-1">View Details</a>` : ''}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        globalToastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastEl = document.getElementById(toastId);
        const toastInstance = new bootstrap.Toast(toastEl, { delay: 6000 });
        toastInstance.show();

        // Clean up toast elements after hiding
        toastEl.addEventListener('hidden.bs.toast', () => {
            toastEl.remove();
        });
    }

    async function pollNotifications() {
        try {
            const response = await fetch('{{ route("notifications.poll") }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            if (!response.ok) return;
            const data = await response.json();

            // Update Chats badge
            if (navChatsBadge) {
                if (data.unread_messages_count > 0) {
                    navChatsBadge.textContent = data.unread_messages_count;
                    navChatsBadge.classList.remove('d-none');
                } else {
                    navChatsBadge.classList.add('d-none');
                }
            }

            // Update Requests badge
            if (navRequestsBadge) {
                if (data.unread_requests_count > 0) {
                    navRequestsBadge.textContent = data.unread_requests_count;
                    navRequestsBadge.classList.remove('d-none');
                } else {
                    navRequestsBadge.classList.add('d-none');
                }
            }

            // Update Notification Bell badge
            if (navNotificationBadge) {
                if (data.unread_notifications_count > 0) {
                    navNotificationBadge.textContent = data.unread_notifications_count;
                    navNotificationBadge.classList.remove('d-none');
                    if (navMarkAllReadWrapper) navMarkAllReadWrapper.classList.remove('d-none');
                } else {
                    navNotificationBadge.classList.add('d-none');
                    if (navMarkAllReadWrapper) navMarkAllReadWrapper.classList.add('d-none');
                }
            }

            // Update drop-down list and trigger real-time toasts
            if (data.notifications && data.notifications.length > 0) {
                let listHtml = '';
                
                data.notifications.slice(0, 5).forEach(notif => {
                    // Decide Icon and Color based on notification type
                    let icon = 'bi-bell-fill';
                    let iconBg = 'bg-secondary';

                    switch(notif.type) {
                        case 'roommate_request':
                            icon = 'bi-person-plus-fill';
                            iconBg = 'bg-primary';
                            break;
                        case 'request_accepted':
                            icon = 'bi-person-check-fill';
                            iconBg = 'bg-success';
                            break;
                        case 'request_rejected':
                            icon = 'bi-person-x-fill';
                            iconBg = 'bg-danger';
                            break;
                        case 'roommate_removed':
                            icon = 'bi-person-dash-fill';
                            iconBg = 'bg-warning';
                            break;
                        case 'new_message':
                            icon = 'bi-chat-dots-fill';
                            iconBg = 'bg-info';
                            break;
                        case 'login':
                            icon = 'bi-shield-lock-fill';
                            iconBg = 'bg-primary';
                            break;
                        case 'top_matches_changed':
                            icon = 'bi-arrow-left-right';
                            iconBg = 'bg-success';
                            break;
                    }

                    listHtml += `
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-3 p-3 position-relative bg-light bg-opacity-50" href="${notif.route}" style="border-radius: 8px; margin: 2px 8px; transition: background-color 0.2s ease;">
                                <div class="rounded-circle ${iconBg} text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 42px; height: 42px; flex-shrink: 0; font-size: 1.25rem;">
                                    <i class="bi ${icon}"></i>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="small fw-semibold text-dark text-truncate mb-1">${notif.title}</div>
                                    <div class="small text-muted text-wrap" style="font-size: 0.85rem; line-height: 1.4;">${notif.message}</div>
                                    <div class="small text-secondary mt-1" style="font-size: 0.78rem;">${notif.created_at_human}</div>
                                </div>
                                <span class="bg-primary rounded-circle" style="width: 8px; height: 8px; flex-shrink: 0; align-self: center;" title="Unread"></span>
                            </a>
                        </li>
                    `;

                    // If this notification is new and hasn't been shown in a toast yet, display it
                    if (!shownNotificationIds.has(notif.id)) {
                        shownNotificationIds.add(notif.id);
                        showToastNotification(notif.title, notif.message, notif.route);
                    }
                });

                if (navNotificationListContainer) {
                    navNotificationListContainer.innerHTML = listHtml;
                }
            } else {
                if (navNotificationListContainer) {
                    navNotificationListContainer.innerHTML = `<li><div class="dropdown-item text-muted text-center py-4">No notifications yet.</div></li>`;
                }
            }
        } catch (error) {
            console.error('Polling failed:', error);
        }
    }

    // Initialize list with any existing unread notification IDs so we don't toast them on first page load
    document.querySelectorAll('#navNotificationListContainer a').forEach(el => {
        const href = el.getAttribute('href');
        if (href) {
            const parts = href.split('/');
            const id = parts[parts.length - 2]; // Extract notification uuid
            if (id) shownNotificationIds.add(id);
        }
    });

    // Run poll every 8 seconds
    setInterval(pollNotifications, 8000);
});
</script>
@endauth
</body>
</html>
