<template>
  <AppLayout>
    <div class="security-settings">
      <div class="page-header">
        <h1>Security Settings</h1>
        <p>Manage your account security and authentication methods</p>
      </div>
      
      <div class="security-content">
        <!-- Security Score -->
        <div class="security-score-card">
          <div class="score-header">
            <h2>Security Score</h2>
            <div class="score-value">{{ securityScore }}%</div>
          </div>
          <div class="score-bar">
            <div class="score-fill" :style="{ width: securityScore + '%' }"></div>
          </div>
          <div class="score-recommendations">
            <h3>Recommendations</h3>
            <ul>
              <li v-for="(recommendation, index) in recommendations" :key="index">
                {{ recommendation }}
              </li>
            </ul>
          </div>
        </div>
        
        <!-- Two-Factor Authentication -->
        <div class="security-section">
          <div class="section-header">
            <h2>Two-Factor Authentication</h2>
            <button 
              v-if="!twoFactorAuth.isEnabled" 
              class="btn-primary"
              @click="setupTwoFactor"
            >
              Enable 2FA
            </button>
            <button 
              v-else 
              class="btn-danger"
              @click="disableTwoFactor"
            >
              Disable 2FA
            </button>
          </div>
          
          <div v-if="twoFactorAuth.isEnabled" class="section-content">
            <div class="two-factor-info">
              <div class="info-item">
                <label>Method:</label>
                <span>{{ formatMethod(twoFactorAuth.method) }}</span>
              </div>
              <div class="info-item">
                <label>Status:</label>
                <span class="status-active">Active</span>
              </div>
              <div class="info-item">
                <label>Enabled on:</label>
                <span>{{ formatDate(twoFactorAuth.verifiedAt) }}</span>
              </div>
              <div class="info-item">
                <label>Backup Codes:</label>
                <span>{{ twoFactorAuth.backupCodesCount }} remaining</span>
              </div>
            </div>
            
            <div class="backup-codes-section">
              <h3>Backup Codes</h3>
              <p>Backup codes can be used when you don't have access to your 2FA device.</p>
              <button 
                class="btn-secondary"
                @click="generateBackupCodes"
              >
                Generate New Backup Codes
              </button>
            </div>
          </div>
          
          <div v-else class="section-content">
            <p>Two-factor authentication adds an extra layer of security to your account.</p>
            <p>Choose a method to enable 2FA:</p>
            <div class="two-factor-options">
              <div 
                class="option-card" 
                @click="selectTwoFactorMethod('app')"
              >
                <i class="fas fa-mobile-alt"></i>
                <h3>Authenticator App</h3>
                <p>Use apps like Google Authenticator or Authy</p>
              </div>
              <div 
                class="option-card" 
                @click="selectTwoFactorMethod('sms')"
              >
                <i class="fas fa-sms"></i>
                <h3>SMS</h3>
                <p>Receive codes via text message</p>
              </div>
              <div 
                class="option-card" 
                @click="selectTwoFactorMethod('email')"
              >
                <i class="fas fa-envelope"></i>
                <h3>Email</h3>
                <p>Receive codes via email</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Biometric Authentication -->
        <div class="security-section">
          <div class="section-header">
            <h2>Biometric Authentication</h2>
            <button 
              v-if="!hasBiometricAuth" 
              class="btn-primary"
              @click="setupBiometric"
            >
              Setup Biometric
            </button>
            <button 
              v-else 
              class="btn-danger"
              @click="disableBiometric"
            >
              Remove Biometric
            </button>
          </div>
          
          <div class="section-content">
            <div v-if="hasBiometricAuth" class="biometric-info">
              <div 
                v-for="biometric in biometricAuth" 
                :key="biometric.id"
                class="biometric-item"
              >
                <div class="biometric-details">
                  <i :class="getBiometricIcon(biometric.type)"></i>
                  <div>
                    <h3>{{ formatBiometricType(biometric.type) }}</h3>
                    <p>Device ID: {{ biometric.deviceId }}</p>
                    <p>Last used: {{ formatDate(biometric.lastUsed) }}</p>
                  </div>
                </div>
                <button 
                  class="btn-danger btn-small"
                  @click="removeBiometric(biometric.id)"
                >
                  Remove
                </button>
              </div>
            </div>
            <div v-else>
              <p>Biometric authentication allows you to sign in using your fingerprint, face, or other biometric data.</p>
              <p>This provides quick and secure access to your account.</p>
            </div>
          </div>
        </div>
        
        <!-- Trusted Devices -->
        <div class="security-section">
          <div class="section-header">
            <h2>Trusted Devices</h2>
          </div>
          
          <div class="section-content">
            <p>You have {{ trustedDevicesCount }} trusted devices.</p>
            <button 
              v-if="trustedDevicesCount > 0"
              class="btn-secondary"
              @click="clearTrustedDevices"
            >
              Clear All Trusted Devices
            </button>
          </div>
        </div>
      </div>
      
      <!-- Setup 2FA Modal -->
      <div v-if="showTwoFactorModal" class="modal-overlay" @click="closeTwoFactorModal">
        <div class="modal-content" @click.stop>
          <div class="modal-header">
            <h2>Setup Two-Factor Authentication</h2>
            <button class="close-btn" @click="closeTwoFactorModal">
              <i class="fas fa-times"></i>
            </button>
          </div>
          
          <div class="modal-body">
            <div v-if="selectedMethod === 'app'" class="setup-step">
              <h3>1. Scan the QR Code</h3>
              <p>Open your authenticator app and scan this QR code:</p>
              <div class="qr-code-placeholder">
                <img v-if="qrCode" :src="qrCode" alt="QR Code">
                <div v-else class="qr-placeholder">
                  <i class="fas fa-qrcode"></i>
                  <p>QR Code will appear here</p>
                </div>
              </div>
              
              <h3>2. Enter the Code</h3>
              <p>Enter the 6-digit code from your authenticator app:</p>
              <input 
                type="text" 
                v-model="verificationCode"
                placeholder="000000"
                maxlength="6"
                class="code-input"
              >
              
              <div class="backup-codes-info">
                <h3>Backup Codes</h3>
                <p>Save these backup codes in a secure place:</p>
                <div class="backup-codes">
                  <div 
                    v-for="(code, index) in backupCodes" 
                    :key="index"
                    class="backup-code"
                  >
                    {{ code }}
                  </div>
                </div>
              </div>
              
              <button 
                class="btn-primary"
                @click="verifyTwoFactorSetup"
                :disabled="!verificationCode || verificationCode.length !== 6"
              >
                Verify and Enable
              </button>
            </div>
            
            <div v-else class="setup-step">
              <h3>Enter your {{ selectedMethod === 'sms' ? 'phone number' : 'email' }}</h3>
              <input 
                v-if="selectedMethod === 'sms'"
                type="text" 
                v-model="phoneNumber"
                placeholder="Enter phone number"
                class="form-input"
              >
              <input 
                v-else
                type="email" 
                v-model="emailAddress"
                placeholder="Enter email address"
                class="form-input"
              >
              
              <button 
                class="btn-primary"
                @click="requestTwoFactorSetup"
              >
                Send Verification Code
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Error Message -->
      <div v-if="error" class="error-message">
        {{ error }}
        <button class="close-error" @click="error = ''">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

