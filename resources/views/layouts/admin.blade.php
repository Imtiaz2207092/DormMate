<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DormMate - Admin Panel</title>
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
            --secondary: #475569;
            --success: #059669;
            --warning: #d97706;
            --danger: #dc2626;
            --dark: #0f172a;
            --slate-50: #f8fafc;
            --slate-100: #f1f5f9;
            --slate-200: #e2e8f0;
            --slate-700: #334155;
            --bg-app: #f8fafc;
            --radius-md: 0.75rem;
            --radius-lg: 1rem;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-app);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            font-weight: 700;
            color: var(--dark);
        }

        .admin-wrapper {
            display: flex;
            flex-grow: 1;
            position: relative;
        }

        .admin-sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 1px solid var(--slate-200);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .admin-sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--slate-100);
        }

        .admin-sidebar-menu {
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            flex-grow: 1;
        }

        .admin-menu-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--secondary);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .admin-menu-link:hover {
            background-color: var(--slate-100);
            color: var(--dark);
        }

        .admin-menu-link.active {
            background-color: rgba(37, 99, 235, 0.08);
            color: var(--primary-light);
            font-weight: 600;
        }

        .admin-content-area {
            flex-grow: 1;
            padding: 2rem;
            min-width: 0;
        }

        .admin-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid var(--slate-200);
            padding: 0.75rem 2rem;
        }

        .card-stat {
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: var(--radius-lg);
            box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.02);
            background-color: #ffffff;
            transition: transform 0.2s ease;
        }

        .card-stat:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 991.98px) {
            .admin-wrapper {
                flex-direction: column;
            }
            .admin-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--slate-200);
            }
            .admin-sidebar-menu {
                flex-direction: row;
                flex-wrap: wrap;
                padding: 1rem;
            }
            .admin-menu-link {
                padding: 0.5rem 0.75rem;
            }
            .admin-content-area {
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg admin-navbar">
        <div class="container-fluid p-0">
            <a class="navbar-brand fw-bold text-primary d-flex align-items-center gap-2" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-shield-lock-fill"></i> DormMate Admin
            </a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-secondary small fw-semibold">Logged in as: <strong class="text-dark">{{ auth()->user()->name }}</strong></span>
                <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">Go to Website</a>
            </div>
        </div>
    </nav>

    <div class="admin-wrapper">
        <aside class="admin-sidebar">
            <div class="admin-sidebar-menu">
                <a href="{{ route('admin.dashboard') }}" class="admin-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.users.index') }}" class="admin-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Users
                </a>
                <a href="{{ route('admin.statistics') }}" class="admin-menu-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart"></i> Statistics
                </a>
                <a href="{{ route('admin.reports') }}" class="admin-menu-link {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-text"></i> Reports
                </a>
                <a href="{{ route('admin.settings') }}" class="admin-menu-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Settings
                </a>
                <hr class="my-3 text-secondary opacity-25">
                <form method="POST" action="{{ route('logout') }}" class="m-0 mt-auto">
                    @csrf
                    <button type="submit" class="admin-menu-link w-100 text-start border-0 bg-transparent text-danger">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="admin-content-area">
            <div class="container-fluid p-0">
                @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('admin_content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
