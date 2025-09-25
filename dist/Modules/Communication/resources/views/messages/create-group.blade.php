@extends('communication::layouts.master')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Create New Group</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('communication.groups.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Group Name</label>
                            <input type="text" name="name" id="name" class="form-control" required maxlength="255" placeholder="Enter group name">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Describe the purpose of this group"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="members" class="form-label">Add Members</label>
                            <select name="members[]" id="members" class="form-select" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple members.</small>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('communication.groups') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Create Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // If you have Select2 or similar, you can initialize it here
    // $('#members').select2({ placeholder: 'Select members' });
</script>
@endpush
@endsection 