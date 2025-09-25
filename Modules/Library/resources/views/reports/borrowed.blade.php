@extends('layouts.app')

@section('title', 'Borrowed Books Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Currently Borrowed Books</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Member</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowedBooks as $book)
                            <tr>
                                <td>{{ $book->book_title }}</td>
                                <td>{{ $book->member_name }}</td>
                                <td>{{ $book->borrow_date }}</td>
                                <td>{{ $book->due_date }}</td>
                                <td>{{ $book->status }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No borrowed books found</td>
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
