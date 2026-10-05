@extends('layouts.app')

@section('content')
<div class="container-fluid px-2 py-2">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-11 col-xl-10">
            <!-- Page Header -->
            <div class="text-center mb-3">
                <h4 class="fw-bold text-primary mb-2">
                    <i class="fas fa-shield-alt me-2"></i>Two-Factor Authentication
                </h4>
                <p class="text-muted mb-0">Add an extra layer of security to your account</p>
            </div>

            <div class="row g-3">
                <!-- Main Setup Card -->
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-cogs me-2"></i>Two-Factor Authentication Setup
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <!-- Alert Messages -->
                            <div id="alertContainer" style="display: none;">
                                <div id="alertBox" class="alert mb-3">
                                    <i id="alertIcon" class="fas me-2"></i>
                                    <span id="alertMessage"></span>
                                </div>
                            </div>

                            <!-- Two-Factor Status -->
                            <div id="statusSection" class="mb-3">
                                <div class="status-card enabled" id="enabledStatus" style="display: none;">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-check-circle status-icon text-success me-3 fs-4"></i>
                                        <div>
                                            <div class="fw-semibold text-success">Two-Factor Authentication is Enabled</div>
                                            <small class="text-muted">Your account is protected with an additional security layer</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="status-card disabled" id="disabledStatus">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-exclamation-triangle status-icon text-warning me-3 fs-4"></i>
                                        <div>
                                            <div class="fw-semibold text-warning">Two-Factor Authentication is Disabled</div>
                                            <small class="text-muted">Enable 2FA to add extra security to your account</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Setup Steps -->
                            <div class="setup-steps mb-3" id="setupSteps">
                                <div class="d-flex justify-content-center">
                                    <div class="step active me-4" id="step1">
                                        <div class="step-number">1</div>
                                        <div class="step-label">Choose Method</div>
                                    </div>
                                    <div class="step me-4" id="step2">
                                        <div class="step-number">2</div>
                                        <div class="step-label">Configure</div>
                                    </div>
                                    <div class="step" id="step3">
                                        <div class="step-number">3</div>
                                        <div class="step-label">Verify</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Method Selection -->
                            <div id="methodSelection" class="section">
                                <div class="section-title mb-3">
                                    <i class="fas fa-cog me-2"></i>
                                    <span class="fw-semibold">Choose your preferred 2FA method</span>
                                </div>

                                <form id="twoFactorForm">
                                    @csrf
                                    <div class="method-options mb-3">
                                        <div class="method-option mb-3">
                                            <input type="radio" id="method-app" name="method" value="app" checked>
                                            <label for="method-app" class="method-label">
                                                <div class="method-icon">
                                                    <i class="fab fa-google"></i>
                                                </div>
                                                <div class="method-details">
                                                    <h6 class="mb-1">Authenticator App</h6>
                                                    <p class="mb-2 text-muted">Use Google Authenticator, Authy, or similar apps for the most secure method</p>
                                                    <span class="badge bg-success">Recommended</span>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="method-option mb-3">
                                            <input type="radio" id="method-sms" name="method" value="sms">
                                            <label for="method-sms" class="method-label">
                                                <div class="method-icon">
                                                    <i class="fas fa-sms"></i>
                                                </div>
                                                <div class="method-details">
                                                    <h6 class="mb-1">SMS Text Message</h6>
                                                    <p class="mb-0 text-muted">Receive verification codes via SMS to your mobile phone</p>
                                                </div>
                                            </label>
                                        </div>

                                        <div class="method-option">
                                            <input type="radio" id="method-email" name="method" value="email">
                                            <label for="method-email" class="method-label">
                                                <div class="method-icon">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                                <div class="method-details">
                                                    <h6 class="mb-1">Email</h6>
                                                    <p class="mb-0 text-muted">Receive verification codes via email (less secure)</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <div id="additionalFields" class="additional-fields" style="display: none;">
                                        <div id="phoneField" class="mb-3" style="display: none;">
                                            <label for="phone" class="form-label">
                                                <i class="fas fa-phone me-2"></i>Phone Number
                                            </label>
                                            <input type="tel" id="phone" name="phone" class="form-control"
                                                   placeholder="+1 (555) 123-4567">
                                        </div>
                                        <div id="emailField" class="mb-3" style="display: none;">
                                            <label for="email" class="form-label">
                                                <i class="fas fa-envelope me-2"></i>Email Address
                                            </label>
                                            <input type="email" id="email" name="email" class="form-control"
                                                   value="{{ auth()->user()->email }}" readonly>
                                            <small class="text-muted">We'll use your account email address</small>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <button type="button" class="btn btn-primary" id="continueBtn" onclick="setupTwoFactor()">
                                            <i class="fas fa-arrow-right me-1"></i>
                                            Continue Setup
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- QR Code Section (for Authenticator App) -->
                            <div id="qrCodeSection" class="section" style="display: none;">
                                <div class="section-title mb-3">
                                    <i class="fas fa-qrcode me-2"></i>
                                    <span class="fw-semibold">Scan QR Code</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 text-center mb-3">
                                        <div class="qr-container">
                                            <div id="qrContainer" class="text-center">
                                                <div class="spinner-border" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-card">
                                            <h6 class="fw-semibold mb-2">Setup Instructions</h6>
                                            <ol class="small mb-0">
                                                <li>Install an authenticator app on your device</li>
                                                <li>Scan the QR code with your app</li>
                                                <li>Enter the 6-digit code from your app</li>
                                            </ol>
                                        </div>

                                        <div class="secret-key" id="secretKeyDisplay" style="display: none;">
                                            <h6 class="fw-semibold mb-2">Manual Entry Key</h6>
                                            <p class="small text-muted mb-1">If you can't scan the QR code, enter this key manually:</p>
                                            <code id="secretKeyText"></code>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="mb-3">
                                        <label for="verificationCode" class="form-label">
                                            <i class="fas fa-key me-2"></i>Verification Code
                                        </label>
                                        <input type="text" id="verificationCode" name="verificationCode"
                                               class="form-control" placeholder="000000" maxlength="6"
                                               autocomplete="off" inputmode="numeric">
                                        <small class="text-muted">Enter the 6-digit code from your authenticator app</small>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-secondary" onclick="goBackToMethodSelection()">
                                            <i class="fas fa-arrow-left me-1"></i>Back
                                        </button>
                                        <button type="button" class="btn btn-success" id="verifyBtn" onclick="verifyAndEnable()">
                                            <i class="fas fa-check me-1"></i>Verify & Enable
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Email/SMS Verification Section -->
                            <div id="emailSmsSection" class="section" style="display: none;">
                                <div class="section-title mb-3">
                                    <i class="fas fa-envelope me-2"></i>
                                    <span class="fw-semibold">Email Verification</span>
                                </div>

                                <div class="info-card mb-3">
                                    <p class="mb-2">We've sent a verification code to your email address.</p>
                                    <p class="mb-0 small text-muted">Please check your inbox and enter the 6-digit code below.</p>
                                </div>

                                <div class="mb-3">
                                    <label for="emailVerificationCode" class="form-label">
                                        <i class="fas fa-key me-2"></i>Verification Code
                                    </label>
                                    <input type="text" id="emailVerificationCode" name="emailVerificationCode"
                                           class="form-control" placeholder="000000" maxlength="6"
                                           autocomplete="off" inputmode="numeric">
                                    <small class="text-muted">Enter the 6-digit code from your email</small>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-secondary" onclick="goBackToMethodSelection()">
                                        <i class="fas fa-arrow-left me-1"></i>Back
                                    </button>
                                    <button type="button" class="btn btn-success" id="verifyEmailBtn" onclick="verifyEmailCode()">
                                        <i class="fas fa-check me-1"></i>Verify & Enable
                                    </button>
                                </div>
                            </div>

                            <!-- Enabled Section -->
                            <div id="enabledSection" class="section" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-card">
                                            <h6 class="fw-semibold text-success mb-2">
                                                <i class="fas fa-check-circle me-2"></i>2FA is Active
                                            </h6>
                                            <div class="mb-2">
                                                <strong>Method:</strong> <span id="currentMethod">-</span>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Enabled:</strong> <span id="enabledDate">-</span>
                                            </div>
                                            <div class="mb-0">
                                                <strong>Backup Codes:</strong> <span id="backupCodesCount">0</span> available
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-warning" onclick="regenerateBackupCodes()">
                                                <i class="fas fa-refresh me-1"></i>Regenerate Backup Codes
                                            </button>
                                            <button type="button" class="btn btn-outline-info" onclick="showBackupCodes()">
                                                <i class="fas fa-eye me-1"></i>View Backup Codes
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-3">

                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="text-danger fw-semibold mb-2">Disable Two-Factor Authentication</h6>
                                        <div class="row">
                                            <div class="col-md-4 mb-2">
                                                <input type="password" id="disablePassword" class="form-control"
                                                       placeholder="Your password">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <input type="text" id="disableCode" class="form-control"
                                                       placeholder="2FA Code" maxlength="6">
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-outline-danger w-100" onclick="disableTwoFactor()">
                                                    <i class="fas fa-times me-1"></i>Disable 2FA
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Information Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>About Two-Factor Authentication
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="small mb-3">Two-Factor Authentication (2FA) adds an extra layer of security to your account by requiring a second form of verification in addition to your password.</p>

                            <h6 class="fw-semibold mb-2">Benefits:</h6>
                            <ul class="small mb-3">
                                <li>Protects against password theft</li>
                                <li>Prevents unauthorized access</li>
                                <li>Meets compliance requirements</li>
                                <li>Provides peace of mind</li>
                            </ul>

                            <div class="alert alert-warning p-2">
                                <small>
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    <strong>Important:</strong> Save your backup codes in a secure location. You'll need them if you lose access to your authenticator device.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}

