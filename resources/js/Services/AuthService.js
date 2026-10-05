import ApiService from './ApiService';

class AuthService {
  // User login
  async login(credentials) {
    try {
      const response = await ApiService.post('/auth/login', credentials);
      if (response.token) {
        // Store token
        localStorage.setItem('authToken', response.token);
        ApiService.setToken(response.token);
        
        // Store user data
        if (response.user) {
          localStorage.setItem('user', JSON.stringify(response.user));
        }
      }
      return response;
    } catch (error) {
      throw error;
    }
  }

  // User logout
  async logout() {
    try {
      await ApiService.post('/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
    } finally {
      // Clear local storage
      localStorage.removeItem('authToken');
      localStorage.removeItem('user');
      ApiService.setToken(null);
    }
  }

  // Forgot password
  async forgotPassword(email) {
    return ApiService.post('/auth/forgot-password', { email });
  }

  // Reset password
  async resetPassword(data) {
    return ApiService.post('/auth/reset-password', data);
  }

  // Get current user
  async getMe() {
    try {
      const response = await ApiService.get('/me');
      // Update local user data
      if (response) {
        localStorage.setItem('user', JSON.stringify(response));
      }
      return response;
    } catch (error) {
      throw error;
    }
  }

  // Setup 2FA
  async setupTwoFactor() {
    return ApiService.post('/auth/setup-2fa');
  }

  // Verify 2FA setup
  async verifyTwoFactorSetup(data) {
    return ApiService.post('/auth/verify-2fa-setup', data);
  }

  // Login with 2FA
  async loginWithTwoFactor(data) {
    return ApiService.post('/auth/login-2fa', data);
  }

  // Setup biometric authentication
  async setupBiometric(data) {
    return ApiService.post('/auth/setup-biometric', data);
  }

  // Login with biometric
  async loginWithBiometric(data) {
    return ApiService.post('/auth/login-biometric', data);
  }

  // Get security settings
  async getSecuritySettings() {
    return ApiService.get('/auth/security-settings');
  }

  // Check if user is authenticated
  isAuthenticated() {
    return !!localStorage.getItem('authToken');
  }

  // Get stored user data
  getUser() {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  }

  // Get auth token
  getToken() {
    return localStorage.getItem('authToken');
  }
}

// Export singleton instance
export default new AuthService();