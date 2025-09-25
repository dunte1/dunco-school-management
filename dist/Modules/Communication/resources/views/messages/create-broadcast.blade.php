@extends('communication::layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Create Broadcast Announcement</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('communication.broadcasts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title</label>
                                    <input type="text" name="title" id="title" class="form-control" required maxlength="255" placeholder="Enter announcement title">
                                </div>
                                <div class="mb-3">
                                    <label for="content" class="form-label">Content</label>
                                    <textarea name="content" id="content" class="form-control" rows="8" required placeholder="Enter announcement content..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="attachments" class="form-label">Attachments</label>
                                    <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                    <small class="form-text text-muted">You can attach multiple files. Max size: 10MB each.</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select name="type" id="type" class="form-select" required>
                                        <option value="">Select type...</option>
                                        <option value="announcement">Announcement</option>
                                        <option value="emergency">Emergency</option>
                                        <option value="reminder">Reminder</option>
                                        <option value="notice">Notice</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="target_type" class="form-label">Target Audience</label>
                                    <select name="target_type" id="target_type" class="form-select" required>
                                        <option value="">Select target...</option>
                                        <option value="all">All Users</option>
                                        <option value="role">Specific Roles</option>
                                        <option value="class">Specific Classes</option>
                                        <option value="group">Specific Groups</option>
                                        <option value="individual">Specific Users</option>
                                    </select>
                                </div>
                                <div class="mb-3" id="target_data_container" style="display: none;">
                                    <label for="target_data" class="form-label">Select Targets</label>
                                    <div id="target_options">
                                        <!-- Dynamic options will be loaded here -->
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="scheduled_at" class="form-label">Schedule (Optional)</label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control">
                                    <small class="form-text text-muted">Leave empty to send immediately</small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('communication.broadcasts') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Create Broadcast</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('target_type').addEventListener('change', function() {
    const targetType = this.value;
    const container = document.getElementById('target_data_container');
    const options = document.getElementById('target_options');
    
    if (targetType && targetType !== 'all') {
        container.style.display = 'block';
        
        // Clear previous options
        options.innerHTML = '';
        
        switch(targetType) {
            case 'role':
                options.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_data[]" value="admin" id="role_admin">
                        <label class="form-check-label" for="role_admin">Administrators</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_data[]" value="teacher" id="role_teacher">
                        <label class="form-check-label" for="role_teacher">Teachers</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_data[]" value="student" id="role_student">
                        <label class="form-check-label" for="role_student">Students</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_data[]" value="parent" id="role_parent">
                        <label class="form-check-label" for="role_parent">Parents</label>
                    </div>
                `;
                break;
            case 'group':
                @foreach($groups as $group)
                options.innerHTML += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_data[]" value="{{ $group->id }}" id="group_{{ $group->id }}">
                        <label class="form-check-label" for="group_{{ $group->id }}">{{ $group->name }}</label>
                    </div>
                `;
                @endforeach
                break;
            case 'individual':
                @foreach($users as $user)
                options.innerHTML += `
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="target_data[]" value="{{ $user->id }}" id="user_{{ $user->id }}">
                        <label class="form-check-label" for="user_{{ $user->id }}">{{ $user->name }}</label>
                    </div>
                `;
                @endforeach
                break;
        }
    } else {
        container.style.display = 'none';
    }
});
</script>
@endpush
@endsection 