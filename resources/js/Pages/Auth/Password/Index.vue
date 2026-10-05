<template>
  <AppLayout>
    <div class="password-settings">
      <div class="page-header">
        <h1>Change Password</h1>
        <p>Update your account password to keep it secure</p>
      </div>

      <div class="password-content">
        <div class="password-card">
          <div class="card-header">
            <h2>Password Requirements</h2>
            <i class="fas fa-key"></i>
          </div>

          <div class="card-body">
            <div class="requirements-list">
              <div class="requirement" :class="{ 'met': password.length >= 8 }">
                <i :class="password.length >= 8 ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                <span>At least 8 characters long</span>
              </div>
              <div class="requirement" :class="{ 'met': hasLowercase }">
                <i :class="hasLowercase ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                <span>Contains lowercase letters</span>
              </div>
              <div class="requirement" :class="{ 'met': hasUppercase }">
                <i :class="hasUppercase ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                <span>Contains uppercase letters</span>
              </div>
              <div class="requirement" :class="{ 'met': hasNumber }">
                <i :class="hasNumber ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                <span>Contains numbers</span>
              </div>
              <div class="requirement" :class="{ 'met': hasSpecial }">
                <i :class="hasSpecial ? 'fas fa-check-circle' : 'fas fa-circle'"></i>
                <span>Contains special characters</span>
              </div>
            </div>

            <form @submit.prevent="updatePassword" class="password-form">
              <div class="form-group">
                <label for="current_password">Current Password</label>
                <input
                  id="current_password"
                  v-model="form.current_password"
                  type="password"
                  class="form-control"
                  :class="{ 'is-invalid': errors.current_password }"
                  required
                >
                <div v-if="errors.current_password" class="invalid-feedback">
                  {{ errors.current_password }}
                </div>
              </div>

              <div class="form-group">
                <label for="password">New Password</label>
                <input
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="form-control"
                  :class="{ 'is-invalid': errors.password }"
                  required
                >
                <div v-if="errors.password" class="invalid-feedback">
                  {{ errors.password }}
                </div>
              </div>

              <div class="form-group">
                <label for="password_confirmation">Confirm New Password</label>
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  type="password"
                  class="form-control"
                  :class="{ 'is-invalid': errors.password_confirmation }"
                  required
                >
                <div v-if="errors.password_confirmation" class="invalid-feedback">
                  {{ errors.password_confirmation }}
                </div>
              </div>

              <div class="form-actions">
                <button type="button" class="btn-secondary" @click="cancel">
                  Cancel
                </button>
                <button type="submit" class="btn-primary" :disabled="loading">
                  <i v-if="loading" class="fas fa-spinner fa-spin"></i>
                  {{ loading ? 'Updating...' : 'Update Password' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Password Strength Indicator -->
        <div class="password-strength-card">
          <div class="card-header">
            <h2>Password Strength</h2>
            <i class="fas fa-shield-alt"></i>
          </div>

          <div class="card-body">
            <div class="strength-meter">
              <div class="strength-bar">
                <div
                  class="strength-fill"
                  :style="{ width: strengthPercentage + '%' }"
                  :class="strengthClass"
                ></div>
              </div>
              <div class="strength-label">{{ strengthText }}</div>
            </div>

            <div class="strength-tips">
              <h3>Tips for a strong password:</h3>
              <ul>
                <li>Use a mix of uppercase and lowercase letters</li>
                <li>Include numbers and special characters</li>
                <li>Make it at least 12 characters long</li>
                <li>Avoid common words or personal information</li>
                <li>Use a unique password for each account</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="success-message">
        <i class="fas fa-check-circle"></i>
        {{ successMessage }}
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="error-message">
        <i class="fas fa-exclamation-circle"></i>
        {{ errorMessage }}
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const form = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
});

const errors = ref({});
const loading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

// Computed properties
const password = computed(() => form.value.password);

const hasLength = computed(() => password.value.length >= 8);
const hasLowercase = computed(() => /[a-z]/.test(password.value));
const hasUppercase = computed(() => /[A-Z]/.test(password.value));
const hasNumber = computed(() => /\d/.test(password.value));
const hasSpecial = computed(() => /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password.value));

const strengthPercentage = computed(() => {
  let score = 0;
  if (password.value.length >= 8) score += 20;
  if (password.value.length >= 12) score += 20;
  if (/[a-z]/.test(password.value)) score += 15;
  if (/[A-Z]/.test(password.value)) score += 15;
  if (hasNumber.value) score += 15;
  if (hasSpecial.value) score += 15;
  return Math.min(score, 100);
});

const strengthClass = computed(() => {
  if (strengthPercentage.value >= 80) return 'very-strong';
  if (strengthPercentage.value >= 60) return 'strong';
  if (strengthPercentage.value >= 40) return 'medium';
  if (strengthPercentage.value >= 20) return 'weak';
  return 'very-weak';
});

