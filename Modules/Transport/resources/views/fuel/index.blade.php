@extends('layouts.app')

@section('title','Fuel Logs')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Fuel Logs</h1>
  <div class="row">
    <div class="col-md-5">
      <div class="card mb-3">
        <div class="card-header">Add Fuel Log</div>
        <div class="card-body">
          <form method="post" action="{{ route('transport.fuel.store') }}">
            @csrf
            <input name="vehicle_id" class="form-control mb-2" placeholder="Vehicle ID" required>
            <input type="date" name="date" class="form-control mb-2" required>
            <input type="number" step="0.01" name="liters" class="form-control mb-2" placeholder="Liters" required>
            <input type="number" step="0.01" name="cost" class="form-control mb-2" placeholder="Cost" required>
            <input type="number" name="odometer" class="form-control mb-2" placeholder="Odometer">
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
                <th>Liters</th>
                <th>Cost</th>
                <th>Odo</th>
              </tr>
            </thead>
            <tbody>
              @foreach($logs as $log)
              <tr>
                <td>{{ $log->date->format('Y-m-d') }}</td>
                <td>#{{ $log->vehicle_id }}</td>
                <td>{{ number_format($log->liters,2) }}</td>
                <td>{{ number_format($log->cost,2) }}</td>
                <td>{{ $log->odometer }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer">{{ $logs->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection


