package com.dunco.schoolmanagement.presentation.viewmodels

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.dunco.schoolmanagement.data.local.TokenManager
import com.dunco.schoolmanagement.domain.models.User
import com.dunco.schoolmanagement.domain.usecases.AuthUseCase
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class AuthViewModel @Inject constructor(
    private val authUseCase: AuthUseCase,
    private val tokenManager: TokenManager
) : ViewModel() {
    
    private val _state = MutableStateFlow(AuthState())
    val state: StateFlow<AuthState> = _state.asStateFlow()
    
    init {
        checkAuthStatus()
    }
    
    private fun checkAuthStatus() {
        viewModelScope.launch {
            val isLoggedIn = tokenManager.isLoggedIn()
            if (isLoggedIn) {
                try {
                    val user = authUseCase.getProfile()
                    _state.value = _state.value.copy(
                        isLoggedIn = true,
                        user = user,
                        isLoading = false
                    )
                } catch (e: Exception) {
                    _state.value = _state.value.copy(
                        isLoggedIn = false,
                        error = "Session expired. Please login again.",
                        isLoading = false
                    )
                    tokenManager.clearTokens()
                }
            }
        }
    }
    
    fun login(email: String, password: String, rememberMe: Boolean) {
        viewModelScope.launch {
            _state.value = _state.value.copy(isLoading = true, error = null)
            
            try {
                val authResponse = authUseCase.login(email, password)
                
                // Save tokens
                tokenManager.saveTokens(
                    authResponse.token,
                    authResponse.refreshToken,
                    authResponse.expiresAt
                )
                
                // Save user info
                tokenManager.saveUserInfo(authResponse.user.id, authResponse.user.role.name)
                
                _state.value = _state.value.copy(
                    isLoggedIn = true,
                    user = authResponse.user,
                    isLoading = false,
                    error = null
                )
            } catch (e: Exception) {
                _state.value = _state.value.copy(
                    isLoading = false,
                    error = e.message ?: "Login failed. Please try again."
                )
            }
        }
    }
    
    fun loginWithBiometric() {
        viewModelScope.launch {
            _state.value = _state.value.copy(isLoading = true, error = null)
            
            try {
                // Check if biometric credentials exist
                val savedCredentials = authUseCase.getSavedBiometricCredentials()
                if (savedCredentials != null) {
                    val authResponse = authUseCase.loginWithBiometric(savedCredentials)
                    
                    // Save tokens
                    tokenManager.saveTokens(
                        authResponse.token,
                        authResponse.refreshToken,
                        authResponse.expiresAt
                    )
                    
                    // Save user info
                    tokenManager.saveUserInfo(authResponse.user.id, authResponse.user.role.name)
                    
                    _state.value = _state.value.copy(
                        isLoggedIn = true,
                        user = authResponse.user,
                        isLoading = false,
                        error = null
                    )
                } else {
                    _state.value = _state.value.copy(
                        isLoading = false,
                        error = "No saved credentials found. Please login with email and password first."
                    )
                }
            } catch (e: Exception) {
                _state.value = _state.value.copy(
                    isLoading = false,
                    error = e.message ?: "Biometric authentication failed."
                )
            }
        }
    }
    
    fun logout() {
        viewModelScope.launch {
            try {
                authUseCase.logout()
            } catch (e: Exception) {
                // Continue with logout even if API call fails
            } finally {
                tokenManager.clearTokens()
                _state.value = AuthState()
            }
        }
    }
    
    fun forgotPassword(email: String) {
        viewModelScope.launch {
            _state.value = _state.value.copy(isLoading = true, error = null)
            
            try {
                authUseCase.forgotPassword(email)
                _state.value = _state.value.copy(
                    isLoading = false,
                    message = "Password reset link sent to your email."
                )
            } catch (e: Exception) {
                _state.value = _state.value.copy(
                    isLoading = false,
                    error = e.message ?: "Failed to send reset email."
                )
            }
        }
    }
    
    fun resetPassword(email: String, token: String, password: String, confirmPassword: String) {
        viewModelScope.launch {
            _state.value = _state.value.copy(isLoading = true, error = null)
            
            try {
                authUseCase.resetPassword(email, token, password, confirmPassword)
                _state.value = _state.value.copy(
                    isLoading = false,
                    message = "Password reset successfully. You can now login with your new password."
                )
            } catch (e: Exception) {
                _state.value = _state.value.copy(
                    isLoading = false,
                    error = e.message ?: "Failed to reset password."
                )
            }
        }
    }
    
    fun clearError() {
        _state.value = _state.value.copy(error = null)
    }
    
    fun clearMessage() {
        _state.value = _state.value.copy(message = null)
    }
}

data class AuthState(
    val isLoading: Boolean = false,
    val isLoggedIn: Boolean = false,
    val user: User? = null,
    val error: String? = null,
    val message: String? = null
) 