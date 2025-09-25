<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Dunco School Management System')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-2xl font-bold text-gray-900">
                                <?php echo e(config('app.name', 'Dunco School Management System')); ?>

                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                Dashboard
                            </a>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                    Logout
                                </button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                Login
                            </a>
                            <a href="<?php echo e(route('register')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                                Register
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="text-center">
                            <h2 class="text-3xl font-bold text-gray-900 mb-4">
                                Welcome to <?php echo e(config('app.name', 'Dunco School Management System')); ?>

                            </h2>
                            <p class="text-lg text-gray-600 mb-8">
                                A comprehensive school management solution for modern educational institutions.
                            </p>
                            
                            <?php if(auth()->guard()->guest()): ?>
                                <div class="space-y-4">
                                    <p class="text-gray-600">
                                        Please login to access your dashboard and manage your school operations.
                                    </p>
                                    <div class="flex justify-center space-x-4">
                                        <a href="<?php echo e(route('login')); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Login
                                        </a>
                                        <a href="<?php echo e(route('register')); ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Register
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="space-y-4">
                                    <p class="text-gray-600">
                                        Welcome back! Access your dashboard to manage school operations.
                                    </p>
                                    <a href="<?php echo e(route('dashboard')); ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        Go to Dashboard
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> <?php /**PATH C:\Users\dunth\dunco school management system\duncoschool\dist\resources\views\welcome.blade.php ENDPATH**/ ?>