@extends('layouts.admin-layout')

@section('title', 'Edit Bath - ' . $bath->name)

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <a href="{{ route('admin.baths') }}" class="btn btn-secondary btn-sm mb-3">
            <i class="fas fa-arrow-left"></i> Back to Baths
        </a>
        <h1 class="h3 mb-0">Edit Bath: {{ $bath->name }}</h1>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.baths.update', $bath) }}" method="POST">
                        @csrf

                        <!-- Basic Information -->
                        <h5 class="mb-3 border-bottom pb-2">Basic Information</h5>

                        <div class="mb-3">
                            <label for="name" class="form-label">Bath Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $bath->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $bath->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Bath Details -->
                        <h5 class="mb-3 border-bottom pb-2">Bath Details</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bath_type" class="form-label">Bath Type *</label>
                                <select class="form-select @error('bath_type') is-invalid @enderror" 
                                        id="bath_type" name="bath_type" required>
                                    <option value="">Select a type</option>
                                    @foreach($bathTypes as $type)
                                        <option value="{{ $type }}" {{ old('bath_type', $bath->bath_type) === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bath_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="dzongkhag_id" class="form-label">Location (Dzongkhag) *</label>
                                <select class="form-select @error('dzongkhag_id') is-invalid @enderror" 
                                        id="dzongkhag_id" name="dzongkhag_id" required>
                                    <option value="">Select location</option>
                                    @foreach($dzongkhags as $dzongkhag)
                                        <option value="{{ $dzongkhag->id }}" {{ old('dzongkhag_id', $bath->dzongkhag_id) == $dzongkhag->id ? 'selected' : '' }}>
                                            {{ $dzongkhag->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('dzongkhag_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="owner_id" class="form-label">Owner *</label>
                                <select class="form-select @error('owner_id') is-invalid @enderror" 
                                        id="owner_id" name="owner_id" required>
                                    <option value="">Select owner</option>
                                    @foreach($owners as $owner)
                                        <option value="{{ $owner->id }}" {{ old('owner_id', $bath->owner_id) == $owner->id ? 'selected' : '' }}>
                                            {{ $owner->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('owner_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_guests" class="form-label">Max Guests *</label>
                                <input type="number" class="form-control @error('max_guests') is-invalid @enderror" 
                                       id="max_guests" name="max_guests" value="{{ old('max_guests', $bath->max_guests) }}" 
                                       min="1" required>
                                @error('max_guests') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Pricing -->
                        <h5 class="mb-3 border-bottom pb-2">Pricing</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="price_per_session" class="form-label">Price per Session (Nu.) *</label>
                                <input type="number" class="form-control @error('price_per_session') is-invalid @enderror" 
                                       id="price_per_session" name="price_per_session" value="{{ old('price_per_session', $bath->price_per_session) }}" 
                                       step="0.01" min="0" required>
                                @error('price_per_session') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="price_per_hour" class="form-label">Price per Hour (Nu.)</label>
                                <input type="number" class="form-control @error('price_per_hour') is-invalid @enderror" 
                                       id="price_per_hour" name="price_per_hour" value="{{ old('price_per_hour', $bath->price_per_hour) }}" 
                                       step="0.01" min="0">
                                @error('price_per_hour') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Operating Hours -->
                        <h5 class="mb-3 border-bottom pb-2">Operating Hours</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="opening_time" class="form-label">Opening Time *</label>
                                <input type="time" class="form-control @error('opening_time') is-invalid @enderror" 
                                       id="opening_time" name="opening_time" value="{{ old('opening_time', $bath->opening_time) }}" required>
                                @error('opening_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="closing_time" class="form-label">Closing Time *</label>
                                <input type="time" class="form-control @error('closing_time') is-invalid @enderror" 
                                       id="closing_time" name="closing_time" value="{{ old('closing_time', $bath->closing_time) }}" required>
                                @error('closing_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-2 mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="{{ route('admin.baths') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar: Bath Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">Bath Status</h6>
                    <div class="mb-2">
                        <small class="text-muted">Current Status</small>
                        <div>
                            @if($bath->status === 'active')
                                <span class="badge bg-success">Active</span>
                            @elseif($bath->status === 'pending_verification')
                                <span class="badge bg-warning">Pending Verification</span>
                            @else
                                <span class="badge bg-danger">{{ ucfirst(str_replace('_', ' ', $bath->status)) }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Verified</small>
                        <div>{{ $bath->verified_at ? $bath->verified_at->format('M d, Y') : 'Not verified' }}</div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">Services</h6>
                    <div class="mb-2">
                        <small class="text-muted">Total Services</small>
                        <div class="fs-5 fw-bold">{{ $bath->services->count() }}</div>
                    </div>
                    @if($bath->services->count() > 0)
                        <div class="list-group list-group-sm">
                            @foreach($bath->services->take(5) as $service)
                                <div class="list-group-item px-0 py-2">
                                    <small>{{ $service->description }}</small>
                                    <br>
                                    <small class="text-muted">
                                        @if($service->approval_status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($service->approval_status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Rejected</span>
                                        @endif
                                    </small>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
