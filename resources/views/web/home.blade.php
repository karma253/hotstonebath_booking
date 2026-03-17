@extends('web.layouts.app')

@section('title', 'Browse Hot Stone Baths')

@section('content')
<div id="home" class="p-4 p-lg-5 rounded-4 hero-bg mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-7">
            <div class="bhutan-chip">Bhutan Wellness Journey</div>
            <h1 class="display-6 fw-bold mb-3">Find and Book Traditional Menchu Experiences Across Bhutan</h1>
            <p class="lead mb-2">Explore local hot stone bath houses by Dzongkhag, compare session prices, and reserve your preferred time slot.</p>
            <p class="mb-0">
                <a href="{{ route('guest.register') }}" class="hero-soft-link">Create guest account</a>
                <span class="mx-2">|</span>
                <a href="{{ route('guest.login') }}" class="hero-soft-link">Already registered? Login</a>
            </p>
        </div>
        <div class="col-lg-5 text-center">
            <img class="img-fluid rounded-4" src="/image/logo.png" alt="Hot Stone Bath Booking Logo" style="max-height: 400px; object-fit: contain;">
        </div>
    </div>
</div>

<div class="my-5">
    <h2 class="h3 section-title mb-4">Featured Bath Services</h2>
    <div class="row g-4">
        @forelse ($featuredServices as $service)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-shadow rounded-4 h-100">
                    <img
                        src="{{ $serviceImages[$service->service_type] ?? 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500' }}"
                        class="card-img-top"
                        alt="{{ $service->service_type }}"
                        style="height: 200px; object-fit: cover;"
                    >
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $service->service_type }}</h5>
                        <p class="text-muted small mb-2">{{ optional($service->bath->dzongkhag)->name }}</p>
                        <p class="small text-secondary mb-3">{{ Str::limit($service->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold text-danger">Nu. {{ number_format((float) $service->price, 2) }}</span>
                            <span class="badge bg-light text-dark">{{ $service->duration_minutes }}m</span>
                        </div>
                        <a href="{{ route('baths.show', ['bath' => $service->bath, 'service' => $service->service_type]) }}" class="btn btn-sm btn-outline-dark mt-auto">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Featured services will appear here soon.</p>
            </div>
        @endforelse
</div>

<!-- Search Form -->
<div class="card card-shadow rounded-4 mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('home') }}" class="row g-3" id="searchForm">
            <div class="col-md-5">
                <label class="form-label">Search by bath name or location</label>
                <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="Search baths...">
            </div>
            <div class="col-md-4">
                <label class="form-label">Dzongkhag</label>
                <select name="dzongkhag_id" class="form-select">
                    <option value="">All Dzongkhags</option>
                    @foreach ($dzongkhags as $dzongkhag)
                        <option value="{{ $dzongkhag->id }}" @selected((string) request('dzongkhag_id') === (string) $dzongkhag->id)>
                            {{ $dzongkhag->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-grid align-items-end">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button type="submit" class="btn btn-dark">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Featured Services - Hidden Initially -->
<div id="featuredSection" class="my-5" @if(!request('keyword') && !request('dzongkhag_id'))style="display: none;"@endif>
    <h2 class="h3 section-title mb-4">Featured Bath Services</h2>
    <div class="row g-4">
        @forelse ($featuredServices as $service)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card card-shadow rounded-4 h-100">
                    <img
                        src="{{ $serviceImages[$service->service_type] ?? 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500' }}"
                        class="card-img-top"
                        alt="{{ $service->service_type }}"
                        style="height: 200px; object-fit: cover;"
                    >
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title mb-1">{{ $service->service_type }}</h5>
                        <p class="text-muted small mb-2">{{ optional($service->bath->dzongkhag)->name }}</p>
                        <p class="small text-secondary mb-3">{{ Str::limit($service->description, 60) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold text-danger">Nu. {{ number_format((float) $service->price, 2) }}</span>
                            <span class="badge bg-light text-dark">{{ $service->duration_minutes }}m</span>
                        </div>
                        <a href="{{ route('baths.show', ['bath' => $service->bath, 'service' => $service->service_type]) }}" class="btn btn-sm btn-outline-dark mt-auto">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-muted">Featured services will appear here soon.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-4">
        <a href="#all-baths" class="text-decoration-none d-inline-flex align-items-center" style="color: #212529; font-weight: 500; transition: all 0.3s ease;">
            <span class="me-2">See More</span>
            <i class="fa-solid fa-arrow-right" style="font-size: 0.95rem;"></i>
        </a>
    </div>
</div>

<!-- Search Results - Hidden Initially -->
<div id="resultsSection" @if(!request('keyword') && !request('dzongkhag_id'))style="display: none;"@endif>
    <div id="all-baths" class="row g-4">
    @forelse ($baths as $bath)
        <div class="col-md-6 col-lg-4">
            <div class="card card-shadow rounded-4 h-100">
                <img
                    src="{{ $bathImages[$bath->name] ?? (optional($bath->images->first())->image_path ?: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=900') }}"
                    class="card-img-top bath-thumb"
                    alt="{{ $bath->name }}"
                >
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title mb-1">{{ $bath->name }}</h5>
                    <p class="text-muted mb-2">{{ optional($bath->dzongkhag)->name }}</p>
                    <p class="small text-secondary mb-3">{{ $bath->short_description }}</p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-semibold text-danger">Nu. {{ number_format((float) ($bath->price_per_session ?? $bath->price_per_hour), 2) }} / person</span>
                        <span class="badge bg-light text-dark">Max {{ $bath->max_guests }}</span>
                    </div>
                    <a href="{{ route('baths.show', $bath) }}" class="btn btn-outline-dark mt-auto">View Details</a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-warning">No bath listings found for the selected filters.</div>
        </div>
    @endforelse
    </div>

    <div class="mt-4">
        {{ $baths->links() }}
    </div>
</div>

<div id="how-to-book" class="card card-shadow rounded-4 mt-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="h4 mb-3 section-title">How to Book</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="border rounded-3 p-3 h-100">
                    <h3 class="h6">1. Browse</h3>
                    <p class="mb-0 small text-muted">Search bath houses by Dzongkhag and view photos, facilities, and prices.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded-3 p-3 h-100">
                    <h3 class="h6">2. Select</h3>
                    <p class="mb-0 small text-muted">Choose booking date, available time slot, and number of guests.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded-3 p-3 h-100">
                    <h3 class="h6">3. Login / Signup</h3>
                    <p class="mb-0 small text-muted">Login or signup to confirm reservation, payment, and booking history.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border rounded-3 p-3 h-100">
                    <h3 class="h6">4. Confirm</h3>
                    <p class="mb-0 small text-muted">Review summary and pay digitally or choose cash on arrival.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="about-us" class="card card-shadow rounded-4 mt-4">
    <div class="card-body p-4 p-lg-5">
        <h2 class="h4 mb-3 section-title">About Us</h2>
        <p class="mb-0 text-muted">
            Hot Stone Bath Booking System connects guests with verified bath owners across Bhutan.
            We promote authentic Bhutanese wellness traditions by making it easy to discover and book
            trusted menchu services while helping local providers manage listings and availability.
        </p>
    </div>
</div>

@push('styles')
<style>
    #featuredSection,
    #resultsSection {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #searchForm {
        background: linear-gradient(135deg, rgba(240, 196, 108, 0.08) 0%, rgba(255, 255, 255, 0.4) 100%);
        padding: 1.5rem;
        border-radius: 1rem;
        border: 1px solid rgba(240, 196, 108, 0.2);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    #searchForm:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        border-color: rgba(240, 196, 108, 0.35);
    }
</style>
@endpush

<script>
    // Show/hide sections based on search
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword');
        const dzongkhagId = urlParams.get('dzongkhag_id');
        const hasSearch = keyword || dzongkhagId;

        const featuredSection = document.getElementById('featuredSection');
        const resultsSection = document.getElementById('resultsSection');

        if (hasSearch) {
            // Hide featured, show results
            if (featuredSection) featuredSection.style.display = 'none';
            if (resultsSection) {
                resultsSection.style.display = 'block';
                // Scroll to results
                setTimeout(function() {
                    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        } else {
            // Show featured, hide results
            if (featuredSection) featuredSection.style.display = 'block';
            if (resultsSection) resultsSection.style.display = 'none';
        }
    });

    // Update display when form is submitted
    document.getElementById('searchForm').addEventListener('submit', function() {
        const featuredSection = document.getElementById('featuredSection');
        const resultsSection = document.getElementById('resultsSection');
        if (featuredSection) featuredSection.style.display = 'none';
        if (resultsSection) resultsSection.style.display = 'block';
    });
</script>
@endsection

