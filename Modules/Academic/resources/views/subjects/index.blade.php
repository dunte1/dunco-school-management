@extends('academic::components.layouts.master')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Subjects</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSubjectModal">+ Create Subject</button>
    </div>
    <form class="row g-3 mb-4" id="subjectFilterForm">
        <div class="col-md-3">
            <input type="text" class="form-control" name="search" placeholder="Search by name...">
        </div>
        <div class="col-md-3">
            <select class="form-select" name="class">
                <option value="">All Classes</option>
                <!-- Populate with real classes -->
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" name="teacher">
                <option value="">All Teachers</option>
                <!-- Populate with real teachers -->
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-outline-secondary w-100" type="submit">Filter</button>
        </div>
    </form>
    <!-- Toast Container for feedback -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
      <div id="toastContainer" aria-live="polite"></div>
    </div>

    <!-- Beautiful Empty State -->
    @if($subjects->isEmpty())
      <div class="text-center my-5 animate__animated animate__fadeIn">
        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
        <div class="h5 text-muted">No subjects found.</div>
        <div>
          <button class="btn btn-primary mt-2 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#createSubjectModal">
            <i class="fas fa-plus me-2"></i> Create Subject
          </button>
        </div>
      </div>
    @else
      <div class="card shadow-sm border-0 mt-4 animate__animated animate__fadeIn">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="sticky-top bg-white" style="z-index:1;">
              <tr>
                <th>Name</th>
                <th>Class</th>
                <th>Teacher</th>
                <th>Groups</th>
                <th>Type</th>
                <th>Exams</th>
                <th>Timetable</th>
                <th>Performance</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($subjects as $subject)
              <tr>
                <td>{{ $subject->name }}</td>
                <td>
                  @if(isset($subject->classes) && count($subject->classes))
                    {{ $subject->classes->pluck('name')->join(', ') }}
                  @else
                    <span class="text-muted">None</span>
                  @endif
                </td>
                <td>
                  @if(isset($subject->teachers) && count($subject->teachers))
                    {{ $subject->teachers->pluck('name')->join(', ') }}
                  @else
                    <span class="text-muted">None</span>
                  @endif
                </td>
                <td>
                  @if(isset($subject->groups) && count($subject->groups))
                    {{ $subject->groups->pluck('name')->join(', ') }}
                  @else
                    <span class="text-muted">None</span>
                  @endif
                </td>
                <td>{{ $subject->type ?? 'Core' }}</td>
                <td>
                  <span class="badge bg-{{ isset($subject->exams_linked) && $subject->exams_linked ? 'success' : 'secondary' }}">
                    {{ isset($subject->exams_linked) && $subject->exams_linked ? 'Linked' : 'Not Linked' }}
                  </span>
                </td>
                <td>
                  <span class="badge bg-{{ isset($subject->timetable_scheduled) && $subject->timetable_scheduled ? 'success' : 'secondary' }}">
                    {{ isset($subject->timetable_scheduled) && $subject->timetable_scheduled ? 'Scheduled' : 'Not Scheduled' }}
                  </span>
                </td>
                <td>
                  <button class="btn btn-sm btn-info btn-performance" data-subject-id="{{ $subject->id }}">View</button>
                </td>
                <td>
                  <button class="btn btn-sm btn-secondary btn-assign" data-subject-id="{{ $subject->id }}">Assign</button>
                  <a href="{{ route('academic.subjects.edit', $subject->id) }}" class="btn btn-sm btn-warning btn-edit" data-subject-id="{{ $subject->id }}">Edit</a>
                  <form action="{{ route('academic.subjects.destroy', $subject->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="9" class="text-center">No subjects found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    @endif

    <!-- Pagination -->
    <div class="mt-3">
        {{ $subjects->links() }}
    </div>

    <!-- Create Subject Modal -->
    <div class="modal fade" id="createSubjectModal" tabindex="-1" aria-labelledby="createSubjectModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST" action="{{ route('academic.subjects.store') }}">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="createSubjectModalLabel">Create Subject</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="name" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="code" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="code" name="code" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"></textarea>
              </div>
              <div class="mb-3">
                <label for="credits" class="form-label">Credits</label>
                <input type="number" class="form-control" id="credits" name="credits" min="1" max="10" required>
              </div>
              <div class="mb-3">
                <label for="class_ids" class="form-label">Assign to Classes</label>
                <select class="form-select" id="class_ids" name="class_ids[]" multiple required>
                  @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="teacher_ids" class="form-label">Assign to Teachers</label>
                <select class="form-select" id="teacher_ids" name="teacher_ids[]" multiple required>
                  @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="group_ids" class="form-label">Assign to Groups/Streams</label>
                <select class="form-select" id="group_ids" name="group_ids[]" multiple>
                  @foreach($groups as $group)
                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Create</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Assign Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="assignForm">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="assignModalLabel">Assign Subject</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="assignSubjectId" name="subject_id">
              <div id="assignModalContent">
                <div class="text-center text-muted">Loading...</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Assign</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- View Performance Modal -->
    <div class="modal fade" id="performanceModal" tabindex="-1" aria-labelledby="performanceModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="performanceModalLabel">Subject Performance</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="performanceModalContent">
            <div class="text-center text-muted">Loading...</div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Edit Subject Modal -->
    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="editSubjectForm">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h5 class="modal-title" id="editSubjectModalLabel">Edit Subject</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="editSubjectId" name="id">
              <div class="mb-3">
                <label for="editName" class="form-label">Subject Name</label>
                <input type="text" class="form-control" id="editName" name="name" required>
              </div>
              <div class="mb-3">
                <label for="editCode" class="form-label">Subject Code</label>
                <input type="text" class="form-control" id="editCode" name="code" required>
              </div>
              <div class="mb-3">
                <label for="editDescription" class="form-label">Description</label>
                <textarea class="form-control" id="editDescription" name="description"></textarea>
              </div>
              <div class="mb-3">
                <label for="editCredits" class="form-label">Credits</label>
                <input type="number" class="form-control" id="editCredits" name="credits" min="1" max="10" required>
              </div>
              <div class="mb-3">
                <label for="editClassIds" class="form-label">Assign to Classes</label>
                <select class="form-select" id="editClassIds" name="class_ids[]" multiple required>
                  @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                      {{ (isset($subject) && $subject->classes->contains($class->id)) ? 'selected' : '' }}>
                      {{ $class->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="editTeacherIds" class="form-label">Assign to Teachers</label>
                <select class="form-select" id="editTeacherIds" name="teacher_ids[]" multiple required>
                  @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}"
                      {{ (isset($subject) && $subject->teachers->contains($teacher->id)) ? 'selected' : '' }}>
                      {{ $teacher->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="editGroupIds" class="form-label">Assign to Groups/Streams</label>
                <select class="form-select" id="editGroupIds" name="group_ids[]" multiple>
                  @foreach($groups as $group)
                    <option value="{{ $group->id }}"
                      {{ (isset($subject) && $subject->groups->contains($group->id)) ? 'selected' : '' }}>
                      {{ $group->name }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Prerequisites</label>
                <ul id="prerequisiteList" class="list-group mb-2">
                  <!-- Prerequisites will be loaded here by JS -->
                </ul>
                <div class="input-group">
                  <select class="form-select" id="addPrerequisiteSelect">
                    <option value="">Add prerequisite...</option>
                    @foreach($allSubjects as $s)
                      <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                  </select>
                  <button type="button" class="btn btn-outline-primary" id="addPrerequisiteBtn">Add</button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Update</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>
@endsection

@section('scripts')
// --- Helper: Show Toast ---
function showToast(message, type = 'success') {
  const toastId = 'toast' + Date.now();
  const toastHtml = `
    <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 mb-2 animate__animated animate__fadeInRight" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">${message}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>`;
  document.getElementById('toastContainer').insertAdjacentHTML('beforeend', toastHtml);
  const toastEl = document.getElementById(toastId);
  const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
  toast.show();
  toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
}
// --- Helper: Disable/Enable Submit Button ---
function setSubmitDisabled(form, disabled) {
  const btn = form.querySelector('button[type="submit"]');
  if (btn) btn.disabled = disabled;
}
// --- Helper: Reset Validation States ---
function resetValidation(form) {
  form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
}
// --- AJAX Create Subject ---
const createForm = document.querySelector('#createSubjectModal form');
if (createForm) {
    createForm.addEventListener('submit', function(e) {
        e.preventDefault();
        resetValidation(this);
        setSubmitDisabled(this, true);
        // Client-side validation
        let valid = true;
        this.querySelectorAll('[required]').forEach(input => {
          if (!input.value) {
            input.classList.add('is-invalid');
            showToast(input.name.charAt(0).toUpperCase() + input.name.slice(1) + ' is required', 'danger');
            valid = false;
          }
        });
        if (!valid) { setSubmitDisabled(this, false); return; }
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            setSubmitDisabled(this, false);
            if (data.success) {
                showToast('Subject created successfully!');
                location.reload();
            } else if (data.errors) {
                Object.entries(data.errors).forEach(([field, messages]) => {
                    const input = this.querySelector(`[name="${field}"]`);
                    if (input) input.classList.add('is-invalid');
                    showToast(messages.join(', '), 'danger');
                });
            } else {
                showToast('Failed to create subject', 'danger');
            }
        })
        .catch(error => {
            setSubmitDisabled(this, false);
            showToast('An error occurred. Please try again.', 'danger');
        });
    });
    // Reset validation on modal open/close
    document.getElementById('createSubjectModal').addEventListener('show.bs.modal', () => resetValidation(createForm));
    document.getElementById('createSubjectModal').addEventListener('hidden.bs.modal', () => resetValidation(createForm));
}
// --- AJAX Delete Subject ---
const deleteForms = document.querySelectorAll('.delete-form');
deleteForms.forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (!confirm('Are you sure you want to delete this subject?')) return;
        setSubmitDisabled(this, true);
        fetchWithTimeout(this.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.querySelector('input[name=_token]').value
            },
            body: new FormData(this)
        })
        .then(res => res.json())
        .then(data => {
            setSubmitDisabled(this, false);
            if (data.success) {
                showToast('Subject deleted successfully!');
                location.reload();
            } else {
                showToast('Error: ' + (data.message || 'Could not delete subject.'), 'danger');
            }
        })
        .catch(err => {
            setSubmitDisabled(this, false);
            if (err.message === 'timeout') {
                showToast('Request timed out. Please try again.', 'danger');
            } else {
                showToast('Network error. Please check your connection.', 'danger');
            }
        });
    });
});
// --- AJAX Filtering/Search ---
const filterForm = document.getElementById('subjectFilterForm');
if (filterForm) {
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const params = new URLSearchParams(new FormData(this)).toString();
        fetch(window.location.pathname + '?' + params, {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.querySelector('table');
            document.querySelector('table').replaceWith(newTable);
            attachSubjectEventListeners();
        })
        .catch(() => {
            showToast('Failed to filter subjects. Please try again.', 'danger');
        });
    });
}
// --- AJAX Edit Subject ---
const editSubjectModal = new bootstrap.Modal(document.getElementById('editSubjectModal'));
const editSubjectForm = document.getElementById('editSubjectForm');
document.querySelectorAll('.btn-warning').forEach(btn => {
    btn.addEventListener('click', function(event) {
        const el = event.target;
        const subjectId = el && el.dataset ? el.dataset.subjectId : null;
        if (!subjectId) {
            showToast('Invalid subject ID', 'danger');
            return;
        }
        fetch(`/academic/subjects/${subjectId}/edit`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            editSubjectForm.reset();
            resetValidation(editSubjectForm);
            document.getElementById('editSubjectId').value = data.id;
            document.getElementById('editName').value = data.name;
            document.getElementById('editCode').value = data.code;
            document.getElementById('editDescription').value = data.description;
            document.getElementById('editCredits').value = data.credits;
            document.getElementById('editClassIds').value = data.class_ids.join(',');
            document.getElementById('editTeacherIds').value = data.teacher_ids.join(',');
            document.getElementById('editGroupIds').value = data.group_ids.join(',');
            editSubjectForm.setAttribute('action', `/academic/subjects/${data.id}`);
            editSubjectModal.show();
        })
        .catch(error => {
            showToast('Failed to load subject', 'danger');
        });
    });
});
editSubjectForm.addEventListener('submit', function(e) {
    e.preventDefault();
    resetValidation(this);
    setSubmitDisabled(this, true);
    // Client-side validation
    let valid = true;
    this.querySelectorAll('[required]').forEach(input => {
      if (!input.value) {
        input.classList.add('is-invalid');
        showToast(input.name.charAt(0).toUpperCase() + input.name.slice(1) + ' is required', 'danger');
        valid = false;
      }
    });
    if (!valid) { setSubmitDisabled(this, false); return; }
    const id = document.getElementById('editSubjectId').value;
    const formData = new FormData(this);
    fetchWithTimeout(`/academic/subjects/${id}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': formData.get('_token')
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        setSubmitDisabled(this, false);
        if (data.success) {
            showToast('Subject updated successfully!');
            location.reload();
        } else if (data.errors) {
            Object.entries(data.errors).forEach(([field, messages]) => {
                const input = this.querySelector(`[name="${field}"]`);
                if (input) input.classList.add('is-invalid');
                showToast(messages.join(', '), 'danger');
            });
        } else {
            showToast('Error: ' + (data.message || 'Could not update subject.'), 'danger');
        }
    })
    .catch(err => {
        setSubmitDisabled(this, false);
        if (err.message === 'timeout') {
          showToast('Request timed out. Please try again.', 'danger');
        } else {
          showToast('Network error. Please check your connection.', 'danger');
        }
    });
});
document.getElementById('editSubjectModal').addEventListener('show.bs.modal', () => resetValidation(editSubjectForm));
document.getElementById('editSubjectModal').addEventListener('hidden.bs.modal', () => resetValidation(editSubjectForm));
// --- Assign Modal AJAX, Performance, and Edit (with error handling) ---
const assignModal = new bootstrap.Modal(document.getElementById('assignModal'));
const performanceModal = new bootstrap.Modal(document.getElementById('performanceModal'));
function attachSubjectEventListeners() {
  // Assign
  document.querySelectorAll('.btn-assign').forEach(btn => {
    btn.onclick = function(e) {
      e.preventDefault();
      const subjectId = this.dataset.subjectId;
      if (!subjectId) {
        showToast('No subject ID found for this action.', 'danger');
        return;
      }
      document.getElementById('assignSubjectId').value = subjectId;
      document.getElementById('assignModalContent').innerHTML = '<div class="text-center text-muted">Loading...</div>';
      assignModal.show();
      fetchWithTimeout(`/academic/subjects/${subjectId}/assign-data`, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
        .then(res => res.json())
        .then(data => {
          let html = '';
          html += '<div class="mb-3">';
          html += '<label class="form-label">Assign to Classes</label>';
          html += '<select class="form-select" name="class_ids[]" multiple required>';
          data.classes.forEach(cls => {
            html += `<option value="${cls.id}" ${cls.assigned ? 'selected' : ''}>${cls.name}</option>`;
          });
          html += '</select></div>';
          html += '<div class="mb-3">';
          html += '<label class="form-label">Assign to Teachers</label>';
          html += '<select class="form-select" name="teacher_ids[]" multiple required>';
          data.teachers.forEach(teacher => {
            html += `<option value="${teacher.id}" ${teacher.assigned ? 'selected' : ''}>${teacher.name}</option>`;
          });
          html += '</select></div>';
          html += '<div class="mb-3">';
          html += '<label class="form-label">Assign to Groups/Streams</label>';
          html += '<select class="form-select" name="group_ids[]" multiple>';
          data.groups.forEach(group => {
            html += `<option value="${group.id}" ${group.assigned ? 'selected' : ''}>${group.name}</option>`;
          });
          html += '</select></div>';
          document.getElementById('assignModalContent').innerHTML = html;
        })
        .catch(err => {
          if (err.message === 'timeout') {
            showToast('Request timed out. Please try again.', 'danger');
          } else {
            showToast('Network error. Please check your connection.', 'danger');
          }
          assignModal.hide();
        });
    };
  });
  // Performance
  document.querySelectorAll('.btn-performance').forEach(btn => {
    btn.onclick = function(e) {
      e.preventDefault();
      const subjectId = this.dataset.subjectId;
      if (!subjectId) {
        showToast('No subject ID found for this action.', 'danger');
        return;
      }
      document.getElementById('performanceModalContent').innerHTML = '<div class="text-center text-muted">Loading...</div>';
      performanceModal.show();
      fetchWithTimeout(`/academic/subjects/${subjectId}/performance`, {headers: {'X-Requested-With': 'XMLHttpRequest'}})
        .then(res => res.json())
        .then(data => {
          let html = '';
          html += `<div><strong>Average Score:</strong> ${data.average_score ?? 'N/A'}</div>`;
          html += `<div><strong>Pass Rate:</strong> ${data.pass_rate ?? 'N/A'}%</div>`;
          html += `<div><strong>Top Performer:</strong> ${data.top_performer ?? 'N/A'}</div>`;
          document.getElementById('performanceModalContent').innerHTML = html;
        })
        .catch(err => {
          if (err.message === 'timeout') {
            showToast('Request timed out. Please try again.', 'danger');
          } else {
            showToast('Network error. Please check your connection.', 'danger');
          }
          performanceModal.hide();
        });
    };
  });
  // Edit
  document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.onclick = function(event) {
      const el = event.target;
      const subjectId = el && el.dataset ? el.dataset.subjectId : null;
      if (!subjectId) {
        showToast('Invalid subject ID', 'danger');
        return;
      }
      fetch(`/academic/subjects/${subjectId}/edit`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(response => response.json())
      .then(data => {
        editSubjectForm.reset();
        resetValidation(editSubjectForm);
        document.getElementById('editSubjectId').value = data.id;
        document.getElementById('editName').value = data.name;
        document.getElementById('editCode').value = data.code;
        document.getElementById('editDescription').value = data.description;
        document.getElementById('editCredits').value = data.credits;
        document.getElementById('editClassIds').value = data.class_ids.join(',');
        document.getElementById('editTeacherIds').value = data.teacher_ids.join(',');
        document.getElementById('editGroupIds').value = data.group_ids.join(',');
        editSubjectForm.setAttribute('action', `/academic/subjects/${data.id}`);
        editSubjectModal.show();
      })
      .catch(error => {
        showToast('Failed to load subject', 'danger');
      });
    };
  });
}
// Call after page load and after AJAX table update
attachSubjectEventListeners();
// --- Add focus effect to inputs ---
Array.from(document.querySelectorAll('input, select')).forEach(el => {
  el.addEventListener('focus', () => el.classList.add('shadow-sm', 'border-primary'));
  el.addEventListener('blur', () => el.classList.remove('shadow-sm', 'border-primary'));
});
// --- Helper: fetch with timeout and debug ---
function fetchWithTimeout(resource, options = {}, timeout = 10000) {
  return Promise.race([
    fetch(resource, options),
    new Promise((_, reject) => setTimeout(() => reject(new Error('timeout')), timeout))
  ]);
}
// --- Prerequisites AJAX ---
function loadPrerequisites(subjectId) {
  fetch(`/academic/subjects/${subjectId}/prerequisites`)
    .then(res => res.json())
    .then(data => {
      const list = document.getElementById('prerequisiteList');
      list.innerHTML = '';
      if (data.prerequisites && data.prerequisites.length) {
        data.prerequisites.forEach(prereq => {
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-center';
          li.innerHTML = `${prereq.name} <button class='btn btn-sm btn-danger' onclick='removePrerequisite(${subjectId}, ${prereq.id})'>Remove</button>`;
          list.appendChild(li);
        });
      } else {
        list.innerHTML = '<li class="list-group-item text-muted">No prerequisites</li>';
      }
    });
}
function addPrerequisite(subjectId) {
  const select = document.getElementById('addPrerequisiteSelect');
  const prereqId = select.value;
  if (!prereqId) return;
  fetch(`/academic/subjects/${subjectId}/prerequisites`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    },
    body: JSON.stringify({ prerequisite_id: prereqId })
  })
  .then(res => res.json())
  .then(data => {
    showToast(data.message || 'Prerequisite added');
    loadPrerequisites(subjectId);
  });
}
function removePrerequisite(subjectId, prereqId) {
  fetch(`/academic/subjects/${subjectId}/prerequisites/${prereqId}`, {
    method: 'DELETE',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    }
  })
  .then(res => res.json())
  .then(data => {
    showToast(data.message || 'Prerequisite removed');
    loadPrerequisites(subjectId);
  });
}
document.addEventListener('DOMContentLoaded', function() {
  const editModal = document.getElementById('editSubjectModal');
  editModal.addEventListener('show.bs.modal', function(event) {
    const subjectId = document.getElementById('editSubjectId').value;
    loadPrerequisites(subjectId);
  });
  document.getElementById('addPrerequisiteBtn').onclick = function() {
    const subjectId = document.getElementById('editSubjectId').value;
    addPrerequisite(subjectId);
  };
});
@endsection