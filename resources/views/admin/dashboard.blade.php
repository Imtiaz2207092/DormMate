@extends('layouts.admin')

@section('admin_content')
    <div class="mb-4">
        <h2 class="mb-1">Admin Dashboard</h2>
        <p class="text-muted">Welcome back. Here is an overview of DormMate system activity.</p>
    </div>

    <div class="row g-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Users</p>
                        <h2 class="mb-0 fw-bold">{{ $totalUsers }}</h2>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-people-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Student Profiles</p>
                        <h2 class="mb-0 fw-bold">{{ $totalProfiles }}</h2>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-badge-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Active Users</p>
                        <h2 class="mb-0 fw-bold">{{ $activeUsers }}</h2>
                    </div>
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 text-info d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-check-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Pending Requests</p>
                        <h2 class="mb-0 fw-bold">{{ $pendingRequests }}</h2>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 text-warning d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-chat-left-quote-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Total Messages</p>
                        <h2 class="mb-0 fw-bold">{{ $totalMessages }}</h2>
                    </div>
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3 text-secondary d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-envelope-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-4 border-0 shadow-sm mt-5">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Quick Actions</h5>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    <i class="bi bi-people me-1"></i> Manage Users
                </a>
                <a href="{{ route('admin.statistics') }}" class="btn btn-outline-primary">
                    <i class="bi bi-bar-chart me-1"></i> View Statistics
                </a>
            </div>
        </div>
    </div>
@endsection
