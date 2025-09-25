@extends('layouts.admin')
@section('content')
<div class="container mt-4">
    <h2>Fee Configurations</h2>
    <button class="btn btn-primary mb-3" id="addFeeBtn">Add Fee</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Amount</th>
                <th>Applies To</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="feesTableBody">
            @foreach($fees as $fee)
            <tr data-id="{{ $fee->id }}">
                <td>{{ $fee->name }}</td>
                <td>{{ number_format($fee->amount, 2) }}</td>
                <td><pre class="mb-0">{{ json_encode($fee->applies_to, JSON_PRETTY_PRINT) }}</pre></td>
                <td>{{ $fee->due_date ? $fee->due_date->format('Y-m-d') : '' }}</td>
                <td>
                    @if($fee->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Inactive</span>
                    @endif
                </td>
                <td>
                    <button class="btn btn-sm btn-info editFeeBtn">Edit</button>
                    <button class="btn btn-sm btn-danger deleteFeeBtn">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="feeModal" tabindex="-1" aria-labelledby="feeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="feeForm">
        <div class="modal-header">
          <h5 class="modal-title" id="feeModalLabel">Add/Edit Fee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="feeId">
          <div class="mb-3">
            <label for="feeName" class="form-label">Name</label>
            <input type="text" class="form-control" id="feeName" required>
          </div>
          <div class="mb-3">
            <label for="feeAmount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="feeAmount" step="0.01" required>
          </div>
          <div class="mb-3">
            <label for="feeAppliesTo" class="form-label">Applies To (JSON)</label>
            <input type="text" class="form-control" id="feeAppliesTo" placeholder='{"class": "Form 1", "boarding": true}'>
          </div>
          <div class="mb-3">
            <label for="feeDueDate" class="form-label">Due Date</label>
            <input type="date" class="form-control" id="feeDueDate">
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" id="feeIsActive">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('addFeeBtn');
    const modal = new bootstrap.Modal(document.getElementById('feeModal'));
    const form = document.getElementById('feeForm');
    const idField = document.getElementById('feeId');
    const nameField = document.getElementById('feeName');
    const amountField = document.getElementById('feeAmount');
    const appliesToField = document.getElementById('feeAppliesTo');
    const dueDateField = document.getElementById('feeDueDate');
    const isActiveField = document.getElementById('feeIsActive');
    const tableBody = document.getElementById('feesTableBody');

    addBtn.addEventListener('click', function() {
        idField.value = '';
        nameField.value = '';
        amountField.value = '';
        appliesToField.value = '';
        dueDateField.value = '';
        isActiveField.value = '1';
        modal.show();
    });

    tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('editFeeBtn')) {
            const row = e.target.closest('tr');
            idField.value = row.dataset.id;
            nameField.value = row.children[0].textContent;
            amountField.value = row.children[1].textContent;
            appliesToField.value = row.children[2].textContent.trim();
            dueDateField.value = row.children[3].textContent.trim();
            isActiveField.value = row.children[4].textContent.includes('Active') ? '1' : '0';
            modal.show();
        } else if (e.target.classList.contains('deleteFeeBtn')) {
            if (confirm('Delete this fee?')) {
                const row = e.target.closest('tr');
                fetch(`/admin/config/fees/${row.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) row.remove();
                });
            }
        }
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = idField.value;
        const url = id ? `/admin/config/fees/${id}` : '/admin/config/fees';
        const method = id ? 'PUT' : 'POST';
        let appliesToJson = {};
        try {
            appliesToJson = appliesToField.value ? JSON.parse(appliesToField.value) : {};
        } catch (err) {
            alert('Applies To must be valid JSON');
            return;
        }
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: nameField.value,
                amount: amountField.value,
                applies_to: appliesToJson,
                due_date: dueDateField.value,
                is_active: isActiveField.value === '1'
            })
        }).then(res => res.json()).then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});
</script>
@endsection 