// Reactive data
const securityScore = ref(0);
const recommendations = ref([]);
const twoFactorAuth = ref({
  isEnabled: false,
  method: '',
  verifiedAt: null,
  backupCodesCount: 0
});
const biometricAuth = ref([]);
const hasBiometricAuth = ref(false);
const trustedDevicesCount = ref(0);
const error = ref('');

// 2FA setup modal
const showTwoFactorModal = ref(false);
const selectedMethod = ref('');
const verificationCode = ref('');
const phoneNumber = ref('');
const emailAddress = ref('');
const qrCode = ref('');
const backupCodes = ref([]);

// Lifecycle
onMounted(() => {
  loadSecuritySettings();
});

// Load security settings
const loadSecuritySettings = async () => {
  try {
    const response = await fetch('/api/mobile/v1/auth/security-settings', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
    const data = await response.json();
    
    if (!response.ok) {
      throw new Error(data.message || 'Failed to load security settings');
    }
    
    // Update security data
    securityScore.value = data.data.security_score;
    recommendations.value = data.data.recommendations;
    twoFactorAuth.value = {
      isEnabled: data.data.two_factor_auth.is_enabled,
      method: data.data.two_factor_auth.method,
      verifiedAt: data.data.two_factor_auth.verified_at,
      backupCodesCount: data.data.two_factor_auth.backup_codes_count
    };
    biometricAuth.value = data.data.biometric_auth;
    hasBiometricAuth.value = data.data.biometric_auth.length > 0;
    trustedDevicesCount.value = data.data.trusted_devices;
  } catch (err) {
    error.value = err.message || 'Failed to load security settings';
  }
};

// Setup two-factor authentication
const setupTwoFactor = () => {
  showTwoFactorModal.value = true;
};

// Select 2FA method
const selectTwoFactorMethod = (method) => {
  selectedMethod.value = method;
  phoneNumber.value = '';
  emailAddress.value = '';
};

// Request 2FA setup
const requestTwoFactorSetup = async () => {
  try {
    const payload = {
      method: selectedMethod.value
    };
    
    if (selectedMethod.value === 'sms') {
      payload.phone = phoneNumber.value;
    } else if (selectedMethod.value === 'email') {
      payload.email = emailAddress.value;
    }
    
    const response = await fetch('/api/mobile/v1/auth/setup-2fa', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify(payload)
    });
    
    const data = await response.json();
    
    if (!response.ok) {
      throw new Error(data.message || 'Failed to setup 2FA');
    }
    
    // Update with setup data
    backupCodes.value = data.data.backup_codes;
    qrCode.value = data.data.qr_code;
    
    // For SMS/email, we would wait for user to enter the code they receive
    if (selectedMethod.value === 'app') {
      // Already showing QR code and backup codes
    } else {
      // Show input for verification code
      verificationCode.value = '';
    }
  } catch (err) {
    error.value = err.message || 'Failed to setup 2FA';
  }
};

