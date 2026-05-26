@extends('web.layouts.app')

@section('title', 'Guest Login')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <!-- Error Alerts -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Login Failed!</strong>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Error</strong>
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-2">
                    <i class="fas fa-user-circle" style="color: #e74c3c;"></i> Customer Login
                </h1>
                <p class="text-muted mb-4">Login is required for booking, payment, and viewing your booking history.</p>

                <form method="POST" action="{{ route('guest.login.submit') }}">
                    @csrf
                    @if ($bathId)
                        <input type="hidden" name="bath_id" value="{{ $bathId }}">
                    @endif
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="Enter your email">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600;">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Enter your password">
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark btn-lg w-100" style="font-weight: 600;">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </button>
                </form>

                <hr class="my-4">
                
                <div style="text-align: center; margin-bottom: 1rem;">
                    <p class="mb-1 text-muted">Don't have an account?</p>
                    <a href="{{ route('guest.register') }}" class="btn btn-outline-dark btn-sm" style="font-weight: 600;">
                        <i class="fas fa-user-plus me-1"></i> Create Account
                    </a>
                </div>
            </div>
        </div>

        <!-- Help Section -->
        <div style="text-align: center; margin-top: 1.5rem; color: #999; font-size: 0.9rem;">
            <p>🛁 <strong>New to Hot Stone Baths?</strong></p>
            <a href="{{ route('home') }}" style="color: #e74c3c; text-decoration: none; font-weight: 600;">Browse available baths and create an account to book →</a>
        </div>
    </div>
</div>
@endsection
