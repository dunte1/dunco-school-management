@extends('communication::layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Contacts</h1>
    <div class="d-flex gap-2">
        <form method="GET" class="d-flex gap-2">
            <select name="category" class="form-select">
                <option value="">All Categories</option>
                <option value="student" {{ request('category') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="parent" {{ request('category') == 'parent' ? 'selected' : '' }}>Parent</option>
                <option value="teacher" {{ request('category') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                <option value="staff" {{ request('category') == 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="admin" {{ request('category') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="external" {{ request('category') == 'external' ? 'selected' : '' }}>External</option>
            </select>
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search contacts...">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </form>
        <div class="btn-group">
            <a href="{{ route('communication.contacts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add Contact
            </a>
            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('communication.contacts.export') }}">
                    <i class="fas fa-download me-2"></i>Export Contacts
                </a></li>
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fas fa-upload me-2"></i>Import Contacts
                </a></li>
            </ul>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($contacts->count())
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact Info</th>
                    <th>Organization</th>
                    <th>Category</th>
                    <th>Groups</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                    <tr>
                        <td>
                            <strong>{{ $contact->name }}</strong>
                            @if($contact->position)
                                <br>
                                <small class="text-muted">{{ $contact->position }}</small>
                            @endif
                        </td>
                        <td>
                            @if($contact->email)
                                <div><i class="fas fa-envelope me-1"></i>{{ $contact->email }}</div>
                            @endif
                            @if($contact->phone)
                                <div><i class="fas fa-phone me-1"></i>{{ $contact->phone }}</div>
                            @endif
                        </td>
                        <td>
                            @if($contact->organization)
                                <span class="badge bg-light text-dark">{{ $contact->organization }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucfirst($contact->category) }}</span>
                        </td>
                        <td>
                            @if($contact->groups->count())
                                @foreach($contact->groups->take(2) as $group)
                                    <span class="badge bg-secondary">{{ $group->name }}</span>
                                @endforeach
                                @if($contact->groups->count() > 2)
                                    <span class="badge bg-secondary">+{{ $contact->groups->count() - 2 }} more</span>
                                @endif
                            @else
                                <span class="text-muted">No groups</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $contact->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $contact->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>{{ $contact->creator->name ?? 'Unknown' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('communication.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('communication.contacts.edit', $contact) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('communication.contacts.toggle-status', $contact) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-toggle-{{ $contact->is_active ? 'off' : 'on' }}"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('communication.contacts.destroy', $contact) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this contact?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $contacts->links() }}
@else
    <div class="text-center py-5">
        <i class="fas fa-address-book fa-3x text-muted mb-3"></i>
        <h4>No contacts found</h4>
        <p class="text-muted">Add your first contact to get started.</p>
        <a href="{{ route('communication.contacts.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Add Contact
        </a>
    </div>
@endif

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Contacts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('communication.contacts.import') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.txt" required>
                        <div class="form-text">Upload a CSV file with contact information.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
