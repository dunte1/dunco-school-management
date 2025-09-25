<?php if (isset($component)) { $__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'core::components.layouts.master','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('core::layouts.master'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Role: <?php echo e($role->display_name ?? $role->name); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('core.roles.update', $role->id)); ?>" method="POST" id="roleEditForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name *</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', $role->name)); ?>" required>
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
                            <label for="display_name" class="form-label">Display Name</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['display_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="display_name" name="display_name" value="<?php echo e(old('display_name', $role->display_name)); ?>">
                            <?php $__errorArgs = ['display_name'];
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
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="description" name="description"><?php echo e(old('description', $role->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
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
                            <label for="permissions" class="form-label">Permissions</label>
                            <select class="form-select <?php $__errorArgs = ['permissions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="permissions" name="permissions[]" multiple>
                                <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($permission->id); ?>" <?php echo e(in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'selected' : ''); ?>><?php echo e($permission->display_name ?? $permission->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['permissions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <div class="form-text">Hold Ctrl (or Cmd on Mac) to select multiple permissions</div>
                        </div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#clonePermissionsModal">
                                <i class="fas fa-clone"></i> Clone Permissions
                            </button>
                        </div>
                        <!-- Clone Permissions Modal -->
                        <div class="modal fade" id="clonePermissionsModal" tabindex="-1" aria-labelledby="clonePermissionsModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?php echo e(route('core.roles.clone_permissions', $role->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="clonePermissionsModalLabel">Clone Permissions from Another Role</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="source_role_id" class="form-label">Source Role</label>
                                                <select class="form-select" id="source_role_id" name="source_role_id" required>
                                                    <option value="">Select Role</option>
                                                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $otherRole): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($otherRole->id !== $role->id): ?>
                                                            <option value="<?php echo e($otherRole->id); ?>"><?php echo e($otherRole->display_name ?? $otherRole->name); ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Clone Permissions</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('core.roles.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary" id="updateRoleBtn">
                                <i class="fas fa-save"></i> Update Role
                            </button>
                        </div>
                        <!-- Debug info -->
                        <div class="mt-3 p-2 bg-light border rounded">
                            <small class="text-muted">
                                <strong>Debug Info:</strong><br>
                                Form Action: <?php echo e(route('core.roles.update', $role->id)); ?><br>
                                Method: POST<br>
                                CSRF Token: <?php echo e(csrf_token() ? 'Present' : 'Missing'); ?><br>
                                Role ID: <?php echo e($role->id); ?>

                            </small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('roleEditForm');
            const updateBtn = document.getElementById('updateRoleBtn');
            const nameField = document.getElementById('name');
            
            console.log('Form found:', form);
            console.log('Update button found:', updateBtn);
            console.log('Name field found:', nameField);
            
            function validateForm() {
                let isValid = true;
                
                // Check if name field has a value
                if (!nameField || !nameField.value.trim()) {
                    isValid = false;
                }
                
                console.log('Form validation - isValid:', isValid, 'Name value:', nameField ? nameField.value : 'no field');
                
                if (updateBtn) {
                    updateBtn.disabled = !isValid;
                    updateBtn.classList.toggle('btn-primary', isValid);
                    updateBtn.classList.toggle('btn-secondary', !isValid);
                }
            }
            
            // Add event listeners to the name field
            if (nameField) {
                nameField.addEventListener('input', validateForm);
                nameField.addEventListener('change', validateForm);
                nameField.addEventListener('blur', validateForm);
            }
            
            // Run validation on page load
            validateForm();
            
            // Handle form submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submission started');
                    if (updateBtn) {
                        updateBtn.disabled = true;
                        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
                    }
                });
            }
            
            // Add click handler to update button for debugging
            if (updateBtn) {
                updateBtn.addEventListener('click', function(e) {
                    console.log('Update button clicked');
                    if (form) {
                        console.log('Submitting form...');
                        form.submit();
                    }
                });
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $attributes = $__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $component = $__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?> <?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Core\resources\views\roles\edit.blade.php ENDPATH**/ ?>