@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Permission Matrix</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('core.permissions.matrix.update') }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Permission</th>
                        @foreach($roles as $role)
                            <th>{{ $role->display_name ?? $role->name }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $permission->display_name ?? $permission->name }}</td>
                            @foreach($roles as $role)
                                <td class="text-center">
                                    <input type="checkbox" name="permissions[{{ $role->id }}][]" value="{{ $permission->id }}"
                                        {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection 