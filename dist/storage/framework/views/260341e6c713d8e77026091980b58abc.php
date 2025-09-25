

<?php $__env->startSection('title', 'ChatBot Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-robot"></i> ChatBot Admin Dashboard
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Total Conversations</h5>
                                            <h3 id="totalConversations"><?php echo e($statistics['total_conversations'] ?? 0); ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-comments fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Total Messages</h5>
                                            <h3 id="totalMessages"><?php echo e($statistics['total_messages'] ?? 0); ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-envelope fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Today's Conversations</h5>
                                            <h3 id="todayConversations"><?php echo e($statistics['today_conversations'] ?? 0); ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-calendar-day fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title">Today's Messages</h5>
                                            <h3 id="todayMessages"><?php echo e($statistics['today_messages'] ?? 0); ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-paper-plane fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Status -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">System Status</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="status-indicator me-2" id="openaiStatus"></div>
                                                <span>OpenAI Connection</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="status-indicator me-2" id="configStatus"></div>
                                                <span>Configuration</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="status-indicator me-2" id="cacheStatus"></div>
                                                <span>Cache System</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="status-indicator me-2" id="databaseStatus"></div>
                                                <span>Database</span>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-outline-primary" onclick="checkHealth()">
                                        <i class="fas fa-sync"></i> Refresh Status
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="<?php echo e(route('chatbot.admin.settings')); ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-cog"></i> Settings
                                        </a>
                                        <button class="btn btn-outline-success" onclick="testOpenAI()">
                                            <i class="fas fa-vial"></i> Test OpenAI
                                        </button>
                                        <button class="btn btn-outline-warning" onclick="clearCache()">
                                            <i class="fas fa-broom"></i> Clear Cache
                                        </button>
                                        <button class="btn btn-outline-info" onclick="getUsage()">
                                            <i class="fas fa-chart-bar"></i> Usage Statistics
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OpenAI Models -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Available OpenAI Models</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="modelsTable">
                                            <thead>
                                                <tr>
                                                    <th>Model ID</th>
                                                    <th>Created</th>
                                                    <th>Object Type</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if(isset($models) && count($models) > 0): ?>
                                                    <?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>
                                                            <td><?php echo e($model['id'] ?? 'N/A'); ?></td>
                                                            <td><?php echo e(isset($model['created']) ? \Carbon\Carbon::createFromTimestamp($model['created'])->format('Y-m-d H:i:s') : 'N/A'); ?></td>
                                                            <td><?php echo e($model['object'] ?? 'N/A'); ?></td>
                                                            <td>
                                                                <span class="badge bg-success">Available</span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No models available</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Usage Modal -->
<div class="modal fade" id="usageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">OpenAI Usage Statistics</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="usageModalBody">
                <!-- Usage data will be loaded here -->
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #dc3545;
}

.status-indicator.online {
    background-color: #28a745;
}

.status-indicator.warning {
    background-color: #ffc107;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.table th {
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    checkHealth();
    updateStatusIndicators();
});

function updateStatusIndicators() {
    // OpenAI Status
    const openaiStatus = document.getElementById('openaiStatus');
    if (<?php echo e($openAIAvailable ? 'true' : 'false'); ?>) {
        openaiStatus.classList.add('online');
        openaiStatus.classList.remove('warning');
    } else {
        openaiStatus.classList.add('warning');
        openaiStatus.classList.remove('online');
    }

    // Config Status
    const configStatus = document.getElementById('configStatus');
    configStatus.classList.add('online');

    // Cache Status
    const cacheStatus = document.getElementById('cacheStatus');
    cacheStatus.classList.add('online');

    // Database Status
    const databaseStatus = document.getElementById('databaseStatus');
    databaseStatus.classList.add('online');
}

async function checkHealth() {
    try {
        const response = await fetch('/chatbot/admin/health');
        const data = await response.json();
        
        if (data.success) {
            updateHealthStatus(data.data);
        }
    } catch (error) {
        console.error('Health check failed:', error);
        showAlert('Health check failed', 'danger');
    }
}

function updateHealthStatus(health) {
    const openaiStatus = document.getElementById('openaiStatus');
    const configStatus = document.getElementById('configStatus');
    const cacheStatus = document.getElementById('cacheStatus');
    const databaseStatus = document.getElementById('databaseStatus');

    // Update status indicators
    openaiStatus.className = 'status-indicator me-2 ' + (health.openai_connected ? 'online' : 'warning');
    configStatus.className = 'status-indicator me-2 ' + (health.config_loaded ? 'online' : 'warning');
    cacheStatus.className = 'status-indicator me-2 ' + (health.cache_working ? 'online' : 'warning');
    databaseStatus.className = 'status-indicator me-2 online'; // Assume database is working
}

async function testOpenAI() {
    try {
        const response = await fetch('/chatbot/admin/test-openai', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            if (data.connected) {
                showAlert('OpenAI connection successful!', 'success');
            } else {
                showAlert('OpenAI connection failed: ' + data.message, 'danger');
            }
        } else {
            showAlert('Test failed: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('OpenAI test failed:', error);
        showAlert('OpenAI test failed', 'danger');
    }
}

async function clearCache() {
    if (!confirm('Are you sure you want to clear the cache?')) return;
    
    try {
        const response = await fetch('/chatbot/admin/clear-cache', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('Cache cleared successfully!', 'success');
        } else {
            showAlert('Failed to clear cache: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Clear cache failed:', error);
        showAlert('Clear cache failed', 'danger');
    }
}

async function getUsage() {
    try {
        const response = await fetch('/chatbot/admin/usage');
        const data = await response.json();
        
        if (data.success) {
            displayUsageData(data.data);
        } else {
            showAlert('Failed to get usage data: ' + data.message, 'danger');
        }
    } catch (error) {
        console.error('Get usage failed:', error);
        showAlert('Failed to get usage data', 'danger');
    }
}

function displayUsageData(usage) {
    const modalBody = document.getElementById('usageModalBody');
    
    if (usage) {
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Usage Statistics</h6>
                    <ul class="list-unstyled">
                        <li><strong>Date:</strong> ${usage.date || 'N/A'}</li>
                        <li><strong>Total Tokens:</strong> ${usage.total_tokens || 0}</li>
                        <li><strong>Requests:</strong> ${usage.requests || 0}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Cost Information</h6>
                    <ul class="list-unstyled">
                        <li><strong>Total Cost:</strong> $${usage.total_cost || 0}</li>
                        <li><strong>Average Cost:</strong> $${usage.average_cost || 0}</li>
                    </ul>
                </div>
            </div>
        `;
    } else {
        modalBody.innerHTML = '<p class="text-center">No usage data available</p>';
    }
    
    const modal = new bootstrap.Modal(document.getElementById('usageModal'));
    modal.show();
}

function showAlert(message, type) {
    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insert at the top of the card body
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}
</script>
<?php $__env->stopPush(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\ChatBot\resources\views\admin\dashboard.blade.php ENDPATH**/ ?>