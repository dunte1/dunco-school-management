@extends('layouts.app')

@section('title','Library Stock-take')

@section('content')
<div class="container py-4">
  <h1 class="h4 mb-3">Library Stock-take</h1>
  <div id="app"></div>
</div>
<script>
  let stockTakeId = null;
  async function start() {
    const res = await fetch('{{ route('library.stocktake.start') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}});
    const data = await res.json();
    stockTakeId = data.id;
    document.getElementById('app').innerHTML = `<div class="card"><div class="card-body">
      <div class="mb-2"><input id="book" class="form-control" placeholder="Book ID"></div>
      <div class="mb-2"><input id="counted" type="number" class="form-control" placeholder="Counted Qty"></div>
      <button class="btn btn-primary" onclick="scan()">Scan/Count</button>
    </div></div>`;
  }
  async function scan() {
    const bookId = document.getElementById('book').value;
    const counted = document.getElementById('counted').value;
    const res = await fetch(`{{ url('library/stocktake') }}/${stockTakeId}/scan`, {
      method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Content-Type':'application/json'},
      body: JSON.stringify({book_id: Number(bookId), counted_qty: Number(counted)})
    });
    const data = await res.json();
    console.log('scanned', data);
  }
  document.addEventListener('DOMContentLoaded', ()=>{
    document.getElementById('app').innerHTML = `<button class="btn btn-primary" onclick="start()">Start Session</button>`;
  });
</script>
@endsection


