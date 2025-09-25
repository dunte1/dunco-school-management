

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Payments</h1>
    <a href="<?php echo e(route('finance.payments.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Record Payment</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Invoice</th>
                <th class="px-4 py-2">Amount</th>
                <th class="px-4 py-2">Date</th>
                <th class="px-4 py-2">Method</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="border px-4 py-2">#<?php echo e($payment->invoice_id); ?></td>
                <td class="border px-4 py-2"><?php echo e(number_format($payment->amount, 2)); ?></td>
                <td class="border px-4 py-2"><?php echo e($payment->payment_date); ?></td>
                <td class="border px-4 py-2"><?php echo e($payment->method ?? '-'); ?></td>
                <td class="border px-4 py-2"><?php echo e(ucfirst($payment->status)); ?></td>
                <td class="border px-4 py-2">
                    <a href="<?php echo e(route('finance.payments.show', $payment)); ?>" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('finance::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Finance/resources/views/payments/index.blade.php ENDPATH**/ ?>