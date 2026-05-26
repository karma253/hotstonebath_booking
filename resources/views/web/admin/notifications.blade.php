@extends('layouts.admin-layout')

@section('title', 'Send Notifications')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0">📢 Send Announcements & Notifications</h1>
    </div>

    <div class="row">
        <!-- Send Notification Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.notification.send') }}" method="POST">
                        @csrf

                        <h5 class="mb-3">Send System Notification</h5>

                        <!-- Recipient Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-500">Send To *</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="recipient_type" 
                                       id="all_owners" value="all_owners" required>
                                <label class="form-check-label" for="all_owners">
                                    <strong>All Owners/Providers</strong>
                                    <div class="small text-muted">
                                        {{ \App\Models\User::where('role', 'owner')->count() }} owners
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="recipient_type" 
                                       id="all_customers" value="all_customers" required>
                                <label class="form-check-label" for="all_customers">
                                    <strong>All Customers/Guests</strong>
                                    <div class="small text-muted">
                                        {{ \App\Models\User::where('role', 'guest')->count() }} customers
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="recipient_type" 
                                       id="all_users" value="all_users" required>
                                <label class="form-check-label" for="all_users">
                                    <strong>All Users (Owners + Customers)</strong>
                                    <div class="small text-muted">
                                        {{ \App\Models\User::count() }} total users
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="mb-3">
                            <label for="subject" class="form-label fw-500">Subject *</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" placeholder="e.g., System Maintenance Notice" 
                                   value="{{ old('subject') }}" required maxlength="255">
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Message -->
                        <div class="mb-3">
                            <label for="message" class="form-label fw-500">Message *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" name="message" rows="6" placeholder="Enter your notification message..."
                                      required>{{ old('message') }}</textarea>
                            <div class="form-text">Markdown formatting is supported.</div>
                            @error('message') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <!-- Preview -->
                        <div class="mb-4 p-3 bg-light rounded">
                            <small class="text-muted">Preview:</small>
                            <div class="mt-2">
                                <h6 id="preview-subject">Subject here</h6>
                                <p id="preview-message" class="small mb-0">Message preview will appear here</p>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex gap-2 pt-3 border-top">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Send this notification to selected users?')">
                                <i class="fas fa-send"></i> Send Notification
                            </button>
                            <button type="reset" class="btn btn-secondary">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Notification Templates -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">📝 Quick Templates</h6>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary text-start" data-template="system_maintenance">
                            <small class="d-block text-truncate">🔧 System Maintenance</small>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary text-start" data-template="new_feature">
                            <small class="d-block text-truncate">✨ New Feature Announcement</small>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary text-start" data-template="policy_update">
                            <small class="d-block text-truncate">📋 Policy Update</small>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary text-start" data-template="promotion">
                            <small class="d-block text-truncate">🎉 Promotion/Reminder</small>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="card-title mb-3">📊 Notification Stats</h6>
                    <div class="mb-3">
                        <small class="text-muted">Total Owners</small>
                        <div class="h5">{{ \App\Models\User::where('role', 'owner')->count() }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Total Customers</small>
                        <div class="h5">{{ \App\Models\User::where('role', 'guest')->count() }}</div>
                    </div>
                    <div>
                        <small class="text-muted">All Users</small>
                        <div class="h5">{{ \App\Models\User::count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Template Content
const templates = {
    system_maintenance: {
        subject: '🔧 System Maintenance Notice',
        message: 'We are performing scheduled maintenance on our platform.\n\nDate: [Date]\nExpected Duration: [Duration]\n\nDuring this time, the system may be unavailable. We apologize for any inconvenience.'
    },
    new_feature: {
        subject: '✨ Introducing New Features',
        message: 'We are excited to announce new features on our platform!\n\n**New Features:**\n- Feature 1\n- Feature 2\n- Feature 3\n\nThese improvements will help you better manage your bookings.'
    },
    policy_update: {
        subject: '📋 Important Policy Update',
        message: 'Please note the following updates to our policies:\n\n**Changes:**\n- [Policy change 1]\n- [Policy change 2]\n\nThese changes are effective from [Date].'
    },
    promotion: {
        subject: '🎉 Special Announcement',
        message: 'We have an exciting announcement for you!\n\n[Add your promotion or announcement details here.]\n\nFor more information, visit our platform.'
    }
};

// Template Button Click Handler
document.querySelectorAll('[data-template]').forEach(btn => {
    btn.addEventListener('click', function() {
        const template = templates[this.dataset.template];
        document.getElementById('subject').value = template.subject;
        document.getElementById('message').value = template.message;
        updatePreview();
    });
});

// Update Preview
function updatePreview() {
    const subject = document.getElementById('subject').value || 'Subject here';
    const message = document.getElementById('message').value || 'Message preview will appear here';
    
    document.getElementById('preview-subject').textContent = subject;
    document.getElementById('preview-message').textContent = message;
}

// Listen for input changes
document.getElementById('subject').addEventListener('input', updatePreview);
document.getElementById('message').addEventListener('input', updatePreview);
</script>

@endsection
