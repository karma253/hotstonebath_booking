@extends('web.layouts.app')

@section('title', 'Add Service - Owner')

@section('content')
<div class="service-page">
    <div class="service-bg-orb orb-1"></div>
    <div class="service-bg-orb orb-2"></div>

    <div class="container py-4">
        <div class="service-hero mb-4">
            <div>
                <h1 class="service-title mb-1">Create A New Service</h1>
            </div>
            <a href="{{ route('owner.dashboard') }}" class="btn btn-soft-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <strong>Please fix these issues:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4 justify-content-center align-items-start">
            <div class="col-12 col-lg-10 col-xl-9">
                <div class="service-card">
                    <div class="service-card-head">
                        <h5 class="mb-0">Service Details</h5>
                    </div>

                    <div class="service-card-body">
                        <form id="addServiceForm" method="POST" action="{{ route('owner.service.save') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="section-label">Core Information</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Service Type <span class="text-danger">*</span></label>
                                    <select name="service_type" class="form-select form-select-lg form-pill @error('service_type') is-invalid @enderror" required>
                                        <option value="">Select a service type</option>
                                        @foreach($serviceTypes as $service)
                                            <option value="{{ $service }}" @selected(old('service_type') === $service)>{{ $service }}</option>
                                        @endforeach
                                    </select>
                                    @error('service_type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Dzongkhag <span class="text-danger">*</span></label>
                                    <select name="dzongkhag_id" class="form-select form-select-lg form-pill @error('dzongkhag_id') is-invalid @enderror" required>
                                        <option value="">Select dzongkhag</option>
                                        @foreach($dzongkhags as $dzongkhag)
                                            <option value="{{ $dzongkhag->id }}" @selected((string) old('dzongkhag_id', $bath->dzongkhag_id) === (string) $dzongkhag->id)>
                                                {{ $dzongkhag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dzongkhag_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Service Location <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="location"
                                        class="form-control form-control-lg form-pill @error('location') is-invalid @enderror"
                                        value="{{ old('location', $bath->full_address) }}"
                                        placeholder="e.g., Simtokha, near Memorial Chorten"
                                        required
                                    >
                                    @error('location')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Price per Service <span class="text-danger">*</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text price-chip">Nu.</span>
                                        <input type="text" name="price" class="form-control form-pill @error('price') is-invalid @enderror"
                                               placeholder="Type service price (e.g., 1200.00)" inputmode="decimal"
                                               value="{{ old('price') }}" required>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Duration (Minutes) <span class="text-danger">*</span></label>
                                    <input type="number" name="duration_minutes" class="form-control form-control-lg form-pill @error('duration_minutes') is-invalid @enderror"
                                           placeholder="60" min="15" step="15" value="{{ old('duration_minutes', 60) }}" required>
                                    @error('duration_minutes')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 section-label">Description & Media</div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-600">Description</label>
                                    <textarea name="description" class="form-control form-pill @error('description') is-invalid @enderror"
                                              rows="4" placeholder="Describe what this service includes...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-600">Service Image</label>
                                    <input type="file" name="image" id="serviceImage" class="form-control form-control-lg form-pill @error('image') is-invalid @enderror"
                                           accept="image/jpeg,image/png,image/jpg,image/gif">
                                    <small class="text-muted d-block mt-2">PNG, JPG, or GIF up to 5MB.</small>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror

                                    <div id="imagePreviewContainer" class="mt-3" style="display: none;">
                                        <img id="imagePreview" src="" alt="Image Preview" class="image-preview">
                                    </div>
                                </div>
                            </div>

                            @php
                                $openingOld = old('opening_time', '09:00');
                                [$openingHour24, $openingMinute] = array_pad(explode(':', $openingOld), 2, '00');
                                $openingHour24 = (int) $openingHour24;
                                $openingPeriod = $openingHour24 >= 12 ? 'PM' : 'AM';
                                $openingHour12 = $openingHour24 % 12;
                                $openingHour12 = $openingHour12 === 0 ? 12 : $openingHour12;

                                $closingOld = old('closing_time', '18:00');
                                [$closingHour24, $closingMinute] = array_pad(explode(':', $closingOld), 2, '00');
                                $closingHour24 = (int) $closingHour24;
                                $closingPeriod = $closingHour24 >= 12 ? 'PM' : 'AM';
                                $closingHour12 = $closingHour24 % 12;
                                $closingHour12 = $closingHour12 === 0 ? 12 : $closingHour12;
                            @endphp

                            <div class="mt-4 section-label">Operating Window (Optional)</div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-600">Opening Time</label>
                                    <div class="time-box">
                                        <input type="number" id="opening_hour" class="form-control form-pill" min="1" max="12" value="{{ $openingHour12 }}" placeholder="HH">
                                        <input type="number" id="opening_minute" class="form-control form-pill" min="0" max="59" value="{{ (int) $openingMinute }}" placeholder="MM">
                                        <select id="opening_period" class="form-select form-pill">
                                            <option value="AM" @selected($openingPeriod === 'AM')>AM</option>
                                            <option value="PM" @selected($openingPeriod === 'PM')>PM</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="opening_time" name="opening_time" value="{{ old('opening_time', '09:00') }}">
                                    @error('opening_time')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-600">Closing Time</label>
                                    <div class="time-box">
                                        <input type="number" id="closing_hour" class="form-control form-pill" min="1" max="12" value="{{ $closingHour12 }}" placeholder="HH">
                                        <input type="number" id="closing_minute" class="form-control form-pill" min="0" max="59" value="{{ (int) $closingMinute }}" placeholder="MM">
                                        <select id="closing_period" class="form-select form-pill">
                                            <option value="AM" @selected($closingPeriod === 'AM')>AM</option>
                                            <option value="PM" @selected($closingPeriod === 'PM')>PM</option>
                                        </select>
                                    </div>
                                    <input type="hidden" id="closing_time" name="closing_time" value="{{ old('closing_time', '18:00') }}">
                                    @error('closing_time')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="service-switch mt-4 mb-4">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" id="isAvailable" @checked(old('is_available', true))>
                                <label class="form-check-label fw-600" for="isAvailable">Service is Available</label>
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-submit btn-lg">
                                    <i class="fas fa-plus-circle me-2"></i>Add Service
                                </button>
                                <a href="{{ route('owner.dashboard') }}" class="btn btn-cancel btn-lg">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .service-page {
        position: relative;
        overflow: hidden;
        background: linear-gradient(180deg, #f2f5f9 0%, #e7edf4 100%);
        border-radius: 20px;
        padding-bottom: 1rem;
    }

    .service-bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(50px);
        z-index: 0;
        opacity: 0.35;
    }

    .orb-1 {
        width: 280px;
        height: 280px;
        background: #b6d9ee;
        top: -70px;
        right: -60px;
    }

    .orb-2 {
        width: 240px;
        height: 240px;
        background: #f2d8b9;
        bottom: 40px;
        left: -90px;
    }

    .service-page .container {
        position: relative;
        z-index: 1;
    }

    .service-hero {
        background: linear-gradient(125deg, #060b12 0%, #0f2237 55%, #173552 100%);
        color: #ffffff;
        border: 1px solid #27425f;
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 14px 30px rgba(6, 11, 18, 0.35);
    }

    .service-kicker {
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .service-title {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
    }

    .service-subtitle {
        opacity: 0.88;
    }

    .btn-soft-back {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.35);
        color: #ffffff;
        border-radius: 12px;
        padding: 0.6rem 1rem;
    }

    .btn-soft-back:hover {
        background: rgba(255, 255, 255, 0.18);
        color: #ffffff;
    }

    .service-card {
        background: #f2f9ff;
        border: 1px solid #bdd9ef;
        border-radius: 16px;
        box-shadow: 0 14px 30px rgba(21, 36, 52, 0.14);
        overflow: hidden;
        transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease;
    }

    .service-card:hover {
        transform: translateY(-4px);
        border-color: #9dc8e7;
        box-shadow: 0 18px 34px rgba(16, 34, 52, 0.2);
    }

    .service-card:active {
        transform: translateY(-1px) scale(0.998);
        box-shadow: 0 12px 24px rgba(16, 34, 52, 0.16);
    }

    .service-card:focus-within {
        border-color: #7fb4da;
        box-shadow: 0 0 0 3px rgba(127, 180, 218, 0.2), 0 18px 34px rgba(16, 34, 52, 0.2);
    }

    .service-card-head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #c8e1f3;
        background: linear-gradient(180deg, #dff1ff 0%, #cfe9fb 100%);
    }

    .service-card-body {
        background: #f2f9ff;
        padding: 1.25rem;
    }

    .section-label {
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #5a6a7b;
        margin-bottom: 0.9rem;
    }

    .fw-600 {
        font-weight: 600;
    }

    .form-pill {
        border-radius: 12px;
        border: 1px solid #d9e1ea;
    }

    .form-control.form-pill,
    .form-select.form-pill,
    .input-group-text.price-chip {
        min-height: 50px;
    }

    .input-group-text.price-chip {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        border: 1px solid #d9e1ea;
        background: #f6f9fc;
        font-weight: 700;
    }

    .time-box {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
    }

    .image-preview {
        width: 100%;
        max-width: 280px;
        border-radius: 14px;
        border: 1px solid #d8e0ea;
        box-shadow: 0 8px 18px rgba(15, 31, 47, 0.12);
    }

    .service-switch {
        background: #f5f9ff;
        border: 1px dashed #b9c8da;
        border-radius: 12px;
        padding: 0.9rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .btn-submit {
        background: linear-gradient(135deg, #1e8f59 0%, #2ca56b 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 0.65rem 1.2rem;
        box-shadow: 0 8px 16px rgba(30, 143, 89, 0.25);
    }

    .btn-submit:hover {
        color: #fff;
        transform: translateY(-1px);
    }

    .btn-cancel {
        background: #fff;
        border: 1px solid #d2dbe7;
        color: #26323f;
        border-radius: 12px;
    }

    @media (max-width: 991px) {
        .service-hero {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
    function to24Hour(hour, minute, period) {
        let h = parseInt(hour, 10);
        let m = parseInt(minute, 10);

        if (Number.isNaN(h) || h < 1 || h > 12) {
            h = 12;
        }
        if (Number.isNaN(m) || m < 0 || m > 59) {
            m = 0;
        }

        if (period === 'AM') {
            if (h === 12) {
                h = 0;
            }
        } else if (h !== 12) {
            h += 12;
        }

        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    }

    const addServiceForm = document.getElementById('addServiceForm');
    addServiceForm.addEventListener('submit', function() {
        const openingTime = to24Hour(
            document.getElementById('opening_hour').value,
            document.getElementById('opening_minute').value,
            document.getElementById('opening_period').value
        );
        const closingTime = to24Hour(
            document.getElementById('closing_hour').value,
            document.getElementById('closing_minute').value,
            document.getElementById('closing_period').value
        );

        document.getElementById('opening_time').value = openingTime;
        document.getElementById('closing_time').value = closingTime;
    });

    document.getElementById('serviceImage').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('imagePreview');
                const container = document.getElementById('imagePreviewContainer');
                preview.src = event.target.result;
                container.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
