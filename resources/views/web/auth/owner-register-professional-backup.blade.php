@extends('web.layouts.app')

@section('title', 'Professional Owner Registration - Hot Stone Bath Booking System')

@push('styles')
<style>
    .registration-container {
        min-height: 100vh;
        padding: 40px 20px;
    }

    .registration-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #667eea;
        margin-top: 30px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #667eea;
    }

    .form-section-title:first-child {
        margin-top: 0;
    }

    .form-label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .form-label .badge-required {
        color: #dc3545;
        font-weight: bold;
        margin-left: 2px;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px 12px;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-control.is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }

    .file-upload-area {
        border: 2px dashed #667eea;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8f9ff;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-area:hover {
        border-color: #764ba2;
        background: #f0f2ff;
    }

    .file-upload-area.active {
        border-color: #28a745;
        background: #f0fff4;
    }

    .file-upload-icon {
        font-size: 2.5rem;
        color: #667eea;
        margin-bottom: 10px;
    }

    .file-list {
        margin-top: 10px;
        text-align: left;
    }

    .file-item {
        background: #fff;
        padding: 8px 12px;
        border-radius: 5px;
        font-size: 0.9rem;
        margin-bottom: 5px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .file-item-remove {
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.8rem;
    }

    .checkbox-group {
        background: #f8f9ff;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #e0e4ff;
    }

    .checkbox-group .form-check {
        display: inline-block;
        margin-right: 15px;
        margin-bottom: 10px;
    }

    .checkbox-group .form-check-label {
        margin-bottom: 0;
        font-weight: 400;
    }

    .password-strength {
        height: 5px;
        border-radius: 3px;
        margin-top: 8px;
        background: #ddd;
    }

    .password-strength.weak {
        background: #dc3545;
    }

    .password-strength.fair {
        background: #ffc107;
    }

    .password-strength.strong {
        background: #28a745;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 12px 0;
        font-weight: 600;
        font-size: 1.05rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        margin-top: 30px;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    .intro-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .status-badge {
        background: #ffc107;
        color: #000;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .registration-container {
            padding: 20px 15px;
        }

        .registration-card .card-body {
            padding: 25px !important;
        }

        .checkbox-group .form-check {
            display: block;
            margin-right: 0;
            margin-bottom: 12px;
        }

        .form-section-title {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="registration-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card registration-card">
                    <div class="card-body p-4 p-lg-5">
                        <!-- Header Section -->
                        <div class="text-center mb-4">
                            <div class="intro-icon">🛁</div>
                            <h1 class="h2 mb-2"><strong>Bath Owner Registration</strong></h1>
                            <p class="text-muted">Professional registration for Hot Stone Bath Booking System</p>
                            <div class="mt-3">
                                <span class="status-badge">⏳ Pending Admin Approval</span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Main Form -->
                        <form id="ownerRegistrationForm" method="POST" action="{{ route('owner.register.submit') }}" enctype="multipart/form-data" novalidate class="needs-validation">
                            @csrf

                            <!-- SECTION 1: PERSONAL INFORMATION -->
                            <h5 class="form-section-title">👤 Personal Information</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name <span class="badge-required">*</span></label>
                                    <input type="text" name="owner_name" class="form-control @error('owner_name') is-invalid @enderror" 
                                        value="{{ old('owner_name') }}" placeholder="Enter your full name" required>
                                    @error('owner_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Contact Number <span class="badge-required">*</span></label>
                                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                        value="{{ old('phone') }}" placeholder="e.g., +975-17111111" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Address <span class="badge-required">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                        value="{{ old('email') }}" placeholder="your@email.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">CID / ID Number <span class="badge-required">*</span></label>
                                    <input type="text" name="cid" class="form-control @error('cid') is-invalid @enderror" 
                                        value="{{ old('cid') }}" placeholder="e.g., 00012345678912" required>
                                    @error('cid') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- SECTION 2: IDENTITY VERIFICATION -->
                            <h5 class="form-section-title">🆔 Identity Verification</h5>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Upload ID Proof <span class="badge-required">*</span></label>
                                    <div class="file-upload-area" id="idProofArea">
                                        <div class="file-upload-icon">📄</div>
                                        <p class="mb-1"><strong>Click or drag to upload ID proof</strong></p>
                                        <p class="text-muted small mb-0">Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                                        <input type="file" name="id_proof" id="idProofInput" class="d-none" accept=".pdf,.jpg,.jpeg,.png" required>
                                    </div>
                                    <div id="idProofFileList" class="file-list"></div>
                                    @error('id_proof') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- SECTION 3: BATH INFORMATION -->
                            <h5 class="form-section-title">🏢 Bath Information</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Bath Name <span class="badge-required">*</span></label>
                                    <input type="text" name="bath_name" class="form-control @error('bath_name') is-invalid @enderror" 
                                        value="{{ old('bath_name') }}" placeholder="e.g., Himalayan Wellness Center" required>
                                    @error('bath_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Bath Type <span class="badge-required">*</span></label>
                                    <select name="bath_type" class="form-select @error('bath_type') is-invalid @enderror" required>
                                        <option value="">Select Bath Type</option>
                                        <option value="menchu" @selected(old('bath_type') === 'menchu')>🛁 Menchu (Traditional Method)</option>
                                        <option value="dotsho" @selected(old('bath_type') === 'dotsho')>🔥 Dotsho (Hot Stone)</option>
                                        <option value="tshachu" @selected(old('bath_type') === 'tshachu')>💧 Tshachu (Hot Spring)</option>
                                    </select>
                                    @error('bath_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Dzongkhag (Location) <span class="badge-required">*</span></label>
                                    <select name="dzongkhag_id" class="form-select @error('dzongkhag_id') is-invalid @enderror" required>
                                        <option value="">Select Dzongkhag</option>
                                        @foreach($dzongkhags as $dzongkhag)
                                            <option value="{{ $dzongkhag->id }}" @selected((string)old('dzongkhag_id')===(string)$dzongkhag->id)>
                                                📍 {{ $dzongkhag->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('dzongkhag_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Full Address <span class="badge-required">*</span></label>
                                    <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                        value="{{ old('address') }}" placeholder="Complete street address" required>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Bath Description <span class="badge-required">*</span></label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                        rows="4" placeholder="Describe your bath facility, amenities, and experience..." required>{{ old('description') }}</textarea>
                                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Facilities (comma separated)</label>
                                    <input type="text" name="facilities" class="form-control @error('facilities') is-invalid @enderror" 
                                        value="{{ old('facilities', 'Changing room, Towels, Herbal bath') }}" 
                                        placeholder="e.g., Changing room, Towels, Herbal bath">
                                    @error('facilities') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- SECTION 4: OPERATIONAL DETAILS -->
                            <h5 class="form-section-title">⏰ Operational Details</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Opening Time <span class="badge-required">*</span></label>
                                    <input type="time" name="opening_time" class="form-control @error('opening_time') is-invalid @enderror" 
                                        value="{{ old('opening_time', '09:00') }}" required>
                                    @error('opening_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Closing Time <span class="badge-required">*</span></label>
                                    <input type="time" name="closing_time" class="form-control @error('closing_time') is-invalid @enderror" 
                                        value="{{ old('closing_time', '18:00') }}" required>
                                    @error('closing_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Available Days <span class="badge-required">*</span></label>
                                    <div class="checkbox-group">
                                        @php
                                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                            $shortDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                                            $selectedDays = old('available_days', $days);
                                        @endphp
                                        @foreach($days as $index => $day)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="available_days[]" 
                                                    value="{{ $day }}" id="day{{ $index }}"
                                                    @checked(in_array($day, (array)$selectedDays))>
                                                <label class="form-check-label" for="day{{ $index }}">
                                                    {{ $shortDays[$index] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('available_days') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- SECTION 5: PRICING & CAPACITY -->
                            <h5 class="form-section-title">💰 Pricing & Capacity</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Price per Session (Nu.) <span class="badge-required">*</span></label>
                                    <input type="number" name="price_per_session" class="form-control @error('price_per_session') is-invalid @enderror" 
                                        min="0" step="0.01" value="{{ old('price_per_session') }}" placeholder="e.g., 1000" required>
                                    @error('price_per_session') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Maximum Guests per Session <span class="badge-required">*</span></label>
                                    <input type="number" name="max_guests" class="form-control @error('max_guests') is-invalid @enderror" 
                                        min="1" value="{{ old('max_guests', 4) }}" required>
                                    @error('max_guests') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- SECTION 6: BATH IMAGES -->
                            <h5 class="form-section-title">📸 Bath Images</h5>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Upload Bath Images <span class="badge-required">*</span></label>
                                    <div class="file-upload-area" id="bathImagesArea">
                                        <div class="file-upload-icon">🖼️</div>
                                        <p class="mb-1"><strong>Click or drag to upload bath images</strong></p>
                                        <p class="text-muted small mb-0">Accepted formats: JPG, PNG (Max 10MB each, up to 5 images)</p>
                                        <input type="file" name="bath_images[]" id="bathImagesInput" class="d-none" accept="image/jpeg,image/png" multiple required>
                                    </div>
                                    <div id="bathImagesFileList" class="file-list"></div>
                                    @error('bath_images') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- SECTION 7: SECURITY -->
                            <h5 class="form-section-title">🔐 Security</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Password <span class="badge-required">*</span></label>
                                    <input type="password" id="passwordInput" name="password" class="form-control @error('password') is-invalid @enderror" 
                                        placeholder="At least 8 characters" required>
                                    <div class="password-strength" id="passwordStrength"></div>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password <span class="badge-required">*</span></label>
                                    <input type="password" id="passwordConfirmInput" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                        placeholder="Re-enter password" required>
                                    @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-submit w-100" id="submitBtn">
                                        📋 Submit Registration for Admin Approval
                                    </button>
                                </div>
                            </div>

                            <!-- Already registered -->
                            <div class="text-center mt-4">
                                <p class="text-muted">Already have an account? <a href="{{ route('owner.login') }}" class="text-decoration-none" style="color: #667eea; font-weight: 600;">Login here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ownerRegistrationForm');
    const passwordInput = document.getElementById('passwordInput');
    const passwordConfirmInput = document.getElementById('passwordConfirmInput');
    const passwordStrength = document.getElementById('passwordStrength');
    const submitBtn = document.getElementById('submitBtn');

    // File upload handlers - ID Proof
    setupFileUpload('idProofArea', 'idProofInput', 'idProofFileList', false);
    
    // File upload handlers - Bath Images
    setupFileUpload('bathImagesArea', 'bathImagesInput', 'bathImagesFileList', true);

    // Password strength indicator
    passwordInput.addEventListener('input', function() {
        const strength = calculatePasswordStrength(this.value);
        updatePasswordStrengthUI(strength);
    });

    // Validate password confirmation on blur
    passwordConfirmInput.addEventListener('blur', function() {
        validatePasswordMatch();
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity() || !validateForm()) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    function setupFileUpload(areaId, inputId, listId, isMultiple) {
        const area = document.getElementById(areaId);
        const input = document.getElementById(inputId);
        const fileList = document.getElementById(listId);
        let selectedFiles = [];

        area.addEventListener('click', () => input.click());

        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.classList.add('active');
        });

        area.addEventListener('dragleave', () => {
            area.classList.remove('active');
        });

        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.classList.remove('active');
            const files = Array.from(e.dataTransfer.files);
            handleFiles(files);
        });

        input.addEventListener('change', (e) => {
            const files = Array.from(e.target.files);
            handleFiles(files);
        });

        function handleFiles(files) {
            if (!isMultiple && files.length > 1) {
                alert('Please select only one file.');
                return;
            }

            if (isMultiple && files.length > 5) {
                alert('Maximum 5 images allowed.');
                return;
            }

            selectedFiles = isMultiple ? selectedFiles.concat(files) : files;
            
            if (selectedFiles.length > 5 && isMultiple) {
                selectedFiles = selectedFiles.slice(0, 5);
                alert('Maximum 5 images allowed. Extra images removed.');
            }

            updateFileListUI();
            updateFileInput();
        }

        function updateFileListUI() {
            fileList.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const item = document.createElement('div');
                item.className = 'file-item';
                item.innerHTML = `
                    <span>✓ ${file.name} (${(file.size / 1024).toFixed(2)} KB)</span>
                    <button type="button" class="file-item-remove" onclick="removeFile(${index}, '${areaId}', '${inputId}', '${listId}')">×</button>
                `;
                fileList.appendChild(item);
            });
        }

        window.removeFile = function(index, areaId, inputId, listId) {
            selectedFiles.splice(index, 1);
            updateFileListUI();
            updateFileInput();
        };

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            input.files = dataTransfer.files;
        }
    }

    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (password.length >= 12) strength++;
        if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^a-zA-Z0-9]/.test(password)) strength++;
        return Math.min(strength, 3);
    }

    function updatePasswordStrengthUI(strength) {
        passwordStrength.className = 'password-strength';
        if (strength === 1) {
            passwordStrength.classList.add('weak');
        } else if (strength === 2) {
            passwordStrength.classList.add('fair');
        } else if (strength >= 3) {
            passwordStrength.classList.add('strong');
        }
    }

    function validatePasswordMatch() {
        if (passwordInput.value && passwordConfirmInput.value) {
            if (passwordInput.value !== passwordConfirmInput.value) {
                passwordConfirmInput.classList.add('is-invalid');
            } else {
                passwordConfirmInput.classList.remove('is-invalid');
            }
        }
    }

    function validateForm() {
        // Validate password match
        if (passwordInput.value !== passwordConfirmInput.value) {
            passwordConfirmInput.classList.add('is-invalid');
            alert('Passwords do not match.');
            return false;
        }

        // Validate at least one day selected
        const daysChecked = form.querySelectorAll('input[name="available_days[]"]:checked').length;
        if (daysChecked === 0) {
            alert('Please select at least one available day.');
            return false;
        }

        return true;
    }
});
</script>
@endpush

@endsection
