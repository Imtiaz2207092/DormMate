@extends('layouts.app')

@section('content')
<div class="py-5" style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--secondary) 100%); border-radius: 0 0 2rem 2rem; box-shadow: 0 15px 35px rgba(79, 70, 229, 0.12); margin-top: -1.5rem;">
    <div class="container text-white py-5">
        <div class="row align-items-center gx-5">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="badge mb-3 py-2 px-3 rounded-pill border border-white border-opacity-20" style="background-color: rgba(255, 255, 255, 0.15); color: #ffffff;">Welcome to <span class="brand-logo brand-logo-inline text-white"><span class="brand-mark">DM</span><span class="brand-text text-white">DormMate</span></span></span>
                <h1 class="display-4 fw-bold mb-3" style="font-family: var(--font-display); line-height: 1.15;">Find the perfect roommate experience.</h1>
                <p class="lead text-white-75 mb-4" style="font-size: 1.15rem; font-weight: 300;">Create your student profile, share your lifestyle preferences, and match with roommates who fit your daily routine.</p>

                <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg text-primary shadow-sm px-4 py-3">Get Started</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4 py-3">Login</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg text-primary shadow-sm px-4 py-3">Go to Dashboard</a>
                    @endguest
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-white bg-opacity-10 border border-white border-opacity-15 rounded-4 p-4 shadow-lg" style="backdrop-filter: blur(12px);">
                    <h5 class="text-white fw-semibold mb-3" style="font-family: var(--font-display);">Community Snapshot</h5>
                    <div class="row gy-3">
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-10 h-100 text-center border border-white border-opacity-10">
                                <div class="display-6 fw-bold text-white mb-1">{{ $totalUsers ?? 0 }}</div>
                                <div class="small text-white-50">Total Users</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-10 h-100 text-center border border-white border-opacity-10">
                                <div class="display-6 fw-bold text-white mb-1">{{ $totalMatches ?? 0 }}</div>
                                <div class="small text-white-50">Successful Matches</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-10 h-100 text-center border border-white border-opacity-10">
                                <div class="display-6 fw-bold text-white mb-1">{{ $totalHalls ?? 0 }}</div>
                                <div class="small text-white-50">Halls Represented</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-10 h-100 text-center border border-white border-opacity-10">
                                <div class="display-6 fw-bold text-white mb-1">{{ $totalDepartments ?? 0 }}</div>
                                <div class="small text-white-50">Departments</div>
                                @if(!empty($topDepartments) && $topDepartments->count())
                                    <div class="small text-white-50 mt-2">
                                        @foreach($topDepartments as $dept)
                                            <span class="me-2">{{ strtoupper($dept->department) }} ({{ $dept->total }})</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5 bg-app">
    <div class="container py-4">
        <div class="text-center mb-5">
            <span class="badge bg-primary py-2 px-3 rounded-pill">Why <span class="brand-logo brand-logo-inline text-primary"><span class="brand-mark">DM</span><span class="brand-text">DormMate</span></span></span>
            <h2 class="mt-3 fw-bold text-dark" style="font-family: var(--font-display);">A smarter way to choose your student roommate.</h2>
            <p class="text-muted mx-auto mt-2" style="max-width: 680px; font-size: 1.05rem;"><span class="brand-logo brand-logo-inline"><span class="brand-mark">DM</span><span class="brand-text">DormMate</span></span> empowers students with a modern, professional roommate search experience using personality-aware preferences and transparent request tracking.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card hover-shadow h-100 p-3">
                    <div class="card-body p-2">
                        <div class="badge bg-primary px-3 py-2 mb-3">01</div>
                        <h5 class="fw-semibold text-dark mb-2">Complete student profiles</h5>
                        <p class="text-muted mb-0 small">Capture academic, hall, and lifestyle details so roommates connect on the right fit.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card hover-shadow h-100 p-3">
                    <div class="card-body p-2">
                        <div class="badge bg-info px-3 py-2 mb-3">02</div>
                        <h5 class="fw-semibold text-dark mb-2">Preference matching</h5>
                        <p class="text-muted mb-0 small">Compare sleep, study, noise and social traits to find a compatible roommate.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card hover-shadow h-100 p-3">
                    <div class="card-body p-2">
                        <div class="badge bg-success px-3 py-2 mb-3">03</div>
                        <h5 class="fw-semibold text-dark mb-2">Request management</h5>
                        <p class="text-muted mb-0 small">Send, accept, reject, or cancel roommate requests from one easy dashboard.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card hover-shadow h-100 p-3">
                    <div class="card-body p-2">
                        <div class="badge bg-warning px-3 py-2 mb-3">04</div>
                        <h5 class="fw-semibold text-dark mb-2">Clean modern style</h5>
                        <p class="text-muted mb-0 small">A premium interface designed for mobile and desktop student workflows.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center mt-5 g-5">
            <div class="col-lg-7">
                <div class="p-5 rounded-4 border border-secondary border-opacity-10" style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.04) 0%, rgba(71, 85, 105, 0.04) 100%);">
                    <h3 class="fw-bold text-dark mb-3" style="font-family: var(--font-display);">Start matching the right way.</h3>
                    <p class="text-muted" style="font-size: 1.05rem; line-height: 1.6;">Get a polished roommate search experience built for student life. Create your profile, choose your habits, and let <span class="brand-logo brand-logo-inline"><span class="brand-mark">DM</span><span class="brand-text">DormMate</span></span> do the rest.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4">Create Account</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg px-4">Login</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4">Go to Dashboard</a>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-primary btn-lg px-4">Find Roommates</a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card p-4 border-0 shadow-sm">
                            <h5 class="fw-semibold text-dark mb-2" style="font-family: var(--font-display);">Premium student match tools</h5>
                            <p class="text-muted mb-0 small">Match with confidence using transparent compatibility signals and request status tracking.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-4 text-white h-100 border-0" style="background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%); box-shadow: 0 8px 20px rgba(37, 99, 235, 0.15);">
                            <h6 class="fw-semibold mb-2">Easy onboarding</h6>
                            <p class="small mb-0 text-white-75">Register and build your profile in minutes.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-4 h-100 border-0 shadow-sm">
                            <h6 class="fw-semibold text-dark mb-2">Fast matching</h6>
                            <p class="small mb-0 text-muted">Find compatible roommates with clarity.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
