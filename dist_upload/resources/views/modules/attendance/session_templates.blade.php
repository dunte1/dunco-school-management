@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Session Templates</h2>
    <button class="btn btn-primary mb-3" id="btn-add-template">Add Session Template</button>
    <div id="templates-list"></div>
</div>

<!-- Modal for Add/Edit Template -->
<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="template-form">
        <div class="modal-header">
          <h5 class="modal-title" id="templateModalLabel">Session Template</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="template-id">
          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" id="template-name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Description</label>
            <input type="text" class="form-control" id="template-description">
          </div>
          <div class="mb-3">
            <label class="form-label">Default Start Time</label>
            <input type="time" class="form-control" id="template-start-time">
          </div>
          <div class="mb-3">
            <label class="form-label">Default End Time</label>
            <input type="time" class="form-control" id="template-end-time">
          </div>
          <div class="mb-3">
            <label class="form-label">Active</label>
            <input type="checkbox" id="template-active" checked>
          </div>
          <hr>
          <h6>Rules</h6>
          <div id="rules-list"></div>
          <button type="button" class="btn btn-sm btn-outline-secondary mt-2" id="btn-add-rule">Add Rule</button>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let templates = [];
let editingId = null;

function fetchTemplates() {
    fetch('/api/attendance/session-templates')
        .then(res => res.json())
        .then(data => {
            templates = data;
            renderTemplates();
        });
}

function renderTemplates() {
    const list = document.getElementById('templates-list');
    if (!templates.length) {
        list.innerHTML = '<div class="alert alert-info">No session templates found.</div>';
        return;
    }
    let html = '<table class="table table-bordered"><thead><tr><th>Name</th><th>Description</th><th>Start</th><th>End</th><th>Status</th><th>Rules</th><th>Actions</th></tr></thead><tbody>';
    templates.forEach(t => {
        html += `<tr>
            <td>${t.name}</td>
            <td>${t.description || ''}</td>
            <td>${t.default_start_time || ''}</td>
            <td>${t.default_end_time || ''}</td>
            <td>${t.is_active ? 'Active' : 'Inactive'}</td>
            <td>${t.rules && t.rules.length ? t.rules.map(r => `<span class='badge bg-secondary me-1'>${r.rule_type}: ${r.value}</span>`).join('') : '<span class="text-muted">None</span>'}</td>
            <td>
                <button class="btn btn-sm btn-info me-1" onclick="editTemplate(${t.id})">Edit</button>
                <button class="btn btn-sm btn-danger" onclick="deleteTemplate(${t.id})">Delete</button>
            </td>
        </tr>`;
    });
    html += '</tbody></table>';
    list.innerHTML = html;
}

function resetForm() {
    document.getElementById('template-id').value = '';
    document.getElementById('template-name').value = '';
    document.getElementById('template-description').value = '';
    document.getElementById('template-start-time').value = '';
    document.getElementById('template-end-time').value = '';
    document.getElementById('template-active').checked = true;
    document.getElementById('rules-list').innerHTML = '';
}

function openModal(edit = false, template = null) {
    resetForm();
    if (edit && template) {
        document.getElementById('template-id').value = template.id;
        document.getElementById('template-name').value = template.name;
        document.getElementById('template-description').value = template.description || '';
        document.getElementById('template-start-time').value = template.default_start_time || '';
        document.getElementById('template-end-time').value = template.default_end_time || '';
        document.getElementById('template-active').checked = !!template.is_active;
        if (template.rules && template.rules.length) {
            template.rules.forEach(rule => addRuleRow(rule));
        }
    }
    const modal = new bootstrap.Modal(document.getElementById('templateModal'));
    modal.show();
}

document.getElementById('btn-add-template').addEventListener('click', () => openModal(false));

document.getElementById('btn-add-rule').addEventListener('click', () => addRuleRow());

function addRuleRow(rule = null) {
    const rulesList = document.getElementById('rules-list');
    const idx = rulesList.children.length;
    const div = document.createElement('div');
    div.className = 'row g-2 align-items-center mb-2 rule-row';
    div.innerHTML = `
        <input type="hidden" class="rule-id" value="${rule && rule.id ? rule.id : ''}">
        <div class="col-4"><input type="text" class="form-control rule-type" placeholder="Type" value="${rule ? rule.rule_type : ''}" required></div>
        <div class="col-4"><input type="text" class="form-control rule-value" placeholder="Value" value="${rule ? rule.value : ''}" required></div>
        <div class="col-3"><input type="text" class="form-control rule-description" placeholder="Description" value="${rule ? rule.description : ''}"></div>
        <div class="col-1"><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('.rule-row').remove()">&times;</button></div>
    `;
    rulesList.appendChild(div);
}

function getFormData() {
    const rules = Array.from(document.querySelectorAll('.rule-row')).map(row => ({
        id: row.querySelector('.rule-id').value || undefined,
        rule_type: row.querySelector('.rule-type').value,
        value: row.querySelector('.rule-value').value,
        description: row.querySelector('.rule-description').value
    }));
    return {
        name: document.getElementById('template-name').value,
        description: document.getElementById('template-description').value,
        default_start_time: document.getElementById('template-start-time').value,
        default_end_time: document.getElementById('template-end-time').value,
        is_active: document.getElementById('template-active').checked ? 1 : 0,
        rules
    };
}

document.getElementById('template-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const id = document.getElementById('template-id').value;
    const data = getFormData();
    const method = id ? 'PUT' : 'POST';
    const url = id ? `/api/attendance/session-templates/${id}` : '/api/attendance/session-templates';
    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(() => {
        fetchTemplates();
        bootstrap.Modal.getInstance(document.getElementById('templateModal')).hide();
    });
});

function editTemplate(id) {
    const template = templates.find(t => t.id === id);
    openModal(true, template);
}

function deleteTemplate(id) {
    if (!confirm('Delete this session template?')) return;
    fetch(`/api/attendance/session-templates/${id}`, { method: 'DELETE' })
        .then(() => fetchTemplates());
}

document.addEventListener('DOMContentLoaded', fetchTemplates);
</script>
@endpush 