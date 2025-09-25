

<?php $__env->startSection('content'); ?>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Bank Accounts</h1>
    <a href="<?php echo e(route('finance.banks.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mb-4 inline-block">Add Bank Account</a>
    <a href="<?php echo e(route('finance.banks.transfer')); ?>" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4 inline-block ml-2">Record Transfer</a>
    <table class="min-w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Account Number</th>
                <th class="px-4 py-2">Bank Name</th>
                <th class="px-4 py-2">Balance</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="border px-4 py-2"><?php echo e($account->name); ?></td>
                <td class="border px-4 py-2"><?php echo e($account->account_number); ?></td>
                <td class="border px-4 py-2"><?php echo e($account->bank_name); ?></td>
                <td class="border px-4 py-2"><?php echo e(number_format($account->balance, 2)); ?></td>
                <td class="border px-4 py-2">
                    <a href="<?php echo e(route('finance.banks.show', $account)); ?>" class="text-blue-600 hover:underline">View</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('finance::layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Finance/resources/views/banks/index.blade.php ENDPATH**/ ?>