@extends('web.layouts.app')

@section('title', 'Guest Signup')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle me-2"></i>Success!</strong>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Error Alerts -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle me-2"></i>Registration Failed!</strong>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-2">
                    <i class="fas fa-user-plus" style="color: #e74c3c;"></i> Create Your Account
                </h1>
                <p class="text-muted mb-4">Sign up to book hot stone baths, manage reservations, and track your wellness journey.</p>

                <form method="POST" action="{{ route('guest.register.submit') }}" class="row g-3">
                    @csrf
                    
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600;">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g., Tenzin Dorji" required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600;">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="e.g., tenzin@example.com" required>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600;">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-phone"></i></span>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="e.g., +975 1700 1234" required>
                            @error('phone')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600;">Dzongkhag
                            <span style="color: #999; font-weight: normal;"> (Optional)</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-map-pin"></i></span>
                            <select name="dzongkhag_id" class="form-control @error('dzongkhag_id') is-invalid @enderror">
                                <option value="">-- Select Dzongkhag --</option>
                                @foreach($dzongkhags as $dzongkhag)
                                    <option value="{{ $dzongkhag->id }}" @selected((string)old('dzongkhag_id')===(string)$dzongkhag->id)>
                                        {{ $dzongkhag->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dzongkhag_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600;">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimum 6 characters" required>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 600;">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="fas fa-check-circle"></i></span>
                            <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Re-enter your password" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 d-grid mt-3">
                        <button type="submit" class="btn btn-dark btn-lg" style="font-weight: 600;">
                            <i class="fas fa-user-check me-2"></i> Create Account
                        </button>
                    </div>

                    <hr class="my-4">
                
                <p class="text-center text-muted">
                    Already have an account?
                    <a href="{{ route('guest.login') }}" style="color: #e74c3c; text-decoration: none; font-weight: 600;">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