.status-card.enabled {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border-color: #10b981;
}

.status-card.disabled {
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%);
    border-color: #f59e0b;
}

.setup-steps .step {
    display: flex;
    flex-direction: column;
    align-items: center;
    opacity: 0.5;
    transition: opacity 0.3s ease;
}

.setup-steps .step.active {
    opacity: 1;
}

.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
    color: #6b7280;
}

.step.active .step-number {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: white;
}

.step-label {
    font-size: 0.75rem;
    font-weight: 500;
    text-align: center;
    color: #6b7280;
}

.step.active .step-label {
    color: #1e293b;
}

.section-title {
    font-size: 1rem;
    color: #374151;
    display: flex;
    align-items: center;
}

.method-options .method-option {
    position: relative;
}

.method-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.method-label {
    display: flex;
    align-items: center;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #fafafa;
}

.method-option input[type="radio"]:checked + .method-label {
    border-color: #2563eb;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(29, 78, 216, 0.05) 100%);
}

.method-icon {
    width: 48px;
    height: 48px;
    border-radius: 8px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.method-details h6 {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
}

.method-details p {
    font-size: 0.875rem;
    color: #6b7280;
}

.qr-container {
    padding: 1.5rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    border: 1px solid #e5e7eb;
}

.qr-code {
    width: 200px;
    height: 200px;
}

.secret-key code {
    background: #f3f4f6;
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 0.5rem 0.75rem;
    font-family: monospace;
    letter-spacing: 0.05em;
    color: #374151;
    word-break: break-all;
}

.info-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.form-control, .form-select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 0.5rem 0.75rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    color: white;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
}

