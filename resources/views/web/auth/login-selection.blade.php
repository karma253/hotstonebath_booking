@extends('web.layouts.app')

@section('title', 'Select Login Type')

@section('content')
<div style="max-width: 1000px; margin: 60px auto; padding: 0 20px;">
    <div class="mb-5">
        <h1 class="h2 mb-2 text-center fw-bold">Select Login Type</h1>
        <p class="text-center text-muted">Choose your account type to access the Hot Stone Bath Booking System</p>
    </div>

    <div class="row g-4">
        <!-- Admin Login -->
        <div class="col-md-4">
            <div class="card card-shadow rounded-4 border-0 h-100 d-flex flex-column" style="border-top: 4px solid #2c3e50 !important;">
                <div class="card-body d-flex flex-column p-5">
                    <div class="mb-4">
                        <i class="fas fa-shield-alt" style="font-size: 2.5rem; color: #2c3e50;"></i>
                    </div>
                    <h5 class="card-title h5 mb-3 fw-semibold">Admin Portal</h5>
                    <p class="text-muted flex-grow-1 mb-4" style="font-size: 0.95rem; line-height: 1.5;">Manage the platform, verify properties, and oversee all system operations</p>
                    <a href="{{ route('admin.login') }}" class="btn btn-dark w-100 py-2 fw-semibold mt-auto">Login as Admin</a>
                </div>
            </div>
        </div>

        <!-- Owner/Provider Login -->
        <div class="col-md-4">
            <div class="card card-shadow rounded-4 border-0 h-100 d-flex flex-column" style="border-top: 4px solid #d4a574 !important;">
                <div class="card-body d-flex flex-column p-5">
                    <div class="mb-4">
                        <i class="fas fa-home" style="font-size: 2.5rem; color: #d4a574;"></i>
                    </div>
                    <h5 class="card-title h5 mb-3 fw-semibold">Owner Portal</h5>
                    <p class="text-muted flex-grow-1 mb-4" style="font-size: 0.95rem; line-height: 1.5;">Manage your hot stone bath properties, services, and bookings</p>
                    <a href="{{ route('owner.login') }}" class="btn btn-dark w-100 py-2 fw-semibold mt-auto">Login as Owner</a>
                </div>
            </div>
        </div>

        <!-- Guest Login -->
        <div class="col-md-4">
            <div class="card card-shadow rounded-4 border-0 h-100 d-flex flex-column" style="border-top: 4px solid #8b7355 !important;">
                <div class="card-body d-flex flex-column p-5">
                    <div class="mb-4">
                        <i class="fas fa-user" style="font-size: 2.5rem; color: #8b7355;"></i>
                    </div>
                    <h5 class="card-title h5 mb-3 fw-semibold">Guest Portal</h5>
                    <p class="text-muted flex-grow-1 mb-4" style="font-size: 0.95rem; line-height: 1.5;">Search, browse, and book hot stone bath experiences across Bhutan</p>
                    <a href="{{ route('guest.login') }}" class="btn btn-dark w-100 py-2 fw-semibold mt-auto">Login as Guest</a>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 pt-3 border-top">
        <p class="text-muted">New to the platform? <a href="{{ route('guest.register') }}" class="fw-semibold">Register as a Guest</a></p>
    </div>
</div>
@endsection
