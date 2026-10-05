@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">
            <!-- Main Card -->
            <div class="password-card">
                <div class="card-body p-4">
                    <!-- Page Title -->
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-primary mb-2">
                            <i class="fas fa-key me-2"></i>Change Password
                        </h4>
                        <p class="text-muted mb-0">Update your account password to keep it secure</p>
                    </div>

                    <!-- Alert Messages -->
                    <div id="alertContainer" style="display: none;">
                        <div id="alertBox" class="alert mb-3">
                            <i id="alertIcon" class="fas me-2"></i>
                            <span id="alertMessage"></span>
                        </div>
                    </div>

                    <form id="passwordForm">
                        @csrf

                        <!-- Current Password -->
                        <div class="form-group mb-3">
                            <label for="current_password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-2 text-muted"></i>Current Password
                            </label>
                            <input type="password"
                                   id="current_password"
                                   name="current_password"
                                   class="form-control"
                                   placeholder="Enter your current password"
                                   required>
                            <div class="invalid-feedback" id="current_password_error"></div>
                        </div>

                        <!-- New Password -->
                        <div class="form-group mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-key me-2 text-muted"></i>New Password
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Enter your new password"
                                   required>
                            <div class="invalid-feedback" id="password_error"></div>

                            <!-- Password Strength Meter -->
                            <div class="strength-meter mt-2">
                                <div class="strength-bar">
                                    <div id="strengthFill" class="strength-fill"></div>
                                </div>
                                <small id="strengthText" class="text-muted">Enter a password to see strength</small>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-check me-2 text-muted"></i>Confirm New Password
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="form-control"
                                   placeholder="Confirm your new password"
                                   required>
                            <div class="invalid-feedback" id="password_confirmation_error"></div>
                        </div>

                        <!-- Password Requirements -->
                        <div class="requirements-card mb-4">
                            <div class="requirements-header">
                                <i class="fas fa-list-check me-2"></i>
                                <span class="fw-semibold">Password Requirements</span>
                            </div>
                            <div class="requirements-list">
                                <div class="requirement" id="req-length">
                                    <i class="fas fa-circle"></i>
                                    <span>At least 8 characters long</span>
                                </div>
                                <div class="requirement" id="req-lowercase">
                                    <i class="fas fa-circle"></i>
                                    <span>Contains lowercase letters</span>
                                </div>
                                <div class="requirement" id="req-uppercase">
                                    <i class="fas fa-circle"></i>
                                    <span>Contains uppercase letters</span>
                                </div>
                                <div class="requirement" id="req-numbers">
                                    <i class="fas fa-circle"></i>
                                    <span>Contains numbers</span>
                                </div>
                                <div class="requirement" id="req-symbols">
                                    <i class="fas fa-circle"></i>
                                    <span>Contains special characters</span>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-secondary" onclick="goBack()">
                                <i class="fas fa-arrow-left me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="updateBtn">
                                <i class="fas fa-save me-1"></i>
                                <span id="updateBtnText">Update Password</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.password-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.form-label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control {
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fafafa;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    background: white;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-control.is-invalid {
    border-color: #dc2626;
    background: #fef2f2;
}

.invalid-feedback {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: none;
}

.requirements-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
}

.requirements-header {
    font-size: 0.95rem;
    color: #374151;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
}

.requirements-list {
    display: grid;
    gap: 0.5rem;
}

.requirement {
    display: flex;
    align-items: center;
    color: #6b7280;
    font-size: 0.875rem;
    transition: color 0.3s ease;
}

.requirement.met {
    color: #059669;
}

.requirement i {
    margin-right: 0.5rem;
    width: 12px;
    font-size: 0.75rem;
}

.requirement.met i {
    color: #059669;
}

.strength-meter {
    margin-top: 0.5rem;
}

