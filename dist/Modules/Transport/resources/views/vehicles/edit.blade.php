@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.vehicles.index') }}">Vehicles</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.vehicles.show', $vehicle) }}">{{ $vehicle->vehicle_number }}</a></li>
                        <li class="breadcrumb-item active">Edit Vehicle</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Vehicle</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="header-title">Edit Vehicle Information</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('transport.vehicles.update', $vehicle) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_number" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                           id="vehicle_number" name="vehicle_number" 
                                           value="{{ old('vehicle_number', $vehicle->vehicle_number) }}" required>
                                    @error('vehicle_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="registration_number" class="form-label">Registration Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                           id="registration_number" name="registration_number" 
                                           value="{{ old('registration_number', $vehicle->registration_number) }}" required>
                                    @error('registration_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="vehicle_type" class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('vehicle_type') is-invalid @enderror" 
                                            id="vehicle_type" name="vehicle_type" required>
                                        <option value="">Select Vehicle Type</option>
                                        <option value="bus" {{ old('vehicle_type', $vehicle->vehicle_type) == 'bus' ? 'selected' : '' }}>Bus</option>
                                        <option value="van" {{ old('vehicle_type', $vehicle->vehicle_type) == 'van' ? 'selected' : '' }}>Van</option>
                                        <option value="car" {{ old('vehicle_type', $vehicle->vehicle_type) == 'car' ? 'selected' : '' }}>Car</option>
                                        <option value="minibus" {{ old('vehicle_type', $vehicle->vehicle_type) == 'minibus' ? 'selected' : '' }}>Minibus</option>
                                    </select>
                                    @error('vehicle_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                           id="brand" name="brand" 
                                           value="{{ old('brand', $vehicle->brand) }}" required>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="model" class="form-label">Model <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           id="model" name="model" 
                                           value="{{ old('model', $vehicle->model) }}" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                           id="year" name="year" 
                                           value="{{ old('year', $vehicle->year) }}" 
                                           min="1900" max="{{ date('Y') + 1 }}" required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Capacity (Passengers) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" 
                                           value="{{ old('capacity', $vehicle->capacity) }}" 
                                           min="1" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_id" class="form-label">Assigned Driver</label>
                                    <select class="form-select @error('driver_id') is-invalid @enderror" 
                                            id="driver_id" name="driver_id">
                                        <option value="">Select Driver</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" 
                                                {{ old('driver_id', $vehicle->driver_id) == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }} ({{ $driver->license_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="active" {{ old('status', $vehicle->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="maintenance" {{ old('status', $vehicle->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="inactive" {{ old('status', $vehicle->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="fuel_type" class="form-label">Fuel Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('fuel_type') is-invalid @enderror" 
                                            id="fuel_type" name="fuel_type" required>
                                        <option value="">Select Fuel Type</option>
                                        <option value="petrol" {{ old('fuel_type', $vehicle->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                        <option value="diesel" {{ old('fuel_type', $vehicle->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="electric" {{ old('fuel_type', $vehicle->fuel_type) == 'electric' ? 'selected' : '' }}>Electric</option>
                                        <option value="hybrid" {{ old('fuel_type', $vehicle->fuel_type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                    @error('fuel_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="mileage" class="form-label">Mileage (km)</label>
                                    <input type="number" class="form-control @error('mileage') is-invalid @enderror" 
                                           id="mileage" name="mileage" 
                                           value="{{ old('mileage', $vehicle->mileage) }}" 
                                           min="0">
                                    @error('mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="insurance_expiry" class="form-label">Insurance Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('insurance_expiry') is-invalid @enderror" 
                                           id="insurance_expiry" name="insurance_expiry" 
                                           value="{{ old('insurance_expiry', $vehicle->insurance_expiry->format('Y-m-d')) }}" required>
                                    @error('insurance_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="fitness_expiry" class="form-label">Fitness Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('fitness_expiry') is-invalid @enderror" 
                                           id="fitness_expiry" name="fitness_expiry" 
                                           value="{{ old('fitness_expiry', $vehicle->fitness_expiry->format('Y-m-d')) }}" required>
                                    @error('fitness_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="permit_expiry" class="form-label">Permit Expiry Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('permit_expiry') is-invalid @enderror" 
                                           id="permit_expiry" name="permit_expiry" 
                                           value="{{ old('permit_expiry', $vehicle->permit_expiry->format('Y-m-d')) }}" required>
                                    @error('permit_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description', $vehicle->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="route_ids" class="form-label">Assigned Routes</label>
                                    <select class="form-select @error('route_ids') is-invalid @enderror" 
                                            id="route_ids" name="route_ids[]" multiple>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}" 
                                                {{ in_array($route->id, old('route_ids', $vehicle->routes->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $route->name }} ({{ $route->stops->count() }} stops)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('route_ids')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple routes.</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('transport.vehicles.show', $vehicle) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Vehicle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Initialize select2 for multiple route selection
    $(document).ready(function() {
        $('#route_ids').select2({
            placeholder: 'Select routes...',
            allowClear: true
        });
    });
</script>
@endpush 