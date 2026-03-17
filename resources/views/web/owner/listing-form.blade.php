@extends('web.layouts.app')

@section('title', 'Manage Bath Listing')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-3">Manage Bath Listing</h1>
                <p class="text-muted mb-4">Update bath information, pricing, availability, facilities, and listing status.</p>

                <form method="POST" action="{{ route('owner.listing.save') }}" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Bath Name</label>
                        <input type="text" name="bath_name" class="form-control" value="{{ old('bath_name', $bath?->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Dzongkhag</label>
                        <select name="dzongkhag_id" class="form-select" required>
                            <option value="">Select Dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}" @selected((string)old('dzongkhag_id', $bath?->dzongkhag_id)===(string)$dzongkhag->id)>{{ $dzongkhag->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Price per Session (Nu.)</label>
                        <input type="number" step="0.01" min="0" name="price_per_session" class="form-control" value="{{ old('price_per_session', $bath?->price_per_session) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Maximum Guests</label>
                        <input type="number" min="1" name="max_guests" class="form-control" value="{{ old('max_guests', $bath?->max_guests ?? 4) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Opening Time</label>
                        <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time', optional(optional($bath)->availabilities?->first())->opening_time ? \Carbon\Carbon::parse($bath->availabilities->first()->opening_time)->format('H:i') : '09:00') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Closing Time</label>
                        <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time', optional(optional($bath)->availabilities?->first())->closing_time ? \Carbon\Carbon::parse($bath->availabilities->first()->closing_time)->format('H:i') : '18:00') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Full Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $bath?->full_address) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $bath?->detailed_description ?? $bath?->short_description) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Facilities (comma separated)</label>
                        <input type="text" name="facilities" class="form-control" value="{{ old('facilities', $bath ? $bath->facilities->pluck('facility_name')->implode(', ') : '') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Listing Status</label>
                        <select name="status" class="form-select">
                            @php($currentStatus = old('status', $bath?->status ?? 'pending_verification'))
                            <option value="pending_verification" @selected($currentStatus === 'pending_verification')>Pending Verification</option>
                            <option value="active" @selected($currentStatus === 'active')>Active</option>
                            <option value="inactive" @selected($currentStatus === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 d-flex gap-2 mt-2">
                        <button class="btn btn-dark">Save Listing</button>
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-dark">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
