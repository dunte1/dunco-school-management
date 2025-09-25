/**
 * Sidebar Manager - Handles dynamic sidebar updates based on permission changes
 */
class SidebarManager {
    constructor() {
        this.sidebarElement = document.getElementById('sidebarNav');
        this.currentPermissions = new Set();
        this.currentModules = new Set();
        this.updateInterval = null;
        this.isInitialized = false;
        
        this.init();
    }

    init() {
        if (!this.sidebarElement) {
            console.warn('Sidebar element not found');
            return;
        }

        this.loadInitialPermissions();
        this.startPermissionMonitoring();
        this.isInitialized = true;
    }

    /**
     * Load initial permissions and set up sidebar
     */
    async loadInitialPermissions() {
        try {
            const response = await fetch('/api/sidebar/user-permissions', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.currentPermissions = new Set(data.permissions);
                this.currentModules = new Set(data.accessible_modules);
                this.updateSidebarVisibility();
            }
        } catch (error) {
            console.error('Failed to load initial permissions:', error);
        }
    }

    /**
     * Start monitoring for permission changes
     */
    startPermissionMonitoring() {
        // Check for permission changes every 30 seconds
        this.updateInterval = setInterval(() => {
            this.checkPermissionChanges();
        }, 30000);

        // Also check when the page becomes visible (user returns to tab)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.checkPermissionChanges();
            }
        });

        // Listen for permission update events
        this.setupEventListeners();
    }

    /**
     * Setup event listeners for real-time updates
     */
    setupEventListeners() {
        // Listen for permission update events via polling (fallback)
        this.setupPollingListener();
        
        // Listen for browser storage events (if using localStorage for communication)
        window.addEventListener('storage', (event) => {
            if (event.key === 'permissions_updated') {
                this.handlePermissionUpdate();
            }
        });
    }

    /**
     * Setup polling listener for permission updates
     */
    setupPollingListener() {
        // Check for permission updates every 10 seconds
        setInterval(() => {
            this.checkForPermissionUpdates();
        }, 10000);
    }

    /**
     * Check for permission updates via API
     */
    async checkForPermissionUpdates() {
        try {
            // First check if there's been a permission update
            const lastUpdateResponse = await fetch('/api/sidebar/last-update', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (lastUpdateResponse.ok) {
                const lastUpdateData = await lastUpdateResponse.json();
                const lastUpdate = lastUpdateData.last_update;
                
                // Check if we have a stored last update time
                const storedLastUpdate = sessionStorage.getItem('last_permission_update');
                
                if (storedLastUpdate && storedLastUpdate !== lastUpdate) {
                    // Permissions have been updated, refresh sidebar
                    this.handlePermissionUpdate();
                    sessionStorage.setItem('last_permission_update', lastUpdate);
                    return;
                }
                
                // Store the current last update time
                if (!storedLastUpdate) {
                    sessionStorage.setItem('last_permission_update', lastUpdate);
                }
            }

            // Fallback: Check actual permissions
            const response = await fetch('/api/sidebar/user-permissions', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                const newPermissions = new Set(data.permissions);
                const newModules = new Set(data.accessible_modules);

                // Check if permissions have changed
                if (this.permissionsChanged(newPermissions, newModules)) {
                    this.handlePermissionUpdate();
                }
            }
        } catch (error) {
            console.error('Failed to check for permission updates:', error);
        }
    }

    /**
     * Handle permission update
     */
    handlePermissionUpdate() {
        this.loadInitialPermissions();
        this.showPermissionUpdateNotification();
    }

    /**
     * Check if permissions have changed and update sidebar accordingly
     */
    async checkPermissionChanges() {
        try {
            const response = await fetch('/api/sidebar/user-permissions', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                const newPermissions = new Set(data.permissions);
                const newModules = new Set(data.accessible_modules);

                // Check if permissions have changed
                if (this.permissionsChanged(newPermissions, newModules)) {
                    this.currentPermissions = newPermissions;
                    this.currentModules = newModules;
                    this.updateSidebarVisibility();
                    this.showPermissionUpdateNotification();
                }
            }
        } catch (error) {
            console.error('Failed to check permission changes:', error);
        }
    }

    /**
     * Compare current permissions with new permissions
     */
    permissionsChanged(newPermissions, newModules) {
        // Check if permissions have changed
        if (this.currentPermissions.size !== newPermissions.size) {
            return true;
        }

        for (const permission of newPermissions) {
            if (!this.currentPermissions.has(permission)) {
                return true;
            }
        }

        // Check if accessible modules have changed
        if (this.currentModules.size !== newModules.size) {
            return true;
        }

        for (const module of newModules) {
            if (!this.currentModules.has(module)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Update sidebar visibility based on current permissions
     */
    updateSidebarVisibility() {
        if (!this.sidebarElement) return;

        // Update module sections
        this.updateModuleSections();
        
        // Update individual menu items
        this.updateMenuItems();
    }

    /**
     * Update module sections visibility
     */
    updateModuleSections() {
        const moduleSections = this.sidebarElement.querySelectorAll('[data-module]');
        
        moduleSections.forEach(section => {
            const moduleName = section.getAttribute('data-module');
            const isAccessible = this.currentModules.has(moduleName);
            
            if (isAccessible) {
                section.style.display = '';
                section.classList.remove('d-none');
            } else {
                section.style.display = 'none';
                section.classList.add('d-none');
            }
        });
    }

    /**
     * Update individual menu items visibility
     */
    updateMenuItems() {
        const menuItems = this.sidebarElement.querySelectorAll('[data-permission]');
        
        menuItems.forEach(item => {
            const permission = item.getAttribute('data-permission');
            const hasPermission = this.currentPermissions.has(permission);
            
            if (hasPermission) {
                item.style.display = '';
                item.classList.remove('d-none');
            } else {
                item.style.display = 'none';
                item.classList.add('d-none');
            }
        });
    }

    /**
     * Show notification when permissions are updated
     */
    showPermissionUpdateNotification() {
        // Create a toast notification
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white bg-info border-0 position-fixed';
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999;';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-sync-alt me-2"></i>
                    Your permissions have been updated. The sidebar has been refreshed.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        // Add to page
        document.body.appendChild(toast);
        
        // Show the toast
        const bsToast = new bootstrap.Toast(toast, {
            autohide: true,
            delay: 5000
        });
        bsToast.show();

        // Remove from DOM after hiding
        toast.addEventListener('hidden.bs.toast', () => {
            document.body.removeChild(toast);
        });
    }

    /**
     * Force refresh sidebar data
     */
    async forceRefresh() {
        await this.loadInitialPermissions();
        this.updateSidebarVisibility();
    }

    /**
     * Stop monitoring
     */
    stop() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
    }

    /**
     * Check if user has specific permission
     */
    async hasPermission(permission) {
        try {
            const response = await fetch('/api/sidebar/check-permission', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({ permission })
            });

            if (response.ok) {
                const data = await response.json();
                return data.has_permission;
            }
        } catch (error) {
            console.error('Failed to check permission:', error);
        }
        
        return false;
    }
}

// Initialize sidebar manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.sidebarManager = new SidebarManager();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SidebarManager;
} 