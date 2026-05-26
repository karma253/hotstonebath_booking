<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking Notification</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .email-header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        .email-body {
            padding: 2rem;
        }
        .booking-section {
            background-color: #f9f9f9;
            border-left: 4px solid #e74c3c;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }
        .booking-section h3 {
            color: #e74c3c;
            margin-top: 0;
        }
        .booking-detail {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .booking-detail:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
        }
        .detail-value {
            color: #333;
        }
        .guest-info {
            background-color: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }
        .guest-info h3 {
            color: #3498db;
            margin-top: 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 4px;
            text-decoration: none;
            margin: 1.5rem 0;
            font-weight: bold;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }
        .cta-button:hover {
            background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.85rem;
            color: #999;
            border-top: 1px solid #eee;
        }
        .special-requests {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }
        .special-requests h3 {
            color: #ff9800;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🛁 New Booking Request</h1>
            <p style="margin: 0.5rem 0 0 0; font-size: 0.95rem;">A guest has booked your bath facility</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Dear Bath Owner,</p>
            <p>You have received a new booking request for <strong>{{ $bath->name }}</strong>. Here are the details:</p>

            <!-- Booking Details -->
            <div class="booking-section">
                <h3>📅 Booking Details</h3>
                <div class="booking-detail">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value"><strong>{{ $booking->booking_id }}</strong></span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Booking Date:</span>
                    <span class="detail-value">{{ $booking->booking_date->format('F d, Y') }}</span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Time Slot:</span>
                    <span class="detail-value">{{ $booking->start_time }} - {{ $booking->end_time }}</span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Number of Guests:</span>
                    <span class="detail-value">{{ $booking->number_of_guests }} {{ $booking->number_of_guests === 1 ? 'Guest' : 'Guests' }}</span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Total Price:</span>
                    <span class="detail-value" style="color: #e74c3c; font-weight: bold;">Nu. {{ number_format($booking->total_price, 2) }}</span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">
                        @if ($booking->payment_method === 'online')
                            <span style="background: #d4edda; padding: 0.25rem 0.5rem; border-radius: 3px;">Digital Payment (Paid)</span>
                        @else
                            <span style="background: #fff3cd; padding: 0.25rem 0.5rem; border-radius: 3px;">Cash on Arrival (Pending)</span>
                        @endif
                    </span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value" style="color: #ff9800; font-weight: bold;">PENDING CONFIRMATION</span>
                </div>
            </div>

            <!-- Guest Information -->
            <div class="guest-info">
                <h3>👤 Guest Information</h3>
                <div class="booking-detail">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $guest->name }}</span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value"><a href="mailto:{{ $guest->email }}" style="color: #3498db; text-decoration: none;">{{ $guest->email }}</a></span>
                </div>
                <div class="booking-detail">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $guest->phone ?? 'Not provided' }}</span>
                </div>
            </div>

            <!-- Special Requests -->
            @if ($booking->special_requests)
            <div class="special-requests">
                <h3>💬 Special Requests / Notes</h3>
                <p style="margin: 0; white-space: pre-wrap;">{{ $booking->special_requests }}</p>
            </div>
            @endif

            <p style="margin-top: 2rem;">Please review this booking request and confirm the availability. The guest is waiting for your confirmation.</p>

            <p style="text-align: center;">
                <strong>⏰ Action Required:</strong> Please confirm or reject this booking as soon as possible.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="margin: 0;">© 2024 Hot Stone Bath Booking System</p>
            <p style="margin: 0.5rem 0 0 0;">This is an automated notification. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
