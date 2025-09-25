@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-bus me-2"></i>Transport Details</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.transport') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    @if($transportDetails->is_allocated)
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Your Assigned Route</h5></div>
        <div class="card-body">
             <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Route Name</small>
                        <p class="fs-5 fw-bold mb-0">{{ $transportDetails->route_name }}</p>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Vehicle Number</small>
                        <p class="fs-5 fw-bold mb-0">{{ $transportDetails->vehicle_number }}</p>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Driver Name</small>
                        <p class="fs-5 fw-bold mb-0">{{ $transportDetails->driver_name }}</p>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Driver Contact</small>
                        <p class="fs-5 fw-bold mb-0">{{ $transportDetails->driver_phone }}</p>
                    </div>
                </div>
                  <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Pickup Point & Time</small>
                        <p class="fs-5 fw-bold mb-0">{{ $transportDetails->pickup_point }} at {{ $transportDetails->pickup_time }}</p>
                    </div>
                </div>
                 <div class="col-md-6">
                    <div class="p-3 bg-light rounded">
                        <small class="text-muted">Drop-off Time</small>
                        <p class="fs-5 fw-bold mb-0">{{ $transportDetails->dropoff_time }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="text-center text-muted py-5"><i class="fas fa-bus-alt fa-3x mb-3"></i><p class="fs-4">No transport allocated.</p></div>
    @endif
</div>
@endsection 