/**
 * Settings Utilities for Real-time Updates
 * This file provides utilities for handling settings updates across the application
 */

class SettingsManager {
    constructor() {
        this.updateCallbacks = new Map();
        this.lastUpdate = null;
        this.isUpdating = false;
    }

    /**
     * Register a callback to be called when settings are updated
     * @param {string} settingKey - The setting key to watch
     * @param {Function} callback - The callback function
     */
    onSettingUpdate(settingKey, callback) {
        if (!this.updateCallbacks.has(settingKey)) {
            this.updateCallbacks.set(settingKey, []);
        }
        this.updateCallbacks.get(settingKey).push(callback);
    }

    /**
     * Notify all callbacks for a specific setting
     * @param {string} settingKey - The setting key
     * @param {*} newValue - The new value
     */
    notifySettingUpdate(settingKey, newValue) {
        if (this.updateCallbacks.has(settingKey)) {
            this.updateCallbacks.get(settingKey).forEach(callback => {
                try {
                    callback(newValue);
                } catch (error) {
                    console.error('Error in settings callback:', error);
                }
            });
        }
    }

    /**
     * Update a setting via AJAX
     * @param {string} key - The setting key
     * @param {*} value - The new value
     * @param {string} type - The settings type (global or per-school)
     * @param {number} schoolId - The school ID (for per-school settings)
     */
    async updateSetting(key, value, type = 'global', schoolId = null) {
        if (this.isUpdating) {
            console.warn('Settings update already in progress');
            return false;
        }

        this.isUpdating = true;

        try {
            const formData = new FormData();
            formData.append(key, value);
            
            if (type === 'per-school' && schoolId) {
                formData.append('school_id', schoolId);
            }

            const url = type === 'global' 
                ? '/settings-global-ajax' 
                : '/settings-per-school-ajax';

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                   document.querySelector('input[name="_token"]')?.value
                }
            });

            const data = await response.json();

            if (data.success) {
                this.lastUpdate = new Date();
                this.notifySettingUpdate(key, value);
                return true;
            } else {
                console.error('Settings update failed:', data.message);
                return false;
            }
        } catch (error) {
            console.error('Error updating setting:', error);
            return false;
        } finally {
            this.isUpdating = false;
        }
    }

    /**
     * Get current settings
     * @param {string} type - The settings type (global or per-school)
     * @param {number} schoolId - The school ID (for per-school settings)
     */
    async getSettings(type = 'global', schoolId = null) {
        try {
            const params = new URLSearchParams();
            params.append('type', type);
            if (schoolId) {
                params.append('school_id', schoolId);
            }

            const response = await fetch(`/settings-get-ajax?${params.toString()}`);
            const data = await response.json();

            if (data.success) {
                return data.settings;
            } else {
                console.error('Failed to get settings:', data.message);
                return {};
            }
        } catch (error) {
            console.error('Error getting settings:', error);
            return {};
        }
    }

    /**
     * Auto-save form on input changes
     * @param {HTMLFormElement} form - The form element
     * @param {string} type - The settings type
     * @param {number} schoolId - The school ID (for per-school settings)
     * @param {number} delay - Delay in milliseconds before auto-save
     */
    enableAutoSave(form, type = 'global', schoolId = null, delay = 2000) {
        let timeout;
        const inputs = form.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            input.addEventListener('change', () => {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    const formData = new FormData(form);
                    this.updateSettingsFromForm(formData, type, schoolId);
                }, delay);
            });
        });
    }

    /**
     * Update settings from form data
     * @param {FormData} formData - The form data
     * @param {string} type - The settings type
     * @param {number} schoolId - The school ID (for per-school settings)
     */
    async updateSettingsFromForm(formData, type = 'global', schoolId = null) {
        try {
            const url = type === 'global' 
                ? '/settings-global-ajax' 
                : '/settings-per-school-ajax';

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                                   document.querySelector('input[name="_token"]')?.value
                }
            });

            const data = await response.json();

            if (data.success) {
                this.lastUpdate = new Date();
                
                // Notify all updated settings
                Object.keys(data.settings).forEach(key => {
                    this.notifySettingUpdate(key, data.settings[key]);
                });

                return data;
            } else {
                console.error('Settings update failed:', data.message);
                return null;
            }
        } catch (error) {
            console.error('Error updating settings:', error);
            return null;
        }
    }
}

// Create global instance
window.settingsManager = new SettingsManager();

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SettingsManager;
} 