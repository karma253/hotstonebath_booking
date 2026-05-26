@extends('web.layouts.app')

@section('title', 'About Us')

@section('content')
<div class="about-page">
    <section class="page-hero mb-4">
        <span class="hero-kicker">Who We Are</span>
        <h1 class="mb-2">About Us</h1>
        <p class="mb-0">We connect guests with trusted bath owners and preserve Bhutanese wellness traditions through simple digital booking.</p>
    </section>

    <section class="info-panel">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="content-card h-100">
                    <h3 class="h5 mb-2">Our Mission</h3>
                    <p class="mb-0">
                        Hot Stone Bath Booking System connects guests with verified bath owners across Bhutan.
                        We make authentic menchu services easy to discover and book while supporting local providers.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-card h-100">
                    <h3 class="h5 mb-2">What We Offer</h3>
                    <p class="mb-0">
                        Search by Dzongkhag, compare services, view approved listings, and book safely in a few steps.
                        Owners can manage offerings while guests enjoy transparent details and smoother reservations.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-card h-100">
                    <h3 class="h5 mb-2">Trust and Quality</h3>
                    <p class="mb-0">
                        Listings and services go through an approval process to maintain quality and consistency for guests.
                        This helps ensure reliable information on pricing, location, and availability.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="content-card h-100">
                    <h3 class="h5 mb-2">Community Impact</h3>
                    <p class="mb-0">
                        By digitizing traditional wellness bookings, we help increase visibility for local bath owners,
                        strengthen cultural tourism, and improve access for guests across Bhutan.
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-dark px-4">Back to Home</a>
        </div>
    </section>
</div>

@push('styles')
<style>
    .about-page {
        --ink: #102a43;
        --soft: #f6fbff;
        --stroke: #d5e3ef;
    }

    .page-hero {
        background: linear-gradient(130deg, #0b2f54 0%, #123d68 100%);
        border-radius: 1rem;
        color: #fff;
        padding: 1.4rem;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 14px 30px rgba(16, 42, 67, 0.25);
    }

    .hero-kicker {
        display: inline-block;
        font-size: 0.78rem;
        letter-spacing: 0.08em;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 0.35rem;
        color: rgba(255, 255, 255, 0.85);
    }

    .info-panel {
        border: 1px solid var(--stroke);
        border-radius: 1rem;
        background: #fff;
        padding: 1.25rem;
        box-shadow: 0 10px 20px rgba(16, 42, 67, 0.08);
    }

    .content-card {
        border-radius: 0.85rem;
        border: 1px solid var(--stroke);
        background: linear-gradient(155deg, #ffffff 0%, var(--soft) 100%);
        padding: 1rem;
        color: #334e68;
    }

    .content-card h3 {
        color: var(--ink);
    }
</style>
@endpush
@endsection
