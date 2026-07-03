@extends('layouts.app')

@section('content')
<div class="py-5" style="background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);">
    <div class="container text-white py-5">
        <div class="row align-items-center gx-5">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <span class="badge bg-white text-primary mb-3 py-2 px-3 rounded-pill">Welcome to <span class="brand-logo brand-logo-inline text-primary"><span class="brand-mark">DM</span><span class="brand-text">DormMate</span></span></span>
                <h1 class="display-5 fw-bold">Find the perfect roommate experience.</h1>
                <p class="lead text-white-75">Create your student profile, share your lifestyle preferences, and match with roommates who fit your daily routine.</p>

                <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                    @guest
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg text-primary shadow-sm">Get Started</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Login</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="btn btn-light btn-lg text-primary shadow-sm">Go to Dashboard</a>
                    @endguest
                </div>
            </div>

            <div class="col-lg-6">
                <div class="bg-white bg-opacity-10 border border-white border-opacity-20 rounded-4 p-4 shadow-lg">
                    <div class="row gy-3">
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-15 h-100">
                                <h6 class="text-white fw-semibold">Profile Builder</h6>
                                <p class="text-white-75 small mb-0">Add your academic, housing, and contact details in one place.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-15 h-100">
                                <h6 class="text-white fw-semibold">Match Preferences</h6>
                                <p class="text-white-75 small mb-0">Set your habits for sleep, study, cleanliness and more.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-15 h-100">
                                <h6 class="text-white fw-semibold">Roommate Requests</h6>
                                <p class="text-white-75 small mb-0">Send and manage offers with clear status tracking.</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded-4 bg-white bg-opacity-15 h-100">
                                <h6 class="text-white fw-semibold">Dashboard Insights</h6>
                                <p class="text-white-75 small mb-0">Monitor pending requests and active roommate matches.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3 rounded-pill">Why <span class="brand-logo brand-logo-inline text-primary"><span class="brand-mark">DM</span><span class="brand-text">DormMate</span></span></span>
            <h2 class="mt-3 fw-bold">A smarter way to choose your student roommate.</h2>
            <p class="text-muted mx-auto" style="max-width: 680px;"><span class="brand-logo brand-logo-inline"><span class="brand-mark">DM</span><span class="brand-text">DormMate</span></span> empowers students with a modern, professional roommate search experience using personality-aware preferences and transparent request tracking.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-xl-3">
                <div class="card rounded-4 shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-3">01</div>
                        <h5 class="fw-semibold">Complete student profiles</h5>
                        <p class="text-muted mb-0">Capture academic, hall, and lifestyle details so roommates connect on the right fit.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card rounded-4 shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="badge bg-violet bg-opacity-10 text-violet rounded-pill px-3 py-2 mb-3">02</div>
                        <h5 class="fw-semibold">Preference matching</h5>
                        <p class="text-muted mb-0">Compare sleep, study, noise and social traits to find a compatible roommate.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card rounded-4 shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 mb-3">03</div>
                        <h5 class="fw-semibold">Request management</h5>
                        <p class="text-muted mb-0">Send, accept, reject, or cancel roommate requests from one easy dashboard.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card rounded-4 shadow-sm h-100 border-0">
                    <div class="card-body">
                        <div class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 mb-3">04</div>
                        <h5 class="fw-semibold">Clean modern style</h5>
                        <p class="text-muted mb-0">A premium interface designed for mobile and desktop student workflows.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row align-items-center mt-5 g-4">
            <div class="col-lg-7">
                <div class="p-5 rounded-4" style="background: linear-gradient(180deg, rgba(59,130,246,0.08), rgba(236,72,153,0.08));">
                    <h3 class="fw-bold">Start matching the right way.</h3>
                    <p class="text-muted">Get a polished roommate search experience built for student life. Create your profile, choose your habits, and let <span class="brand-logo brand-logo-inline"><span class="brand-mark">DM</span><span class="brand-text">DormMate</span></span> do the rest.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Create Account</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Login</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Go to Dashboard</a>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-primary btn-lg">Find Roommates</a>
                        @endguest
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="card rounded-4 border-0 shadow-sm p-4">
                            <h5 class="fw-semibold">Premium student match tools</h5>
                            <p class="text-muted mb-0">Match with confidence using transparent compatibility signals and request status tracking.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card rounded-4 border-0 shadow-sm p-4 bg-primary text-white h-100">
                            <h6 class="fw-semibold">Easy onboarding</h6>
                            <p class="small mb-0">Register and build your profile in minutes.</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card rounded-4 border-0 shadow-sm p-4 bg-white h-100">
                            <h6 class="fw-semibold">Fast matching</h6>
                            <p class="small mb-0 text-muted">Find compatible roommates with clarity.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
