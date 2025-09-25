<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Portal Module - <?php echo e(config('app.name', 'Laravel')); ?></title>

        <meta name="description" content="<?php echo e($description ?? ''); ?>">
        <meta name="keywords" content="<?php echo e($keywords ?? ''); ?>">
        <meta name="author" content="<?php echo e($author ?? ''); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap 5 CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        
        
    </head>

    <body>
        <div class="container-fluid p-0">
            <div class="row g-0">
                <div class="col-auto">
                    <?php echo $__env->make('portal::components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <div class="col" style="margin-left:220px;min-height:100vh;">
                    <main class="p-4">
                        <?php echo $__env->yieldContent('content'); ?>
                    </main>
                </div>
            </div>
        </div>
        <!-- Bootstrap 5 JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        
        
    </body>
<?php /**PATH C:\Users\dunth\Documents\duncoschool\Modules/Portal/resources/views/components/layouts/master.blade.php ENDPATH**/ ?>