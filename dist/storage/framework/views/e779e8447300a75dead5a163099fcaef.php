

<?php $__env->startSection('title', 'ChatBot Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-cog"></i> ChatBot Settings
                    </h4>
                </div>
                <div class="card-body">
                    <form id="settingsForm">
                        <!-- OpenAI Configuration -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">OpenAI Configuration</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="openai_api_key" class="form-label">OpenAI API Key</label>
                                    <input type="password" class="form-control" id="openai_api_key" name="openai_api_key" 
                                           value="<?php echo e(env('OPENAI_API_KEY')); ?>" placeholder="sk-...">
                                    <div class="form-text">Your OpenAI API key for authentication</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="openai_model" class="form-label">Model</label>
                                    <select class="form-select" id="openai_model" name="openai_model">
                                        <option value="gpt-3.5-turbo" <?php echo e(config('chatbot.openai.model') == 'gpt-3.5-turbo' ? 'selected' : ''); ?>>GPT-3.5 Turbo</option>
                                        <option value="gpt-4" <?php echo e(config('chatbot.openai.model') == 'gpt-4' ? 'selected' : ''); ?>>GPT-4</option>
                                        <option value="gpt-4-turbo" <?php echo e(config('chatbot.openai.model') == 'gpt-4-turbo' ? 'selected' : ''); ?>>GPT-4 Turbo</option>
                                    </select>
                                    <div class="form-text">The OpenAI model to use for responses</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="openai_max_tokens" class="form-label">Max Tokens</label>
                                    <input type="number" class="form-control" id="openai_max_tokens" name="openai_max_tokens" 
                                           value="<?php echo e(config('chatbot.openai.max_tokens', 1000)); ?>" min="1" max="4000">
                                    <div class="form-text">Maximum tokens per response (1-4000)</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="openai_temperature" class="form-label">Temperature</label>
                                    <input type="number" class="form-control" id="openai_temperature" name="openai_temperature" 
                                           value="<?php echo e(config('chatbot.openai.temperature', 0.7)); ?>" min="0" max="2" step="0.1">
                                    <div class="form-text">Controls randomness (0-2)</div>
                                </div>
                            </div>
                        </div>

                        <!-- ChatBot Configuration -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">ChatBot Configuration</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="chatbot_enabled" name="chatbot_enabled" 
                                               <?php echo e(config('chatbot.chatbot.enabled', true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="chatbot_enabled">
                                            Enable ChatBot
                                        </label>
                                    </div>
                                    <div class="form-text">Enable or disable the chatbot functionality</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="welcome_message" class="form-label">Welcome Message</label>
                                    <textarea class="form-control" id="welcome_message" name="welcome_message" rows="2"
                                              placeholder="Hello! I'm your AI assistant..."><?php echo e(config('chatbot.chatbot.welcome_message')); ?></textarea>
                                    <div class="form-text">Message shown when starting a new conversation</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_conversation_length" class="form-label">Max Conversation Length</label>
                                    <input type="number" class="form-control" id="max_conversation_length" name="max_conversation_length" 
                                           value="<?php echo e(config('chatbot.chatbot.max_conversation_length', 50)); ?>" min="10" max="100">
                                    <div class="form-text">Maximum messages per conversation</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="session_timeout" class="form-label">Session Timeout (minutes)</label>
                                    <input type="number" class="form-control" id="session_timeout" name="session_timeout" 
                                           value="<?php echo e(config('chatbot.chatbot.session_timeout', 30)); ?>" min="5" max="120">
                                    <div class="form-text">Session timeout in minutes</div>
                                </div>
                            </div>
                        </div>

                        <!-- Rate Limiting -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">Rate Limiting</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="requests_per_minute" class="form-label">Requests per Minute</label>
                                    <input type="number" class="form-control" id="requests_per_minute" name="requests_per_minute" 
                                           value="<?php echo e(config('chatbot.chatbot.rate_limit.requests_per_minute', 60)); ?>" min="10" max="200">
                                    <div class="form-text">Maximum requests per minute per user</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="requests_per_hour" class="form-label">Requests per Hour</label>
                                    <input type="number" class="form-control" id="requests_per_hour" name="requests_per_hour" 
                                           value="<?php echo e(config('chatbot.chatbot.rate_limit.requests_per_hour', 1000)); ?>" min="100" max="5000">
                                    <div class="form-text">Maximum requests per hour per user</div>
                                </div>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2">Features</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="voice_input" name="voice_input" 
                                               <?php echo e(config('chatbot.features.voice_input', false) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="voice_input">
                                            Voice Input
                                        </label>
                                    </div>
                                    <div class="form-text">Enable voice input functionality</div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="voice_output" name="voice_output" 
                                               <?php echo e(config('chatbot.features.voice_output', false) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="voice_output">
                                            Voice Output
                                        </label>
                                    </div>
                                    <div class="form-text">Enable voice output functionality</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="file_upload" name="file_upload" 
                                               <?php echo e(config('chatbot.features.file_upload', true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="file_upload">
                                            File Upload
                                        </label>
                                    </div>
                                    <div class="form-text">Enable file upload functionality</div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="code_generation" name="code_generation" 
                                               <?php echo e(config('chatbot.features.code_generation', true) ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="code_generation">
                                            Code Generation
                                        </label>
                                    </div>
                                    <div class="form-text">Enable code generation functionality</div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" onclick="testConnection()">
                                        <i class="fas fa-vial"></i> Test Connection
                                    </button>
                                    <button type="button" class="btn btn-outline-info" onclick="resetToDefaults()">
                                        <i class="fas fa-undo"></i> Reset to Defaults
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Connection Modal -->
<div class="modal fade" id="testModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Connection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="testModalBody">
                <!-- Test results will be shown here -->
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.border-bottom {
    border-bottom: 2px solid #dee2e6 !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('settingsForm');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        saveSettings();
    });
});

async function saveSettings() {
    const form = document.getElementById('settingsForm');
    const formData = new FormData(form);
    
    // Convert form data to JSON
    const data = {};
    for (let [key, value] of formData.entries()) {
        if (key === 'chatbot_enabled' || key === 'voice_input' || key === 'voice_output' || 
            key === 'file_upload' || key === 'code_generation') {
            data[key] = value === 'on';
        } else {
            data[key] = value;
        }
    }
    
    try {
        const response = await fetch('/chatbot/admin/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showAlert('Settings saved successfully!', 'success');
        } else {
            showAlert('Failed to save settings: ' + result.message, 'danger');
        }
    } catch (error) {
        console.error('Save settings failed:', error);
        showAlert('Failed to save settings', 'danger');
    }
}

async function testConnection() {
    const modalBody = document.getElementById('testModalBody');
    modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div><p>Testing connection...</p></div>';
    
    const modal = new bootstrap.Modal(document.getElementById('testModal'));
    modal.show();
    
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
                modalBody.innerHTML = `
                    <div class="text-center text-success">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h5>Connection Successful!</h5>
                        <p>OpenAI API is working correctly.</p>
                    </div>
                `;
            } else {
                modalBody.innerHTML = `
                    <div class="text-center text-danger">
                        <i class="fas fa-times-circle fa-3x mb-3"></i>
                        <h5>Connection Failed</h5>
                        <p>${data.message}</p>
                    </div>
                `;
            }
        } else {
            modalBody.innerHTML = `
                <div class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5>Test Failed</h5>
                    <p>${data.message}</p>
                </div>
            `;
        }
    } catch (error) {
        console.error('Test connection failed:', error);
        modalBody.innerHTML = `
            <div class="text-center text-danger">
                <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                <h5>Test Failed</h5>
                <p>An error occurred while testing the connection.</p>
            </div>
        `;
    }
}

function resetToDefaults() {
    if (!confirm('Are you sure you want to reset all settings to defaults?')) return;
    
    // Reset form to default values
    document.getElementById('openai_model').value = 'gpt-3.5-turbo';
    document.getElementById('openai_max_tokens').value = '1000';
    document.getElementById('openai_temperature').value = '0.7';
    document.getElementById('chatbot_enabled').checked = true;
    document.getElementById('welcome_message').value = 'Hello! I\'m your AI assistant. How can I help you today?';
    document.getElementById('max_conversation_length').value = '50';
    document.getElementById('session_timeout').value = '30';
    document.getElementById('requests_per_minute').value = '60';
    document.getElementById('requests_per_hour').value = '1000';
    document.getElementById('voice_input').checked = false;
    document.getElementById('voice_output').checked = false;
    document.getElementById('file_upload').checked = true;
    document.getElementById('code_generation').checked = true;
    
    showAlert('Settings reset to defaults. Click "Save Settings" to apply.', 'info');
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\ChatBot\resources\views\admin\settings.blade.php ENDPATH**/ ?>