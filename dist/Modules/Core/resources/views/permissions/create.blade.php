<x-core::layouts.master>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus"></i> Create New Permission
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('core.permissions.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Permission Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="display_name" class="form-label">Display Name</label>
                            <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name') }}">
                            @error('display_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="module" class="form-label">Module</label>
                            <input type="text" class="form-control @error('module') is-invalid @enderror" id="module" name="module" value="{{ old('module', 'Core') }}">
                            @error('module')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('core.permissions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Permission
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-core::layouts.master> 