<tr>
    <td>{{ $student->name }}</td>
    <td>{{ $student->admission_number }}</td>
    <td>{{ $student->class->name ?? '-' }}</td>
    <td>
        <a href="{{ route('academic.students.edit', $student->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <form action="{{ route('academic.students.destroy', $student->id) }}" method="POST" style="display:inline-block;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
    </td>
</tr> 