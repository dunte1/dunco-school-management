@php($token = csrf_token())
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-semibold mb-4">Attendance Alert Rules</h1>
    <div class="mb-4">
        <button id="triggerBtn" class="bg-blue-600 text-white px-4 py-2 rounded">Trigger Evaluation</button>
        <span id="status" class="ml-3 text-sm text-gray-600"></span>
    </div>
    <div>
        <pre id="output" class="bg-gray-100 p-3 rounded text-sm"></pre>
    </div>
</div>
<script>
document.getElementById('triggerBtn').addEventListener('click', async () => {
  const statusEl = document.getElementById('status');
  const outEl = document.getElementById('output');
  statusEl.textContent = 'Triggering...';
  outEl.textContent = '';
  try {
    const res = await fetch('{{ route('admin.attendance.alert_rules.trigger') }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': '{{ $token }}',
        'Accept': 'application/json'
      }
    });
    const data = await res.json();
    statusEl.textContent = 'Done';
    outEl.textContent = (data && data.message) ? data.message : JSON.stringify(data, null, 2);
  } catch (e) {
    statusEl.textContent = 'Failed';
    outEl.textContent = e?.message || 'Error';
  }
});
</script>


