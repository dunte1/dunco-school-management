<div class="sidebar-card mb-3">
  <div class="sidebar-card-header d-flex justify-content-between align-items-center">
    <h5 class="sidebar-card-title m-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
    <div class="dropdown">
      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="qaDropdownGlobal" data-bs-toggle="dropdown" aria-expanded="false">
        + Add New
      </button>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="qaDropdownGlobal">
        <li><a class="dropdown-item" href="#" data-action="add-student"><i class="fas fa-user-plus me-2"></i>Student</a></li>
        <li><a class="dropdown-item" href="#" data-action="record-payment"><i class="fas fa-credit-card me-2"></i>Payment</a></li>
        <li><a class="dropdown-item" href="#" data-action="create-exam"><i class="fas fa-file-alt me-2"></i>Exam</a></li>
        <li><a class="dropdown-item" href="#" data-action="send-message"><i class="fas fa-envelope me-2"></i>Message</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="#" data-action="system-settings"><i class="fas fa-cog me-2"></i>System Settings</a></li>
      </ul>
    </div>
  </div>
  <div class="sidebar-card-body">
    <div class="row g-2">
      <div class="col-6"><button data-action="add-student" class="btn btn-outline-primary w-100" type="button"><i class="fas fa-user-plus me-1"></i>Student</button></div>
      <div class="col-6"><button data-action="record-payment" class="btn btn-outline-success w-100" type="button"><i class="fas fa-credit-card me-1"></i>Payment</button></div>
      <div class="col-6"><button data-action="create-exam" class="btn btn-outline-info w-100" type="button"><i class="fas fa-file-alt me-1"></i>Exam</button></div>
      <div class="col-6"><button data-action="send-message" class="btn btn-outline-warning w-100" type="button"><i class="fas fa-envelope me-1"></i>Message</button></div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function(){
  function go(url){ try{ window.location.href = url; }catch(e){ console.error(e); } }
  document.addEventListener('click', function(e){
    const a = e.target.closest('[data-action]'); if(!a) return;
    const act = a.getAttribute('data-action');
    if (act === 'add-student') { go('/students/create'); }
    else if (act === 'record-payment') { go('/finance/payments/create'); }
    else if (act === 'create-exam') { go('/examinations/create'); }
    else if (act === 'send-message') { go('/communication/compose'); }
    else if (act === 'system-settings') { go('/admin/settings'); }
  });
})();
</script>
@endpush
