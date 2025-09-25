<?php if (isset($component)) { $__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'core::components.layouts.master','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('core::layouts.master'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Audit Log Details</h1>
            <a href="<?php echo e(route('core.audit-logs.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Audit Logs
            </a>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Log Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>ID:</strong></div>
                            <div class="col-md-9"><?php echo e($log->id); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>User:</strong></div>
                            <div class="col-md-9"><?php echo e(optional($log->user)->name ?? 'System'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Action:</strong></div>
                            <div class="col-md-9">
                                <?php switch($log->action):
                                    case ('created'): ?>
                                        <span class="badge bg-success">Created</span>
                                        <?php break; ?>
                                    <?php case ('updated'): ?>
                                        <span class="badge bg-warning">Updated</span>
                                        <?php break; ?>
                                    <?php case ('deleted'): ?>
                                        <span class="badge bg-danger">Deleted</span>
                                        <?php break; ?>
                                    <?php default: ?>
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($log->action)); ?></span>
                                <?php endswitch; ?>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Model:</strong></div>
                            <div class="col-md-9"><?php echo e($log->model_type); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Record ID:</strong></div>
                            <div class="col-md-9"><?php echo e($log->model_id); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>IP Address:</strong></div>
                            <div class="col-md-9"><?php echo e($log->ip_address ?? 'N/A'); ?></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>Created:</strong></div>
                            <div class="col-md-9"><?php echo e($log->created_at ? $log->created_at->format('M d, Y H:i:s') : 'Unknown'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Changes</h5>
                    </div>
                    <div class="card-body">
                        <?php if($log->changes): ?>
                            <div class="mb-3">
                                <h6>Old Values:</h6>
                                <pre class="bg-light p-2 rounded"><?php echo e(json_encode($log->changes['old'] ?? [], JSON_PRETTY_PRINT)); ?></pre>
                            </div>
                            <div class="mb-3">
                                <h6>New Values:</h6>
                                <pre class="bg-light p-2 rounded"><?php echo e(json_encode($log->changes['new'] ?? [], JSON_PRETTY_PRINT)); ?></pre>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No changes recorded</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $attributes = $__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $component = $__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?> <?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Core\resources\views\audit_logs\show.blade.php ENDPATH**/ ?>