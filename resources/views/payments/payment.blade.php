@extends('layouts.app')

@section('content')
<div class="payment-container">
    <!-- Payment Method Selection -->
    <div id="paymentMethodSelection" class="payment-section">
        <div class="payment-card">
            <div class="section-header">
                <h2>Choose Payment Method</h2>
                <p class="booking-info">Booking ID: <strong>{{ $booking['booking_id'] }}</strong></p>
            </div>

            <div class="booking-details">
                <div class="detail-row">
                    <span class="label">Amount to Pay:</span>
                    <span class="value amount">₹ {{ number_format($booking['total_price'], 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Guest Name:</span>
                    <span class="value">{{ $booking['guest_name'] }}</span>
                </div>
                <div class="detail-row">
                    <span class="label">Bath:</span>
                    <span class="value">{{ $booking['bath_name'] }}</span>
                </div>
            </div>

            <div class="payment-options">
                <button class="payment-method-btn cash-btn" data-method="cash">
                    <div class="method-icon">💵</div>
                    <div class="method-name">Cash Payment</div>
                    <div class="method-desc">Pay at the bath facility</div>
                </button>

                <button class="payment-method-btn digital-btn" data-method="digital">
                    <div class="method-icon">📱</div>
                    <div class="method-name">Digital Payment</div>
                    <div class="method-desc">Pay using banking apps</div>
                </button>
            </div>
        </div>
    </div>

    <!-- Banking Apps Selection -->
    <div id="bankingAppSelection" class="payment-section hidden">
        <div class="payment-card">
            <button class="back-btn" onclick="goBack()">← Back</button>
            
            <div class="section-header">
                <h2>Select Banking App</h2>
                <p class="booking-info">Amount: <strong>₹ {{ number_format($booking['total_price'], 2) }}</strong></p>
            </div>

            <div class="banking-apps">
                <div class="bank-option" data-app="MBoB">
                    <div class="bank-card" style="border-color: #003366;">
                        <div class="bank-logo">🏦</div>
                        <div class="bank-name">Mobile Banking</div>
                        <div class="bank-code">(MBoB)</div>
                    </div>
                </div>

                <div class="bank-option" data-app="MPay">
                    <div class="bank-card" style="border-color: #FF6B6B;">
                        <div class="bank-logo">💳</div>
                        <div class="bank-name">MPay</div>
                        <div class="bank-code">Digital Payment</div>
                    </div>
                </div>

                <div class="bank-option" data-app="BDBL">
                    <div class="bank-card" style="border-color: #4CAF50;">
                        <div class="bank-logo">🏛️</div>
                        <div class="bank-name">BDBL</div>
                        <div class="bank-code">Development Bank</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PIN Entry Page -->
    <div id="pinEntryPage" class="payment-section hidden">
        <div class="payment-card bank-login">
            <button class="back-btn" onclick="goBack()">← Back</button>
            
            <div class="bank-header" id="bankHeader">
                <div class="bank-logo-large">🏦</div>
                <div class="bank-title" id="bankTitle">Mobile Banking</div>
            </div>

            <div class="payment-details">
                <p>Transaction Amount</p>
                <h3 class="amount-big">₹ {{ number_format($booking['total_price'], 2) }}</h3>
            </div>

            <div class="pin-form">
                <label for="pinInput">Enter 4-digit PIN</label>
                <input 
                    type="password" 
                    id="pinInput" 
                    maxlength="4" 
                    placeholder="••••" 
                    class="pin-input"
                    inputmode="numeric"
                >
                <small class="pin-hint">Hint: Use 1234 for success</small>
            </div>

            <button id="payNowBtn" class="pay-btn" onclick="processPayment()">Pay Now</button>
            
            <div id="errorMessage" class="error-message hidden"></div>

            <div class="security-badge">
                🔒 Secure & Encrypted
            </div>
        </div>
    </div>

    <!-- Payment Processing -->
    <div id="paymentProcessing" class="payment-section hidden">
        <div class="payment-card loading">
            <div class="loader"></div>
            <h3>Processing Payment...</h3>
            <p>Please wait while we process your transaction</p>
        </div>
    </div>

    <!-- Payment Success -->
    <div id="paymentSuccess" class="payment-section hidden">
        <div class="payment-card success">
            <div class="success-animation">
                <div class="checkmark">✓</div>
            </div>
            <h2>Payment Successful!</h2>
            <p>Your booking has been confirmed</p>
            
            <div class="success-details">
                <div class="detail">
                    <span>Transaction ID:</span>
                    <strong id="successTransactionId"></strong>
                </div>
                <div class="detail">
                    <span>Amount Paid:</span>
                    <strong id="successAmount">₹ {{ number_format($booking['total_price'], 2) }}</strong>
                </div>
            </div>

            <button class="confirm-btn" onclick="redirectToConfirmation()">View Booking Confirmation</button>
        </div>
    </div>

    <!-- Payment Failed -->
    <div id="paymentFailed" class="payment-section hidden">
        <div class="payment-card failed">
            <div class="fail-icon">✕</div>
            <h2>Payment Failed</h2>
            <p id="failureMessage">Invalid PIN. Please try again.</p>
            
            <div class="failed-details">
                <p id="retryCount" class="retry-info"></p>
            </div>

            <div class="button-group">
                <button class="retry-btn" onclick="retryPayment()">Try Again</button>
                <button class="cancel-btn" onclick="goBack()">Cancel Payment</button>
            </div>
        </div>
    </div>

    <!-- Cash Payment Confirmation -->
    <div id="cashPaymentConfirm" class="payment-section hidden">
        <div class="payment-card">
            <div class="cash-confirm-icon">💵</div>
            <h2>Booking Confirmed</h2>
            <p>Your booking has been confirmed!</p>
            
            <div class="cash-details">
                <p><strong>Payment Method:</strong> Cash Payment on Arrival</p>
                <p><strong>Amount Due:</strong> ₹ {{ number_format($booking['total_price'], 2) }}</p>
                <p><strong>Please bring exact amount</strong></p>
            </div>

            <button class="confirm-btn" onclick="redirectToConfirmation()">View Booking Details</button>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .payment-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: 'Inter', sans-serif;
    }

    .payment-section {
        width: 100%;
        max-width: 500px;
        animation: slideUp 0.5s ease-out;
    }

    .payment-section.hidden {
        display: none;
    }

    .payment-card {
        background: white;
        border-radius: 16px;
        padding: 32px 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        position: relative;
    }

    .section-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .section-header h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 8px;
    }

    .booking-info {
        color: #999;
        font-size: 14px;
        margin-top: 8px;
    }

    .booking-details {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 30px;
        border-left: 4px solid #667eea;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-row .label {
        color: #666;
        font-weight: 500;
    }

    .detail-row .value {
        color: #333;
        font-weight: 600;
    }

    .detail-row .amount {
        color: #667eea;
        font-size: 18px;
    }

    .payment-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-top: 30px;
    }

    .payment-method-btn {
        padding: 24px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .payment-method-btn:hover {
        border-color: #667eea;
        background: #f0f3ff;
    }

    .payment-method-btn.cash-btn:hover {
        border-color: #4CAF50;
        background: #f1f8f5;
    }

    .method-icon {
        font-size: 32px;
    }

    .method-name {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .method-desc {
        font-size: 12px;
        color: #999;
    }

    .banking-apps {
        display: grid;
        gap: 16px;
    }

    .bank-option {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .bank-card {
        padding: 24px;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .bank-option:hover .bank-card {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        border-color: currentColor;
    }

    .bank-logo {
        font-size: 48px;
        margin-bottom: 12px;
    }

    .bank-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }

    .bank-code {
        font-size: 12px;
        color: #999;
    }

    .bank-header {
        text-align: center;
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e0e0e0;
    }

    .bank-logo-large {
        font-size: 64px;
        margin-bottom: 12px;
    }

    .bank-title {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    .payment-details {
        text-align: center;
        margin-bottom: 32px;
        padding: 24px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .payment-details p {
        color: #999;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .amount-big {
        font-size: 32px;
        color: #667eea;
        margin: 0;
    }

    .pin-form {
        margin-bottom: 24px;
    }

    .pin-form label {
        display: block;
        margin-bottom: 12px;
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .pin-input {
        width: 100%;
        padding: 16px;
        font-size: 32px;
        text-align: center;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        letter-spacing: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .pin-input:focus {
        outline: none;
        border-color: #667eea;
        background: #f0f3ff;
    }

    .pin-hint {
        display: block;
        text-align: center;
        color: #999;
        font-size: 12px;
        margin-top: 8px;
    }

    .pay-btn {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 16px;
    }

    .pay-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
    }

    .pay-btn:active {
        transform: translateY(0);
    }

    .error-message {
        padding: 12px;
        background: #fee;
        color: #c33;
        border-radius: 8px;
        font-size: 14px;
        text-align: center;
        margin-bottom: 16px;
        border: 1px solid #fcc;
    }

    .error-message.hidden {
        display: none;
    }

    .security-badge {
        text-align: center;
        color: #999;
        font-size: 12px;
        margin-top: 16px;
    }

    .back-btn {
        padding: 8px 12px;
        background: #f0f0f0;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        color: #333;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 24px;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: #e0e0e0;
    }

    /* Loading Animation */
    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #667eea;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .payment-card.loading {
        text-align: center;
    }

    .payment-card.loading h3 {
        color: #333;
        margin-bottom: 8px;
    }

    .payment-card.loading p {
        color: #999;
        font-size: 14px;
    }

    /* Success Animation */
    .success-animation {
        margin: 0 auto 24px;
        width: 80px;
        height: 80px;
        background: #4CAF50;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: scaleUp 0.6s ease-out;
    }

    .checkmark {
        font-size: 48px;
        color: white;
        animation: popIn 0.6s ease-out;
    }

    @keyframes scaleUp {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    @keyframes popIn {
        0% { 
            transform: scale(0);
            opacity: 0;
        }
        50% { 
            transform: scale(1.2);
        }
        100% { 
            transform: scale(1);
            opacity: 1;
        }
    }

    .payment-card.success {
        text-align: center;
    }

    .payment-card.success h2 {
        color: #4CAF50;
        margin-bottom: 8px;
    }

    .payment-card.success > p {
        color: #999;
        margin-bottom: 24px;
    }

    .success-details {
        background: #f1f8f5;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        text-align: left;
    }

    .success-details .detail {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
        border-bottom: 1px solid rgba(76, 175, 80, 0.2);
    }

    .success-details .detail:last-child {
        border-bottom: none;
    }

    /* Failed Animation */
    .fail-icon {
        margin: 0 auto 24px;
        width: 80px;
        height: 80px;
        background: #f44336;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: white;
        animation: shake 0.6s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .payment-card.failed {
        text-align: center;
    }

    .payment-card.failed h2 {
        color: #f44336;
        margin-bottom: 8px;
    }

    .payment-card.failed > p {
        color: #999;
        margin-bottom: 16px;
    }

    .failed-details {
        background: #ffebee;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .retry-info {
        color: #c62828;
        font-size: 14px;
        margin: 0;
    }

    .button-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .retry-btn {
        padding: 12px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .retry-btn:hover {
        transform: translateY(-2px);
    }

    .cancel-btn {
        padding: 12px;
        background: #f0f0f0;
        color: #333;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cancel-btn:hover {
        background: #e0e0e0;
    }

    .confirm-btn {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .confirm-btn:hover {
        transform: translateY(-2px);
    }

    /* Cash Payment */
    .cash-confirm-icon {
        font-size: 64px;
        text-align: center;
        margin-bottom: 16px;
    }

    .payment-card.success,
    .payment-card.failed {
        animation: slideUp 0.5s ease-out;
    }

    .cash-details {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
        text-align: left;
    }

    .cash-details p {
        margin: 8px 0;
        color: #333;
        font-size: 14px;
    }

    .cash-details strong {
        color: #667eea;
    }

    .payment-card > h2 {
        color: #333;
        margin-bottom: 8px;
    }

    .payment-card > p {
        color: #999;
        margin-bottom: 24px;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 600px) {
        .payment-container {
            padding: 16px;
        }

        .payment-card {
            padding: 24px 16px;
        }

        .payment-options {
            grid-template-columns: 1fr;
        }

        .amount-big {
            font-size: 24px;
        }

        .button-group {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    let currentBookingId = '{{ $booking["booking_id"] }}';
    let selectedBankingApp = null;
    let transactionInProgress = false;

    // Show different sections
    function showSection(sectionId) {
        document.querySelectorAll('.payment-section').forEach(section => {
            section.classList.add('hidden');
        });
        document.getElementById(sectionId).classList.remove('hidden');
    }

    // Payment method selection
    document.querySelectorAll('.payment-method-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const method = this.dataset.method;
            if (method === 'cash') {
                processCashPayment();
            } else if (method === 'digital') {
                showSection('bankingAppSelection');
            }
        });
    });

    // Banking app selection
    document.querySelectorAll('.bank-option').forEach(option => {
        option.addEventListener('click', function() {
            selectedBankingApp = this.dataset.app;
            updateBankHeader(selectedBankingApp);
            showSection('pinEntryPage');
            document.getElementById('pinInput').focus();
        });
    });

    // Update bank header based on selected app
    function updateBankHeader(app) {
        const headers = {
            'MBoB': { icon: '🏦', name: 'Mobile Banking (MBoB)' },
            'MPay': { icon: '💳', name: 'MPay - Digital Payment' },
            'BDBL': { icon: '🏛️', name: 'Bhutan Development Bank' }
        };
        
        const header = headers[app];
        document.querySelector('.bank-logo-large').textContent = header.icon;
        document.getElementById('bankTitle').textContent = header.name;
    }

    // PIN input validation
    document.getElementById('pinInput').addEventListener('input', function(e) {
        // Only allow 4 digits
        this.value = this.value.replace(/[^\d]/g, '').slice(0, 4);
        
        // Clear error message
        document.getElementById('errorMessage').classList.add('hidden');
    });

    // Process digital payment
    async function processPayment() {
        const pin = document.getElementById('pinInput').value;
        
        if (pin.length !== 4) {
            showError('Please enter a 4-digit PIN');
            return;
        }

        if (transactionInProgress) return;
        transactionInProgress = true;

        document.getElementById('payNowBtn').disabled = true;
        showSection('paymentProcessing');

        try {
            const response = await fetch(`/api/payments/process/${currentBookingId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                },
                body: JSON.stringify({
                    banking_app: selectedBankingApp,
                    pin: pin
                })
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('successTransactionId').textContent = data.transaction_id;
                showSection('paymentSuccess');
            } else {
                showSection('paymentFailed');
                document.getElementById('failureMessage').textContent = data.message;
                document.getElementById('pinInput').value = '';
                transactionInProgress = false;
                document.getElementById('payNowBtn').disabled = false;
            }
        } catch (error) {
            console.error('Payment error:', error);
            showError('Payment processing error. Please try again.');
            showSection('pinEntryPage');
            document.getElementById('payNowBtn').disabled = false;
            transactionInProgress = false;
        }
    }

    // Process cash payment
    async function processCashPayment() {
        try {
            const response = await fetch(`/api/payments/cash/${currentBookingId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                }
            });

            const data = await response.json();

            if (data.success) {
                showSection('cashPaymentConfirm');
            } else {
                showError(data.message);
            }
        } catch (error) {
            console.error('Cash payment error:', error);
            showError('Error processing cash payment');
        }
    }

    // Retry payment
    async function retryPayment() {
        const pin = document.getElementById('pinInput').value;
        
        if (pin.length !== 4) {
            showError('Please enter a 4-digit PIN');
            return;
        }

        transactionInProgress = true;
        showSection('paymentProcessing');

        try {
            const response = await fetch(`/api/payments/retry/${currentBookingId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Authorization': `Bearer ${localStorage.getItem('auth_token')}`
                },
                body: JSON.stringify({
                    banking_app: selectedBankingApp,
                    pin: pin
                })
            });

            const data = await response.json();

            if (data.success) {
                document.getElementById('successTransactionId').textContent = data.transaction_id;
                showSection('paymentSuccess');
            } else {
                showSection('paymentFailed');
                document.getElementById('failureMessage').textContent = data.message;
                document.getElementById('pinInput').value = '';
                transactionInProgress = false;
            }
        } catch (error) {
            console.error('Retry error:', error);
            showError('Error during payment retry');
            showSection('pinEntryPage');
            transactionInProgress = false;
        }
    }

    // Go back to previous section
    function goBack() {
        const activeSection = document.querySelector('.payment-section:not(.hidden)').id;
        
        if (activeSection === 'pinEntryPage') {
            showSection('bankingAppSelection');
        } else if (activeSection === 'bankingAppSelection') {
            showSection('paymentMethodSelection');
        } else if (activeSection === 'paymentFailed') {
            showSection('pinEntryPage');
            document.getElementById('pinInput').value = '';
        }
    }

    // Show error message
    function showError(message) {
        const errorEl = document.getElementById('errorMessage');
        errorEl.textContent = message;
        errorEl.classList.remove('hidden');
    }

    // Redirect to confirmation
    function redirectToConfirmation() {
        window.location.href = `/booking/${currentBookingId}/confirmation`;
    }

    // Initialize
    showSection('paymentMethodSelection');
</script>

@endsection
