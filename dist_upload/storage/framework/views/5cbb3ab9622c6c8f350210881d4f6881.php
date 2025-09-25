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
    <div class="container d-flex justify-content-center align-items-start" style="min-height: 80vh;">
        <div class="w-100" style="max-width: 900px;">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">School Settings</li>
                </ol>
            </nav>
            <h1 class="mb-4"><i class="fas fa-cog"></i> School Settings</h1>
            <div class="row">
                <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-6 mb-4">
                    <div class="card shadow rounded-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0"><?php echo e($setting->display_name ?? $setting->key); ?></h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted"><?php echo e($setting->description ?? 'No description available'); ?></p>
                            <div class="mb-3">
                                <strong>Current Value:</strong>
                                <div class="mt-2">
                                    <?php if($setting->type === 'boolean'): ?>
                                        <?php if($setting->value): ?>
                                            <span class="badge bg-success">Enabled</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Disabled</span>
                                        <?php endif; ?>
                                    <?php elseif($setting->type === 'json'): ?>
                                        <pre class="bg-light p-2 rounded"><?php echo e(json_encode(json_decode($setting->value), JSON_PRETTY_PRINT)); ?></pre>
                                    <?php else: ?>
                                        <code><?php echo e($setting->value); ?></code>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo e(route('core.settings.edit', $setting->id)); ?>" class="btn btn-warning btn-sm" title="Edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <style>
    .breadcrumb {
        background: #fff;
        font-size: 1rem;
    }
    .card {
        border-radius: 1.5rem;
    }
    </style>
    <script>
        // Enable Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $attributes = $__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__attributesOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410)): ?>
<?php $component = $__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410; ?>
<?php unset($__componentOriginalad79281a5cb24e3b5a0a8a09fb4c0410); ?>
<?php endif; ?> <?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Core\resources\views\settings\index.blade.php ENDPATH**/ ?>