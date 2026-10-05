@extends('layouts.app')

@section('title', 'Profile - ' . config('app.name'))

@section('content')
<div class="container-fluid profile-container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center align-items-center mb-3">
                <h1 class="h4 mb-0 text-dark fw-bold">
                    <i class="fas fa-user-edit me-2 text-primary"></i>Profile Settings
                </h1>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Profile Photo Section -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3 profile-card">
                <div class="card-header bg-gradient-primary text-white border-0 py-2">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-camera me-2"></i>Profile Photo
                    </h6>
                </div>
                <div class="card-body text-center p-3 d-flex flex-column justify-content-center">
                    <div class="profile-photo-container mb-3">
                        <div class="profile-photo-wrapper">
                            @if($user->profile_photo_path)
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" 
                                     alt="Profile Photo" class="profile-photo" id="profilePhoto">
                            @else
                                <div class="profile-photo-placeholder" id="profilePhotoPlaceholder">
                                    <i class="fas fa-user text-muted"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-primary btn-sm shadow-sm" onclick="document.getElementById('photoInput').click()">
                            <i class="fas fa-upload me-1"></i>Upload Photo
                        </button>
                        @if($user->profile_photo_path)
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeProfilePhoto()">
                                <i class="fas fa-trash me-1"></i>Remove Photo
                            </button>
                        @endif
                    </div>
                    
                    <input type="file" id="photoInput" accept="image/*" style="display: none;" onchange="handlePhotoUpload(event)">
                    
                    <div class="form-text mt-2">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Square image, max 2MB
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3 profile-card">
                <div class="card-header bg-gradient-primary text-white border-0 py-2">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-user me-2"></i>Personal Information
                    </h6>
                </div>
                <div class="card-body p-3">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm py-2 mb-3" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm py-2 mb-3" role="alert">
                            <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" class="d-flex flex-column">
                        @csrf
                        @method('PATCH')

                        <div class="row g-2">
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold text-dark small">Full Name</label>
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold text-dark small">Email Address</label>
                                <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="username" class="form-label fw-semibold text-dark small">Username</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="username" 
                                       value="{{ $user->username ?? 'Not set' }}" readonly>
                                <div class="form-text small">
                                    <i class="fas fa-lock me-1"></i>Username cannot be changed
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="role" class="form-label fw-semibold text-dark small">Role</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="role" 
                                       value="{{ $user->roles->first()->name ?? 'No role assigned' }}" readonly>
                                <div class="form-text small">
                                    <i class="fas fa-shield-alt me-1"></i>Role is managed by administrators
                                </div>
                            </div>

                            <div class="col-12">
                                <label for="created_at" class="form-label fw-semibold text-dark small">Member Since</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="created_at" 
                                       value="{{ $user->created_at->format('F j, Y') }}" readonly>
                            </div>

                            <div class="col-12">
                                <label for="last_login" class="form-label fw-semibold text-dark small">Last Login</label>
                                <input type="text" class="form-control form-control-sm bg-light" id="last_login" 
                                       value="{{ $user->last_login_at ? $user->last_login_at->format('F j, Y g:i A') : 'Never' }}" readonly>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <button type="submit" class="btn btn-primary btn-sm shadow-sm px-3">
                                <i class="fas fa-save me-1"></i>Update Profile
                            </button>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Security & Danger Zone -->
        <div class="col-md-4">
            <div class="card shadow-lg border-0 rounded-3 profile-card">
                <div class="card-header bg-gradient-warning text-dark border-0 py-2">
                    <h6 class="card-title mb-0 fw-semibold">
                        <i class="fas fa-shield-alt me-2"></i>Account Security
                    </h6>
                </div>
                <div class="card-body p-3 d-flex flex-column justify-content-between">
                    <div class="d-grid gap-2 mb-3">
                        <a href="{{ route('security.password') }}" class="btn btn-outline-primary btn-sm shadow-sm">
                            <i class="fas fa-key me-1"></i>Change Password
                        </a>
                        
                        <a href="{{ route('security.two-factor') }}" class="btn btn-outline-info btn-sm shadow-sm">
                            <i class="fas fa-mobile-alt me-1"></i>Two-Factor Authentication
                        </a>
                        
                        <a href="{{ route('security.login-history') }}" class="btn btn-outline-success btn-sm shadow-sm">
                            <i class="fas fa-history me-1"></i>Login History
                        </a>
                    </div>

                    <div class="border-top pt-3">
                        <h6 class="text-danger fw-semibold mb-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>Danger Zone
                        </h6>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Once you delete your account, there is no going back. Please be certain.
                        </p>
                        <button type="button" class="btn btn-outline-danger btn-sm w-100 shadow-sm" 
                                onclick="if(confirm('Are you sure you want to delete your account? This action cannot be undone.')) { document.getElementById('delete-account-form').submit(); }">
                            <i class="fas fa-trash me-1"></i>Delete Account
                        </button>
                        
                        <form id="delete-account-form" method="POST" action="{{ route('profile.destroy') }}" class="d-none">
                            @csrf
                            @method('DELETE')
                            <input type="password" name="password" placeholder="Enter your password to confirm" required>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Cropper Modal -->
