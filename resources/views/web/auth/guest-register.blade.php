@extends('web.layouts.app')

@section('title', 'Guest Signup')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-3">Customer Signup</h1>
                <p class="text-muted mb-4">Create your account to confirm bookings, pay, and manage reservations.</p>

                <form method="POST" action="{{ route('guest.register.submit') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address (Optional)</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="col-12 d-grid mt-2">
                        <button type="submit" class="btn btn-dark">Create Account</button>
                    </div>
                </form>

                <hr class="my-4">
                <p class="mb-0">Already have an account? <a href="{{ route('guest.login') }}">Login here</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
