@extends('portal::components.layouts.master')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0"><i class="fas fa-hotel me-2"></i>Hostel & Cafeteria</h3>
        @if(Auth::check() && Auth::user()->hasRole('parent'))
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
        <!-- Hostel Allocation Card -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bed me-2"></i>Hostel Allocation</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Hostel:</strong>
                            <span class="badge bg-primary">{{ $hostelDetails->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Room:</strong>
                            <span>{{ $hostelDetails->room_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Bed:</strong>
                            <span>{{ $hostelDetails->bed_number }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Type:</strong>
                            <span>{{ $hostelDetails->room_type }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Warden:</strong>
                            <span>{{ $hostelDetails->warden }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <strong>Check-in:</strong>
                            <span>{{ $hostelDetails->check_in ? $hostelDetails->check_in->format('M d, Y') : 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Hostel Fees Card -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-money-bill me-2"></i>Hostel Fees</h5>
                </div>
                <div class="card-body">
                    @if($hostelFees->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Fee Type</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hostelFees->take(3) as $fee)
                                    <tr>
                                        <td>{{ $fee->fee_type }}</td>
                                        <td>KSh {{ number_format($fee->amount) }}</td>
                                        <td>
                                            @if($fee->status == 'paid')
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($fee->status == 'partial')
                                                <span class="badge bg-warning">Partial</span>
                                            @else
                                                <span class="badge bg-danger">Unpaid</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($hostelFees->count() > 3)
                            <small class="text-muted">+{{ $hostelFees->count() - 3 }} more fees</small>
                        @endif
                    @else
                        <p class="text-muted mb-0">No hostel fees found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Issues Card -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Recent Issues</h5>
                </div>
                <div class="card-body">
                    @if($hostelIssues->count() > 0)
                        @foreach($hostelIssues->take(3) as $issue)
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong>{{ $issue->title }}</strong>
                                <br>
                                <small class="text-muted">{{ $issue->description }}</small>
                            </div>
                            <span class="badge bg-{{ $issue->priority == 'high' ? 'danger' : ($issue->priority == 'medium' ? 'warning' : 'info') }}">
                                {{ ucfirst($issue->priority) }}
                            </span>
                        </div>
                        @endforeach
                        @if($hostelIssues->count() > 3)
                            <small class="text-muted">+{{ $hostelIssues->count() - 3 }} more issues</small>
                        @endif
                    @else
                        <p class="text-muted mb-0">No issues reported.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row g-4 mt-2">
        <!-- Cafeteria Menu -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-utensils me-2"></i>Cafeteria Menu (This Week)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Breakfast</th>
                                    <th>Lunch</th>
                                    <th>Dinner</th>
                                </tr>
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

        <!-- Announcements & Leave Requests -->
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Announcements</h6>
                </div>
                <div class="card-body">
                    @if($hostelAnnouncements->count() > 0)
                        @foreach($hostelAnnouncements->take(3) as $announcement)
                        <div class="mb-2">
                            <strong>{{ $announcement->title }}</strong>
                            <br>
                            <small class="text-muted">{{ Str::limit($announcement->content, 100) }}</small>
                            <br>
                            <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No announcements.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Leave Requests</h6>
                </div>
                <div class="card-body">
                    @if($leaveRequests->count() > 0)
                        @foreach($leaveRequests->take(3) as $request)
                        <div class="mb-2">
                            <strong>{{ $request->reason }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $request->start_date->format('M d') }} - {{ $request->end_date->format('M d, Y') }}
                            </small>
                            <br>
                            <span class="badge bg-{{ $request->status == 'approved' ? 'success' : ($request->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">No leave requests.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="text-center text-muted py-5">
        <i class="fas fa-bed fa-3x mb-3"></i>
        <p class="fs-4">No hostel allocated.</p>
        <p class="text-muted">Please contact the administration for hostel allocation.</p>
    </div>
    @endif
</div>
@endsection 