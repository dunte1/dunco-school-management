<x-core::layouts.master>
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Setting: {{ $setting->display_name ?? $setting->key }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('core.settings.update', $setting->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Setting Key</label>
                            <input type="text" class="form-control" value="{{ $setting->key }}" readonly>
                            <div class="form-text">This is a system setting and cannot be changed</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" class="form-control" value="{{ $setting->description ?? 'No description' }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="value" class="form-label">Value *</label>
                            @if($setting->type === 'boolean')
                                <select class="form-select @error('value') is-invalid @enderror" id="value" name="value" required>
                                    <option value="1" {{ old('value', $setting->value) == '1' ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ old('value', $setting->value) == '0' ? 'selected' : '' }}>Disabled</option>
                                </select>
                            @elseif($setting->type === 'json')
                                <textarea class="form-control @error('value') is-invalid @enderror" id="value" name="value" rows="5" required>{{ old('value', $setting->value) }}</textarea>
                                <div class="form-text">Enter valid JSON format</div>
                            @else
                                <input type="text" class="form-control @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value', $setting->value) }}" required>
                            @endif
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('core.settings.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Setting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-core::layouts.master> 