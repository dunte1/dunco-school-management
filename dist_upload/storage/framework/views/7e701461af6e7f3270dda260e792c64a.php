<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Academic Module - <?php echo e(config('app.name', 'Laravel')); ?></title>

        <meta name="description" content="<?php echo e($description ?? ''); ?>">
        <meta name="keywords" content="<?php echo e($keywords ?? ''); ?>">
        <meta name="author" content="<?php echo e($author ?? ''); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <?php echo app('Illuminate\Foundation\Vite')('resources/css/app.css'); ?>
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>

    <body class="bg-gray-50 dark:bg-gray-900">
        <div class="container-fluid">
            <div class="row">
                <!-- Main Content -->
                <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                    <?php echo $__env->yieldContent('content'); ?>
                </main>
            </div>
        </div>
        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
    </body>

</html>
<?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules\Academic\resources\views\components\layouts\master.blade.php ENDPATH**/ ?>