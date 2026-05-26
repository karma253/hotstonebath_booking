@extends('web.layouts.app')

@section('title', 'Edit Service - Owner')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Service</h1>
        <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card card-shadow rounded-4">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('owner.service.update', $service) }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Service Type <span class="text-danger">*</span></label>
                        <select name="service_type" class="form-select @error('service_type') is-invalid @enderror" required>
                            <option value="">Select a service type</option>
                            @foreach($serviceTypes as $type)
                                <option value="{{ $type }}" @selected(old('service_type', $service->service_type) === $type)>{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Dzongkhag <span class="text-danger">*</span></label>
                        <select name="dzongkhag_id" class="form-select @error('dzongkhag_id') is-invalid @enderror" required>
                            <option value="">Select dzongkhag</option>
                            @foreach($dzongkhags as $dzongkhag)
                                <option value="{{ $dzongkhag->id }}" @selected((string) old('dzongkhag_id', $service->dzongkhag_id) === (string) $dzongkhag->id)>
                                    {{ $dzongkhag->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Service Location <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location', $service->location) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Price (Nu.) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $service->price) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Duration (Minutes) <span class="text-danger">*</span></label>
                        <input type="number" min="15" step="15" name="duration_minutes" class="form-control @error('duration_minutes') is-invalid @enderror"
                               value="{{ old('duration_minutes', $service->duration_minutes) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Opening Time</label>
                        <input type="time" name="opening_time" class="form-control @error('opening_time') is-invalid @enderror"
                               value="{{ old('opening_time', $service->opening_time) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Closing Time</label>
                        <input type="time" name="closing_time" class="form-control @error('closing_time') is-invalid @enderror"
                               value="{{ old('closing_time', $service->closing_time) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Service Image</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/gif">
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="is_available" id="is_available" @checked(old('is_available', $service->is_available))>
                            <label class="form-check-label" for="is_available">Service is available</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Update Service
                    </button>
                    <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>

                <div class="alert alert-info mt-3 mb-0">
                    After update, this service will go to pending for admin approval.
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
