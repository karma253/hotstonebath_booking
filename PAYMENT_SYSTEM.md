# 💳 Digital Payment System - Documentation

## Overview

The Hot Stone Bath Booking System now includes a sophisticated digital payment flow that simulates real-world mobile banking payment experiences. The system supports both digital payments through banking apps and cash payments on arrival.

## Features

### 1. Payment Method Selection
- **Cash Payment**: Pay at the bath facility on arrival
- **Digital Payment**: Pay using three banking app options

### 2. Digital Banking Apps
Users can choose from three banking app options:
- **MBoB** (Mobile Banking Bhutan)
- **MPay** (Digital Payment Solution)
- **BDBL** (Bhutan Development Bank)

### 3. Secure PIN Entry
- 4-digit PIN input with masking
- Test PIN: `1234` (success), any other PIN will fail
- Shows loading animation during processing
- 2-3 second simulated transaction delay
- Retry functionality for failed payments

### 4. Payment Tracking
- Unique transaction IDs (e.g., TXN20260317142530123456)
- Complete transaction history
- Admin dashboard showing recent transactions
- Filter and search capabilities

### 5. Admin Dashboard Features
- View last 10 recent transactions
- Transaction statistics (success/failed counts)
- Filter by status, payment method, and date range
- Sort by latest transactions first

## Database Schema

### Transactions Table
```sql
CREATE TABLE transactions (
    id BIGINT PRIMARY KEY,
    transaction_id VARCHAR(255) UNIQUE,
    user_id BIGINT FOREIGN KEY (users),
    booking_id BIGINT FOREIGN KEY (bookings),
    payment_method ENUM('MBoB', 'MPay', 'BDBL', 'cash'),
    amount DECIMAL(10, 2),
    status ENUM('success', 'failed', 'pending'),
    error_message TEXT NULL,
    retry_count INT DEFAULT 0,
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## API Endpoints

### Guest Payment Endpoints (Protected)

#### 1. Get Payment Methods
```
GET /api/payments/methods/{bookingId}
```
Returns available payment methods and booking details.

#### 2. Show Banking Apps
```
GET /api/payments/banking-apps/{bookingId}
```
Shows available banking app options for digital payment.

#### 3. Process Digital Payment
```
POST /api/payments/process/{bookingId}
Content-Type: application/json

{
    "banking_app": "MBoB",
    "pin": "1234"
}

Response:
{
    "success": true,
    "message": "Payment Successful!",
    "transaction_id": "TXN...",
    "redirect_url": "/booking/{bookingId}/confirmation"
}
```

#### 4. Process Cash Payment
```
POST /api/payments/cash/{bookingId}
Content-Type: application/json

Response:
{
    "success": true,
    "message": "Booking confirmed! Please pay when you arrive.",
    "transaction_id": "TXN...",
    "redirect_url": "/booking/{bookingId}/confirmation"
}
```

#### 5. Retry Failed Payment
```
POST /api/payments/retry/{bookingId}
Content-Type: application/json

{
    "banking_app": "MBoB",
    "pin": "1234"
}
```

#### 6. Get Payment Status
```
GET /api/payments/status/{bookingId}
```
Returns current status of booking and associated transaction.

### Admin Endpoints (Protected - Admin Only)

#### 1. Get Recent Transactions
```
GET /api/admin/transactions/recent?limit=10
```
Returns last 10 transactions with user and booking details.

#### 2. Get Transaction Statistics
```
GET /api/admin/transactions/stats
```
Returns overall transaction statistics and breakdown by payment method.

#### 3. Filter Transactions
```
POST /api/admin/transactions/filter
Content-Type: application/json

{
    "status": "success",
    "payment_method": "MBoB",
    "date_from": "2026-03-01",
    "date_to": "2026-03-31",
    "limit": 20
}
```

## Controllers

### PaymentController (`app/Http/Controllers/Guest/PaymentController.php`)
Handles all payment processing logic:
- `showPaymentMethods()` - Display payment options
- `showBankingApps()` - Display banking app selection
- `processPayment()` - Process digital payment with PIN
- `processCashPayment()` - Create cash payment record
- `retryPayment()` - Retry failed payment
- `getPaymentStatus()` - Check transaction status

### AdminVerificationController (updated)
New transaction management methods:
- `getRecentTransactions()` - Get last 10 transactions
- `getTransactionStats()` - Get transaction statistics
- `filterTransactions()` - Filter and search transactions

## Models

### Transaction Model (`app/Models/Transaction.php`)
```php
// Relationships
$transaction->user()     // BelongsTo User
$transaction->booking()  // BelongsTo Booking

// Methods
Transaction::generateTransactionId()  // Generate unique TXN ID
```

## Views

### Payment Page (`resources/views/payments/payment.blade.php`)
Complete payment flow UI with:
- Payment method selection (Cash/Digital)
- Banking app selection
- PIN entry page with masking
- Processing animation
- Success/failure screens
- Modern gradient design
- Smooth animations and transitions
- Responsive design for mobile devices

## File Structure

```
app/
├── Models/
│   └── Transaction.php (NEW)
├── Http/
│   └── Controllers/
│       └── Guest/
│           └── PaymentController.php (NEW)

