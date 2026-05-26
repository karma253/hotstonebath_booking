@extends('web.layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Owner Dashboard</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('owner.service.form') }}" class="btn btn-success">
            <i class="fas fa-plus-circle"></i> Add Service
        </a>
        <a href="{{ route('owner.listing.form') }}" class="btn btn-outline-dark">
            <i class="fas fa-edit"></i> Manage Listing
        </a>
        <form method="POST" action="{{ route('owner.logout') }}">
            @csrf
            <button class="btn btn-dark">Logout</button>
        </form>
    </div>
</div>

@if($message = Session::get('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-start border-success border-5" role="alert" style="background-color: #d4edda; animation: slideInDown 0.5s ease-out;">
        <div class="d-flex align-items-start">
            <i class="fas fa-check-circle me-3" style="font-size: 1.2rem; color: #28a745;"></i>
            <div>
                <strong class="d-block mb-1">✓ {{ $message }}</strong>
                <small class="text-muted">Your service is now visible on the home page!</small>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($message = Session::get('error'))
    <div class="alert alert-danger alert-dismissible fade show mb-4 border-start border-danger border-5" role="alert" style="animation: slideInDown 0.5s ease-out;">
        <i class="fas fa-exclamation-circle me-2"></i><strong>Error:</strong> {{ $message }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(auth()->user()->status === 'pending_verification')
    <div class="alert alert-warning">
        Your account is pending admin verification. You can still update your listing details.
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card card-shadow rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Bath Name</h6>
                <p class="h5 mb-0">{{ $bath?->name ?? 'Not Added Yet' }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-shadow rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Listing Status</h6>
                <p class="h5 mb-0 text-capitalize">{{ str_replace('_', ' ', $bath?->status ?? 'pending_verification') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-shadow rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Price / Session</h6>
                <p class="h5 mb-0">Nu. {{ number_format((float)($bath?->price_per_session ?? 0), 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-shadow rounded-4">
            <div class="card-body">
                <h6 class="text-muted">Services Added</h6>
                <p class="h5 mb-0">
                    <span class="badge bg-success">{{ $bath?->services?->count() ?? 0 }} Services</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Services Section -->
@if($bath)
<div class="card card-shadow rounded-4 mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">🛁 Services Offered</h5>
        <a href="{{ route('owner.service.form') }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus"></i> Add Service
        </a>
    </div>
    <div class="card-body p-0">
        @if($bath->services && $bath->services->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Service Type</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Hours</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bath->services as $service)
                        <tr>
                            <td>
                                @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->service_type }}" 
                                         style="max-width: 60px; max-height: 60px; border-radius: 4px; object-fit: cover;">
                                @else
                                    <div style="width: 60px; height: 60px; background-color: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-image" style="color: #ccc;"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $service->service_type }}</strong></td>
                            <td>
                                <small class="text-muted">{{ $service->location ?: optional($bath->dzongkhag)->name ?: '-' }}</small>
                            </td>
                            <td>Nu. {{ number_format((float)$service->price, 2) }}</td>
                            <td>{{ $service->duration_minutes }} min</td>
                            <td>
                                @if($service->opening_time && $service->closing_time)
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($service->opening_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($service->closing_time)->format('H:i') }}</small>
                                @else
                                    <small class="text-secondary">-</small>
                                @endif
                            </td>
                            <td>
                                @if($service->approval_status === 'approved' && $service->is_available)
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                @elseif($service->approval_status === 'pending')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i> Pending Approval</span>
                                @elseif($service->approval_status === 'rejected')
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Rejected</span>
                                @else
                                    <span class="badge bg-secondary"><i class="fas fa-times-circle"></i> Unavailable</span>
                                @endif
                            </td>
                            <td>
                                @if($service->description)
                                    <small class="text-muted">{{ Str::limit($service->description, 50) }}</small>
                                @else
                                    <small class="text-muted text-secondary">No description</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-2 action-buttons">
                                    <a href="{{ route('owner.service.edit', $service) }}" class="btn btn-sm btn-primary action-btn" title="Edit service">
                                        <i class="fas fa-pen me-1" aria-hidden="true"></i>
                                        <span>Edit</span>
                                    </a>

                                    <form method="POST" action="{{ route('owner.service.flag', $service) }}" class="m-0">
                                        @csrf
                                        <input type="hidden" name="reason" value="Flagged by owner from dashboard.">
                                        <button class="btn btn-sm btn-warning action-btn" title="Flag for admin review" onclick="return confirm('Flag this service for admin review?')">
                                            <i class="fas fa-flag me-1" aria-hidden="true"></i>
                                            <span>Flag</span>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('owner.service.delete', $service) }}" class="m-0">
                                        @csrf
                                        <button class="btn btn-sm btn-danger action-btn" title="Delete service" onclick="return confirm('Delete this service? This cannot be undone.')">
                                            <i class="fas fa-trash me-1" aria-hidden="true"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="p-4 text-center text-muted">
                <i class="fas fa-inbox" style="font-size: 2rem; color: #ccc;"></i>
                <p class="mt-3">No services added yet. <a href="{{ route('owner.service.form') }}">Add your first service</a></p>
            </div>
        @endif
    </div>
</div>
@endif

<!-- Bookings Section -->
<div class="card card-shadow rounded-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">📅 Recent Bookings</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Guests</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->booking_id }}</td>
                        <td>{{ $booking->guest_name }}</td>
                        <td>{{ optional($booking->booking_date)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</td>
                        <td>{{ $booking->number_of_guests }}</td>
                        <td>Nu. {{ number_format((float)$booking->total_price, 2) }}</td>
                        <td><span class="badge bg-secondary">{{ strtoupper($booking->status) }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('owner.booking.status', $booking) }}" class="d-flex gap-1">
                                @csrf
                                <select name="status" class="form-select form-select-sm">
                                    @foreach(['pending', 'confirmed', 'cancelled', 'completed', 'no_show'] as $status)
                                        <option value="{{ $status }}" @selected($booking->status === $status)>{{ strtoupper($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-dark">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No bookings available.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $bookings->links() }}</div>

@push('styles')
<style>
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert {
        animation: slideInDown 0.5s ease-out;
    }

    .badge.bg-success {
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    .action-buttons .action-btn {
        min-width: 84px;
        font-size: 0.8rem;
        font-weight: 600;
        border-width: 1px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush
@endsection
