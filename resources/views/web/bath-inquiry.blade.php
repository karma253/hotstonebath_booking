<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Inquiry - {{ $bath->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', sans-serif;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #2c3e50 !important;
            font-size: 1.3rem;
        }
        
        .main-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 3rem 1rem;
        }
        
        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 2rem;
            text-align: left;
        }
        
        .info-section {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .info-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .info-title i {
            color: #7f8c8d;
            font-size: 1.4rem;
        }
        
        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .info-list li {
            padding: 0.5rem 0;
            color: #555;
            border-bottom: 1px solid #eee;
        }
        
        .info-list li:last-child {
            border-bottom: none;
        }
        
        .info-list strong {
            color: #2c3e50;
            font-weight: 600;
        }
        
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        
        .form-section p {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            background-color: white;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #e67e22;
            box-shadow: 0 0 0 0.2rem rgba(230, 126, 34, 0.15);
            color: #2c3e50;
        }
        
        .form-control::placeholder {
            color: #bbb;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-row {
            margin-bottom: 1.5rem;
        }
        
        .form-row.two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        
        .form-row.three-col {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
        }
        
        .price-display {
            background: #f8f9fa;
            border-left: 4px solid #e67e22;
            padding: 1rem 1.5rem;
            border-radius: 6px;
            margin: 1.5rem 0;
        }
        
        .price-display small {
            color: #7f8c8d;
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .price-display strong {
            color: #e67e22;
            font-size: 1.3rem;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            border: none;
            color: white;
            padding: 1rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            border-radius: 6px;
            width: 100%;
            margin-top: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(230, 126, 34, 0.3);
        }
        
        .submit-btn:hover {
            background: linear-gradient(135deg, #d35400 0%, #ba4a00 100%);
            box-shadow: 0 6px 16px rgba(230, 126, 34, 0.4);
            transform: translateY(-2px);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .back-btn {
            background: white;
            border: 1px solid #ddd;
            color: #2c3e50;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: #f8f9fa;
            border-color: #bbb;
            color: #2c3e50;
        }
        
        .invalid-feedback {
            display: block;
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        .is-invalid {
            border-color: #e74c3c !important;
        }
        
        @media (max-width: 768px) {
            .page-title {
                font-size: 1.8rem;
                margin-bottom: 1.5rem;
            }
            
            .form-row.two-col,
            .form-row.three-col {
                grid-template-columns: 1fr;
            }
            
            .form-section {
                padding: 1.5rem;
            }
            
            .info-section {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <i class="fas fa-spa"></i> Hot Stone Bath Booking
        </a>
    </div>
</nav>

<div class="main-container">
    <h1 class="page-title">Book Your {{ $bath->name }}</h1>

    <!-- Booking Information Section -->
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-info-circle"></i>
            Booking Information
        </div>
        <ul class="info-list">
            <li><strong>Advance booking required:</strong> 2 days advance booking required</li>
            <li><strong>Working hours:</strong> 
                @if ($bath->availabilities && $bath->availabilities->count() > 0)
                    @php
                        $firstSlot = $bath->availabilities->first();
                        $openTime = \Carbon\Carbon::parse($firstSlot->opening_time)->format('h:i A');
                        $closeTime = \Carbon\Carbon::parse($firstSlot->closing_time)->format('h:i A');
                    @endphp
                    {{ $openTime }} to {{ $closeTime }}
                @else
                    Available as per schedule
                @endif
            </li>
            <li><strong>Max capacity:</strong> {{ $bath->max_guests }} guests per session</li>
            <li><strong>Price:</strong> Nu. {{ number_format((float) ($bath->price_per_session ?? $bath->price_per_hour), 2) }} per person</li>
        </ul>
    </div>

    <!-- Booking Form Section -->
    <div class="form-section">
        <p>Fill out the form to request a booking. We'll confirm availability by phone or email.</p>

        <form method="POST" action="{{ route('guest.inquiry.store', $bath) }}" novalidate>
            @csrf

            <!-- Full Name -->
            <div class="form-row">
                <div>
                    <label class="form-label">Full Name <span style="color: #e74c3c;">*</span></label>
                    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" 
                           placeholder="Your full name" value="{{ old('full_name') }}" required>
                    @error('full_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email & Phone -->
            <div class="form-row two-col">
                <div>
                    <label class="form-label">Email <span style="color: #e74c3c;">*</span></label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           placeholder="your@email.com" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Phone Number <span style="color: #e74c3c;">*</span></label>
                    <input type="tel" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" 
                           placeholder="Your phone" value="{{ old('phone_number') }}" required>
                    @error('phone_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Date, Time & Guests -->
            <div class="form-row three-col">
                <div>
                    <label class="form-label">Preferred Date <span style="color: #e74c3c;">*</span></label>
                    <input type="date" name="preferred_date" class="form-control @error('preferred_date') is-invalid @enderror" 
                           min="{{ now()->addDays(2)->toDateString() }}" value="{{ old('preferred_date') }}" required>
                    <small style="color: #7f8c8d;">Minimum 2 days advance booking</small>
                    @error('preferred_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Preferred Time <span style="color: #e74c3c;">*</span></label>
                    <select name="preferred_time" class="form-select @error('preferred_time') is-invalid @enderror" required>
                        <option value="">Select preferred time</option>
                        @php
                            $timeSlots = [];
                            
                            // Generate time slots from 6 AM to 8 PM in hourly increments
                            for ($hour = 6; $hour < 20; $hour++) {
                                $time = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                                $display = \Carbon\Carbon::createFromFormat('H:i', $time)->format('h:i A');
                                $timeSlots[$time] = $display;
                            }
                        @endphp
                        
                        @foreach ($timeSlots as $time => $display)
                            <option value="{{ $time }}" @selected(old('preferred_time') == $time)>
                                {{ $display }}
                            </option>
                        @endforeach
                    </select>
                    @error('preferred_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Number of Guests <span style="color: #e74c3c;">*</span></label>
                    <select name="number_of_persons" class="form-select @error('number_of_persons') is-invalid @enderror" 
                            id="numberOfPersons" onchange="updateGroupPrice()" required>
                        <option value="">Select number of guests</option>
                        @for ($i = 1; $i <= 20; $i++)
                            <option value="{{ $i }}" @selected(old('number_of_persons') == $i)>
                                {{ $i }} {{ $i === 1 ? 'Guest' : 'Guests' }}
                            </option>
                        @endfor
                    </select>
                    @error('number_of_persons')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Price Display -->
            <div class="price-display" id="priceDisplay" style="display: none;">
                <small>Total Estimated Price</small>
                <strong>Nu. <span id="totalEstimate">0.00</span></strong>
            </div>

            <!-- Special Requests -->
            <div class="form-row">
                <div>
                    <label class="form-label">Special Requests or Message</label>
                    <textarea name="special_requests" class="form-control @error('special_requests') is-invalid @enderror" 
                              placeholder="Any special requests, allergies, or health concerns...">{{ old('special_requests') }}</textarea>
                    @error('special_requests')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="submit-btn">
                <i class="fas fa-check me-2"></i> SUBMIT BOOKING REQUEST
            </button>

            <a href="{{ route('baths.show', $bath) }}" class="back-btn">
                <i class="fas fa-arrow-left me-2"></i> Back to Bath Details
            </a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateGroupPrice() {
        const numberOfPersons = document.getElementById('numberOfPersons').value;
        const pricePerPerson = {{ $bath->price_per_session ?? $bath->price_per_hour }};
        const priceDisplay = document.getElementById('priceDisplay');
        const totalEstimate = document.getElementById('totalEstimate');

        if (numberOfPersons) {
            const total = numberOfPersons * pricePerPerson;
            totalEstimate.textContent = total.toFixed(2);
            priceDisplay.style.display = 'block';
        } else {
            priceDisplay.style.display = 'none';
        }
    }
</script>

</body>
</html>
