<tr>
    <td><?php echo e($student->name); ?></td>
    <td><?php echo e($student->admission_number); ?></td>
    <td><?php echo e($student->class->name ?? '-'); ?></td>
    <td>
        <a href="<?php echo e(route('academic.students.edit', $student->id)); ?>" class="btn btn-sm btn-warning">Edit</a>
        <form action="<?php echo e(route('academic.students.destroy', $student->id)); ?>" method="POST" style="display:inline-block;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
        </form>
    </td>
</tr> <?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\students\partials\row.blade.php ENDPATH**/ ?>