const strengthText = computed(() => {
  if (strengthPercentage.value >= 80) return 'Very Strong';
  if (strengthPercentage.value >= 60) return 'Strong';
  if (strengthPercentage.value >= 40) return 'Medium';
  if (strengthPercentage.value >= 20) return 'Weak';
  return 'Very Weak';
});

// Methods
const updatePassword = async () => {
  loading.value = true;
  errors.value = {};
  successMessage.value = '';
  errorMessage.value = '';

  try {
    const response = await fetch('/security/password/update', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify(form.value)
    });

    const data = await response.json();

    if (!response.ok) {
      if (data.errors) {
        errors.value = data.errors;
      } else {
        errorMessage.value = data.message || 'Failed to update password';
      }
      return;
    }

    successMessage.value = data.message || 'Password updated successfully!';
    form.value = {
      current_password: '',
      password: '',
      password_confirmation: ''
    };

    // Redirect after success
    setTimeout(() => {
      router.visit('/profile');
    }, 2000);

  } catch (error) {
    errorMessage.value = 'An unexpected error occurred. Please try again.';
  } finally {
    loading.value = false;
  }
};

const cancel = () => {
  router.visit('/profile');
};

// Watch password for real-time validation
watch(password, () => {
  errors.value = {};
});
</script>

<style scoped>
.password-settings {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  margin-bottom: 30px;
  text-align: center;
}

.page-header h1 {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 10px;
  color: #1f2937;
}

.page-header p {
  font-size: 1.1rem;
  color: #6b7280;
}

.password-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
}

.password-card, .password-strength-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.card-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.card-header h2 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 0;
}

.card-header i {
  font-size: 1.5rem;
  opacity: 0.8;
}

.card-body {
  padding: 25px;
}

.requirements-list {
  margin-bottom: 25px;
}

.requirement {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 8px 0;
  color: #6b7280;
}

.requirement.met {
  color: #22c55e;
}

.requirement i {
  width: 16px;
  color: #d1d5db;
}

.requirement.met i {
  color: #22c55e;
}

.password-form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.form-control {
  padding: 12px 15px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.2s ease;
}

.form-control:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control.is-invalid {
  border-color: #ef4444;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.invalid-feedback {
  color: #ef4444;
  font-size: 0.875rem;
  margin-top: 5px;
}

.form-actions {
  display: flex;
  gap: 15px;
  justify-content: flex-end;
  margin-top: 10px;
}

.btn-primary, .btn-secondary {
  padding: 12px 24px;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-primary {
  background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px rgba(34, 197, 94, 0.3);
}

.btn-primary:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  transform: none;
  box-shadow: none;
}

.btn-secondary {
  background: #f3f4f6;
  color: #1f2937;
  border: 1px solid #d1d5db;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.strength-meter {
  margin-bottom: 20px;
}

.strength-bar {
  height: 12px;
  background: #e5e7eb;
  border-radius: 6px;
  overflow: hidden;
  margin-bottom: 10px;
}

.strength-fill {
  height: 100%;
  border-radius: 6px;
  transition: all 0.3s ease;
}

.strength-fill.very-weak {
  background: #ef4444;
  width: 20%;
}

.strength-fill.weak {
  background: #f97316;
  width: 40%;
}

.strength-fill.medium {
  background: #eab308;
  width: 60%;
}

.strength-fill.strong {
  background: #22c55e;
  width: 80%;
}

.strength-fill.very-strong {
  background: #16a34a;
  width: 100%;
}

.strength-label {
  font-weight: 600;
  text-align: center;
}

.strength-tips h3 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #1f2937;
}

.strength-tips ul {
  list-style-type: none;
  padding: 0;
}

.strength-tips li {
  padding: 5px 0;
  color: #6b7280;
  position: relative;
  padding-left: 20px;
}

.strength-tips li:before {
  content: '•';
  color: #667eea;
  position: absolute;
  left: 0;
}

.success-message, .error-message {
  position: fixed;
  bottom: 20px;
  right: 20px;
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 10px;
  z-index: 1000;
  max-width: 400px;
}

.success-message {
  background: #dcfce7;
  color: #166534;
}

.error-message {
  background: #fee2e2;
  color: #b91c1c;
}

/* Dark mode support */
.dark .password-settings {
  color: #f9fafb;
}

.dark .page-header h1 {
  color: #f9fafb;
}

.dark .password-card, .dark .password-strength-card {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .requirement {
  color: #d1d5db;
}

.dark .requirement.met {
  color: #4ade80;
}

.dark .form-group label {
  color: #f9fafb;
}

.dark .form-control {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.dark .btn-secondary {
  background: #374151;
  color: #f9fafb;
  border-color: #4b5563;
}

.dark .btn-secondary:hover {
  background: #4b5563;
}

.dark .strength-tips h3 {
  color: #f9fafb;
}

.dark .strength-tips li {
  color: #d1d5db;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .password-settings {
    padding: 15px;
  }

  .password-content {
    grid-template-columns: 1fr;
    gap: 20px;
  }

  .page-header h1 {
    font-size: 1.75rem;
  }

  .form-actions {
    flex-direction: column;
  }
}
</style>
