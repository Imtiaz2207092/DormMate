@extends('layouts.admin')

@section('admin_content')
    <div class="card rounded-4 border-0 shadow-sm py-5 text-center my-4">
        <div class="card-body p-5">
            <div class="rounded-circle bg-primary bg-opacity-10 p-4 text-primary d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                <i class="bi {{ $icon }} fs-1"></i>
            </div>
            <h3 class="fw-bold mb-2">{{ $title }} Panel</h3>
            <p class="text-secondary mx-auto mb-4" style="max-width: 480px;">This panel is currently a placeholder for the {{ strtolower($title) }} management interface. Full functionality will be integrated in future phases.</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary px-4">Go to Dashboard</a>
        </div>
    </div>
@endsection
