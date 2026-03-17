@extends('web.layouts.app')

@section('title', $bath->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card card-shadow rounded-4 mb-4">
            <div class="card-body p-0">
                <img
                    src="{{ isset($selectedService) && isset($serviceImages[$selectedService]) ? $serviceImages[$selectedService] : ($bathImages[$bath->name] ?? (optional($bath->images->where('is_primary', true)->first())->image_path ?: optional($bath->images->first())->image_path ?: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200')) }}"
                    alt="{{ $bath->name }}"
                    class="img-fluid w-100 rounded-top-4"
                    style="max-height: 440px; object-fit: cover;"
                >
            </div>
        </div>

        @if ($bath->images->count() > 1)
            <div class="row g-3 mb-4">
                @foreach ($bath->images->take(6) as $image)
                    <div class="col-6 col-md-4">
                        <img src="{{ $image->image_path }}" class="img-fluid rounded-3" alt="Bath image" style="height: 120px; object-fit: cover; width: 100%;">
                    </div>
                @endforeach
            </div>
        @endif

        <div class="card card-shadow rounded-4 mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Bath Experience</h2>
                <p class="mb-0">{{ $bath->detailed_description ?: $bath->short_description }}</p>
            </div>
        </div>

        <div class="card card-shadow rounded-4 mb-4">
            <div class="card-body p-4">
                <h2 class="h4 mb-3">Facilities</h2>
                <div class="d-flex flex-wrap gap-2">
                    @forelse ($bath->facilities as $facility)
                        <span class="badge rounded-pill text-bg-secondary px-3 py-2">{{ $facility->facility_name }}</span>
                    @empty
                        <span class="text-muted">Facilities will be updated by the owner.</span>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-shadow rounded-4 sticky-top" style="top: 1rem;">
            <div class="card-body p-4">
                <h1 class="h3 mb-2">{{ $bath->name }}</h1>
                <p class="text-muted mb-1">{{ optional($bath->dzongkhag)->name }}</p>
                <p class="small text-muted mb-3">{{ $bath->full_address }}</p>

                <div class="mb-3 p-3 bg-light rounded-3">
                    <div class="fw-semibold text-danger h5 mb-1">Nu. {{ number_format((float) ($bath->price_per_session ?? $bath->price_per_hour), 2) }} / person</div>
                    <div class="small text-muted">Maximum guests per session: {{ $bath->max_guests }}</div>
                </div>

                <h2 class="h6 mb-2">Available Time Slots</h2>
                <ul class="list-group list-group-flush mb-3">
                    @forelse ($bath->availabilities as $slot)
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span>{{ ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'][$slot->day_of_week] ?? 'Day' }}</span>
                            <span>{{ \Carbon\Carbon::parse($slot->opening_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->closing_time)->format('h:i A') }}</span>
                        </li>
                    @empty
                        <li class="list-group-item px-0 text-muted">Owner has not added time slots yet.</li>
                    @endforelse
                </ul>

                @auth
                    <a href="{{ route('guest.booking.create', $bath) }}" class="btn btn-dark w-100">Book Now</a>
                @else
                    <a href="{{ route('guest.login') }}" class="btn btn-dark w-100">Login to Book</a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
