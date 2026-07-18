@extends('layouts.admin')

@section('admin_content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="mb-1">User Management</h2>
            <p class="text-muted mb-0">View, search, edit, and delete system users.</p>
        </div>
    </div>

    <div class="card rounded-4 border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-center">
                <div class="col-md-9 col-lg-10">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="search" name="q" class="form-control border-start-0 ps-0" placeholder="Search by name or email..." value="{{ $q ?? '' }}">
                    </div>
                </div>
                <div class="col-md-3 col-lg-2">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card rounded-4 border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">User Type</th>
                            <th scope="col">Active Status</th>
                            <th scope="col">Joined Date</th>
                            <th scope="col" class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="ps-3">
                                    <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->user_type === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">Student</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Suspended</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at?->format('M j, Y') ?? 'N/A' }}</td>
                                <td class="text-end pe-3">
                                    <div class="d-inline-flex gap-2">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-outline-primary px-3">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary px-3">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        @if($user->id === auth()->id())
                                            <button type="button" class="btn btn-sm btn-outline-danger px-3" disabled title="Cannot delete yourself">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger px-3">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No users found matching your search.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
