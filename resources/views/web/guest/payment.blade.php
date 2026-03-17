@extends('web.layouts.app')

@section('title', 'Payment - ' . $booking->booking_id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-shadow rounded-4">
            <div class="card-body p-4 p-lg-5">
                <h1 class="h3 mb-4">Complete Your Payment</h1>
                
                <!-- Booking Summary -->
                <div class="border-bottom pb-4 mb-4">
                    <h5 class="mb-3">Booking Details</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Booking ID</small>
                            <strong>{{ $booking->booking_id }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Bath</small>
                            <strong>{{ optional($booking->bath)->name }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Date & Time</small>
                            <strong>{{ optional($booking->booking_date)->format('d M Y') }} | {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('h:i A') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Guests</small>
                            <strong>{{ $booking->number_of_guests }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Service</small>
                            <strong>{{ optional($booking->service)->service_type }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Total Amount</small>
                            <strong class="text-primary fs-5">Nu. {{ number_format((float) $booking->total_price, 2) }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Payment Options -->
                <div class="mb-4">
                    <h5 class="mb-3">Select Payment Method</h5>
                    
                    <!-- Digital Payment Option -->
                    <div class="card mb-3 payment-option-card" onclick="selectPaymentMethod('digital')">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1">💳 Digital Payment</h6>
                                <small class="text-muted">Pay using banking apps (MBoB, MPay, BDBL)</small>
                            </div>
                            <input type="radio" name="payment_method" value="digital" class="form-check-input" style="width: 24px; height: 24px;">
                        </div>
                    </div>

                    <!-- Cash Payment Option -->
                    <div class="card mb-3 payment-option-card" onclick="selectPaymentMethod('cash')">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1">💵 Cash Payment</h6>
                                <small class="text-muted">Pay at the bath facility when you arrive</small>
                            </div>
                            <input type="radio" name="payment_method" value="cash" class="form-check-input" style="width: 24px; height: 24px;">
                        </div>
                    </div>
                </div>

                <!-- Digital Payment Details (Hidden initially) -->
                <div id="digitalPaymentDetails" class="mb-4" style="display: none;">
                    <h5 class="mb-3">Select Your Bank</h5>
                    <div class="row g-3">
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-primary w-100 bank-btn" data-bank="MBoB">
                                <div style="font-size: 32px;">🏦</div>
                                <div class="small mt-2">MBoB</div>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-primary w-100 bank-btn" data-bank="MPay">
                                <div style="font-size: 32px;">💳</div>
                                <div class="small mt-2">MPay</div>
                            </button>
                        </div>
                        <div class="col-4">
                            <button type="button" class="btn btn-outline-primary w-100 bank-btn" data-bank="BDBL">
                                <div style="font-size: 32px;">🏛️</div>
                                <div class="small mt-2">BDBL</div>
                            </button>
                        </div>
                    </div>

                    <!-- PIN Entry (Hidden initially) -->
                    <div id="pinEntrySection" class="mt-4" style="display: none;">
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Secure Payment</strong><br>
                            Enter your 4-digit PIN to complete the payment.
                            <small class="d-block mt-2" style="color: #666;">Test PIN: 1234</small>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="mb-3">
                            <label for="pinInput" class="form-label">4-Digit PIN</label>
                            <input type="password" id="pinInput" class="form-control form-control-lg text-center" 
                                   maxlength="4" inputmode="numeric" placeholder="••••" style="letter-spacing: 8px; font-size: 24px;">
                            <small class="text-muted">Your PIN is masked for security</small>
                        </div>

                        <div id="paymentError" class="alert alert-danger mb-3" style="display: none;"></div>

                        <button type="button" class="btn btn-primary w-100" onclick="processDigitalPayment()" id="payBtn" style="padding: 12px;">
                            <span class="spinner-border spinner-border-sm me-2" id="paySpinner" style="display: none;"></span>
                            <span id="payBtnText">Complete Payment</span>
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('guest.booking.summary', $booking) }}" class="btn btn-outline-secondary flex-grow-1">Back</a>
                    <button type="button" class="btn btn-primary flex-grow-1" id="proceedBtn" onclick="proceedWithPayment()">Proceed</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.payment-option-card:hover {
    border-color: #0d6efd;
    background-color: #f8f9ff;
}

.payment-option-card input[type="radio"]:checked ~ .card-body,
.payment-option-card input:checked + .card-body {
    border-color: #0d6efd;
}

.bank-btn {
    padding: 20px 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.bank-btn.active {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.bank-btn:hover {
    transform: translateY(-2px);
}

#pinInput {
    font-weight: bold;
}

#pinInput:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}
</style>

<script>
let selectedPaymentMethod = null;
let selectedBank = null;

function selectPaymentMethod(method) {
    selectedPaymentMethod = method;
    const digitalDetails = document.getElementById('digitalPaymentDetails');
    
    if (method === 'digital') {
        digitalDetails.style.display = 'block';
        document.querySelector('input[value="digital"]').checked = true;
    } else {
        digitalDetails.style.display = 'none';
        document.querySelector('input[value="cash"]').checked = true;
    }
    
    updateButtonState();
}

document.querySelectorAll('.bank-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.bank-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        selectedBank = this.dataset.bank;
        document.getElementById('pinEntrySection').style.display = 'block';
        document.getElementById('pinInput').focus();
    });
});

document.getElementById('pinInput').addEventListener('input', function(e) {
    // Allow only digits
    this.value = this.value.replace(/[^\d]/g, '').slice(0, 4);
});

function proceedWithPayment() {
    if (!selectedPaymentMethod) {
        alert('Please select a payment method');
        return;
    }

    if (selectedPaymentMethod === 'cash') {
        processCashPayment();
    } else if (selectedPaymentMethod === 'digital') {
        if (!selectedBank) {
            alert('Please select a bank');
            return;
        }
    }

    updateButtonState();
}

function updateButtonState() {
    const proceedBtn = document.getElementById('proceedBtn');
    if (!selectedPaymentMethod || (selectedPaymentMethod === 'digital' && !selectedBank)) {
        proceedBtn.disabled = true;
    } else {
        proceedBtn.disabled = false;
    }
}

async function processDigitalPayment() {
    const pin = document.getElementById('pinInput').value;
    const errorDiv = document.getElementById('paymentError');
    const payBtn = document.getElementById('payBtn');
    const payBtnText = document.getElementById('payBtnText');
    const paySpinner = document.getElementById('paySpinner');

    if (pin.length !== 4) {
        errorDiv.style.display = 'block';
        errorDiv.textContent = 'Please enter a 4-digit PIN';
        return;
    }

    try {
        payBtn.disabled = true;
        paySpinner.style.display = 'inline-block';
        payBtnText.textContent = 'Processing...';
        errorDiv.style.display = 'none';

        const response = await fetch('{{ route("api.payments.process", $booking->booking_id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                booking_id: '{{ $booking->id }}',
                banking_app: selectedBank,
                pin: pin
            })
        });

        const data = await response.json();

        if (data.success) {
            // Show success message and redirect
            window.location.href = '{{ route("guest.booking.confirmation", $booking) }}?transaction=' + data.transaction_id;
        } else {
            errorDiv.style.display = 'block';
            errorDiv.textContent = data.message || 'Payment failed. Please try again.';
            document.getElementById('pinInput').value = '';
            payBtn.disabled = false;
            paySpinner.style.display = 'none';
            payBtnText.textContent = 'Try Again';
        }
    } catch (error) {
        console.error('Payment error:', error);
        errorDiv.style.display = 'block';
        errorDiv.textContent = 'An error occurred. Please try again.';
        payBtn.disabled = false;
        paySpinner.style.display = 'none';
        payBtnText.textContent = 'Try Again';
    }
}

async function processCashPayment() {
    try {
        const response = await fetch('{{ route("api.payments.cash", $booking->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = '{{ route("guest.booking.confirmation", $booking) }}?method=cash';
        } else {
            alert(data.message || 'Error processing payment');
        }
    } catch (error) {
        console.error('Cash payment error:', error);
        alert('An error occurred. Please try again.');
    }
}

// Initialize
document.getElementById('proceedBtn').disabled = true;
</script>
@endsection
