

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Global Settings</h1>
    <div id="ajax-alert" class="alert" style="display: none;"></div>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <form id="global-settings-form" action="<?php echo e(route('settings.global.update')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label for="system_name" class="form-label">System Name</label>
            <input type="text" name="system_name" id="system_name" class="form-control" value="<?php echo e(old('system_name', $settings['system_name'] ?? '')); ?>" required>
        </div>
        <div class="mb-3">
            <label for="logo" class="form-label">Logo</label>
            <input type="file" name="logo" id="logo" class="form-control">
            <?php if(!empty($settings['logo_path'])): ?>
                <img src="<?php echo e(asset('storage/' . $settings['logo_path'])); ?>" alt="Logo" height="40">
                <div class="form-check">
                    <input type="checkbox" name="delete_logo" id="delete_logo" class="form-check-input">
                    <label for="delete_logo" class="form-check-label">Delete Logo</label>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="favicon" class="form-label">Favicon</label>
            <input type="file" name="favicon" id="favicon" class="form-control">
            <?php if(!empty($settings['favicon_path'])): ?>
                <img src="<?php echo e(asset('storage/' . $settings['favicon_path'])); ?>" alt="Favicon" height="24">
                <div class="form-check">
                    <input type="checkbox" name="delete_favicon" id="delete_favicon" class="form-check-input">
                    <label for="delete_favicon" class="form-check-label">Delete Favicon</label>
                </div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="default_language" class="form-label">Default Language</label>
            <select name="default_language" id="default_language" class="form-control" required>
                <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($code); ?>" <?php echo e((old('default_language', $settings['default_language'] ?? '') == $code) ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="default_timezone" class="form-label">Default Timezone</label>
            <select name="default_timezone" id="default_timezone" class="form-control" required>
                <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tz): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($tz); ?>" <?php echo e((old('default_timezone', $settings['default_timezone'] ?? '') == $tz) ? 'selected' : ''); ?>><?php echo e($tz); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_exams" id="enable_exams" class="form-check-input" value="1" <?php echo e(old('enable_exams', $settings['enable_exams'] ?? false) ? 'checked' : ''); ?>>
            <label for="enable_exams" class="form-check-label">Enable Exams</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_finance" id="enable_finance" class="form-check-input" value="1" <?php echo e(old('enable_finance', $settings['enable_finance'] ?? false) ? 'checked' : ''); ?>>
            <label for="enable_finance" class="form-check-label">Enable Finance</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_attendance" id="enable_attendance" class="form-check-input" value="1" <?php echo e(old('enable_attendance', $settings['enable_attendance'] ?? false) ? 'checked' : ''); ?>>
            <label for="enable_attendance" class="form-check-label">Enable Attendance</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_library" id="enable_library" class="form-check-input" value="1" <?php echo e(old('enable_library', $settings['enable_library'] ?? false) ? 'checked' : ''); ?>>
            <label for="enable_library" class="form-check-label">Enable Library</label>
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" name="enable_notifications" id="enable_notifications" class="form-check-input" value="1" <?php echo e(old('enable_notifications', $settings['enable_notifications'] ?? false) ? 'checked' : ''); ?>>
            <label for="enable_notifications" class="form-check-label">Enable Notifications</label>
        </div>
        <h4>SMTP & Mail Settings</h4>
        <div class="mb-3">
            <label for="smtp_host" class="form-label">SMTP Host</label>
            <input type="text" name="smtp_host" id="smtp_host" class="form-control" value="<?php echo e(old('smtp_host', $settings['smtp_host'] ?? '')); ?>">
        </div>
        <div class="mb-3">
            <label for="smtp_port" class="form-label">SMTP Port</label>
            <input type="text" name="smtp_port" id="smtp_port" class="form-control" value="<?php echo e(old('smtp_port', $settings['smtp_port'] ?? '')); ?>">
        </div>
        <div class="mb-3">
            <label for="smtp_user" class="form-label">SMTP User</label>
            <input type="text" name="smtp_user" id="smtp_user" class="form-control" value="<?php echo e(old('smtp_user', $settings['smtp_user'] ?? '')); ?>">
        </div>
        <div class="mb-3">
            <label for="smtp_pass" class="form-label">SMTP Password</label>
            <input type="password" name="smtp_pass" id="smtp_pass" class="form-control" value="<?php echo e(old('smtp_pass', $settings['smtp_pass'] ?? '')); ?>">
        </div>
        <div class="mb-3">
            <label for="smtp_encryption" class="form-label">SMTP Encryption</label>
            <select name="smtp_encryption" id="smtp_encryption" class="form-control">
                <option value="">None</option>
                <option value="ssl" <?php echo e((old('smtp_encryption', $settings['smtp_encryption'] ?? '') == 'ssl') ? 'selected' : ''); ?>>SSL</option>
                <option value="tls" <?php echo e((old('smtp_encryption', $settings['smtp_encryption'] ?? '') == 'tls') ? 'selected' : ''); ?>>TLS</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="smtp_from_address" class="form-label">SMTP From Address</label>
            <input type="email" name="smtp_from_address" id="smtp_from_address" class="form-control" value="<?php echo e(old('smtp_from_address', $settings['smtp_from_address'] ?? '')); ?>">
        </div>
        <h4>SMS Gateway Settings</h4>
        <div class="mb-3">
            <label for="sms_gateway_url" class="form-label">SMS Gateway URL</label>
            <input type="text" name="sms_gateway_url" id="sms_gateway_url" class="form-control" value="<?php echo e(old('sms_gateway_url', $settings['sms_gateway_url'] ?? '')); ?>">
        </div>
        <div class="mb-3">
            <label for="sms_api_key" class="form-label">SMS API Key</label>
            <input type="text" name="sms_api_key" id="sms_api_key" class="form-control" value="<?php echo e(old('sms_api_key', $settings['sms_api_key'] ?? '')); ?>">
        </div>
        <div class="mb-3">
            <label for="sms_sender_id" class="form-label">SMS Sender ID</label>
            <input type="text" name="sms_sender_id" id="sms_sender_id" class="form-control" value="<?php echo e(old('sms_sender_id', $settings['sms_sender_id'] ?? '')); ?>">
        </div>
        <h4>API Token</h4>
        <div class="mb-3">
            <label for="api_token" class="form-label">API Token</label>
            <input type="text" name="api_token" id="api_token" class="form-control" value="<?php echo e(old('api_token', $settings['api_token'] ?? '')); ?>">
        </div>
        <h4>Push Notification Settings</h4>
        <div class="mb-3">
            <label for="fcm_server_key" class="form-label">FCM Server Key</label>
            <input type="text" name="fcm_server_key" id="fcm_server_key" class="form-control" value="<?php echo e(old('fcm_server_key', $settings['fcm_server_key'] ?? '')); ?>">
        </div>
        <h4>SMS Provider: Africa's Talking</h4>
        <div class="mb-3">
            <label for="africastalking_username" class="form-label">Africa's Talking Username</label>
            <input type="text" name="africastalking_username" id="africastalking_username" class="form-control" value="<?php echo e(old('africastalking_username', $settings['africastalking_username'] ?? '')); ?>">
        </div>
        <div class="mb-3">
            <label for="africastalking_api_key" class="form-label">Africa's Talking API Key</label>
            <input type="text" name="africastalking_api_key" id="africastalking_api_key" class="form-control" value="<?php echo e(old('africastalking_api_key', $settings['africastalking_api_key'] ?? '')); ?>">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Save Settings</button>
            <button type="button" id="save-ajax" class="btn btn-primary">Save with AJAX</button>
        </div>
    </form>
