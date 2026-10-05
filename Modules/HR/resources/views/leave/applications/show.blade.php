@extends('layouts.app')

@section('title', 'Leave Application Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Leave Application Details</h4>
                    <div>
                        <a href="{{ route('hr.leave.applications.edit', $leave->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('hr.leave.applications.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Staff Member:</th>
                                    <td>{{ $leave->staff->first_name }} {{ $leave->staff->last_name }}</td>
                                </tr>
                                <tr>
                                    <th>Leave Type:</th>
                                    <td>{{ $leave->leaveType->name ?? $leave->type }}</td>
                                </tr>
                                <tr>
                                    <th>Start Date:</th>
                                    <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>End Date:</th>
                                    <td>{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Total Days:</th>
                                    <td>{{ $leave->days }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Status:</th>
                                    <td>
                                        <span class="badge 
                                            @if($leave->status == 'approved') bg-success
                                            @elseif($leave->status == 'rejected') bg-danger
                                            @else bg-warning
                                            @endif">
                                            {{ ucfirst($leave->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @if($leave->approved_by)
                                    <tr>
                                        <th>Approved By:</th>
                                        <td>{{ $leave->approvedBy->name ?? 'Unknown' }}</td>
                                    </tr>
                                @endif
                                @if($leave->approved_at)
                                    <tr>
                                        <th>Approved At:</th>
                                        <td>{{ \Carbon\Carbon::parse($leave->approved_at)->format('M d, Y H:i') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ \Carbon\Carbon::parse($leave->updated_at)->format('M d, Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @if($leave->reason)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Reason:</h5>
                                <div class="border p-3 rounded">
                                    {{ $leave->reason }}
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    @if($leave->status == 'pending')
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Actions:</h5>
                                <div class="btn-group" role="group">
                                    <form method="POST" action="{{ route('hr.leave.approve', $leave->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" onclick="return confirm('Approve this leave application?')">
                                            <i class="fas fa-check me-2"></i>Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('hr.leave.reject', $leave->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this leave application?')">
                                            <i class="fas fa-times me-2"></i>Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
