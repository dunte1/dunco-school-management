<?php if (isset($component)) { $__componentOriginal9435c5bf0c3b36361e286097c09bb249 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9435c5bf0c3b36361e286097c09bb249 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'academic::components.layouts.master','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('academic::layouts.master'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <h1>Hello World</h1>

    <p>Module: <?php echo config('academic.name'); ?></p>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9435c5bf0c3b36361e286097c09bb249)): ?>
<?php $attributes = $__attributesOriginal9435c5bf0c3b36361e286097c09bb249; ?>
<?php unset($__attributesOriginal9435c5bf0c3b36361e286097c09bb249); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9435c5bf0c3b36361e286097c09bb249)): ?>
<?php $component = $__componentOriginal9435c5bf0c3b36361e286097c09bb249; ?>
<?php unset($__componentOriginal9435c5bf0c3b36361e286097c09bb249); ?>
<?php endif; ?>
<?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\Modules\Academic\resources\views\index.blade.php ENDPATH**/ ?>