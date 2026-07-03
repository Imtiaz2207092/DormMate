@extends('layouts.app')

@section('content')
<div class="py-5">
    <div class="container">
        <div class="row align-items-center gx-5">
            <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
                <span class="badge bg-primary mb-3">Welcome to DormMate</span>
                <h1 class="display-5 fw-bold">Build your student profile. Match with roommates.</h1>
                <p class="lead text-secondary">Create a complete profile, save your lifestyle preferences, and keep your roommate search simple and reliable.</p>

                <div class="d-flex flex-column flex-sm-row gap-2 mt-4 justify-content-center justify-content-lg-start">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Get Started</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Login</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                    @endguest
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h2 class="h4">Your profile includes</h2>
                        <ul class="list-unstyled mt-3 mb-0">
                            <li class="mb-2">• University, major, year, and contact details</li>
                            <li class="mb-2">• Hall, student ID, gender, and a short bio</li>
                            <li class="mb-2">• Living habits, sleep schedule, and roommate preferences</li>
                            <li class="mb-2">• A clean dashboard for profile and preference management</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row text-center mt-5 gy-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold">Simple onboarding</h5>
                        <p class="text-muted mb-0">Register quickly and complete your profile with the details that matter.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold">Clear preference settings</h5>
                        <p class="text-muted mb-0">Choose your sleep, study, and social habits so you share better matches.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="fw-semibold">Easy access</h5>
                        <p class="text-muted mb-0">Return to your dashboard, update your profile, and manage your preferences anytime.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
