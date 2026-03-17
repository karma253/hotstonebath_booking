@extends('web.layouts.app')

@section('title', 'Owner Registration')

@section('content')
<div class="card card-shadow rounded-4">
    <div class="card-body p-4 p-lg-5">
        <h1 class="h3 mb-2">Bath Owner Registration</h1>
        <p class="text-muted mb-4">Submit owner details and bath information for admin verification.</p>

        <form method="POST" action="{{ route('owner.register.submit') }}" class="row g-3">
            @csrf
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bath Name</label>
                <input type="text" name="bath_name" class="form-control" value="{{ old('bath_name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Dzongkhag</label>
                <select name="dzongkhag_id" class="form-select" required>
                    <option value="">Select Dzongkhag</option>
                    @foreach($dzongkhags as $dzongkhag)
                        <option value="{{ $dzongkhag->id }}" @selected((string)old('dzongkhag_id')===(string)$dzongkhag->id)>{{ $dzongkhag->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Price per Session (Nu.)</label>
                <input type="number" name="price_per_session" class="form-control" min="0" step="0.01" value="{{ old('price_per_session') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Maximum Guests</label>
                <input type="number" name="max_guests" class="form-control" min="1" value="{{ old('max_guests', 4) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Opening Time</label>
                <input type="time" name="opening_time" class="form-control" value="{{ old('opening_time', '09:00') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Closing Time</label>
                <input type="time" name="closing_time" class="form-control" value="{{ old('closing_time', '18:00') }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Full Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Bath Description</label>
                <textarea name="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Facilities (comma separated)</label>
                <input type="text" name="facilities" class="form-control" value="{{ old('facilities', 'Changing room, Towels, Herbal bath') }}" placeholder="Changing room, Towels, Herbal bath">
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
                <button class="btn btn-dark">Submit Registration</button>
            </div>
        </form>
    </div>
</div>
@endsection