</div>

<script src="<?php echo e(asset('js/settings-utils.js')); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('global-settings-form');
    const ajaxButton = document.getElementById('save-ajax');
    const ajaxAlert = document.getElementById('ajax-alert');
    
    // Show alert function
    function showAlert(message, type) {
        ajaxAlert.textContent = message;
        ajaxAlert.className = `alert alert-${type}`;
        ajaxAlert.style.display = 'block';
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            ajaxAlert.style.display = 'none';
        }, 5000);
    }
    
    // AJAX save function
    ajaxButton.addEventListener('click', function() {
        const formData = new FormData(form);
        
        // Show loading state
        ajaxButton.disabled = true;
        ajaxButton.textContent = 'Saving...';
        
        fetch('<?php echo e(route("settings.global.ajax")); ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                // Update form values with returned settings
                Object.keys(data.settings).forEach(key => {
                    const input = document.querySelector(`[name="${key}"]`);
                    if (input) {
                        if (input.type === 'checkbox') {
                            input.checked = data.settings[key] === '1';
                        } else {
                            input.value = data.settings[key];
                        }
                    }
                });
            } else {
                showAlert(data.message, 'danger');
            }
        })
        .catch(error => {
            showAlert('Error saving settings: ' + error.message, 'danger');
        })
        .finally(() => {
            ajaxButton.disabled = false;
            ajaxButton.textContent = 'Save with AJAX';
        });
    });
    
    // Auto-save on input change (debounced)
    let autoSaveTimeout;
    const inputs = form.querySelectorAll('input, select, textarea');
    
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(() => {
                // Only auto-save if AJAX button is available
                if (ajaxButton && !ajaxButton.disabled) {
                    ajaxButton.click();
                }
            }, 2000); // 2 second delay
        });
    });
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Settings/resources/views/global.blade.php ENDPATH**/ ?>