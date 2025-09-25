@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.index') }}">Transport</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('transport.vehicles.index') }}">Vehicles</a></li>
                        <li class="breadcrumb-item active">Add Vehicle</li>
                    </ol>
                </div>
                <h4 class="page-title">Add New Vehicle</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('transport.vehicles.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_number" class="form-label">Vehicle Number *</label>
                                    <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" 
                                           id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}" required>
                                    @error('vehicle_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="vehicle_type" class="form-label">Vehicle Type *</label>
                                    <select class="form-select @error('vehicle_type') is-invalid @enderror" 
                                            id="vehicle_type" name="vehicle_type" required>
                                        <option value="">Select Type</option>
                                        <option value="bus" {{ old('vehicle_type') == 'bus' ? 'selected' : '' }}>Bus</option>
                                        <option value="van" {{ old('vehicle_type') == 'van' ? 'selected' : '' }}>Van</option>
                                        <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>Car</option>
                                        <option value="minibus" {{ old('vehicle_type') == 'minibus' ? 'selected' : '' }}>Minibus</option>
                                    </select>
                                    @error('vehicle_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand *</label>
                                    <input type="text" class="form-control @error('brand') is-invalid @enderror" 
                                           id="brand" name="brand" value="{{ old('brand') }}" required>
                                    @error('brand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Model *</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           id="model" name="model" value="{{ old('model') }}" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="year" class="form-label">Year *</label>
                                    <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                           id="year" name="year" value="{{ old('year') }}" min="1900" max="{{ date('Y') + 1 }}" required>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="registration_number" class="form-label">Registration Number *</label>
                                    <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                           id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required>
                                    @error('registration_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Passenger Capacity *</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity') }}" min="1" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="driver_id" class="form-label">Assigned Driver</label>
                                    <select class="form-select @error('driver_id') is-invalid @enderror" 
                                            id="driver_id" name="driver_id">
                                        <option value="">Select Driver</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }} ({{ $driver->license_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('driver_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fuel_type" class="form-label">Fuel Type *</label>
                                    <select class="form-select @error('fuel_type') is-invalid @enderror" 
                                            id="fuel_type" name="fuel_type" required>
                                        <option value="">Select Fuel Type</option>
                                        <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                                        <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                                        <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                                        <option value="hybrid" {{ old('fuel_type') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                    </select>
                                    @error('fuel_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="insurance_expiry" class="form-label">Insurance Expiry *</label>
                                    <input type="date" class="form-control @error('insurance_expiry') is-invalid @enderror" 
                                           id="insurance_expiry" name="insurance_expiry" value="{{ old('insurance_expiry') }}" required>
                                    @error('insurance_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fitness_expiry" class="form-label">Fitness Expiry *</label>
                                    <input type="date" class="form-control @error('fitness_expiry') is-invalid @enderror" 
                                           id="fitness_expiry" name="fitness_expiry" value="{{ old('fitness_expiry') }}" required>
                                    @error('fitness_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="permit_expiry" class="form-label">Permit Expiry *</label>
                                    <input type="date" class="form-control @error('permit_expiry') is-invalid @enderror" 
                                           id="permit_expiry" name="permit_expiry" value="{{ old('permit_expiry') }}" required>
                                    @error('permit_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mileage" class="form-label">Current Mileage</label>
                                    <input type="number" class="form-control @error('mileage') is-invalid @enderror" 
                                           id="mileage" name="mileage" value="{{ old('mileage', 0) }}" min="0">
                                    @error('mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <a href="{{ route('transport.vehicles.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Vehicle</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 