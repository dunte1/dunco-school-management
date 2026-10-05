@extends('layouts.app')

@section('title', 'Debug Online Classes')

@section('content')
<div class="container-fluid px-4 py-4">
    <h1>Debug Online Classes Permissions</h1>
    
    <div class="card">
        <div class="card-body">
            <h5>User Information</h5>
            <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p><strong>ID:</strong> {{ auth()->user()->id }}</p>
            
            <h5>Roles</h5>
            @if(auth()->user()->roles->count() > 0)
                <ul>
                    @foreach(auth()->user()->roles as $role)
                        <li>{{ $role->name }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-warning">No roles assigned</p>
            @endif
            
            <h5>Permissions</h5>
            <p><strong>Can create online classes:</strong> {{ auth()->user()->can('create_online_classes') ? 'Yes' : 'No' }}</p>
            <p><strong>Has teacher role:</strong> {{ auth()->user()->hasRole('teacher') ? 'Yes' : 'No' }}</p>
            <p><strong>Has admin role:</strong> {{ auth()->user()->hasRole('admin') ? 'Yes' : 'No' }}</p>
            <p><strong>Has super-admin role:</strong> {{ auth()->user()->hasRole('super-admin') ? 'Yes' : 'No' }}</p>
            
            <h5>Role Check Results</h5>
            <p><strong>hasRole(['teacher', 'admin']):</strong> {{ auth()->user()->hasRole(['teacher', 'admin']) ? 'Yes' : 'No' }}</p>
            <p><strong>hasRole(['teacher', 'admin', 'super-admin']):</strong> {{ auth()->user()->hasRole(['teacher', 'admin', 'super-admin']) ? 'Yes' : 'No' }}</p>
            <p><strong>hasRole(['teacher', 'admin', 'super-admin']) || can('create_online_classes'):</strong> {{ (auth()->user()->hasRole(['teacher', 'admin', 'super-admin']) || auth()->user()->can('create_online_classes')) ? 'Yes' : 'No' }}</p>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-body">
            <h5>Test Buttons</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createClassModal">
                Test Modal Button
            </button>
        </div>
    </div>
</div>

<!-- Test Modal -->
<div class="modal fade" id="createClassModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Test Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>If you can see this modal, the button is working!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
