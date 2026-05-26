@extends('web.layouts.app')

@section('title', 'Register as Bath Owner')

@push('styles')
<style>
    body {
        background-color: #f9f9f9;
    }

    .register-wrapper {
        max-width: 700px;
        margin: 40px auto;
        padding: 30px;
        background-color: #e8f4f8;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .register-wrapper:hover {
        background-color: #d4ecf5;
        box-shadow: 0 8px 20px rgba(77, 166, 214, 0.25);
        transform: translateY(-2px);
    }

    .register-wrapper:active {
        background-color: #b8e0ee;
        box-shadow: 0 4px 12px rgba(77, 166, 214, 0.4);
        transform: translateY(0);
    }

    h1 {
        font-size: 28px;
        margin-bottom: 10px;
        color: #333;
    }

    .intro-text {
        color: #666;
        margin-bottom: 30px;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        font-size: 14px;
        color: #333;
    }

    .required {
        color: red;
        margin-left: 2px;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="number"],
    input[type="time"],
    input[type="password"],
    input[type="file"],
    select,
    textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
        transition: all 0.3s ease;
    }

    input[type="text"]:hover,
    input[type="email"]:hover,
    input[type="tel"]:hover,
    input[type="number"]:hover,
    input[type="time"]:hover,
    input[type="password"]:hover,
    input[type="file"]:hover,
    select:hover,
    textarea:hover {
        border-color: #4da6d6;
        background-color: #f0f8fb;
        box-shadow: 0 0 5px rgba(77, 166, 214, 0.3);
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus,
    input[type="number"]:focus,
    input[type="time"]:focus,
    input[type="password"]:focus,
    input[type="file"]:focus,
    select:focus,
    textarea:focus {
        outline: none;
        border-color: #2980b9;
        background-color: #ffffff;
        box-shadow: 0 0 8px rgba(77, 166, 214, 0.5);
    }

    .error-message {
        color: #dc3545;
        font-size: 12px;
        margin-top: 3px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-top: 30px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #ddd;
        color: #333;
    }

    .section-title:first-of-type {
        margin-top: 0;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-row.full {
        grid-template-columns: 1fr;
    }

    .btn-register {
        width: 100%;
        padding: 10px;
        background-color: #333;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        margin-top: 30px;
        transition: all 0.3s ease;
    }

    .btn-register:hover {
        background-color: #555;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    .btn-register:active {
        transform: translateY(0);
    }

    .login-link {
        text-align: center;
        margin-top: 15px;
        font-size: 14px;
    }

    .login-link a {
        color: #0066cc;
        text-decoration: none;
    }

    .login-link a:hover {
        text-decoration: underline;
    }

    .help-text {
        font-size: 12px;
        color: #666;
        margin-top: 3px;
    }

    .facilities-checkboxes {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 10px;
        padding: 10px;
        background-color: #ffffff;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        font-size: 14px;
        color: #333;
        cursor: pointer;
        margin: 0;
    }

    .checkbox-item input[type="checkbox"] {
        width: auto;
        margin-right: 8px;
        cursor: pointer;
        accent-color: #4da6d6;
    }

    .checkbox-item input[type="checkbox"]:hover {
        transform: scale(1.1);
    }

    @media (max-width: 600px) {
        .register-wrapper {
            margin: 20px auto;
            padding: 15px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        h1 {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="register-wrapper">
    <h1>Register as Bath Owner</h1>
    <p class="intro-text">Fill out the form below to register your bath facility. All fields marked with * are required.</p>

    <form method="POST" action="{{ route('owner.register.submit') }}" enctype="multipart/form-data" novalidate>
        @csrf

        <!-- Personal Information Section -->
        <div class="section-title">Personal Information</div>

        <div class="form-row">
            <div class="form-group">
                <label for="owner_name">Full Name <span class="required">*</span></label>
                <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" required>
                @error('owner_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Contact Number <span class="required">*</span></label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="email">Email Address <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Bath Information Section -->
        <div class="section-title">Bath Information</div>

        <div class="form-row">
            <div class="form-group">
                <label for="bath_type">Bath Type <span class="required">*</span></label>
                <select id="bath_type" name="bath_type" required>
                    <option value="">-- Select Bath Type --</option>
                    <option value="menchu" @selected(old('bath_type') === 'menchu')>Menchu (Herbal Bath)</option>
                    <option value="dotsho" @selected(old('bath_type') === 'dotsho')>Dotsho (Hot Stone Bath)</option>
                    <option value="tshachu" @selected(old('bath_type') === 'tshachu')>Tshachu (Hot Spring)</option>
                </select>
                @error('bath_type')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="dzongkhag_id">Dzongkhag <span class="required">*</span></label>
                <select id="dzongkhag_id" name="dzongkhag_id" required>
                    <option value="">-- Select Dzongkhag --</option>
                    @foreach($dzongkhags as $dzongkhag)
                        <option value="{{ $dzongkhag->id }}" @selected((string)old('dzongkhag_id')===(string)$dzongkhag->id)>
                            {{ $dzongkhag->name }}
                        </option>
                    @endforeach
                </select>
                @error('dzongkhag_id')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group form-row full">
            <div>
                <label for="address">Location <span class="required">*</span></label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required>
                @error('address')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group form-row full">
            <div>
                <label for="description">Description <span class="required">*</span></label>
                <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                <p class="help-text">Tell us about your bath facility - features, atmosphere, etc.</p>
                @error('description')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group form-row full">
            <div>
                <label>Facilities <span class="required">*</span></label>
                <div class="facilities-checkboxes">
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Changing room" @if(is_array(old('facilities')) && in_array('Changing room', old('facilities'))) checked @endif>
                        Changing room
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Towels" @if(is_array(old('facilities')) && in_array('Towels', old('facilities'))) checked @endif>
                        Towels
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Herbal bath" @if(is_array(old('facilities')) && in_array('Herbal bath', old('facilities'))) checked @endif>
                        Herbal bath
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Hot shower" @if(is_array(old('facilities')) && in_array('Hot shower', old('facilities'))) checked @endif>
                        Hot shower
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Waiting area" @if(is_array(old('facilities')) && in_array('Waiting area', old('facilities'))) checked @endif>
                        Waiting area
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Parking" @if(is_array(old('facilities')) && in_array('Parking', old('facilities'))) checked @endif>
                        Parking
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="WiFi" @if(is_array(old('facilities')) && in_array('WiFi', old('facilities'))) checked @endif>
                        WiFi
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="facilities[]" value="Massage services" @if(is_array(old('facilities')) && in_array('Massage services', old('facilities'))) checked @endif>
                        Massage services
                    </label>
                </div>
                @error('facilities')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Operating Hours Section -->
        <div class="section-title">Operating Hours</div>

        <div class="form-row">
            <div class="form-group">
                <label for="opening_time_hour">Opening Time <span class="required">*</span></label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <input type="number" id="opening_time_hour" name="opening_time_hour" min="1" max="12" value="{{ old('opening_time_hour', '09') }}" placeholder="HH" required style="text-align: center;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="number" id="opening_time_minute" name="opening_time_minute" min="0" max="59" value="{{ old('opening_time_minute', '00') }}" placeholder="MM" required style="text-align: center;">
                        <select id="opening_time_period" name="opening_time_period" required style="flex: 1;">
                            <option value="">Period</option>
                            <option value="AM" @selected(old('opening_time_period', 'AM') === 'AM')>AM</option>
                            <option value="PM" @selected(old('opening_time_period', 'PM') === 'PM')>PM</option>
                        </select>
                    </div>
                </div>
                @error('opening_time')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="closing_time_hour">Closing Time <span class="required">*</span></label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <input type="number" id="closing_time_hour" name="closing_time_hour" min="1" max="12" value="{{ old('closing_time_hour', '06') }}" placeholder="HH" required style="text-align: center;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <input type="number" id="closing_time_minute" name="closing_time_minute" min="0" max="59" value="{{ old('closing_time_minute', '00') }}" placeholder="MM" required style="text-align: center;">
                        <select id="closing_time_period" name="closing_time_period" required style="flex: 1;">
                            <option value="">Period</option>
                            <option value="AM" @selected(old('closing_time_period') === 'AM')>AM</option>
                            <option value="PM" @selected(old('closing_time_period', 'PM') === 'PM')>PM</option>
                        </select>
                    </div>
                </div>
                @error('closing_time')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Hidden time inputs for form submission -->
        <input type="hidden" id="opening_time" name="opening_time">
        <input type="hidden" id="closing_time" name="closing_time">

        <!-- Pricing & Capacity Section -->
        <div class="section-title">Pricing & Capacity</div>

        <div class="form-row">
            <div class="form-group">
                <label for="price_per_session">Price per Session (Nu.) <span class="required">*</span></label>
                <input type="number" id="price_per_session" name="price_per_session" value="{{ old('price_per_session') }}" min="0" step="0.01" required>
                @error('price_per_session')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="max_guests">Maximum Guests <span class="required">*</span></label>
                <input type="number" id="max_guests" name="max_guests" value="{{ old('max_guests', 4) }}" min="1" required>
                @error('max_guests')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Password Section -->
        <div class="section-title">Password</div>

        <div class="form-row">
            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" required>
                <p class="help-text">Minimum 8 characters</p>
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password <span class="required">*</span></label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                @error('password_confirmation')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-register">Register My Bath</button>

        <div class="login-link">
            Already registered? <a href="{{ route('owner.login') }}">Login here</a>
        </div>
    </form>
</div>

<script>
    // Convert 12-hour to 24-hour format
    function convertTo24Hour(hour, minute, period) {
        hour = parseInt(hour);
        minute = parseInt(minute);
        
        if (period === 'AM') {
            if (hour === 12) hour = 0;
        } else if (period === 'PM') {
            if (hour !== 12) hour += 12;
        }
        
        return `${String(hour).padStart(2, '0')}:${String(minute).padStart(2, '0')}`;
    }

    // Handle form submission
    document.querySelector('form').addEventListener('submit', function(e) {
        const openingHour = document.getElementById('opening_time_hour').value;
        const openingMinute = document.getElementById('opening_time_minute').value;
        const openingPeriod = document.getElementById('opening_time_period').value;
        
        const closingHour = document.getElementById('closing_time_hour').value;
        const closingMinute = document.getElementById('closing_time_minute').value;
        const closingPeriod = document.getElementById('closing_time_period').value;

        // Validate that all fields are filled
        if (!openingHour || !openingMinute || !openingPeriod) {
            e.preventDefault();
            alert('Please fill in all opening time fields (hour, minute, AM/PM)');
            return;
        }

        if (!closingHour || !closingMinute || !closingPeriod) {
            e.preventDefault();
            alert('Please fill in all closing time fields (hour, minute, AM/PM)');
            return;
        }

        // Validate hour and minute ranges
        if (openingHour < 1 || openingHour > 12 || openingMinute < 0 || openingMinute > 59) {
            e.preventDefault();
            alert('Please enter valid opening time (hour: 1-12, minute: 0-59)');
            return;
        }

        if (closingHour < 1 || closingHour > 12 || closingMinute < 0 || closingMinute > 59) {
            e.preventDefault();
            alert('Please enter valid closing time (hour: 1-12, minute: 0-59)');
            return;
        }

        // Convert to 24-hour format
        const opening24 = convertTo24Hour(openingHour, openingMinute, openingPeriod);
        const closing24 = convertTo24Hour(closingHour, closingMinute, closingPeriod);

        // Set hidden input values
        document.getElementById('opening_time').value = opening24;
        document.getElementById('closing_time').value = closing24;
    });
</script>
@endsection