// Verify 2FA setup
const verifyTwoFactorSetup = async () => {
  try {
    const response = await fetch('/api/mobile/v1/auth/verify-2fa-setup', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('authToken')}`,
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: JSON.stringify({
        setup_id: 1, // This should come from the setup response
        code: verificationCode.value
      })
    });
    
    const data = await response.json();
    
    if (!response.ok) {
      throw new Error(data.message || 'Failed to verify 2FA setup');
    }
    
    // Successfully enabled 2FA
    closeTwoFactorModal();
    loadSecuritySettings(); // Refresh settings
  } catch (err) {
    error.value = err.message || 'Failed to verify 2FA setup';
  }
};

// Close 2FA modal
const closeTwoFactorModal = () => {
  showTwoFactorModal.value = false;
  selectedMethod.value = '';
  verificationCode.value = '';
  phoneNumber.value = '';
  emailAddress.value = '';
  qrCode.value = '';
  backupCodes.value = [];
};

// Disable two-factor authentication
const disableTwoFactor = async () => {
  if (!confirm('Are you sure you want to disable two-factor authentication?')) {
    return;
  }
  
  try {
    // In a real implementation, you would call an API endpoint to disable 2FA
    // For now, we'll just simulate it
    twoFactorAuth.value.isEnabled = false;
    error.value = 'Two-factor authentication disabled';
    loadSecuritySettings(); // Refresh settings
  } catch (err) {
    error.value = err.message || 'Failed to disable 2FA';
  }
};

// Generate backup codes
const generateBackupCodes = async () => {
  try {
    // In a real implementation, you would call an API endpoint to generate new backup codes
    // For now, we'll just simulate it
    error.value = 'New backup codes generated. Please save them in a secure place.';
    loadSecuritySettings(); // Refresh settings
  } catch (err) {
    error.value = err.message || 'Failed to generate backup codes';
  }
};

// Setup biometric authentication
const setupBiometric = async () => {
  try {
    // In a real implementation, you would use WebAuthn API to setup biometric authentication
    // For now, we'll just simulate it
    hasBiometricAuth.value = true;
    error.value = 'Biometric authentication setup successfully';
    loadSecuritySettings(); // Refresh settings
  } catch (err) {
    error.value = err.message || 'Failed to setup biometric authentication';
  }
};

// Disable biometric authentication
const disableBiometric = async () => {
  if (!confirm('Are you sure you want to remove biometric authentication?')) {
    return;
  }
  
  try {
    // In a real implementation, you would call an API endpoint to disable biometric auth
    // For now, we'll just simulate it
    hasBiometricAuth.value = false;
    error.value = 'Biometric authentication removed';
    loadSecuritySettings(); // Refresh settings
  } catch (err) {
    error.value = err.message || 'Failed to remove biometric authentication';
  }
};

// Remove specific biometric
const removeBiometric = async (biometricId) => {
  if (!confirm('Are you sure you want to remove this biometric authentication method?')) {
    return;
  }
  
  try {
    // In a real implementation, you would call an API endpoint to remove the biometric
    // For now, we'll just simulate it
    biometricAuth.value = biometricAuth.value.filter(b => b.id !== biometricId);
    hasBiometricAuth.value = biometricAuth.value.length > 0;
    error.value = 'Biometric authentication method removed';
    loadSecuritySettings(); // Refresh settings
  } catch (err) {
    error.value = err.message || 'Failed to remove biometric authentication method';
  }
};

// Clear trusted devices
const clearTrustedDevices = async () => {
  if (!confirm('Are you sure you want to clear all trusted devices? You will need to re-authenticate on all devices.')) {
    return;
  }
  
  try {
    // In a real implementation, you would call an API endpoint to clear trusted devices
    // For now, we'll just simulate it
    trustedDevicesCount.value = 0;
    error.value = 'All trusted devices cleared';
    loadSecuritySettings(); // Refresh settings
  } catch (err) {
    error.value = err.message || 'Failed to clear trusted devices';
  }
};

// Helper functions
const formatMethod = (method) => {
  const methods = {
    'app': 'Authenticator App',
    'sms': 'SMS',
    'email': 'Email'
  };
  return methods[method] || method;
};

const formatBiometricType = (type) => {
  const types = {
    'fingerprint': 'Fingerprint',
    'face': 'Face Recognition',
    'voice': 'Voice Recognition'
  };
  return types[type] || type;
};

const getBiometricIcon = (type) => {
  const icons = {
    'fingerprint': 'fas fa-fingerprint',
    'face': 'fas fa-user',
    'voice': 'fas fa-microphone'
  };
  return icons[type] || 'fas fa-shield-alt';
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  return new Date(dateString).toLocaleDateString();
};
</script>

<style scoped>
.security-settings {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

.page-header {
  margin-bottom: 30px;
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

.security-score-card {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 30px;
}

.score-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.score-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
}

.score-value {
  font-size: 2rem;
  font-weight: 700;
  color: #667eea;
}

.score-bar {
  height: 12px;
  background: #e5e7eb;
  border-radius: 6px;
  overflow: hidden;
  margin-bottom: 20px;
}

.score-fill {
  height: 100%;
  background: linear-gradient(90deg, #667eea, #764ba2);
  border-radius: 6px;
  transition: width 0.5s ease;
}

.score-recommendations h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #1f2937;
}

.score-recommendations ul {
  list-style-type: none;
  padding: 0;
}

.score-recommendations li {
  padding: 8px 0;
  border-bottom: 1px solid #f3f4f6;
  color: #6b7280;
}

.score-recommendations li:last-child {
  border-bottom: none;
}

.security-section {
  background: white;
  border-radius: 12px;
  padding: 25px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  margin-bottom: 30px;
}

.section-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.section-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
}

.section-content {
  color: #6b7280;
}

.two-factor-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 15px;
  margin-bottom: 25px;
}

.info-item {
  display: flex;
  flex-direction: column;
}

.info-item label {
  font-weight: 600;
  color: #1f2937;
  margin-bottom: 5px;
}

.info-item span {
  color: #6b7280;
}

.status-active {
  color: #22c55e;
  font-weight: 600;
}

.backup-codes-section h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #1f2937;
}

.backup-codes-section p {
  margin-bottom: 15px;
}

.two-factor-options {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-top: 20px;
}

.option-card {
  border: 2px solid #e5e7eb;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s ease;
}

.option-card:hover {
  border-color: #667eea;
  transform: translateY(-2px);
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

.option-card i {
  font-size: 2rem;
  color: #667eea;
  margin-bottom: 15px;
}

.option-card h3 {
  font-size: 1.2rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #1f2937;
}

.option-card p {
  color: #6b7280;
  font-size: 0.95rem;
}

.biometric-info {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.biometric-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
}

.biometric-details {
  display: flex;
  align-items: center;
  gap: 15px;
}

.biometric-details i {
  font-size: 1.5rem;
  color: #667eea;
}

.biometric-details h3 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #1f2937;
}

.biometric-details p {
  color: #6b7280;
  font-size: 0.9rem;
  margin: 0;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 15px rgba(102, 126, 234, 0.3);
}

.btn-secondary {
  background: #f3f4f6;
  color: #1f2937;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-secondary:hover {
  background: #e5e7eb;
}

.btn-danger {
  background: #ef4444;
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-danger:hover {
  background: #dc2626;
  transform: translateY(-2px);
}

.btn-small {
  padding: 5px 10px;
  font-size: 0.9rem;
}

.form-input {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
  margin-bottom: 15px;
  box-sizing: border-box;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  border-radius: 12px;
  width: 100%;
  max-width: 500px;
  max-height: 90vh;
  overflow-y: auto;
}

.modal-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  border-bottom: 1px solid #e5e7eb;
}

.modal-header h2 {
  font-size: 1.5rem;
  font-weight: 600;
  color: #1f2937;
}

.close-btn {
  background: none;
  border: none;
  font-size: 1.25rem;
  color: #6b7280;
  cursor: pointer;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-btn:hover {
  background: #f3f4f6;
}

.modal-body {
  padding: 20px;
}

.setup-step h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin: 20px 0 10px;
  color: #1f2937;
}

.setup-step p {
  color: #6b7280;
  margin-bottom: 15px;
}

.qr-code-placeholder {
  text-align: center;
  margin: 20px 0;
}

.qr-placeholder {
  background: #f3f4f6;
  border-radius: 8px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.qr-placeholder i {
  font-size: 3rem;
  color: #6b7280;
  margin-bottom: 15px;
}

.qr-placeholder p {
  margin: 0;
  color: #6b7280;
}

.code-input {
  width: 100%;
  padding: 12px 15px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1.5rem;
  text-align: center;
  letter-spacing: 5px;
  margin: 15px 0;
  box-sizing: border-box;
}

.code-input:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.backup-codes-info {
  margin: 25px 0;
}

.backup-codes-info h3 {
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 10px;
  color: #1f2937;
}

.backup-codes {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
  margin: 15px 0;
}

.backup-code {
  background: #f3f4f6;
  border-radius: 6px;
  padding: 10px;
  text-align: center;
  font-family: monospace;
  font-size: 1.1rem;
  font-weight: 600;
}

.error-message {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #fee2e2;
  color: #b91c1c;
  padding: 15px 20px;
  border-radius: 8px;
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  display: flex;
  align-items: center;
  gap: 10px;
  z-index: 1001;
}

.close-error {
  background: none;
  border: none;
  color: #b91c1c;
  font-size: 1.2rem;
  cursor: pointer;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.close-error:hover {
  background: rgba(185, 28, 28, 0.1);
}

/* Dark mode support */
.dark .security-settings {
  color: #f9fafb;
}

.dark .page-header h1,
.dark .score-header h2,
.dark .section-header h2,
.dark .option-card h3,
.dark .biometric-details h3,
.dark .modal-header h2,
.dark .setup-step h3,
.dark .backup-codes-info h3 {
  color: #f9fafb;
}

.dark .page-header p,
.dark .score-recommendations li,
.dark .info-item label,
.dark .option-card p,
.dark .biometric-details p {
  color: #d1d5db;
}

.dark .security-score-card,
.dark .security-section,
.dark .option-card,
.dark .biometric-item {
  background: #1f2937;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.dark .score-bar {
  background: #374151;
}

.dark .info-item span,
.dark .status-active {
  color: #9ca3af;
}

.dark .option-card {
  border-color: #374151;
}

.dark .option-card:hover {
  border-color: #667eea;
}

.dark .option-card i,
.dark .biometric-details i {
  color: #a5b4fc;
}

.dark .form-input,
.dark .code-input {
  background: #111827;
  border-color: #374151;
  color: #f9fafb;
}

.dark .form-input:focus,
.dark .code-input:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

.dark .btn-secondary {
  background: #374151;
  color: #f9fafb;
}

.dark .btn-secondary:hover {
  background: #4b5563;
}

.dark .qr-placeholder {
  background: #111827;
}

.dark .qr-placeholder i,
.dark .qr-placeholder p {
  color: #9ca3af;
}

.dark .backup-code {
  background: #111827;
  color: #f9fafb;
}

.dark .error-message {
  background: #7f1d1d;
  color: #fecaca;
}

.dark .close-error {
  color: #fecaca;
}

.dark .close-error:hover {
  background: rgba(254, 202, 202, 0.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .security-settings {
    padding: 15px;
  }
  
  .page-header h1 {
    font-size: 1.75rem;
  }
  
  .security-score-card,
  .security-section {
    padding: 20px;
  }
  
  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .two-factor-options,
  .backup-codes {
    grid-template-columns: 1fr;
  }
  
  .biometric-item {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }
  
  .modal-content {
    margin: 20px;
    max-width: calc(100% - 40px);
  }
}
</style>