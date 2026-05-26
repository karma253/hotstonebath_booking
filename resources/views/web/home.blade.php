@extends('web.layouts.app')

@section('title', 'Browse Hot Stone Baths')

@section('content')
<div class="home-shell">
    <section id="home" class="hero-panel mb-4">
        <div class="row justify-content-center">
            <div class="col-lg-9 text-center">
                <span class="kicker-pill">Bhutan Wellness Journey</span>
                <h1 class="hero-title mt-3 mb-3">Find and Book Traditional Menchu Experiences Across Bhutan</h1>
                <p class="hero-text mb-4">Explore trusted hot stone bath houses by Dzongkhag, compare session prices, and reserve your preferred time slot in minutes.</p>
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-3">
                    <a href="{{ route('guest.register') }}" class="btn btn-brand">Create Guest Account</a>
                    <a href="{{ route('guest.login') }}" class="btn btn-soft">Already Registered? Login</a>
                </div>
            </div>
        </div>
    </section>

    <section class="search-panel mb-5">
        <form method="GET" action="{{ route('home') }}" class="row g-3 align-items-end" id="searchForm">
            <div class="col-md-5">
                <label class="form-label">Search by bath name or location</label>
                <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="Search baths...">
            </div>
            <div class="col-md-4">
                <label class="form-label">Dzongkhag</label>
                <select name="dzongkhag_id" class="form-select">
                    <option value="">All Dzongkhags</option>
                    @foreach ($dzongkhags as $dzongkhag)
                        <option value="{{ $dzongkhag->id }}" @selected((string) request('dzongkhag_id') === (string) $dzongkhag->id)>{{ $dzongkhag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-grid">
                <button type="submit" class="btn btn-brand h-100">Search Services</button>
            </div>
        </form>
    </section>

    <section id="featuredSection" class="my-5" @if(!request('keyword') && !request('dzongkhag_id'))style="display: none;"@endif>
        <div class="section-head mb-4">
            <h2 class="section-title mb-0">Featured Bath Services</h2>
        </div>
        <div class="row g-4" id="servicesContainer">
            @forelse ($featuredServices as $service)
                <div class="col-6 col-md-4 col-lg-3 service-card" data-index="{{ $loop->index }}">
                    <article class="listing-card h-100">
                        @php
                            $featuredImage = $service->image
                                ? asset('storage/' . ltrim($service->image, '/'))
                                : ($serviceImages[$service->service_type] ?? (optional($service->bath->images->first())->image_path ?: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500'));
                        @endphp
                        <img
                            src="{{ $featuredImage }}"
                            class="listing-image"
                            alt="{{ $service->service_type }}"
                        >
                        <div class="listing-body d-flex flex-column">
                            <h5 class="listing-title mb-1">{{ $service->service_type }}</h5>
                            <p class="listing-subtitle mb-2"><strong>{{ optional($service->bath)->name }}</strong></p>
                            <p class="listing-meta mb-2">{{ optional($service->dzongkhag)->name ?: optional($service->bath->dzongkhag)->name }}@if($service->location) - {{ $service->location }}@endif</p>
                            <p class="listing-desc mb-3">{{ Str::limit($service->description, 70) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="price-tag">Nu. {{ number_format((float) $service->price, 2) }}</span>
                                <span class="badge badge-soft">{{ $service->duration_minutes }}m</span>
                            </div>
                            <a href="{{ route('baths.show', ['bath' => $service->bath, 'service' => $service->service_type]) }}" class="btn btn-soft mt-auto">View Details</a>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">Featured services will appear here soon.</div>
                </div>
            @endforelse
        </div>
        <div class="mt-4 d-flex justify-content-end">
            <button id="toggleServicesBtn" class="toggle-services-btn" type="button">
                <span id="toggleServicesText">See More</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </div>
    </section>

    <section id="latestServicesSection" class="my-5">
        <div class="section-head mb-4">
            <h2 class="section-title mb-0">Latest Services Added</h2>
        </div>
        @if($latestServices && $latestServices->count() > 0)
            <div class="row g-4">
                @foreach($latestServices->take(8) as $service)
                    <div class="col-6 col-md-4 col-lg-3">
                        <article class="listing-card h-100">
                            <div class="position-relative">
                                @php
                                    $latestImage = $service->image
                                        ? asset('storage/' . ltrim($service->image, '/'))
                                        : ($bathImages[optional($service->bath)->name] ?? ($serviceImages[$service->service_type] ?? (optional($service->bath->images->first())->image_path ?: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=500')));
                                @endphp
                                <img
                                    src="{{ $latestImage }}"
                                    class="listing-image"
                                    alt="{{ $service->service_type }}"
                                >
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">New</span>
                            </div>
                            <div class="listing-body d-flex flex-column">
                                <h5 class="listing-title mb-1">{{ $service->service_type }}</h5>
                                <p class="listing-subtitle mb-2"><strong>{{ optional($service->bath)->name }}</strong></p>
                                <p class="listing-meta mb-2">{{ optional($service->dzongkhag)->name ?: optional($service->bath->dzongkhag)->name }}@if($service->location) - {{ $service->location }}@endif</p>
                                <p class="listing-desc mb-3">{{ Str::limit($service->description, 70) }}</p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="price-tag">Nu. {{ number_format((float) $service->price, 2) }}</span>
                                    <span class="badge badge-soft">{{ $service->duration_minutes }}m</span>
                                </div>
                                <a href="{{ route('baths.show', ['bath' => $service->bath, 'service' => $service->service_type]) }}" class="btn btn-soft mt-auto">View Details</a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-info-circle me-2"></i>
                <strong>New services coming soon!</strong> Bath owners are adding exciting new services. Remove filters to view all available options.
            </div>
        @endif
    </section>

    <section id="resultsSection" @if(!request('keyword') && !request('dzongkhag_id'))style="display: none;"@endif>
        <div class="section-head mb-4">
            <span class="section-kicker">Search Results</span>
            <h2 class="section-title mb-0">Available Bath Listings</h2>
        </div>

        <div id="all-baths" class="row g-4">
        @forelse ($baths as $bath)
            <div class="col-md-6 col-lg-4">
                <article class="listing-card h-100">
                    <img
                        src="{{ $bathImages[$bath->name] ?? (optional($bath->images->first())->image_path ?: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=900') }}"
                        class="listing-image bath-thumb"
                        alt="{{ $bath->name }}"
                    >
                    <div class="listing-body d-flex flex-column">
                        <h5 class="listing-title mb-1">{{ $bath->name }}</h5>
                        <p class="listing-meta mb-2">{{ optional($bath->dzongkhag)->name }}</p>
                        <p class="listing-desc mb-3">{{ $bath->short_description }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="price-tag">Nu. {{ number_format((float) ($bath->price_per_session ?? $bath->price_per_hour), 2) }} / person</span>
                            <span class="badge badge-soft">Max {{ $bath->max_guests }}</span>
                        </div>

                        @if($bath->services && $bath->services->count() > 0)
                        <div class="service-strip mb-3">
                            <p class="small fw-semibold mb-2">
                                <i class="fas fa-spa me-2"></i>{{ $bath->services->count() }} Service{{ $bath->services->count() > 1 ? 's' : '' }} Available
                            </p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($bath->services->take(3) as $service)
                                    <span class="badge bg-success text-white" title="{{ $service->service_type }}">{{ Str::limit($service->service_type, 15) }}</span>
                                @endforeach
                                @if($bath->services->count() > 3)
                                    <span class="badge bg-secondary text-white">+{{ $bath->services->count() - 3 }} more</span>
                                @endif
                            </div>
                        </div>
                        @endif

                        <a href="{{ route('baths.show', $bath) }}" class="btn btn-soft mt-auto">View Details</a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">No bath listings found for the selected filters.</div>
            </div>
        @endforelse
        </div>

        <div class="mt-4">{{ $baths->links() }}</div>
    </section>

</div>

@push('styles')
<style>
    .home-shell {
        --brand-ink: #102a43;
        --brand-deep: #0d3b66;
        --brand-mid: #1e4f7a;
        --brand-light: #eaf2f8;
        --brand-slate: #334e68;
        --surface: #ffffff;
        --surface-soft: #f6fbff;
        --stroke: #d5e3ef;
    }

    .hero-panel {
        background: linear-gradient(130deg, #0b2f54 0%, #123d68 100%);
        border-radius: 1.35rem;
        border: 1px solid rgba(255, 255, 255, 0.18);
        box-shadow: 0 24px 48px rgba(16, 42, 67, 0.2);
        color: #fff;
        overflow: hidden;
        padding: clamp(1.4rem, 2.6vw, 2.6rem);
        transition: transform 0.28s ease, box-shadow 0.28s ease, background 0.28s ease;
        cursor: pointer;
    }

    .hero-panel:hover,
    .hero-panel:focus-within {
        transform: translateY(-4px);
        box-shadow: 0 30px 56px rgba(11, 47, 84, 0.35);
        background: linear-gradient(130deg, #082745 0%, #10365b 100%);
    }

    .hero-panel:active {
        transform: translateY(-1px) scale(0.998);
    }

    .kicker-pill {
        display: inline-flex;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        padding: 0.35rem 0.85rem;
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.42);
    }

    .hero-title {
        font-size: clamp(1.8rem, 2.8vw, 3rem);
        font-weight: 700;
        line-height: 1.14;
        max-width: 18ch;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-text {
        font-size: 1.06rem;
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.92);
        max-width: 58ch;
        margin-left: auto;
        margin-right: auto;
    }

    .hero-metrics .metric-chip {
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #fff;
        font-size: 0.8rem;
        padding: 0.3rem 0.7rem;
    }

    .btn-brand {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.55);
        font-weight: 600;
        border-radius: 0.7rem;
        padding: 0.62rem 1rem;
        transition: all 0.25s ease;
    }

    .btn-brand:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.75);
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-soft {
        background: transparent;
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.65);
        font-weight: 600;
        border-radius: 0.7rem;
        padding: 0.58rem 0.95rem;
        transition: all 0.25s ease;
    }

    .btn-soft:hover {
        background: rgba(255, 255, 255, 0.16);
        color: #fff;
        border-color: rgba(255, 255, 255, 0.85);
    }

    .search-panel {
        background: linear-gradient(130deg, var(--surface) 0%, var(--surface-soft) 100%);
        border-radius: 1rem;
        padding: 1.25rem;
        border: 1px solid var(--stroke);
        box-shadow: 0 10px 20px rgba(16, 42, 67, 0.08);
    }

    .search-panel .form-control,
    .search-panel .form-select {
        border-color: #cfd8e3;
        border-radius: 0.7rem;
        min-height: 2.85rem;
    }

    .search-panel .form-control:focus,
    .search-panel .form-select:focus {
        border-color: #6c9ec7;
        box-shadow: 0 0 0 0.2rem rgba(13, 59, 102, 0.15);
    }

    .section-head {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        flex-direction: column;
        flex-wrap: wrap;
        gap: 0.4rem 1rem;
        text-align: left;
    }

    .section-kicker {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #627d98;
    }

    .section-title {
        color: var(--brand-ink);
        font-weight: 700;
    }

    .listing-card {
        background: var(--surface);
        border: 1px solid var(--stroke);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 8px 20px rgba(16, 42, 67, 0.08);
        transition: transform 0.28s ease, box-shadow 0.28s ease;
    }

    .listing-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 36px rgba(16, 42, 67, 0.15);
    }

    .listing-image {
        width: 100%;
        height: 210px;
        object-fit: cover;
    }

    .listing-body {
        padding: 1rem;
    }

    .listing-title {
        color: var(--brand-ink);
        font-weight: 700;
    }

    .listing-subtitle {
        color: var(--brand-slate);
        font-size: 0.88rem;
    }

    .listing-meta {
        color: #627d98;
        font-size: 0.85rem;
    }

    .listing-meta::before {
        content: "📍 ";
    }

    .listing-desc {
        color: var(--brand-slate);
        font-size: 0.88rem;
        line-height: 1.5;
    }

    .price-tag {
        color: var(--brand-deep);
        font-weight: 700;
        font-size: 0.96rem;
    }

    .badge-soft {
        background: #edf2f7;
        color: #334e68;
        border: 1px solid #d9e2ec;
    }

    .toggle-services-btn {
        padding: 0.66rem 1.1rem;
        border-radius: 0.7rem;
        border: 1px solid var(--brand-deep);
        background: var(--brand-deep);
        color: #fff;
        font-weight: 600;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .toggle-services-btn:hover {
        background: #0a2f52;
        border-color: #0a2f52;
        transform: translateY(-1px);
    }

    .service-card {
        transition: opacity 0.25s ease;
    }

    .service-strip {
        border-radius: 0.75rem;
        border: 1px solid #d5e3ef;
        background: linear-gradient(135deg, #f3f8fc 0%, #ffffff 100%);
        padding: 0.75rem;
        color: #334e68;
    }

    .info-panel {
        border: 1px solid var(--stroke);
        border-radius: 1rem;
        background: var(--surface);
        padding: 1.3rem;
        box-shadow: 0 10px 20px rgba(16, 42, 67, 0.08);
    }

    .step-card {
        border-radius: 0.85rem;
        border: 1px solid #d5e3ef;
        background: linear-gradient(155deg, #ffffff 0%, #f3f8fc 100%);
        padding: 1rem;
        color: #334e68;
    }

    .about-panel {
        border-radius: 1rem;
        padding: 1.4rem;
        background: linear-gradient(130deg, var(--brand-deep) 0%, var(--brand-mid) 100%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 14px 30px rgba(16, 42, 67, 0.2);
    }

    .empty-state {
        border-radius: 0.8rem;
        padding: 1rem;
        border: 1px dashed #c9d6e2;
        background: #f8fbfd;
        color: #52606d;
    }

    #featuredSection,
    #resultsSection,
    #latestServicesSection {
        animation: fadeInUp 0.55s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(16px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 767.98px) {
        .hero-panel {
            padding: 1.2rem;
        }

        .listing-image {
            height: 180px;
        }

        .section-title {
            font-size: 1.35rem;
        }
    }
</style>
@endpush
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceCards = document.querySelectorAll('.service-card');
        const toggleButton = document.getElementById('toggleServicesBtn');
        const toggleText = document.getElementById('toggleServicesText');
        let isExpanded = false;

        function applyServiceCardVisibility() {
            serviceCards.forEach(function(card, index) {
                if (index < 4 || isExpanded) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            if (toggleText) {
                toggleText.textContent = isExpanded ? 'See Less' : 'See More';
            }
        }

        if (toggleButton) {
            applyServiceCardVisibility();

            if (serviceCards.length > 4) {
                toggleButton.addEventListener('click', function() {
                    isExpanded = !isExpanded;
                    applyServiceCardVisibility();
                });
            }
        }

        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword');
        const dzongkhagId = urlParams.get('dzongkhag_id');
        const hasSearch = keyword || dzongkhagId;

        const featuredSection = document.getElementById('featuredSection');
        const resultsSection = document.getElementById('resultsSection');

        if (hasSearch) {
            if (featuredSection) featuredSection.style.display = 'block';
            if (resultsSection) {
                resultsSection.style.display = 'block';
                setTimeout(function() {
                    resultsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            }
        } else {
            if (featuredSection) featuredSection.style.display = 'block';
            if (resultsSection) resultsSection.style.display = 'none';
        }

        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function() {
                if (featuredSection) featuredSection.style.display = 'none';
                if (resultsSection) resultsSection.style.display = 'block';
            });
        }
    });
</script>
@endpush
@endsection

