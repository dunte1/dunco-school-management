<x-core::layouts.master>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Role: {{ $role->display_name ?? $role->name }}
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Please fix the following errors:
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('core.roles.update', $role->id) }}" method="POST" id="roleEditForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="display_name" class="form-label">Display Name</label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name', $role->display_name) }}">
                            @error('display_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $role->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="permissions" class="form-label">Permissions</label>
                            <select class="form-select @error('permissions') is-invalid @enderror" id="permissions" name="permissions[]" multiple>
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->id }}" {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'selected' : '' }}>{{ $permission->display_name ?? $permission->name }}</option>
                                @endforeach
                            </select>
                            @error('permissions')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                    <form action="{{ route('core.roles.clone_permissions', $role->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="clonePermissionsModalLabel">Clone Permissions from Another Role</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="source_role_id" class="form-label">Source Role</label>
                                                <select class="form-select" id="source_role_id" name="source_role_id" required>
                                                    <option value="">Select Role</option>
                                                    @foreach($roles as $otherRole)
                                                        @if($otherRole->id !== $role->id)
                                                            <option value="{{ $otherRole->id }}">{{ $otherRole->display_name ?? $otherRole->name }}</option>
                                                        @endif
                                                    @endforeach
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
                            <a href="{{ route('core.roles.index') }}" class="btn btn-secondary">
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
                                Form Action: {{ route('core.roles.update', $role->id) }}<br>
                                Method: POST<br>
                                CSRF Token: {{ csrf_token() ? 'Present' : 'Missing' }}<br>
                                Role ID: {{ $role->id }}
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
</x-core::layouts.master> 