.btn-secondary {
    background: #f8fafc;
    color: #6b7280;
    border: 1px solid #e5e7eb;
}

.btn-secondary:hover {
    background: #e5e7eb;
    color: #374151;
}

.btn-outline-warning {
    border-color: #f59e0b;
    color: #f59e0b;
}

.btn-outline-warning:hover {
    background: #f59e0b;
    color: white;
}

.btn-outline-danger {
    border-color: #dc2626;
    color: #dc2626;
}

.btn-outline-danger:hover {
    background: #dc2626;
    color: white;
}

.alert {
    border-radius: 8px;
    border: none;
    padding: 0.75rem 1rem;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.alert-success {
    background: #ecfdf5;
    color: #065f46;
    border-left: 3px solid #10b981;
}

.alert-warning {
    background: #fef3c7;
    color: #92400e;
    border-left: 3px solid #f59e0b;
}

.alert-danger {
    background: #fef2f2;
    color: #991b1b;
    border-left: 3px solid #dc2626;
}

.spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 0.5rem;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .method-label {
        flex-direction: column;
        text-align: center;
        padding: 1rem 0.75rem;
    }

    .method-icon {
        margin-right: 0;
        margin-bottom: 0.75rem;
    }

    .d-flex.gap-2 {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .setup-steps .d-flex {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .setup-steps .step {
        flex-direction: row;
        gap: 0.75rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    let secretKey = '';
    let backupCodes = [];

    // Setup event listeners
    setupEventListeners();
    checkTwoFactorStatus();

    function setupEventListeners() {
        // Method selection change
        document.querySelectorAll('input[name="method"]').forEach(radio => {
            radio.addEventListener('change', handleMethodChange);
        });
    }

    function handleMethodChange(event) {
        const selectedMethod = event.target.value;
        const additionalFields = document.getElementById('additionalFields');
        const phoneField = document.getElementById('phoneField');
        const emailField = document.getElementById('emailField');

        // Hide all additional fields first
        additionalFields.style.display = 'none';
        phoneField.style.display = 'none';
        emailField.style.display = 'none';

        // Show relevant fields based on selection
        if (selectedMethod === 'sms') {
            additionalFields.style.display = 'block';
            phoneField.style.display = 'block';
        } else if (selectedMethod === 'email') {
            additionalFields.style.display = 'block';
            emailField.style.display = 'block';
        }
    }

    function checkTwoFactorStatus() {
        console.log('Checking two-factor status...');
        fetch('/security/two-factor/status')
            .then(response => {
                console.log('Status response:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Status data:', data);
                if (data.enabled) {
                    showEnabledState(data);
                } else {
                    showSetupState();
                }
            })
            .catch(error => {
                console.error('Status check failed:', error);
                showAlert('danger', 'fas fa-exclamation-triangle', 'Failed to load two-factor status');
                showSetupState();
            });
    }

    function showSetupState() {
        document.getElementById('enabledStatus').style.display = 'none';
        document.getElementById('disabledStatus').style.display = 'block';
        document.getElementById('setupSteps').style.display = 'block';
        document.getElementById('methodSelection').style.display = 'block';
        document.getElementById('qrCodeSection').style.display = 'none';
        document.getElementById('emailSmsSection').style.display = 'none';
        document.getElementById('enabledSection').style.display = 'none';
        updateSteps(1);
    }

    function showEnabledState(data) {
        document.getElementById('enabledStatus').style.display = 'block';
        document.getElementById('disabledStatus').style.display = 'none';
        document.getElementById('setupSteps').style.display = 'none';
        document.getElementById('methodSelection').style.display = 'none';
        document.getElementById('qrCodeSection').style.display = 'none';
        document.getElementById('emailSmsSection').style.display = 'none';
        document.getElementById('enabledSection').style.display = 'block';

        // Update information
        document.getElementById('currentMethod').textContent = data.method_display || 'Authenticator App';
        document.getElementById('enabledDate').textContent = data.enabled_date || 'Unknown';
        document.getElementById('backupCodesCount').textContent = data.backup_codes_count || '0';
    }

    function updateSteps(activeStep) {
        currentStep = activeStep;
        document.querySelectorAll('.step').forEach((step, index) => {
            step.classList.toggle('active', index + 1 <= activeStep);
        });
    }

    // Global functions
    window.setupTwoFactor = function() {
        const selectedMethod = document.querySelector('input[name="method"]:checked').value;
        const continueBtn = document.getElementById('continueBtn');
        const formData = { method: selectedMethod };

        // Add additional data based on method
        if (selectedMethod === 'sms') {
            const phone = document.getElementById('phone').value;
            if (!phone) {
                showAlert('danger', 'fas fa-exclamation-triangle', 'Please enter your phone number');
                return;
            }
            formData.phone = phone;
        } else if (selectedMethod === 'email') {
            // Use the user's account email - no need for validation since it's readonly
            formData.email = document.getElementById('email').value;
        }

        // Show loading state
        continueBtn.disabled = true;
        continueBtn.innerHTML = '<div class="spinner"></div>Setting up...';

        fetch('/security/two-factor/enable', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(formData)
        })
        .then(response => {
            console.log('Enable response:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Enable data:', data);
            if (data.success) {
                if (selectedMethod === 'app' && data.qr_code) {
                    showQRCodeSection(data);
                } else if (selectedMethod === 'email' || selectedMethod === 'sms') {
                    showEmailSmsSection(selectedMethod);
                } else {
                    showAlert('success', 'fas fa-check-circle',
                        '2FA setup initiated. Please check your ' + (selectedMethod === 'sms' ? 'phone' : 'email'));
                }
            } else {
                showAlert('danger', 'fas fa-exclamation-triangle', data.message || 'Failed to setup 2FA');
            }
        })
        .catch(error => {
            console.error('Setup error:', error);
            showAlert('danger', 'fas fa-exclamation-triangle', 'An error occurred during setup: ' + error.message);
        })
        .finally(() => {
            continueBtn.disabled = false;
            continueBtn.innerHTML = '<i class="fas fa-arrow-right me-1"></i>Continue Setup';
        });
    };

    function showQRCodeSection(data) {
        document.getElementById('methodSelection').style.display = 'none';
        document.getElementById('qrCodeSection').style.display = 'block';

        // Display QR code
        if (data.qr_code) {
            document.getElementById('qrContainer').innerHTML = `<img src="${data.qr_code}" alt="QR Code" class="qr-code">`;
        } else {
            document.getElementById('qrContainer').innerHTML = '<p class="text-danger">Failed to generate QR code</p>';
            console.error('No QR code in response');
        }

        if (data.secret_key) {
            document.getElementById('secretKeyText').textContent = data.secret_key;
            document.getElementById('secretKeyDisplay').style.display = 'block';
        } else {
            console.error('No secret key in response');
        }

        backupCodes = data.backup_codes || [];
        secretKey = data.secret_key;

        updateSteps(2);
    }

    function showEmailSmsSection(method) {
        document.getElementById('methodSelection').style.display = 'none';
        document.getElementById('emailSmsSection').style.display = 'block';

        // Update the section title based on method
        const sectionTitle = document.querySelector('#emailSmsSection .section-title span');
        if (method === 'sms') {
            sectionTitle.innerHTML = '<i class="fas fa-sms me-2"></i>SMS Verification';
        } else {
            sectionTitle.innerHTML = '<i class="fas fa-envelope me-2"></i>Email Verification';
        }

        updateSteps(2);

        // Show success message
        showAlert('success', 'fas fa-check-circle',
            `Verification code sent to your ${method === 'sms' ? 'phone' : 'email'}. Please check and enter the code.`);
    }

    window.goBackToMethodSelection = function() {
        document.getElementById('qrCodeSection').style.display = 'none';
        document.getElementById('emailSmsSection').style.display = 'none';
        document.getElementById('methodSelection').style.display = 'block';
        updateSteps(1);
    };

    window.verifyAndEnable = function() {
        const code = document.getElementById('verificationCode').value;
        const verifyBtn = document.getElementById('verifyBtn');

        if (!code || code.length !== 6) {
            showAlert('danger', 'fas fa-exclamation-triangle', 'Please enter a valid 6-digit code');
            return;
        }

        // Show loading state
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<div class="spinner"></div>Verifying...';

        fetch('/security/two-factor/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => {
            console.log('Verify response:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Verify data:', data);
            if (data.success) {
                updateSteps(3);
                showAlert('success', 'fas fa-check-circle',
                    data.message || '2FA enabled successfully!');

                // Show enabled state after delay
                setTimeout(() => {
                    showEnabledState({
                        method_display: 'Authenticator App',
                        enabled_date: 'Just now',
                        backup_codes_count: backupCodes.length
                    });
                }, 2000);
            } else {
                showAlert('danger', 'fas fa-exclamation-triangle',
                    data.message || 'Invalid verification code');
            }
        })
        .catch(error => {
            console.error('Verification error:', error);
            showAlert('danger', 'fas fa-exclamation-triangle',
                'An error occurred during verification: ' + error.message);
        })
        .finally(() => {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Verify & Enable';
        });
    };

    window.verifyEmailCode = function() {
        const code = document.getElementById('emailVerificationCode').value;
        const verifyBtn = document.getElementById('verifyEmailBtn');

        if (!code || code.length !== 6) {
            showAlert('danger', 'fas fa-exclamation-triangle', 'Please enter a valid 6-digit code');
            return;
        }

        // Show loading state
        verifyBtn.disabled = true;
        verifyBtn.innerHTML = '<div class="spinner"></div>Verifying...';

        fetch('/security/two-factor/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ code: code })
        })
        .then(response => {
            console.log('Email verify response:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Email verify data:', data);
            if (data.success) {
                updateSteps(3);
                showAlert('success', 'fas fa-check-circle',
                    data.message || '2FA enabled successfully!');

                // Show enabled state after delay
                setTimeout(() => {
                    showEnabledState({
                        method_display: 'Email',
                        enabled_date: 'Just now',
                        backup_codes_count: 10
                    });
                }, 2000);
            } else {
                showAlert('danger', 'fas fa-exclamation-triangle',
                    data.message || 'Invalid verification code');
            }
        })
        .catch(error => {
            console.error('Email verification error:', error);
            showAlert('danger', 'fas fa-exclamation-triangle',
                'An error occurred during verification: ' + error.message);
        })
        .finally(() => {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Verify & Enable';
        });
    };

    window.showBackupCodes = function() {
        if (backupCodes.length === 0) {
            // Try to fetch current backup codes
            fetch('/security/two-factor/status')
                .then(response => response.json())
                .then(data => {
                    if (data.enabled && data.backup_codes_count > 0) {
                        showAlert('info', 'fas fa-info-circle',
                            `You have ${data.backup_codes_count} backup codes available. Contact administrator to view them.`);
                    } else {
                        showAlert('warning', 'fas fa-exclamation-triangle',
                            'No backup codes available. Please regenerate them.');
                    }
                })
                .catch(() => {
                    showAlert('warning', 'fas fa-exclamation-triangle',
                        'No backup codes available. Please regenerate them.');
                });
            return;
        }

        // Create a modal-like display for backup codes
        const codesDisplay = backupCodes.map((code, index) =>
            `${(index + 1).toString().padStart(2, '0')}. ${code}`
        ).join('\n');

        const message = `BACKUP CODES - SAVE THESE SECURELY:\n\n${codesDisplay}\n\n⚠️  Each code can only be used once\n⚠️  Save them in a secure location`;

        alert(message);
    };

    window.regenerateBackupCodes = function() {
        if (!confirm('Are you sure you want to regenerate backup codes? Old codes will no longer work.')) {
            return;
        }

        fetch('/security/two-factor/regenerate-codes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                backupCodes = data.backup_codes || [];
                document.getElementById('backupCodesCount').textContent = backupCodes.length;
                showAlert('success', 'fas fa-check-circle', 'Backup codes regenerated successfully');

                // Auto-show the new codes
                setTimeout(() => {
                    window.showBackupCodes();
                }, 1000);
            } else {
                showAlert('danger', 'fas fa-exclamation-triangle', data.message || 'Failed to regenerate codes');
            }
        })
        .catch(error => {
            console.error('Regenerate codes error:', error);
            showAlert('danger', 'fas fa-exclamation-triangle', 'An error occurred while regenerating codes: ' + error.message);
        });
    };

    window.disableTwoFactor = function() {
        const password = document.getElementById('disablePassword').value;
        const code = document.getElementById('disableCode').value;

        if (!password) {
            showAlert('danger', 'fas fa-exclamation-triangle', 'Please enter your password');
            return;
        }

        if (!code) {
            showAlert('danger', 'fas fa-exclamation-triangle', 'Please enter your 2FA code');
            return;
        }

        if (!confirm('Are you sure you want to disable two-factor authentication? This will make your account less secure.')) {
            return;
        }

        fetch('/security/two-factor/disable', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({
                password: password,
                code: code
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showAlert('success', 'fas fa-check-circle', data.message || '2FA disabled successfully');

                // Clear the form
                document.getElementById('disablePassword').value = '';
                document.getElementById('disableCode').value = '';

                // Show setup state after delay
                setTimeout(() => {
                    showSetupState();
                }, 1500);
            } else {
                showAlert('danger', 'fas fa-exclamation-triangle', data.message || 'Failed to disable 2FA');
            }
        })
        .catch(error => {
            console.error('Disable 2FA error:', error);
            showAlert('danger', 'fas fa-exclamation-triangle', 'An error occurred while disabling 2FA: ' + error.message);
        });
    };

    function showAlert(type, icon, message) {
        const alertContainer = document.getElementById('alertContainer');
        const alertBox = document.getElementById('alertBox');
        const alertIcon = document.getElementById('alertIcon');
        const alertMessage = document.getElementById('alertMessage');

        // Set alert type
        alertBox.className = `alert alert-${type} mb-3`;
        alertIcon.className = `${icon} me-2`;
        alertMessage.textContent = message;

        // Show alert
        alertContainer.style.display = 'block';

        // Auto-hide success alerts after 5 seconds
        if (type === 'success') {
            setTimeout(() => {
                alertContainer.style.display = 'none';
            }, 5000);
        }

        // Scroll to alert
        alertContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // Add input event listeners for better UX
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-format verification code inputs
        const codeInputs = ['verificationCode', 'emailVerificationCode', 'disableCode'];
        codeInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('input', function(e) {
                    // Only allow digits
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');

                    // Auto-submit when 6 digits are entered (for verification codes only)
                    if (e.target.value.length === 6 && inputId.includes('verification')) {
                        if (inputId === 'emailVerificationCode') {
                            setTimeout(() => window.verifyEmailCode(), 100);
                        } else if (inputId === 'verificationCode') {
                            setTimeout(() => window.verifyAndEnable(), 100);
                        }
                    }
                });

                // Prevent paste of non-numeric content
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const numericPaste = paste.replace(/[^0-9]/g, '').substring(0, 6);
                    e.target.value = numericPaste;

                    // Trigger input event
                    e.target.dispatchEvent(new Event('input'));
                });
            }
        });
    });

    // Initialize CSRF token for all fetch requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');

    if (csrfToken) {
        // Set default headers for all fetch requests
        const originalFetch = window.fetch;
        window.fetch = function(url, options = {}) {
            options.headers = {
                'X-CSRF-TOKEN': csrfToken,
                ...options.headers
            };
            return originalFetch(url, options);
        };
        console.log('CSRF token configured for all requests');
    } else {
        console.error('CSRF token not found in meta tags');
        showAlert('danger', 'fas fa-exclamation-triangle', 'Security token missing. Please refresh the page.');
    }

});
</script>
@endsection
