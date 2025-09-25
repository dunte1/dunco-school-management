<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Dunco School Management System')); ?></title>
    
    <!-- Simple styling for tests -->
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #f8f9fa; padding: 20px; margin-bottom: 20px; border-radius: 5px; }
        .content { background: white; padding: 20px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><?php echo e(config('app.name', 'Dunco School Management System')); ?></h1>
            <?php if(auth()->guard()->check()): ?>
                <p>Welcome, <?php echo e(auth()->user()->name); ?>!</p>
                <a href="<?php echo e(route('dashboard')); ?>">Dashboard</a> |
                <a href="<?php echo e(route('profile.edit')); ?>">Profile</a> |
                <form method="POST" action="<?php echo e(route('logout')); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" style="background: none; border: none; color: #007bff; text-decoration: underline; cursor: pointer;">Logout</button>
                </form>
            <?php else: ?>
                <p>Please <a href="<?php echo e(route('login')); ?>">login</a> or <a href="<?php echo e(route('register')); ?>">register</a></p>
            <?php endif; ?>
        </div>
        
        <div class="content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\dunth\Documents\duncoschool\resources\views/app.blade.php ENDPATH**/ ?>