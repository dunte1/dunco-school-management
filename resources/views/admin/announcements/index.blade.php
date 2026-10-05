@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0"><i class="fas fa-bullhorn me-2"></i>Announcements</h1>
        <a href="{{ url('/admin/announcements/create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>New Announcement
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Search announcements...">
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option value="">All Priorities</option>
                        <option>High</option>
                        <option>Medium</option>
                        <option>Low</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-outline-secondary">Filter</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Priority</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($announcements ?? []) as $item)
                            <tr>
                                <td>{{ $item['title'] ?? '' }}</td>
                                <td>{{ $item['priority'] ?? '' }}</td>
                                <td>{{ $item['date'] ?? '' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-muted">No announcements found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ ($announcements ?? null) ? $announcements->links() : '' }}
            </div>
        </div>
    </div>
</div>
@endsection
