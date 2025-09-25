@extends('layouts.app')
@section('content')
<div class="container py-4">
    <div class="attendance-header-gradient rounded shadow-sm mb-4 p-3 d-flex align-items-center">
        <h1 class="mb-0 text-uppercase attendance-accent"><i class="bi bi-inbox"></i> Absence Excuse Requests</h1>
    </div>
    <form method="GET" action="" class="mb-3 d-flex flex-wrap align-items-end gap-3">
        <div>
            <div class="form-floating">
                <select name="status" id="status" class="form-select">
                    <option value="">All</option>
                    <option value="pending" @if(request('status')=='pending') selected @endif>Pending</option>
                    <option value="approved" @if(request('status')=='approved') selected @endif>Approved</option>
                    <option value="rejected" @if(request('status')=='rejected') selected @endif>Rejected</option>
                </select>
                <label for="status">Status</label>
            </div>
        </div>
        <div>
            <div class="form-floating">
                <input type="date" name="date" id="date" value="{{ request('date') }}" class="form-control">
                <label for="date">Date</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-modern rounded-pill px-4"><i class="bi bi-funnel"></i> Filter</button>
    </form>
    <div class="card-modern p-4">
        <div class="table-responsive">
            <table class="table table-modern align-middle">
                <thead>
                    <tr>
                        <th>Student/Staff</th>
                        <th>Date</th>
                        <th>Period</th>
                        <th>Reason</th>
                        <th>Document</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($excuses as $excuse)
                    <tr>
                        <td>
                            @if($excuse->student)
                                <span class="text-info"><i class="bi bi-person"></i> {{ $excuse->student->full_name ?? $excuse->student->name }}</span>
                            @elseif($excuse->staff)
                                <span class="text-warning"><i class="bi bi-person-badge"></i> {{ $excuse->staff->first_name }} {{ $excuse->staff->last_name }}</span>
                            @endif
                        </td>
                        <td>{{ $excuse->date->format('Y-m-d') }}</td>
                        <td>{{ $excuse->period }}</td>
                        <td>{{ $excuse->reason }}</td>
                        <td>
                            @if($excuse->document)
                                <a href="{{ asset('storage/'.$excuse->document) }}" target="_blank" class="btn btn-outline-info btn-sm btn-modern">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-{{ $excuse->status == 'pending' ? 'warning text-dark' : ($excuse->status == 'approved' ? 'success' : 'danger') }}">
                                <i class="bi {{ $excuse->status == 'pending' ? 'bi-hourglass-split' : ($excuse->status == 'approved' ? 'bi-check-circle' : 'bi-x-circle') }}"></i>
                                {{ ucfirst($excuse->status) }}
                            </span>
                        </td>
                        <td>
                            @if($excuse->status == 'pending')
                            <form method="POST" action="{{ route('academic.attendance.excuse.action', $excuse->id) }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn btn-success btn-sm btn-modern"><i class="bi bi-check"></i> Approve</button>
                            </form>
                            <form method="POST" action="{{ route('academic.attendance.excuse.action', $excuse->id) }}" class="d-inline ms-1">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn btn-danger btn-sm btn-modern"><i class="bi bi-x"></i> Reject</button>
                            </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No excuses found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $excuses->links() }}
        </div>
    </div>
</div>
@endsection
@push('styles')
<style>
.card-modern {
    background: var(--color-surface, #fff);
    border-radius: 18px;
    box-shadow: var(--color-shadow, 0 8px 32px 0 rgba(30,167,255,0.10), 0 1.5px 6px 0 rgba(21,101,192,0.10));
    margin-bottom: 24px;
}
.table-modern th, .table-modern td {
    vertical-align: middle;
    background: transparent;
}
.table-modern tbody tr:nth-child(even) {
    background: #f4f8fb;
}
.btn-modern {
    font-weight: 500;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(30,167,255,0.08);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
}
.btn-primary { background: var(--color-primary, #1ea7ff); border: none; }
.btn-primary:hover, .btn-primary:focus { background: var(--color-accent, #1565c0); color: #fff; }
.btn-outline-info:hover, .btn-outline-info:focus { background: #6ec1e4; color: #0a1931; }
.badge.bg-warning { background: #ffb300; color: #222; }
.badge.bg-success { background: #22c55e; }
.badge.bg-danger { background: #e53935; }
</style>
@endpush 