.strength-bar {
    height: 6px;
    background: #e5e7eb;
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.strength-fill {
    height: 100%;
    transition: all 0.4s ease;
    border-radius: 3px;
}

.strength-very-weak { background: #dc2626; width: 20%; }
.strength-weak { background: #f59e0b; width: 40%; }
.strength-medium { background: #3b82f6; width: 60%; }
.strength-strong { background: #059669; width: 80%; }
.strength-very-strong { background: #10b981; width: 100%; }

.btn {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn i {
    font-size: 0.875rem;
}

.btn-primary {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    border: none;
    color: white;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
}

.btn-primary:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-secondary {
    background: #f8fafc;
    color: #6b7280;
    border: 1px solid #e5e7eb;
}

.btn-secondary:hover {
    background: #e5e7eb;
    color: #374151;
    border-color: #d1d5db;
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
    .d-flex.gap-2 {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }

    .requirements-list {
        font-size: 0.8rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('passwordForm');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const updateBtn = document.getElementById('updateBtn');

    // Password strength checking
    passwordInput.addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordRequirements(this.value);
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        updatePassword();
    });

    // Confirm password validation
    confirmPasswordInput.addEventListener('input', function() {
        validatePasswordConfirmation();
    });

    function checkPasswordStrength(password) {
        const strengthFill = document.getElementById('strengthFill');
        const strengthText = document.getElementById('strengthText');

        let score = 0;
        if (password.length >= 8) score += 20;
        if (password.length >= 12) score += 10;
        if (/[a-z]/.test(password)) score += 20;
        if (/[A-Z]/.test(password)) score += 20;
        if (/\d/.test(password)) score += 15;
        if (/[^a-zA-Z0-9]/.test(password)) score += 15;

        let className = 'strength-very-weak';
        let text = 'Very Weak';

        if (score >= 80) {
            className = 'strength-very-strong';
            text = 'Very Strong';
        } else if (score >= 60) {
            className = 'strength-strong';
            text = 'Strong';
        } else if (score >= 40) {
            className = 'strength-medium';
            text = 'Medium';
        } else if (score >= 20) {
            className = 'strength-weak';
            text = 'Weak';
        }

        strengthFill.className = 'strength-fill ' + className;
        strengthText.textContent = text;
    }

    function checkPasswordRequirements(password) {
        const requirements = {
            'req-length': password.length >= 8,
            'req-lowercase': /[a-z]/.test(password),
            'req-uppercase': /[A-Z]/.test(password),
            'req-numbers': /\d/.test(password),
            'req-symbols': /[^a-zA-Z0-9]/.test(password)
        };

        Object.keys(requirements).forEach(req => {
            const element = document.getElementById(req);
            const icon = element.querySelector('i');

            if (requirements[req]) {
                element.classList.add('met');
                icon.className = 'fas fa-check-circle';
            } else {
                element.classList.remove('met');
                icon.className = 'fas fa-circle';
            }
        });
    }

    function validatePasswordConfirmation() {
        const password = passwordInput.value;
        const confirmation = confirmPasswordInput.value;
        const errorDiv = document.getElementById('password_confirmation_error');

        if (confirmation && password !== confirmation) {
            confirmPasswordInput.classList.add('is-invalid');
            errorDiv.textContent = 'Passwords do not match';
            errorDiv.style.display = 'block';
        } else {
            confirmPasswordInput.classList.remove('is-invalid');
            errorDiv.style.display = 'none';
        }
    }

    async function updatePassword() {
        clearErrors();

        // Show loading state
        updateBtn.disabled = true;
        updateBtn.innerHTML = '<div class="spinner"></div>Updating Password...';

        const formData = new FormData(form);

        try {
            const response = await fetch('/security/password/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAlert('success', 'fa-check-circle', data.message || 'Password updated successfully!');
                form.reset();
                // Reset strength meter
                document.getElementById('strengthFill').className = 'strength-fill';
                document.getElementById('strengthText').textContent = 'Enter a password to see strength';
                // Reset requirements
                document.querySelectorAll('.requirement').forEach(req => {
                    req.classList.remove('met');
                    req.querySelector('i').className = 'fas fa-circle';
                });

                // Redirect after 3 seconds
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 3000);
            } else {
                if (data.errors) {
                    showValidationErrors(data.errors);
                } else {
                    showAlert('danger', 'fa-exclamation-triangle', data.message || 'Failed to update password');
                }
            }
        } catch (error) {
            showAlert('danger', 'fa-exclamation-triangle', 'An unexpected error occurred. Please try again.');
        } finally {
            // Reset button state
            updateBtn.disabled = false;
            updateBtn.innerHTML = '<i class="fas fa-save me-1"></i><span>Update Password</span>';
        }
    }

    function showAlert(type, icon, message) {
        const container = document.getElementById('alertContainer');
        const alertBox = document.getElementById('alertBox');
        const alertIcon = document.getElementById('alertIcon');
        const alertMessage = document.getElementById('alertMessage');

        alertBox.className = `alert alert-${type}`;
        alertIcon.className = `fas ${icon} me-2`;
        alertMessage.textContent = message;
        container.style.display = 'block';

        // Scroll to top to show alert
        container.scrollIntoView({ behavior: 'smooth' });

        // Auto-hide success messages
        if (type === 'success') {
            setTimeout(() => {
                container.style.display = 'none';
            }, 6000);
        }
    }

    function showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById(field + '_error');

            if (input && errorDiv) {
                input.classList.add('is-invalid');
                errorDiv.textContent = errors[field][0];
                errorDiv.style.display = 'block';
            }
        });
    }

    function clearErrors() {
        document.querySelectorAll('.is-invalid').forEach(input => {
            input.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.style.display = 'none';
        });
        document.getElementById('alertContainer').style.display = 'none';
    }

    window.goBack = function() {
        if (window.history.length > 1) {
            window.history.back();
        } else {
            window.location.href = '/dashboard';
        }
    };
});
</script>
@endsection
