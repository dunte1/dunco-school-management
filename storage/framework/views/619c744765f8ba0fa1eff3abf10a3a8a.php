

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Receipts</h1>
    <div class="bg-white rounded shadow p-6">
        <form method="GET" class="mb-4 flex flex-wrap gap-2 items-center">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search by Receipt #, Invoice #, Method..." class="border rounded px-3 py-2 w-64">
            <input type="date" name="from" value="<?php echo e(request('from')); ?>" class="border rounded px-3 py-2">
            <input type="date" name="to" value="<?php echo e(request('to')); ?>" class="border rounded px-3 py-2">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            <?php if(request('search') || request('from') || request('to')): ?>
                <a href="<?php echo e(route('finance.receipts.index')); ?>" class="ml-2 text-gray-600 hover:underline">Reset</a>
            <?php endif; ?>
        </form>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="px-4 py-2">Receipt #</th>
                    <th class="px-4 py-2">Invoice #</th>
                    <th class="px-4 py-2">Amount</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Method</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="border px-4 py-2"><?php echo e($receipt->id); ?></td>
                    <td class="border px-4 py-2"><?php echo e($receipt->invoice ? $receipt->invoice->id : '-'); ?></td>
                    <td class="border px-4 py-2"><?php echo e(number_format($receipt->amount, 2)); ?></td>
                    <td class="border px-4 py-2"><?php echo e($receipt->payment_date ? \Carbon\Carbon::parse($receipt->payment_date)->format('d M Y') : '-'); ?></td>
                    <td class="border px-4 py-2"><?php echo e($receipt->method); ?></td>
                    <td class="border px-4 py-2"><?php echo e(ucfirst($receipt->status)); ?></td>
                    <td class="border px-4 py-2 flex gap-2">
                        <a href="<?php echo e(route('finance.receipts.show', $receipt)); ?>" class="text-blue-600 hover:underline" title="View"><i class="fas fa-eye"></i></a>
                        <a href="<?php echo e(route('finance.receipts.print', $receipt)); ?>" class="text-green-600 hover:underline" title="Print" target="_blank"><i class="fas fa-print"></i></a>
                        <a href="<?php echo e(route('finance.receipts.download', $receipt)); ?>" class="text-purple-600 hover:underline" title="Download PDF"><i class="fas fa-file-pdf"></i></a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-4">No receipts found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('finance::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Finance/resources/views/receipts/index.blade.php ENDPATH**/ ?>