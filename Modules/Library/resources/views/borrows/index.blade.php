@extends('layouts.app')

@section('title', 'Library Borrows')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Borrow Records</h3>
                    <a href="{{ route('library.borrows.create') }}" class="btn btn-primary">Add Borrow</a>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrows as $borrow)
                            <tr>
                                <td>{{ $borrow->book_title }}</td>
                                <td>{{ $borrow->member_name }}</td>
                                <td>{{ $borrow->borrow_date }}</td>
                                <td>{{ $borrow->due_date }}</td>
                                <td>{{ $borrow->status }}</td>
                                <td>
                                    <a href="{{ route('library.borrows.show', $borrow->id) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('library.borrows.edit', $borrow->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No borrow records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection