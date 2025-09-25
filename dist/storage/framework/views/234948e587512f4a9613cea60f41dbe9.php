<?php ($errors = $errors ?? app('view')->shared('errors', new \Illuminate\Support\ViewErrorBag)); ?>



<?php $__env->startSection('content'); ?>
<div class="container d-flex justify-content-center align-items-start" style="min-height: 80vh;">
    <div class="w-100" style="max-width: 600px;">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('core.users.index')); ?>">Users</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create User</li>
            </ol>
        </nav>
        <div class="card shadow rounded-4">
                <div class="card-body">
                <h2 class="mb-4"><i class="fas fa-user-plus"></i> Create New User</h2>
                    <form action="<?php echo e(route('core.users.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password *</label>
                        <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="password" name="password" required>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password *</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role Assignments</label>
                            <div id="role-assignments">
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-5">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="role_assignments[<?php echo e($role->id); ?>][role_id]" value="<?php echo e($role->id); ?>" id="role_<?php echo e($role->id); ?>">
                                                <label class="form-check-label" for="role_<?php echo e($role->id); ?>">
                                                    <?php echo e($role->display_name ?? $role->name); ?>

                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-7">
                                            <select class="form-select" name="role_assignments[<?php echo e($role->id); ?>][school_id]">
                                                <option value="">Global</option>
                                                <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
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
                            <select class="form-select" id="primary_role_id" name="primary_role_id" required>
                                <option value="">Select Primary Role</option>
                                <!-- Options will be populated by JS based on selected roles -->
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
                                    <option value="<?php echo e($school->id); ?>" <?php echo e(old('school_id') == $school->id ? 'selected' : ''); ?>><?php echo e($school->name); ?></option>
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
unset($__errorArgs, $__bag); ?>" id="phone" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="e.g. +1234567890">
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
unset($__errorArgs, $__bag); ?>" id="address" name="address" value="<?php echo e(old('address')); ?>" placeholder="e.g. 123 Main St, City, Country">
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
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?php echo e(old('is_active', true) ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_active">Active User</label>
                        </div>
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" id="force_password_reset" name="force_password_reset" value="1" <?php echo e(old('force_password_reset') ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="force_password_reset">Force password reset on next login</label>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('core.users.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?> 

<script>
// Dynamically populate primary role dropdown based on checked roles
function updatePrimaryRoleOptions() {
    const roleAssignments = document.querySelectorAll('#role-assignments input[type=checkbox]:checked');
    const primaryRoleSelect = document.getElementById('primary_role_id');
    primaryRoleSelect.innerHTML = '<option value="">Select Primary Role</option>';
    roleAssignments.forEach(cb => {
        const roleId = cb.value;
        const label = cb.closest('.row').querySelector('label').innerText;
        primaryRoleSelect.innerHTML += `<option value="${roleId}">${label}</option>`;
    });
}
document.querySelectorAll('#role-assignments input[type=checkbox]').forEach(cb => {
    cb.addEventListener('change', updatePrimaryRoleOptions);
});
updatePrimaryRoleOptions();
</script> 
<?php echo $__env->make('core::layouts.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Core\resources\views\users\create.blade.php ENDPATH**/ ?>