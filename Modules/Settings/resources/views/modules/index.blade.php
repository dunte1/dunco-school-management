@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Module Management</h1>
            <p class="text-muted mb-0">Enable or disable system modules for all users</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        @foreach($statuses as $moduleName => $isEnabled)
            @php
                $desc = $moduleDescriptions[$moduleName] ?? ['System module', 'fa-puzzle-piece'];
            @endphp
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-{{ $isEnabled ? 'success' : 'secondary' }} shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-{{ $isEnabled ? 'success' : 'secondary' }} text-uppercase mb-1">
                                    {{ $moduleName }}
                                </div>
                                <div class="text-gray-800 small">{{ $desc[0] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas {{ $desc[1] }} fa-2x text-gray-300"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <form method="POST" action="{{ route('admin.modules.toggle') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="module" value="{{ $moduleName }}">
                                <button type="submit" class="btn btn-sm {{ $isEnabled ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    <i class="fas {{ $isEnabled ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-1"></i>
                                    {{ $isEnabled ? 'Enabled' : 'Disabled' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Module Status Summary</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Module</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($statuses as $moduleName => $isEnabled)
                            <tr class="{{ $isEnabled ? 'table-light' : 'table-secondary' }}">
                                <td class="font-weight-bold">{{ $moduleName }}</td>
                                <td>
                                    <span class="badge badge-{{ $isEnabled ? 'success' : 'danger' }}">
                                        {{ $isEnabled ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.modules.toggle') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="module" value="{{ $moduleName }}">
                                        <button type="submit" class="btn btn-sm btn-{{ $isEnabled ? 'warning' : 'success' }}">
                                            {{ $isEnabled ? 'Disable' : 'Enable' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
