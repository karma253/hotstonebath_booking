@extends('layouts.admin-layout')

@section('title', 'Admin Settings')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="h3 mb-0">⚙️ Admin Settings</h1>
    </div>

    <div class="row">
        <!-- Tabs Navigation -->
        <div class="col-lg-3 mb-3 mb-lg-0">
            <div class="list-group">
                <button class="list-group-item list-group-item-action active" data-bs-toggle="list" 
                        href="#profile-tab" role="tab">
                    <i class="fas fa-user-circle"></i> Admin Profile
                </button>
                <button class="list-group-item list-group-item-action" data-bs-toggle="list" 
                        href="#system-tab" role="tab">
                    <i class="fas fa-cogs"></i> System Settings
                </button>
                <button class="list-group-item list-group-item-action" data-bs-toggle="list" 
                        href="#types-tab" role="tab">
                    <i class="fas fa-spa"></i> Bath Types
                </button>
            </div>
        </div>

        <!-- Tabs Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile-tab" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Admin Profile Information</h5>
                            <form action="{{ route('admin.settings.profile') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $admin->phone) }}">
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                               id="address" name="address" value="{{ old('address', $admin->address) }}">
                                        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <hr>

                                <h6 class="mb-3">Change Password (Optional)</h6>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password" placeholder="Leave blank to keep current password">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>

                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- System Settings Tab -->
                <div class="tab-pane fade" id="system-tab" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">System-Wide Settings</h5>
                            <form action="{{ route('admin.settings.system') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label for="commission_rate" class="form-label">Commission Rate (%) *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('commission_rate') is-invalid @enderror" 
                                               id="commission_rate" name="commission_rate" value="{{ old('commission_rate', 15) }}" 
                                               min="0" max="100" step="0.1" required>
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <small class="form-text text-muted">Percentage charged from each booking</small>
                                    @error('commission_rate') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="max_guests_default" class="form-label">Default Max Guests per Bath *</label>
                                        <input type="number" class="form-control @error('max_guests_default') is-invalid @enderror" 
                                               id="max_guests_default" name="max_guests_default" value="{{ old('max_guests_default', 10) }}" 
                                               min="1" required>
                                        @error('max_guests_default') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="booking_time_limit" class="form-label">Booking Time Limit (hours) *</label>
                                        <input type="number" class="form-control @error('booking_time_limit') is-invalid @enderror" 
                                               id="booking_time_limit" name="booking_time_limit" value="{{ old('booking_time_limit', 24) }}" 
                                               min="1" required>
                                        <small class="form-text text-muted">Maximum hours in advance for bookings</small>
                                        @error('booking_time_limit') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="enable_notifications" 
                                               name="enable_notifications" value="1" checked>
                                        <label class="form-check-label" for="enable_notifications">
                                            Enable System Notifications
                                        </label>
                                        <small class="d-block form-text text-muted">
                                            Allow system to send notifications to users
                                        </small>
                                    </div>
                                </div>

                                <div class="alert alert-info" role="alert">
                                    <i class="fas fa-info-circle"></i> 
                                    <strong>Note:</strong> These settings apply system-wide and affect all active bookings and operations.
                                </div>

                                <div class="d-flex gap-2 pt-3 border-top">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save System Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bath Types Tab -->
                <div class="tab-pane fade" id="types-tab" role="tabpanel">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Available Bath Types</h5>
                            <p class="text-muted mb-3">These are the types of baths available in the system:</p>

                            <div class="list-group">
                                @foreach(['menchu' => 'Menchu', 'dotsho' => 'Dotsho', 'tshachu' => 'Tshachu'] as $key => $label)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $label }}</h6>
                                                <small class="text-muted">{{ ucfirst($key) }} Type</small>
                                            </div>
                                            <div class="badge bg-secondary">
                                                {{ \App\Models\Bath::where('bath_type', $key)->count() }} Baths
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="alert alert-warning mt-3" role="alert">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Note:</strong> Bath types are managed by the system administrator. To add new types, contact technical support.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
