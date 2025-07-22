@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-hotel me-2"></i>Hostel & Cafeteria</h3>
        @if(Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.hostel') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    @if($hostelDetails->is_allocated)
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Hostel Allocation</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between"><strong>Hostel:</strong><span>{{ $hostelDetails->name }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Room:</strong><span>{{ $hostelDetails->room_number }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Type:</strong><span>{{ $hostelDetails->room_type }}</span></li>
                        <li class="list-group-item d-flex justify-content-between"><strong>Warden:</strong><span>{{ $hostelDetails->warden }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Cafeteria Menu (This Week)</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr><th>Day</th><th>Breakfast</th><th>Lunch</th><th>Dinner</th></tr>
                            </thead>
                            <tbody>
                                @foreach($menu as $day => $meals)
                                <tr>
                                    <td><strong>{{ $day }}</strong></td>
                                    <td>{{ $meals['Breakfast'] }}</td>
                                    <td>{{ $meals['Lunch'] }}</td>
                                    <td>{{ $meals['Dinner'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
     <div class="text-center text-muted py-5"><i class="fas fa-bed fa-3x mb-3"></i><p class="fs-4">No hostel allocated.</p></div>
    @endif
</div>
@endsection 