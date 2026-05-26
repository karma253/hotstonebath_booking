@extends('web.layouts.app')

@section('title', 'How to Book')

@section('content')
<div class="how-book-page">
    <section class="page-hero mb-4">
        <span class="hero-kicker">Guest Guide</span>
        <h1 class="mb-2">How to Book</h1>
        <p class="mb-0">Follow these simple steps to reserve your hot stone bath experience.</p>
    </section>

    <section class="info-panel">
        <div class="row g-3">
            <div class="col-md-6 col-lg-3">
                <div class="step-card h-100">
                    <h3 class="h6 mb-2">1. Browse</h3>
                    <p class="mb-0 small">Search bath houses by Dzongkhag and view photos, facilities, and prices.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card h-100">
                    <h3 class="h6 mb-2">2. Select</h3>
                    <p class="mb-0 small">Choose booking date, available time slot, and number of guests.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card h-100">
                    <h3 class="h6 mb-2">3. Login / Signup</h3>
                    <p class="mb-0 small">Login or signup to confirm reservation, payment, and booking history.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="step-card h-100">
                    <h3 class="h6 mb-2">4. Confirm</h3>
                    <p class="mb-0 small">Review summary and pay digitally or choose cash on arrival.</p>
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
    .how-book-page {
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

    .step-card {
        border-radius: 0.85rem;
        border: 1px solid var(--stroke);
        background: linear-gradient(155deg, #ffffff 0%, var(--soft) 100%);
        padding: 1rem;
        color: #334e68;
    }

    .step-card h3 {
        color: var(--ink);
    }
</style>
@endpush
@endsection
