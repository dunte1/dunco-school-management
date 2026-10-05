@extends('chatbot::layouts.chatbot')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Knowledge Ingestion</h1>
  <div class="card mb-3">
    <div class="card-body">
      <form method="post" action="{{ route('chatbot.admin.ingestion.upload') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-2"><input type="file" class="form-control" name="file" required></div>
        <div class="mb-2"><input class="form-control" name="description" placeholder="Description (optional)"></div>
        <button class="btn btn-primary">Upload & Index</button>
      </form>
    </div>
  </div>
  <div class="card">
    <div class="card-header">Documents</div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead><tr><th>ID</th><th>Name</th><th>Size</th><th>Processed</th></tr></thead>
        <tbody>
          @foreach($docs as $d)
          <tr>
            <td>{{ $d->id }}</td>
            <td>{{ $d->original_filename }}</td>
            <td>{{ number_format($d->file_size/1024,1) }} KB</td>
            <td>{{ $d->is_processed ? 'Yes' : 'No' }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="card-footer">{{ $docs->links() }}</div>
  </div>
</div>
@endsection


