@extends('layouts.admin')

@section('admin_content')
    <div class="mb-4">
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Back to Users
        </a>
        <h2 class="mb-1">Edit User: {{ $user->name }}</h2>
        <p class="text-muted">Modify user details, system privileges, and active account status.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card rounded-4 border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="user_type" class="form-label fw-semibold">User Type</label>
                                <select name="user_type" id="user_type" class="form-select @error('user_type') is-invalid @enderror" required>
                                    <option value="student" {{ old('user_type', $user->user_type) === 'student' ? 'selected' : '' }}>Student</option>
                                    <option value="admin" {{ old('user_type', $user->user_type) === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                @error('user_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="active" class="form-label fw-semibold">Active Status</label>
                                <select name="active" id="active" class="form-select @error('active') is-invalid @enderror" required>
                                    <option value="1" {{ old('active', $user->active) ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('active', $user->active) ? '' : 'selected' }}>Suspended</option>
                                </select>
                                @error('active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 border-top pt-3">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
