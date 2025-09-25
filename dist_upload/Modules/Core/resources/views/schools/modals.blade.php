<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSchoolModal" tabindex="-1" aria-labelledby="deleteSchoolModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteSchoolModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <strong id="deleteSchoolName"></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- Add School Modal -->
<div class="modal fade" id="addSchoolModal" tabindex="-1" aria-labelledby="addSchoolModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSchoolModalLabel"><i class="fas fa-plus"></i> Create New School</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSchoolForm" action="{{ route('core.schools.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">School Name *</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="code" class="form-label">School Code *</label>
                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="motto" class="form-label">Motto</label>
                <input type="text" class="form-control @error('motto') is-invalid @enderror" id="motto" name="motto" value="{{ old('motto') }}">
                @error('motto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="domain" class="form-label">Domain</label>
                <input type="text" class="form-control @error('domain') is-invalid @enderror" id="domain" name="domain" value="{{ old('domain') }}" placeholder="e.g., school.example.com">
                @error('domain')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Create School
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div> 