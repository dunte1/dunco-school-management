@extends('academic::layouts.app')

@section('content')
<div class="container">
    <h1>Bulk Import Preview</h1>
    <form method="POST" action="{{ route('academic.students.handle_bulk_import') }}">
        @csrf
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="btn btn-success mb-3" {{ count($validRows) ? '' : 'disabled' }}>Import Valid Students</button>
    </form>
    <h4>Valid Rows ({{ count($validRows) }})</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                @foreach(array_keys($validRows[0] ?? []) as $col)
                    @if(strpos($col, '__') !== 0)
                        <th>{{ $col }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($validRows as $row)
                <tr>
                    @foreach($row as $col => $val)
                        @if(strpos($col, '__') !== 0)
                            <td>{{ $val }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4 class="mt-4">Errors ({{ count($errors) }})</h4>
    <a href="{{ route('academic.students.bulk_import_errors') }}" class="btn btn-outline-danger btn-sm mb-2">Download Error Report (CSV)</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                @foreach(array_keys($errors[0] ?? []) as $col)
                    @if(strpos($col, '__') !== 0)
                        <th>{{ $col }}</th>
                    @endif
                @endforeach
                <th>Errors</th>
            </tr>
        </thead>
        <tbody>
            @foreach($errors as $row)
                <tr>
                    @foreach($row as $col => $val)
                        @if(strpos($col, '__') !== 0)
                            <td>{{ $val }}</td>
                        @endif
                    @endforeach
                    <td>
                        @if(isset($row['__errors']))
                            <ul>
                                @foreach($row['__errors'] as $err)
                                    <li class="text-danger">{{ $err }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 