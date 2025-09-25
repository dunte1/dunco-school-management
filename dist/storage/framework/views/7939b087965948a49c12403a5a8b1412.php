<?php $__env->startSection('title', 'Edit User'); ?>
<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
<div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-user-edit fa-lg me-2"></i>
                <h4 class="mb-0">Edit User</h4>
                </div>
                <div class="card-body">
                <form action="<?php echo e(route('core.users.update', $user->id)); ?>" method="POST" autocomplete="off" enctype="multipart/form-data" id="editUserForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" name="password" placeholder="Leave blank to keep current password">
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Leave blank to keep the current password</div>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                        <div class="mb-3">
                            <label class="form-label">Role Assignments</label>
                            <div id="role-assignments">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $assignment = $user->roles->firstWhere('id', $role->id);
                                        $pivotSchoolId = $assignment ? $assignment->pivot->school_id : null;
                                    ?>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-5">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="role_assignments[<?php echo e($role->id); ?>][role_id]" value="<?php echo e($role->id); ?>" id="role_<?php echo e($role->id); ?>" <?php echo e($assignment ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="role_<?php echo e($role->id); ?>">
                                        <?php echo e($role->display_name ?? $role->name); ?>

                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <select class="form-select" name="role_assignments[<?php echo e($role->id); ?>][school_id]">
                                                <option value="" <?php echo e(is_null($pivotSchoolId) ? 'selected' : ''); ?>>Global</option>
                                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($school->id); ?>" <?php echo e($pivotSchoolId == $school->id ? 'selected' : ''); ?>><?php echo e($school->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <small class="form-text text-muted">Select roles and assign to a specific school or globally.</small>
                        </div>
                        <div class="mb-3">
                            <label for="primary_role_id" class="form-label">Primary Role</label>
                            <select class="form-select" id="primary_role_id" name="primary_role_id">
                                <option value="">Select Primary Role</option>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($role->id); ?>" <?php echo e(old('primary_role_id', $user->primary_role_id) == $role->id ? 'selected' : ''); ?>>
                                        <?php echo e($role->display_name ?? $role->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="form-text text-muted">Choose the main role for dashboard landing.</small>
                        </div>
                        <div class="mb-3">
                            <label for="school_id" class="form-label">School</label>
                            <select class="form-select <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="school_id" name="school_id">
                                <option value="">Select School</option>
                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($school->id); ?>" <?php echo e(old('school_id', $user->school_id) == $school->id ? 'selected' : ''); ?>><?php echo e($school->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="phone" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>" placeholder="e.g. +1234567890">
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="address" name="address" value="<?php echo e(old('address', $user->address)); ?>" placeholder="e.g. 123 Main St, City, Country">
                            <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="avatar" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="avatar" name="avatar">
                            <?php if($user->avatar): ?>
                                <img src="<?php echo e(asset('storage/' . $user->avatar)); ?>" alt="Avatar" class="rounded-circle mt-2" width="40" height="40">
                            <?php endif; ?>
                            <?php $__errorArgs = ['avatar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3 form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', $user->is_active) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_active">Active User</label>
                            </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="force_password_reset" name="force_password_reset" value="1" <?php echo e(old('force_password_reset', $user->force_password_reset) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="force_password_reset">Force password reset on next login</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notification Preferences</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_email" name="notify_email" value="1" <?php echo e(old('notify_email', $user->notify_email) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="notify_email">Email Notifications</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_sms" name="notify_sms" value="1" <?php echo e(old('notify_sms', $user->notify_sms) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="notify_sms">SMS Notifications</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="notify_push" name="notify_push" value="1" <?php echo e(old('notify_push', $user->notify_push) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="notify_push">App Notifications</label>
                            </div>
                        </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="<?php echo e(route('core.users.index')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?> 
<script>
// Simple form submission handler - minimal JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up form handlers');
    
    // Role assignment functionality
    function updatePrimaryRoleOptions() {
        const roleAssignments = document.querySelectorAll('#role-assignments input[type=checkbox]:checked');
        const primaryRoleSelect = document.getElementById('primary_role_id');
        const currentPrimary = '<?php echo e(old('primary_role_id', $user->primary_role_id)); ?>';
        
        if (!primaryRoleSelect) return;
        
        // If no roles are checked, show all available roles
        if (roleAssignments.length === 0) {
            primaryRoleSelect.disabled = false;
            return;
        }
        
        // Clear existing options and add only checked roles
        primaryRoleSelect.innerHTML = '<option value="">Select Primary Role</option>';
        
        roleAssignments.forEach(cb => {
            const roleId = cb.value;
            const labelElement = cb.closest('.form-check').querySelector('label');
            const label = labelElement ? labelElement.textContent.trim() : `Role ${roleId}`;
            const selected = (roleId == currentPrimary) ? 'selected' : '';
            primaryRoleSelect.innerHTML += `<option value="${roleId}" ${selected}>${label}</option>`;
        });
    }
    
    // Add event listeners to role checkboxes
    const roleCheckboxes = document.querySelectorAll('#role-assignments input[type=checkbox]');
    roleCheckboxes.forEach(cb => {
        cb.addEventListener('change', updatePrimaryRoleOptions);
    });
    
    // Initialize primary role options
    updatePrimaryRoleOptions();
    
    // Simple form submission - just show loading state
    const form = document.getElementById('editUserForm');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Core\resources\views\users\edit.blade.php ENDPATH**/ ?>