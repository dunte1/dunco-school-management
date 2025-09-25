
<?php $__env->startSection('content'); ?>
<div class="container mt-4">
    <h2>Required Documents</h2>
    <button class="btn btn-primary mb-3" id="addDocumentBtn">Add Document</button>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Applies To</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="documentsTableBody">
            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr data-id="<?php echo e($doc->id); ?>">
                <td><?php echo e($doc->name); ?></td>
                <td><?php echo e($doc->description); ?></td>
                <td><pre class="mb-0"><?php echo e(json_encode($doc->applies_to, JSON_PRETTY_PRINT)); ?></pre></td>
                <td>
                    <?php if($doc->is_active): ?>
                        <span class="badge bg-success">Active</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Inactive</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="btn btn-sm btn-info editDocumentBtn">Edit</button>
                    <button class="btn btn-sm btn-danger deleteDocumentBtn">Delete</button>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="documentForm">
        <div class="modal-header">
          <h5 class="modal-title" id="documentModalLabel">Add/Edit Document</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="documentId">
          <div class="mb-3">
            <label for="docName" class="form-label">Name</label>
            <input type="text" class="form-control" id="docName" required>
          </div>
          <div class="mb-3">
            <label for="docDescription" class="form-label">Description</label>
            <textarea class="form-control" id="docDescription"></textarea>
          </div>
          <div class="mb-3">
            <label for="docAppliesTo" class="form-label">Applies To (JSON)</label>
            <input type="text" class="form-control" id="docAppliesTo" placeholder='{"class": "Form 1", "type": "boarding"}'>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" id="docIsActive">
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
    const addBtn = document.getElementById('addDocumentBtn');
    const modal = new bootstrap.Modal(document.getElementById('documentModal'));
    const form = document.getElementById('documentForm');
    const idField = document.getElementById('documentId');
    const nameField = document.getElementById('docName');
    const descField = document.getElementById('docDescription');
    const appliesToField = document.getElementById('docAppliesTo');
    const isActiveField = document.getElementById('docIsActive');
    const tableBody = document.getElementById('documentsTableBody');

    addBtn.addEventListener('click', function() {
        idField.value = '';
        nameField.value = '';
        descField.value = '';
        appliesToField.value = '';
        isActiveField.value = '1';
        modal.show();
    });

    tableBody.addEventListener('click', function(e) {
        if (e.target.classList.contains('editDocumentBtn')) {
            const row = e.target.closest('tr');
            idField.value = row.dataset.id;
            nameField.value = row.children[0].textContent;
            descField.value = row.children[1].textContent;
            appliesToField.value = row.children[2].textContent.trim();
            isActiveField.value = row.children[3].textContent.includes('Active') ? '1' : '0';
            modal.show();
        } else if (e.target.classList.contains('deleteDocumentBtn')) {
            if (confirm('Delete this document?')) {
                const row = e.target.closest('tr');
                fetch(`/admin/config/documents/${row.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
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
        const url = id ? `/admin/config/documents/${id}` : '/admin/config/documents';
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
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: nameField.value,
                description: descField.value,
                applies_to: appliesToJson,
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
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\resources\views\admin\config\documents\index.blade.php ENDPATH**/ ?>