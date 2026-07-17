@extends('layouts.app')

@section('content')
    <div class="py-4">
        <div class="container">
            <h1>Admin Dashboard</h1>
            <p class="text-muted">Welcome to the admin panel. Use the navigation to manage users and data.</p>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Manage Users</a>
        </div>
    </div>
@endsection
