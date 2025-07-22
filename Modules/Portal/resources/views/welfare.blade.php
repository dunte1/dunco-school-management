@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-heartbeat me-2"></i>Student Welfare</h3>
        @if(Auth::user()->hasRole('parent'))
        <form method="GET" action="{{ route('portal.welfare') }}" class="d-flex align-items-center gap-2">
            <label for="student_id" class="fw-semibold me-2">Viewing for:</label>
            <select name="student_id" id="student_id" class="form-select w-auto" onchange="this.form.submit()">
                @foreach($all_students as $child)
                    <option value="{{ $child->id }}" @if(request('student_id', $child->id) == $child->id) selected @endif>{{ $child->name }}</option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-header"><h5 class="mb-0">Counseling Request</h5></div>
                <div class="card-body">
                    <p class="mb-4">If you need to talk, our school counselor is available. Please fill out the form below to request a session.</p>
                    <form>
                        <div class="mb-3">
                            <label for="reason" class="form-label">Reason for Request</label>
                            <textarea class="form-control" id="reason" rows="4" placeholder="Briefly describe what you would like to discuss..."></textarea>
                        </div>
                        <div class="mb-3">
                             <label class="form-label">Preferred Contact Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="contact_method" id="contact_email" checked>
                                <label class="form-check-label" for="contact_email">Email</label>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="radio" name="contact_method" id="contact_phone">
                                <label class="form-check-label" for="contact_phone">Phone Call</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card mb-4">
                <div class="card-header"><h5 class="mb-0">Counselor Information</h5></div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-user-tie me-2"></i><strong>Name:</strong> {{ $counselor->name }}</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i><strong>Email:</strong> {{ $counselor->email }}</li>
                        <li class="mb-2"><i class="fas fa-phone me-2"></i><strong>Phone:</strong> {{ $counselor->phone }}</li>
                        <li><i class="fas fa-clock me-2"></i><strong>Availability:</strong> {{ $counselor->availability }}</li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><h5 class="mb-0">Helplines</h5></div>
                <div class="card-body">
                     <ul class="list-group list-group-flush">
                        @foreach($helplines as $helpline)
                         <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $helpline->name }}
                            <span class="fw-bold">{{ $helpline->number }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 