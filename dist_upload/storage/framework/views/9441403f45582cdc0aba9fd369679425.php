<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <title>Core Module - <?php echo e(config('app.name', 'Laravel')); ?></title>

        <meta name="description" content="<?php echo e($description ?? ''); ?>">
        <meta name="keywords" content="<?php echo e($keywords ?? ''); ?>">
        <meta name="author" content="<?php echo e($author ?? ''); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        
        
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- FontAwesome (optional, for icons) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>

    <body>
        <?php echo e($slot); ?>


        
        
        <!-- Bootstrap JS (for modals, tooltips, etc.) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
<?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\Modules/Core/resources/views/components/layouts/master.blade.php ENDPATH**/ ?>