@extends('web.layouts.app')

@section('title', 'Book ' . $bath->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4">
                <h1 class="h3 mb-3">Book Now</h1>
                <p class="text-muted">Select your date, time slot, and number of people.</p>

                <form id="bookingForm" method="POST" action="{{ route('guest.booking.store', $bath) }}" class="row g-3" novalidate>
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Booking Date</label>
                        <input type="date" name="booking_date" class="form-control" min="{{ now()->toDateString() }}" value="{{ old('booking_date') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Time Slot</label>
                        <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Number of People</label>
                        <input type="number" name="number_of_guests" class="form-control" min="1" max="{{ $bath->max_guests }}" value="{{ old('number_of_guests', 1) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Payment Method</label>
                        <select name="payment_method" class="form-select" id="paymentMethodSelect" onchange="toggleBankingApps()" required>
                            <option value="">Select payment</option>
                            <option value="digital" @selected(old('payment_method')==='digital')>Digital Payment</option>
                            <option value="cash_on_arrival" @selected(old('payment_method')==='cash_on_arrival')>Cash On Arrival</option>
                        </select>
                    </div>

                    <!-- Banking Apps Selection (shown when Digital Payment is selected) -->
                    <div class="col-12" id="bankingAppsSection" style="display: none;">
                        <label class="form-label">Select Banking App</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('MBoB', this)">
                                    <div class="bank-logo">🏦</div>
                                    <div class="bank-name">MBoB</div>
                                    <div class="bank-desc">Mobile Banking</div>
                                    <input type="hidden" name="banking_app" id="bankingAppInput" value="">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('MPay', this)">
                                    <div class="bank-logo">💳</div>
                                    <div class="bank-name">MPay</div>
                                    <div class="bank-desc">Digital Payment</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bank-option-card" onclick="selectBankingApp('BDBL', this)">
                                    <div class="bank-logo">🏛️</div>
                                    <div class="bank-name">BDBL</div>
                                    <div class="bank-desc">Development Bank</div>
                                </div>
                            </div>
                        </div>
                        <small class="text-danger" id="bankingAppError" style="display: none;">Please select a banking app</small>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Special Requests (Optional)</label>
                        <textarea name="special_requests" class="form-control" rows="3">{{ old('special_requests') }}</textarea>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="button" class="btn btn-dark" id="confirmBookingBtn" onclick="handleBookingSubmit()">Confirm Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4">
                <h2 class="h5 mb-3">Booking Summary</h2>
                <p class="mb-1"><strong>Bath:</strong> {{ $bath->name }}</p>
                <p class="mb-1"><strong>Location:</strong> {{ optional($bath->dzongkhag)->name }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $bath->full_address }}</p>
                <p class="mb-1"><strong>Price per person/session:</strong> Nu. {{ number_format((float) ($bath->price_per_session ?? $bath->price_per_hour), 2) }}</p>
                <p class="mb-0"><strong>Maximum guests:</strong> {{ $bath->max_guests }}</p>
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
    });

    // Main button click handler - replaces form submit
    function handleBookingSubmit() {
        console.log('handleBookingSubmit called');
        
        const paymentMethod = document.getElementById('paymentMethodSelect').value;
        
        // Validate banking app selection if digital payment
        if (paymentMethod === 'digital') {
            if (!selectedBank) {
                console.log('No banking app selected');
                document.getElementById('bankingAppError').style.display = 'block';
                document.getElementById('bankingAppsSection').scrollIntoView({ behavior: 'smooth' });
                return;
            }
            
            console.log('Showing TPIN modal for digital payment');
            showTpinModal();
        } else {
            // Cash payment - submit directly
            console.log('Cash payment selected, submitting form directly');
            bookingForm.submit();
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
            
            // Submit the form directly
            console.log('Submitting form after TPIN validation');
            if (bookingForm) {
                bookingForm.submit();
            }
        }, 500);
        
        return false;
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
