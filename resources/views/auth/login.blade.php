@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f2f5 !important;
    }
    .fb-logo {
        font-family: var(--font-display);
        color: #1877f2;
        font-size: 3.5rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-bottom: 0.2rem;
    }
    .fb-card {
        background: #ffffff;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1);
        padding: 1.25rem 1.25rem 2rem 1.25rem;
    }
    .fb-input {
        height: 52px;
        font-size: 16px;
        padding: 14px 16px;
        border: 1px solid #dddfe2;
        border-radius: 6px;
    }
    .fb-input:focus {
        border-color: #1877f2;
        box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.2);
    }
    .fb-btn-login {
        background-color: #1877f2;
        border: none;
        border-radius: 6px;
        color: #ffffff;
        font-size: 20px;
        font-weight: 700;
        padding: 10px 16px;
        width: 100%;
        transition: background-color 0.15s ease;
    }
    .fb-btn-login:hover {
        background-color: #166fe5;
    }
    .fb-btn-create {
        background-color: #42b72a;
        border: none;
        border-radius: 6px;
        color: #ffffff;
        font-size: 17px;
        font-weight: 700;
        padding: 12px 20px;
        display: inline-block;
        text-decoration: none;
        transition: background-color 0.15s ease;
    }
    .fb-btn-create:hover {
        background-color: #36a420;
        color: #ffffff;
    }
</style>

<div class="row align-items-center justify-content-center py-5 g-5" style="min-height: 75vh;">
    <!-- Brand Info Section -->
    <div class="col-md-6 col-lg-6 text-center text-md-start">
        <h1 class="fb-logo">DormMate</h1>
        <p class="text-dark fs-3 fw-normal" style="line-height: 1.34; max-width: 500px; font-family: var(--font-sans);">
            DormMate helps you connect and share matching preferences with students at your university to find the perfect roommate.
        </p>
    </div>

    <!-- Login Form Card -->
    <div class="col-md-6 col-lg-5 ms-md-auto">
        <div class="card fb-card">
            <div class="card-body p-0">
                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control fb-input @error('email') is-invalid @enderror" placeholder="Email address" required>
                        @error('email')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" class="form-control fb-input @error('password') is-invalid @enderror" placeholder="Password" required>
                        @error('password')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-muted small" for="remember">Remember me</label>
                    </div>

                    <button class="btn fb-btn-login mb-3" type="submit">Log in</button>
                </form>

                <hr class="my-4" style="border-top: 1px solid #dadde1;">

                <div class="text-center">
                    <a href="{{ route('register') }}" class="fb-btn-create">Create new account</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