<div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white border-0">
                <h5 class="modal-title fw-semibold" id="cropperModalLabel">
                    <i class="fas fa-crop me-2"></i>Crop Profile Photo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="cropper-container">
                    <img id="cropperImage" src="" alt="Crop Image" style="max-width: 100%;">
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-lg shadow-sm" onclick="cropAndSave()">
                    <i class="fas fa-check me-2"></i>Crop & Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include Cropper.js CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<style>
/* Single Screen Profile Layout */
.profile-container {
    height: auto;
    min-height: calc(100vh - 120px);
    padding: 1rem;
    overflow-y: visible;
    overflow-x: hidden;
    position: relative;
}

.profile-card {
    height: auto;
    min-height: 600px;
    display: flex;
    flex-direction: column;
}

.card {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
}

.card-header {
    border-bottom: none;
    padding: 0.75rem 1rem;
}

.card-body {
    padding: 1rem;
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: visible;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #162447 0%, #1f4068 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
}

/* Fix Success Message Z-Index */
.alert {
    border-radius: 8px;
    border: none;
    padding: 0.5rem 1rem;
    font-weight: 500;
    font-size: 0.875rem;
    z-index: 1050 !important;
    position: relative;
    max-width: 50%;
    width: 50%;
    overflow: hidden;
    margin: 0 auto;
    text-align: center;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
    color: #0c5460;
}

/* Compact Form Controls */
.form-control {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #162447;
    box-shadow: 0 0 0 0.15rem rgba(22, 36, 71, 0.25);
    transform: translateY(-1px);
}

.form-control-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

/* Compact Buttons */
.btn {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.btn-primary {
    background: linear-gradient(135deg, #162447 0%, #1f4068 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #1a2a4a 0%, #2a4f7a 100%);
}

/* Compact Profile Photo */
.profile-photo-wrapper {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #fff;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.profile-photo-wrapper:hover {
    border-color: #162447;
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(22, 36, 71, 0.2);
}

.profile-photo {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.profile-photo-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 50%;
    font-size: 2.5rem;
    color: #adb5bd;
}

/* Form Label Enhancements */
.form-label {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.25rem;
    font-size: 0.875rem;
}

/* Read-only Input Styling */
.form-control[readonly] {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #6c757d;
    cursor: not-allowed;
}

/* Fix SVG rendering issues */
.fas, .far, .fab {
    display: inline-block;
    font-style: normal;
    font-variant: normal;
    text-rendering: auto;
    line-height: 1;
    vertical-align: middle;
}

/* Cropper Modal Styles */
.cropper-container {
    max-height: 400px;
    overflow: hidden;
}

.modal-lg {
    max-width: 800px;
}

.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-container {
        height: auto;
        padding: 0.5rem;
    }
    
    .profile-card {
        height: auto;
        min-height: auto;
        margin-bottom: 1rem;
    }
    
    .profile-photo-wrapper {
        width: 80px;
        height: 80px;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }
}

/* Ensure alerts are above sidebar and contained within dashboard */
.alert {
    z-index: 9999 !important;
    position: relative;
    max-width: 50%;
    overflow: hidden;
    margin: 0 auto;
    border-radius: 8px;
    text-align: center;
}

/* Container to allow content visibility */
.profile-container {
    overflow: visible;
    position: relative;
}

.card-body {
    overflow: visible;
    position: relative;
}
</style>

<script>
let cropper = null;
let uploadedImage = null;

function handlePhotoUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Validate file type
    if (!file.type.startsWith('image/')) {
        showAlert('Please select a valid image file.', 'error');
        return;
    }

    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        showAlert('Image size must be less than 2MB.', 'error');
        return;
    }

    const reader = new FileReader();
    reader.onload = function(e) {
        uploadedImage = e.target.result;
        document.getElementById('cropperImage').src = uploadedImage;
        
        // Show cropper modal
        const modal = new bootstrap.Modal(document.getElementById('cropperModal'));
        modal.show();
        
        // Initialize cropper after modal is shown
        setTimeout(() => {
            initCropper();
        }, 300);
    };
    reader.readAsDataURL(file);
}

