@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f0f2f5 !important;
    }
    .fb-register-card {
        background: #ffffff;
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, .1), 0 8px 16px rgba(0, 0, 0, .1);
        padding: 1.75rem 2rem;
        max-width: 450px;
        margin: 3rem auto;
    }
    .fb-input {
        height: 48px;
        font-size: 15px;
        padding: 11px 16px;
        border: 1px solid #dddfe2;
        border-radius: 6px;
        background-color: #f5f6f7;
    }
    .fb-input:focus {
        border-color: #1877f2;
        box-shadow: 0 0 0 2px rgba(24, 119, 242, 0.2);
        background-color: #ffffff;
    }
    .fb-btn-signup {
        background-color: #00a400;
        border: none;
        border-radius: 6px;
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
        padding: 10px 60px;
        display: inline-block;
        transition: background-color 0.15s ease;
    }
    .fb-btn-signup:hover {
        background-color: #008f00;
        color: #ffffff;
    }
</style>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card fb-register-card">
            <div class="border-bottom border-secondary border-opacity-10 pb-3 mb-4 text-center">
                <h2 class="fw-bold text-dark mb-1" style="font-size: 26px; font-family: var(--font-display);">Create a new account</h2>
                <p class="text-muted mb-0" style="font-size: 15px;">It's quick and easy.</p>
            </div>

            <div class="card-body p-0">
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="mb-3">
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control fb-input @error('name') is-invalid @enderror" placeholder="Full name" required>
                        @error('name')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control fb-input @error('email') is-invalid @enderror" placeholder="Email address" required>
                        @error('email')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password" class="form-control fb-input @error('password') is-invalid @enderror" placeholder="New password" required>
                        @error('password')<div class="invalid-feedback small">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password_confirmation" class="form-control fb-input" placeholder="Confirm password" required>
                    </div>

                    <div class="text-center mt-4 mb-3">
                        <button class="btn fb-btn-signup" type="submit">Sign Up</button>
                    </div>
                </form>

                <div class="text-center mt-3 pt-3 border-top border-secondary border-opacity-10">
                    <a href="{{ route('login') }}" class="text-decoration-none small fw-semibold" style="color: #1877f2;">Already have an account?</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
