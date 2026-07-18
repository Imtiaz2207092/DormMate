@extends('layouts.admin')

@section('admin_content')
    <div class="mb-4">
        <h2 class="mb-1">System Statistics</h2>
        <p class="text-muted">General statistics, user breakdown, and recent registration logs.</p>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Admin Accounts</p>
                        <h2 class="mb-0 fw-bold">{{ $totalAdmins }}</h2>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 text-danger d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-shield-lock-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Student Accounts</p>
                        <h2 class="mb-0 fw-bold">{{ $totalStudents }}</h2>
                    </div>
                    <div class="rounded-circle bg-secondary bg-opacity-10 p-3 text-secondary d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-person-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card card-stat border-0 p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold">Completed Profiles</p>
                        <h2 class="mb-0 fw-bold">{{ $profilesCompleted }}</h2>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 text-success d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                        <i class="bi bi-check-circle-fill fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card rounded-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-3">Recently Registered Users</h5>
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Account Type</th>
                            <th scope="col" class="pe-3">Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                            <tr>
                                <td class="ps-3 fw-semibold text-dark">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->user_type === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">Student</span>
                                    @endif
                                </td>
                                <td class="pe-3">{{ $user->created_at?->format('F j, Y, h:i A') ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No users registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
