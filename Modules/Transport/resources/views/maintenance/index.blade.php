@extends('layouts.app')

@section('title','Vehicle Maintenances')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Vehicle Maintenance</h1>
  <div class="row">
    <div class="col-md-5">
      <div class="card mb-3">
        <div class="card-header">Add Maintenance</div>
        <div class="card-body">
          <form method="post" action="{{ route('transport.maintenance.store') }}">
            @csrf
            <input name="vehicle_id" class="form-control mb-2" placeholder="Vehicle ID" required>
            <input name="type" class="form-control mb-2" placeholder="Type (service, repair, tires)" required>
            <input type="date" name="date" class="form-control mb-2" required>
            <input type="number" step="0.01" name="cost" class="form-control mb-2" placeholder="Cost" required>
            <input type="number" name="odometer" class="form-control mb-2" placeholder="Odometer">
            <textarea name="notes" class="form-control mb-2" placeholder="Notes"></textarea>
            <button class="btn btn-primary">Save</button>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card">
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr>
                <th>Date</th>
                <th>Vehicle</th>
                <th>Type</th>
                <th>Cost</th>
                <th>Odo</th>
              </tr>
            </thead>
            <tbody>
              @foreach($records as $rec)
              <tr>
                <td>{{ $rec->date->format('Y-m-d') }}</td>
                <td>#{{ $rec->vehicle_id }}</td>
                <td>{{ ucfirst($rec->type) }}</td>
                <td>{{ number_format($rec->cost,2) }}</td>
                <td>{{ $rec->odometer }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer">{{ $records->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection


