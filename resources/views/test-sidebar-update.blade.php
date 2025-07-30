@extends('layouts.app')

@section('title', 'Sidebar Update Test')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-cog me-2"></i>Sidebar Update Test</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">This page demonstrates the automatic sidebar filtering based on permissions.</p>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>How it works:</h6>
                        <ul class="mb-0">
                            <li>When you update role permissions in the admin panel, the sidebar will automatically filter</li>
                            <li>The system checks for permission changes every 10 seconds</li>
                            <li>You'll see a notification when permissions are updated</li>
                            <li>Menu items will show/hide based on your current permissions</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Current User Permissions:</h6>
                            <div id="currentPermissions" class="border rounded p-3 bg-light">
                                <small class="text-muted">Loading...</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6>Accessible Modules:</h6>
                            <div id="accessibleModules" class="border rounded p-3 bg-light">
                                <small class="text-muted">Loading...</small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Test Actions:</h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="testPermissionCheck()">
                                <i class="fas fa-sync me-1"></i>Check Permissions
                            </button>
                            <button class="btn btn-success btn-sm" onclick="forceSidebarRefresh()">
                                <i class="fas fa-redo me-1"></i>Force Refresh
                            </button>
                            <button class="btn btn-info btn-sm" onclick="showSidebarStatus()">
                                <i class="fas fa-info me-1"></i>Sidebar Status
                            </button>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Instructions:</h6>
                        <ol>
                            <li>Go to <strong>Core → Roles</strong> in the sidebar</li>
                            <li>Edit a role and change its permissions</li>
                            <li>Save the changes</li>
                            <li>Return to this page and watch the sidebar update automatically</li>
                            <li>You should see a notification and menu items appearing/disappearing</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6><i class="fas fa-clock me-2"></i>Last Update</h6>
                </div>
                <div class="card-body">
                    <div id="lastUpdate" class="text-muted">
                        <small>Loading...</small>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6><i class="fas fa-chart-line me-2"></i>Sidebar Manager Status</h6>
                </div>
                <div class="card-body">
                    <div id="sidebarStatus" class="text-muted">
                        <small>Loading...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function loadCurrentPermissions() {
    try {
        const response = await fetch('/api/sidebar/user-permissions', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();
            
            // Display permissions
            const permissionsDiv = document.getElementById('currentPermissions');
            permissionsDiv.innerHTML = data.permissions.length > 0 
                ? data.permissions.map(p => `<span class="badge bg-primary me-1 mb-1">${p}</span>`).join('')
                : '<span class="text-muted">No permissions found</span>';
            
            // Display modules
            const modulesDiv = document.getElementById('accessibleModules');
            modulesDiv.innerHTML = data.accessible_modules.length > 0
                ? data.accessible_modules.map(m => `<span class="badge bg-success me-1 mb-1">${m}</span>`).join('')
                : '<span class="text-muted">No accessible modules</span>';
        }
    } catch (error) {
        console.error('Failed to load permissions:', error);
    }
}

async function loadLastUpdate() {
    try {
        const response = await fetch('/api/sidebar/last-update', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();
            document.getElementById('lastUpdate').innerHTML = `
                <small>
                    <strong>Last Update:</strong> ${new Date(data.last_update).toLocaleString()}<br>
                    <strong>Current Time:</strong> ${new Date(data.current_time).toLocaleString()}
                </small>
            `;
        }
    } catch (error) {
        console.error('Failed to load last update:', error);
    }
}

function testPermissionCheck() {
    if (window.sidebarManager) {
        window.sidebarManager.checkPermissionChanges();
        alert('Permission check triggered!');
    } else {
        alert('Sidebar manager not initialized');
    }
}

function forceSidebarRefresh() {
    if (window.sidebarManager) {
        window.sidebarManager.forceRefresh();
        alert('Sidebar refresh triggered!');
    } else {
        alert('Sidebar manager not initialized');
    }
}

function showSidebarStatus() {
    if (window.sidebarManager) {
        const status = {
            initialized: window.sidebarManager.isInitialized,
            permissions: window.sidebarManager.currentPermissions.size,
            modules: window.sidebarManager.currentModules.size,
            monitoring: window.sidebarManager.updateInterval !== null
        };
        
        document.getElementById('sidebarStatus').innerHTML = `
            <small>
                <strong>Initialized:</strong> ${status.initialized ? 'Yes' : 'No'}<br>
                <strong>Permissions:</strong> ${status.permissions}<br>
                <strong>Modules:</strong> ${status.modules}<br>
                <strong>Monitoring:</strong> ${status.monitoring ? 'Active' : 'Inactive'}
            </small>
        `;
    } else {
        document.getElementById('sidebarStatus').innerHTML = '<small class="text-danger">Sidebar manager not available</small>';
    }
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadCurrentPermissions();
    loadLastUpdate();
    showSidebarStatus();
    
    // Refresh data every 30 seconds
    setInterval(() => {
        loadCurrentPermissions();
        loadLastUpdate();
        showSidebarStatus();
    }, 30000);
});
</script>
@endsection 