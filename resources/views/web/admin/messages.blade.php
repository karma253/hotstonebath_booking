<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages - {{ $otherParty->name }} - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-title h1 {
            font-size: 1.5rem;
            color: #1a1a2e;
        }

        .header-title .subtitle {
            font-size: 0.9rem;
            color: #666;
        }

        .back-btn {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s;
        }

        .back-btn:hover {
            background: #5568d3;
        }

        .container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .chat-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: white;
            margin: 1rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .booking-info {
            background: #f9f9f9;
            padding: 1rem;
            border-bottom: 1px solid #eee;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .booking-info-item {
            font-size: 0.9rem;
        }

        .booking-info-label {
            color: #666;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 0.25rem;
        }

        .booking-info-value {
            color: #1a1a2e;
            font-weight: 500;
        }

        .messages-list {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background: white;
        }

        .message {
            display: flex;
            margin-bottom: 1rem;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message-content {
            max-width: 70%;
            background: #667eea;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            word-wrap: break-word;
        }

        .message.received .message-content {
            background: #f0f0f0;
            color: #333;
        }

        .message-time {
            font-size: 0.75rem;
            color: #999;
            margin-top: 0.25rem;
            padding: 0 0.5rem;
        }

        .message-sender {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.25rem;
            font-weight: 500;
        }

        .no-messages {
            text-align: center;
            color: #999;
            padding: 2rem;
            font-size: 0.95rem;
        }

        .input-area {
            background: #f9f9f9;
            padding: 1rem;
            border-top: 1px solid #eee;
            display: flex;
            gap: 0.5rem;
        }

        .input-area input {
            flex: 1;
            border: 1px solid #ddd;
            padding: 0.75rem;
            border-radius: 4px;
            font-size: 0.95rem;
            font-family: inherit;
        }

        .input-area input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
        }

        .input-area button {
            background: #667eea;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 500;
        }

        .input-area button:hover {
            background: #5568d3;
        }

        .input-area button:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
            }

            .message-content {
                max-width: 90%;
            }

            .booking-info {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-title">
            <a href="{{ route('admin.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <div>
                <h1>Chat with {{ $otherParty->name }}</h1>
                <div class="subtitle">Booking Request for {{ $booking->bath->name }}</div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="chat-wrapper">
            <!-- Booking Info -->
            <div class="booking-info">
                <div class="booking-info-item">
                    <div class="booking-info-label">Booking ID</div>
                    <div class="booking-info-value">{{ $booking->booking_id }}</div>
                </div>
                <div class="booking-info-item">
                    <div class="booking-info-label">Guest</div>
                    <div class="booking-info-value">{{ $booking->guest_name }}</div>
                </div>
                <div class="booking-info-item">
                    <div class="booking-info-label">Date & Time</div>
                    <div class="booking-info-value">{{ optional($booking->booking_date)->format('d M Y') }} | {{ \Carbon\Carbon::parse($booking->start_time)->format('h:i A') }}</div>
                </div>
                <div class="booking-info-item">
                    <div class="booking-info-label">Guests</div>
                    <div class="booking-info-value">{{ $booking->number_of_guests }} person(s)</div>
                </div>
                <div class="booking-info-item">
                    <div class="booking-info-label">Amount</div>
                    <div class="booking-info-value">Nu. {{ number_format($booking->total_price, 2) }}</div>
                </div>
                <div class="booking-info-item">
                    <div class="booking-info-label">Status</div>
                    <div class="booking-info-value" style="color: #ff9500; font-weight: 600;">{{ ucfirst($booking->status) }}</div>
                </div>
            </div>

            <!-- Messages List -->
            <div class="messages-list" id="messagesList">
                @if ($messages->isEmpty())
                    <div class="no-messages">
                        <i class="fas fa-comments" style="font-size: 2rem; margin-bottom: 1rem; color: #ddd;"></i>
                        <p>No messages yet. Start a conversation!</p>
                    </div>
                @else
                    @foreach ($messages as $message)
                        <div class="message @if ($message->sender_id === auth()->id()) sent @else received @endif">
                            <div>
                                <div class="message-sender">
                                    {{ $message->sender->name }}
                                    @if ($message->is_read && $message->sender_id === auth()->id())
                                        <i class="fas fa-check-double" style="color: #667eea; margin-left: 0.25rem;"></i>
                                    @endif
                                </div>
                                <div class="message-content">{{ $message->message }}</div>
                                <div class="message-time">{{ $message->created_at->format('h:i A') }}</div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Input Area -->
            <div class="input-area">
                <input type="text" id="messageInput" placeholder="Type your message..." maxlength="1000">
                <button id="sendBtn" onclick="sendMessage()">
                    <i class="fas fa-paper-plane"></i> Send
                </button>
            </div>
        </div>
    </div>

    <script>
        const messageInput = document.getElementById('messageInput');
        const sendBtn = document.getElementById('sendBtn');
        const messagesList = document.getElementById('messagesList');

        // Enable send button on input
        messageInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });

        function sendMessage() {
            const message = messageInput.value.trim();
            if (!message) return;

            const bookingId = {{ $booking->id }};

            fetch(`/admin/messages/${bookingId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ message })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear input
                    messageInput.value = '';
                    
                    // Remove "no messages" message if exists
                    const noMessagesDiv = messagesList.querySelector('.no-messages');
                    if (noMessagesDiv) {
                        noMessagesDiv.remove();
                    }

                    // Add message to list
                    const newMessage = document.createElement('div');
                    newMessage.className = 'message sent';
                    newMessage.innerHTML = `
                        <div>
                            <div class="message-sender">{{ Auth::user()->name }}</div>
                            <div class="message-content">${message}</div>
                            <div class="message-time">Just now</div>
                        </div>
                    `;
                    messagesList.appendChild(newMessage);
                    
                    // Scroll to bottom
                    messagesList.scrollTop = messagesList.scrollHeight;
                } else {
                    alert('Error sending message: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error sending message');
            });
        }

        // Auto-scroll to bottom
        document.addEventListener('DOMContentLoaded', () => {
            messagesList.scrollTop = messagesList.scrollHeight;
        });

        // Refresh messages every 5 seconds
        setInterval(() => {
            location.reload();
        }, 30000);
    </script>
</body>
</html>