database/
├── migrations/
│   └── 2026_03_17_000012_create_transactions_table.php (NEW)

resources/
└── views/
    └── payments/
        └── payment.blade.php (NEW)

routes/
└── api.php (UPDATED - Added payment routes)
```

## Testing the Payment System

### 1. Create a Booking First
```bash
# Register as guest
POST /api/auth/guest/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "phone": "1234567890"
}

# Login
POST /api/auth/guest/login
{
    "email": "john@example.com",
    "password": "password123"
}

# Create booking
POST /api/bookings
{
    "bath_id": 1,
    "service_id": 1,
    "booking_date": "2026-04-01",
    "start_time": "10:00",
    "end_time": "11:00",
    "number_of_guests": 2,
    "guest_name": "John Doe",
    "guest_email": "john@example.com",
    "guest_phone": "1234567890"
}
```

### 2. Test Digital Payment
```bash
# Get payment methods
GET /api/payments/methods/{bookingId}

# Get banking apps
GET /api/payments/banking-apps/{bookingId}

# Process payment with correct PIN (should succeed)
POST /api/payments/process/{bookingId}
{
    "banking_app": "MBoB",
    "pin": "1234"
}

# Process payment with incorrect PIN (should fail)
POST /api/payments/process/{bookingId}
{
    "banking_app": "MBoB",
    "pin": "5678"
}

# Retry payment after failure
POST /api/payments/retry/{bookingId}
{
    "banking_app": "MBoB",
    "pin": "1234"
}
```

### 3. Test Cash Payment
```bash
# Process cash payment
POST /api/payments/cash/{bookingId}
```

### 4. View Transactions (Admin)
```bash
# Login as admin first
POST /api/auth/owner/login
{
    "email": "admin@example.com",
    "password": "password"
}

# Get recent transactions
GET /api/admin/transactions/recent

# Get transaction stats
GET /api/admin/transactions/stats

# Filter transactions
POST /api/admin/transactions/filter
{
    "status": "success",
    "payment_method": "MBoB",
    "limit": 20
}
```

## UI/UX Features

### Animations & Interactions
1. **Slide Up Animation** - Payment sections slide up smoothly
2. **Loading Spinner** - Animated spinner during payment processing
3. **Success Animation** - Green circle with checkmark for successful payments
4. **Failure Animation** - Red circle with shake animation for failed payments
5. **Hover Effects** - Card elevation and color changes on hover
6. **PIN Input Masking** - Dots displayed instead of actual PIN digits

### Color Scheme
- **Primary**: Purple gradient (#667eea → #764ba2)
- **Success**: Green (#4CAF50)
- **Error**: Red (#f44336)
- **Neutral**: Gray tones (#f0f0f0, #999, #333)

### Responsive Design
- Mobile-first approach
- Optimized for all screen sizes
- Touch-friendly buttons
- Proper padding and spacing

## Security Considerations

1. **PIN Validation**: Always validated server-side (never trust client)
2. **Database Transactions**: Payment processing is wrapped in DB transactions
3. **Booking Lock**: Pessimistic locking prevents concurrent payment processing
4. **Error Messages**: Generic error messages to prevent information leakage
5. **Status Codes**: Proper HTTP status codes for different scenarios
6. **CSRF Protection**: All requests require CSRF token validation

## Running Migrations

```bash
# Run the new migration
php artisan migrate

# If you need to rollback
php artisan migrate:rollback --step=1
```

## Development Notes

- **Test PIN**: Use `1234` for successful payments
- **Transaction IDs**: Format is `TXN{YYYYMMDDHHMMSS}{6RandomDigits}`
- **Simulated Delay**: 2-3 seconds to simulate real payment processing
- **Error Handling**: All operations use try-catch with database rollback on failure
- **Status Codes**: 
  - 200/201: Success
  - 400: Bad request or validation error
  - 500: Server error

## Future Enhancements

1. **Real Payment Gateway Integration**: Connect with actual banking APIs
2. **Payment Receipts**: Email receipts to users
3. **Refund Processing**: Handle refunds through admin interface
4. **Payment Analytics**: Advanced reporting and analytics
5. **Multiple Currencies**: Support for different currencies
6. **Payment Installments**: Allow payment plans for large bookings
7. **Wallet**: User wallet for quick payments
8. **Transaction History**: User-facing transaction history page

## Support & Troubleshooting

### Payment Not Processing
- Ensure booking status is 'pending'
- Check if PIN is exactly 4 digits
- Verify all required fields are sent

### Transaction Not Saving
- Check database connection
- Ensure migrations have run successfully
- Verify foreign key constraints

### PIN Always Fails
- The test PIN is exactly `1234` (no extra spaces)
- PIN must be 4 digits only
- Check database for transaction records

## Contact & Support

For issues or questions about the payment system, refer to the main project documentation or contact the development team.
