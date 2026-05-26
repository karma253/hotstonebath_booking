@extends('web.layouts.app')

@section('title', 'Book ' . $bath->name)

@section('content')

<!-- Sending to Owner Message -->
<div id="sendingMessage" class="alert alert-info alert-dismissible fade" role="alert" style="display: none;">
    <div class="text-center">
        <h4 class="alert-heading mb-2">
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            <i class="fas fa-paper-plane" style="color: #0d6efd; font-size: 2rem;"></i>
        </h4>
        <h5 class="mb-2">Sending Your Booking to Owner...</h5>
        <p class="mb-0">Your booking request is being sent to the bath owner for confirmation.</p>
    </div>
</div>

<!-- Success Message -->
<div id="successMessage" class="alert alert-success alert-dismissible fade" role="alert" style="display: none;">
    <div class="text-center">
        <h4 class="alert-heading mb-2">
            <i class="fas fa-check-circle" style="color: #28a745; font-size: 2rem;"></i>
        </h4>
        <h5 class="mb-2">Booking Confirmed!</h5>
        <p class="mb-0">Your booking has been successfully confirmed. The owner will review and contact you shortly.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-10 mx-auto">
        <div class="card card-shadow rounded-4" style="border: none; overflow: hidden;">
            <div class="card-body p-6" style="
                background: url('{{ isset($bathImages[$bath->name]) ? $bathImages[$bath->name] : (optional($bath->images->where('is_primary', true)->first())->image_path ?: optional($bath->images->first())->image_path ?: 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200') }}') center/cover no-repeat;
                background-attachment: fixed;
                position: relative;
                padding: 3rem !important;
            ">
                <!-- Transparent Overlay -->
                <div style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(255, 255, 255, 0.65);
                    pointer-events: none;
                "></div>

                <!-- Content Wrapper -->
                <div style="position: relative; z-index: 1;">
                    <!-- Bath Details Section -->
                    <div class="mb-5 pb-5" style="border-bottom: 3px solid #f0f0f0;">
                        <h1 class="h2 mb-4 text-center" style="color: #6b2d5c; font-weight: 700; font-size: 2rem; letter-spacing: 1px;">{{ $bath->name }}</h1>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <p style="color: #000000; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Location</p>
                                <p style="color: #000000; font-size: 1rem; font-weight: 700;">{{ optional($bath->dzongkhag)->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p style="color: #000000; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700;">Price Per Person</p>
                                <p style="color: #000000; font-size: 1.3rem; font-weight: 700;">Nu. {{ number_format((float) ($bath->price_per_session ?? $bath->price_per_hour), 2) }}</p>
                            </div>
                        </div>
                        
                        <p class="mt-3 mb-0" style="color: #000000; font-size: 0.9rem; font-weight: 700;">
                            <i class="fas fa-map-pin" style="color: #000000; margin-right: 8px;"></i>
                            <strong>Address:</strong> {{ $bath->full_address }}
                        </p>
                    </div>

                    <!-- Booking Form Section -->
                    <h5 class="mb-4" style="color: #000000; font-size: 1.1rem; font-weight: 700;">Booking Details</h5>
                    
                    <form id="bookingForm" method="POST" action="{{ route('guest.booking.store', $bath) }}" class="row g-3" novalidate>
                        @csrf
                        <div class="col-md-6">
                            <label class="form-label" style="font-weight: 700; color: #000000;">Booking Date</label>
                            <input type="date" name="booking_date" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('booking_date') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 700; color: #000000;">Preferred Time *</label>
                        <select name="start_time" class="form-select" required>
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
                                <option value="{{ $time }}" @selected(old('start_time') == $time)>
                                    {{ $display }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted" style="color: #000000; font-weight: 700;">Available from 6:00 AM to 8:00 PM</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 700; color: #000000;">Number of People</label>
                        <select name="number_of_guests" class="form-select" id="numberOfGuests" onchange="updatePriceSummary()" required>
                            <option value="">Select number of people</option>
                            @for ($i = 1; $i <= 20; $i++)
                                <option value="{{ $i }}" @selected(old('number_of_guests') == $i || (request('guests') && request('guests') == $i))>
                                    {{ $i }} {{ $i === 1 ? 'Person' : 'Persons' }}
                                </option>
                            @endfor
                        </select>
                        <small class="text-muted" style="color: #000000; font-weight: 700;">Maximum: 20 guests allowed</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight: 700; color: #000000;">Payment Method</label>
                        <select name="payment_method" class="form-select" id="paymentMethodSelect" onchange="toggleBankingApps()" required>
                            <option value="">Select payment</option>
                            <option value="digital" @selected(old('payment_method')==='digital')>Digital Payment</option>
                            <option value="cash_on_arrival" @selected(old('payment_method')==='cash_on_arrival')>Cash On Arrival</option>
                        </select>
                    </div>

                    <!-- Banking Apps Selection (shown when Digital Payment is selected) -->
                    <div class="col-12" id="bankingAppsSection" style="display: none;">
                        <label class="form-label" style="font-weight: 700; color: #000000;">Select Banking App</label>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('MBoB', this)">
                                    <div class="bank-logo">🏦</div>
                                    <div class="bank-name">MBoB</div>
                                    <div class="bank-desc">Bhutan National Bank</div>
                                    <input type="hidden" name="banking_app" id="bankingAppInput" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('Bhutan Kura', this)">
                                    <div class="bank-logo">💳</div>
                                    <div class="bank-name">Bhutan Kura</div>
                                    <div class="bank-desc">Mobile Banking</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('Druk Bank', this)">
                                    <div class="bank-logo">🏛️</div>
                                    <div class="bank-name">Druk Bank</div>
                                    <div class="bank-desc">Mobile Banking</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('Tashi Bank', this)">
                                    <div class="bank-logo">🏪</div>
                                    <div class="bank-name">Tashi Bank</div>
                                    <div class="bank-desc">Mobile Banking</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('BDBL', this)">
                                    <div class="bank-logo">💰</div>
                                    <div class="bank-name">BDBL</div>
                                    <div class="bank-desc">Development Bank</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('T-Bank', this)">
                                    <div class="bank-logo">📱</div>
                                    <div class="bank-name">T-Bank</div>
                                    <div class="bank-desc">Digital Banking</div>
                                </div>
                            </div>
                        </div>
                        <small class="text-danger" id="bankingAppError" style="display: none; font-weight: 700;">Please select a banking app</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label" style="font-weight: 700; color: #000000;">Special Requests (Optional)</label>
                        <textarea name="special_requests" class="form-control" rows="3">{{ old('special_requests') }}</textarea>
                    </div>

                    <!-- Price Summary -->
                    <div class="col-12 pt-3" id="totalPriceSection" style="display: none; border-top: 3px solid #f0f0f0; padding-top: 1.5rem !important;">
                        <div class="row">
                            <div class="col-md-6">
                                <p style="color: #000000; font-size: 0.85rem; text-transform: uppercase; font-weight: 700;">Number of Guests</p>
                                <h5 id="guestCount" style="color: #000000; font-weight: 700;">-</h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <p style="color: #000000; font-size: 0.85rem; text-transform: uppercase; font-weight: 700;">Total Price</p>
                                <h3 style="color: #000000; font-weight: 700;">Nu. <span id="totalPrice">0.00</span></h3>
                                <small style="color: #000000; font-size: 0.85rem; font-weight: 700;" id="priceBreakdown"></small>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-grid pt-3">
                        <button type="button" class="btn btn-dark btn-lg" id="confirmBookingBtn" onclick="handleBookingSubmit()" style="padding: 0.8rem 1.5rem; font-weight: 600; font-size: 1rem;">
                            <i class="fas fa-check me-2"></i> Confirm Booking
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TPIN Modal for Digital Payment -->
<div class="modal fade" id="tpinModal" tabindex="-1" aria-labelledby="tpinModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tpinModalLabel">Enter Transaction PIN</h5>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Enter your 4-digit TPIN to confirm the booking payment.</p>
                
                <div class="mb-3">
                    <label for="tpinInput" class="form-label">4-Digit TPIN</label>
                    <input type="password" class="form-control form-control-lg text-center" id="tpinInput" 
                           maxlength="4" inputmode="numeric" placeholder="••••" required>
                    <small class="text-muted d-block mt-2">Default PIN for testing: 1234</small>
                </div>

                <div id="tpinError" class="alert alert-danger d-none" role="alert"></div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" onclick="validateAndSubmitBooking()">
                        <span id="submitBtnText">Confirm Payment</span>
                        <span id="submitBtnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bank-option-card {
        padding: 15px;
        border: 2px solid #ddd;
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .bank-option-card:hover {
        border-color: #0d6efd;
        background-color: #f0f3ff;
        transform: translateY(-2px);
    }

    .bank-option-card.selected {
        border-color: #0d6efd;
        background-color: #0d6efd;
        color: white;
    }

    .bank-option-card.selected .bank-logo,
    .bank-option-card.selected .bank-name,
    .bank-option-card.selected .bank-desc {
        color: white;
    }

    .bank-logo {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .bank-name {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
        color: #333;
    }

    .bank-desc {
        font-size: 12px;
        color: #999;
    }
</style>

<script>
    let selectedBank = null;
    let bookingForm = null;
    let tpinValidated = false;
    const CORRECT_PIN = '1234'; // Default PIN for testing

    function toggleBankingApps() {
        const paymentMethod = document.getElementById('paymentMethodSelect').value;
        const bankingAppsSection = document.getElementById('bankingAppsSection');
        const bankingAppInput = document.getElementById('bankingAppInput');
        
        if (paymentMethod === 'digital') {
            bankingAppsSection.style.display = 'block';
            // Reset selection when switching to digital
            document.querySelectorAll('.bank-option-card').forEach(card => {
                card.classList.remove('selected');
            });
            selectedBank = null;
            bankingAppInput.value = '';
        } else {
            bankingAppsSection.style.display = 'none';
            document.getElementById('bankingAppError').style.display = 'none';
            selectedBank = null;
            bankingAppInput.value = '';
        }
    }

    function selectBankingApp(app, element) {
        // Remove selected class from all cards
        document.querySelectorAll('.bank-option-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selected class to clicked card
        element.classList.add('selected');
        selectedBank = app;
        
        // Set the hidden input value
        document.getElementById('bankingAppInput').value = app;
        
        // Hide error message
        document.getElementById('bankingAppError').style.display = 'none';
    }

    // Update price summary when number of guests changes
    function updatePriceSummary() {
        const numberOfGuests = document.getElementById('numberOfGuests').value;
        const pricePerPerson = {{ $bath->price_per_session ?? $bath->price_per_hour }};
        const totalPriceSection = document.getElementById('totalPriceSection');
        const totalPriceSpan = document.getElementById('totalPrice');
        const priceBreakdown = document.getElementById('priceBreakdown');
        const guestCountSpan = document.getElementById('guestCount');
        
        if (numberOfGuests) {
            const totalPrice = numberOfGuests * pricePerPerson;
            totalPriceSpan.textContent = totalPrice.toFixed(2);
            priceBreakdown.textContent = numberOfGuests + ' × Nu. ' + pricePerPerson.toFixed(2) + ' = Nu. ' + totalPrice.toFixed(2);
            guestCountSpan.textContent = numberOfGuests + ' ' + (numberOfGuests === '1' ? 'Guest' : 'Guests');
            totalPriceSection.style.display = 'block';
        } else {
            totalPriceSection.style.display = 'none';
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        bookingForm = document.getElementById('bookingForm');
        
        // Only allow numeric input in TPIN field
        document.getElementById('tpinInput').addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Initialize on page load - show banking apps if digital is pre-selected
        if (document.getElementById('paymentMethodSelect').value === 'digital') {
            document.getElementById('bankingAppsSection').style.display = 'block';
        }

        // Initialize price summary if number of guests is pre-selected
        updatePriceSummary();
    });

    // Main button click handler - replaces form submit
    function handleBookingSubmit() {
        console.log('handleBookingSubmit called');
        
        // Disable button to prevent double submissions
        const submitBtn = document.querySelector('[onclick="handleBookingSubmit()"]');
        if (submitBtn) {
            submitBtn.disabled = true;
        }
        
        // Validate all required fields first
        const bookingDate = document.querySelector('input[name="booking_date"]').value;
        const startTime = document.querySelector('input[name="start_time"]').value;
        const numberOfGuests = document.querySelector('select[name="number_of_guests"]').value;
        const paymentMethod = document.getElementById('paymentMethodSelect').value;
        
        // Check required fields
        if (!bookingDate) {
            alert('Please select a booking date');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }
        if (!startTime) {
            alert('Please select a time slot');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }
        if (!numberOfGuests) {
            alert('Please select number of people');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }
        if (!paymentMethod) {
            alert('Please select a payment method');
            if (submitBtn) submitBtn.disabled = false;
            return;
        }
        
        // Validate banking app selection if digital payment
        if (paymentMethod === 'digital') {
            if (!selectedBank) {
                console.log('No banking app selected');
                document.getElementById('bankingAppError').style.display = 'block';
                document.getElementById('bankingAppsSection').scrollIntoView({ behavior: 'smooth' });
                if (submitBtn) submitBtn.disabled = false;
                return;
            }
            
            console.log('Showing TPIN modal for digital payment');
            if (submitBtn) submitBtn.disabled = false;
            showTpinModal();
        } else if (paymentMethod === 'cash_on_arrival') {
            // Cash payment - submit via AJAX
            console.log('Cash payment selected, submitting form via AJAX');
            submitFormViaAjax();
        } else {
            alert('Invalid payment method');
            if (submitBtn) submitBtn.disabled = false;
        }
    }

    function showTpinModal() {
        // Clear previous TPIN input
        document.getElementById('tpinInput').value = '';
        document.getElementById('tpinError').classList.add('d-none');
        
        // Reset button state
        const submitBtn = document.querySelector('[onclick="validateAndSubmitBooking()"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            document.getElementById('submitBtnText').classList.remove('d-none');
            document.getElementById('submitBtnSpinner').classList.add('d-none');
        }
        
        // Show the modal
        const tpinModal = new bootstrap.Modal(document.getElementById('tpinModal'));
        tpinModal.show();
        
        // Focus on TPIN input
        setTimeout(() => {
            document.getElementById('tpinInput').focus();
        }, 500);
    }

    function validateAndSubmitBooking() {
        const tpin = document.getElementById('tpinInput').value;
        const errorDiv = document.getElementById('tpinError');
        
        console.log('TPIN validation called');
        console.log('TPIN input value:', tpin);
        console.log('TPIN length:', tpin.length);
        
        // Validate TPIN length
        if (tpin.length !== 4) {
            errorDiv.textContent = 'TPIN must be 4 digits';
            errorDiv.classList.remove('d-none');
            return false;
        }
        
        // Validate TPIN value
        if (tpin !== CORRECT_PIN) {
            errorDiv.textContent = 'Incorrect TPIN. Please try again.';
            errorDiv.classList.remove('d-none');
            document.getElementById('tpinInput').value = '';
            document.getElementById('tpinInput').focus();
            return false;
        }
        
        // TPIN is correct, proceed with booking
        console.log('TPIN validated successfully!');
        errorDiv.classList.add('d-none');
        
        // Show loading state
        const submitBtn = document.querySelector('[onclick="validateAndSubmitBooking()"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            document.getElementById('submitBtnText').classList.add('d-none');
            document.getElementById('submitBtnSpinner').classList.remove('d-none');
        }
        
        // Close modal and submit form
        setTimeout(() => {
            try {
                const modal = bootstrap.Modal.getInstance(document.getElementById('tpinModal'));
                if (modal) {
                    modal.hide();
                }
            } catch (error) {
                console.error('Error closing modal:', error);
            }
            
            // Submit the form via AJAX
            console.log('Submitting form via AJAX after TPIN validation');
            submitFormViaAjax();
        }, 500);
        
        return false;
    }

    // Function to submit form via AJAX
    function submitFormViaAjax() {
        const formData = new FormData(bookingForm);
        
        console.log('Submitting booking form via AJAX');
        console.log('Booking date:', formData.get('booking_date'));
        console.log('Start time:', formData.get('start_time'));
        console.log('Number of guests:', formData.get('number_of_guests'));
        console.log('Payment method:', formData.get('payment_method'));
        console.log('Banking app:', formData.get('banking_app'));
        
        // Show sending message immediately
        const sendingDiv = document.getElementById('sendingMessage');
        sendingDiv.style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        fetch(bookingForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                return response.json().then(data => Promise.reject(data));
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                // Wait 2 seconds before showing success message
                setTimeout(() => {
                    // Hide sending message and show success message
                    sendingDiv.style.display = 'none';
                    const successDiv = document.getElementById('successMessage');
                    successDiv.style.display = 'block';
                    
                    // Reset the form
                    bookingForm.reset();
                    
                    // Reset UI elements
                    document.getElementById('paymentMethodSelect').value = '';
                    document.getElementById('bankingAppsSection').style.display = 'none';
                    document.querySelectorAll('.bank-option-card').forEach(card => {
                        card.classList.remove('selected');
                    });
                    selectedBank = null;
                    document.getElementById('totalPriceSection').style.display = 'none';
                    
                    // Re-enable button
                    const submitBtn = document.querySelector('[onclick="handleBookingSubmit()"]');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                }, 2000); // 2 second delay for better UX
            } else {
                sendingDiv.style.display = 'none';
                alert(data.error || 'There was an error processing your booking. Please try again.');
                
                // Re-enable button
                const submitBtn = document.querySelector('[onclick="handleBookingSubmit()"]');
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            sendingDiv.style.display = 'none';
            const errorMessage = error.error || error.message || 'An error occurred. Please try again.';
            alert(errorMessage);
            
            // Re-enable button
            const submitBtn = document.querySelector('[onclick="handleBookingSubmit()"]');
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        });
    }
    }

    // Allow Enter key to submit TPIN
    document.addEventListener('keypress', function(e) {
        const tpinInput = document.getElementById('tpinInput');
        if (document.activeElement === tpinInput && e.key === 'Enter') {
            e.preventDefault();
            validateAndSubmitBooking();
        }
    });
</script>
@endsection