function initCropper() {
    const image = document.getElementById('cropperImage');
    
    if (cropper) {
        cropper.destroy();
    }
    
    cropper = new Cropper(image, {
        aspectRatio: 1,
        viewMode: 1,
        dragMode: 'move',
        autoCropArea: 1,
        restore: false,
        guides: true,
        center: true,
        highlight: false,
        cropBoxMovable: true,
        cropBoxResizable: true,
        toggleDragModeOnDblclick: false,
    });
}

function cropAndSave() {
    if (!cropper) return;
    
    const canvas = cropper.getCroppedCanvas({
        width: 300,
        height: 300,
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    canvas.toBlob(function(blob) {
        uploadCroppedImage(blob);
    }, 'image/jpeg', 0.9);
}

function uploadCroppedImage(blob) {
    const formData = new FormData();
    formData.append('profile_photo', blob, 'profile-photo.jpg');
    formData.append('_token', '{{ csrf_token() }}');
    
    fetch('{{ route("profile.photo.update") }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update profile photo display
            const profilePhoto = document.getElementById('profilePhoto');
            const placeholder = document.getElementById('profilePhotoPlaceholder');
            
            if (profilePhoto) {
                profilePhoto.src = data.photo_url + '?v=' + new Date().getTime();
            } else {
                placeholder.innerHTML = `<img src="${data.photo_url}?v=${new Date().getTime()}" alt="Profile Photo" class="profile-photo">`;
                placeholder.classList.remove('profile-photo-placeholder');
            }
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('cropperModal'));
            modal.hide();
            
            // Show success message
            showAlert('Profile photo updated successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to update profile photo.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while uploading the photo.', 'error');
    });
}

function removeProfilePhoto() {
    if (!confirm('Are you sure you want to remove your profile photo?')) return;
    
    fetch('{{ route("profile.photo.remove") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reset to placeholder
            const profilePhoto = document.getElementById('profilePhoto');
            const placeholder = document.getElementById('profilePhotoPlaceholder');
            
            if (profilePhoto) {
                profilePhoto.remove();
                placeholder.innerHTML = '<i class="fas fa-user text-muted"></i>';
                placeholder.classList.add('profile-photo-placeholder');
            }
            
            showAlert('Profile photo removed successfully!', 'success');
        } else {
            showAlert(data.message || 'Failed to remove profile photo.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('An error occurred while removing the photo.', 'error');
    });
}

function showAlert(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show border-0 shadow-sm" role="alert" style="z-index: 9999 !important; max-width: 50%; width: 50%; overflow: hidden; margin: 0 auto; text-align: center;">
            <i class="fas ${icon} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    // Insert alert at the top of the first card body
    const firstCardBody = document.querySelector('.card-body');
    firstCardBody.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alert = firstCardBody.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}

// Clean up cropper when modal is hidden
document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function () {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
});
</script>
@endsection
