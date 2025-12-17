<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Laravel')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Scripts -->
    <link rel="stylesheet" href="<?php echo e(mix('css/app.css')); ?>">
    <script src="<?php echo e(mix('js/app.js')); ?>" defer></script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Page Heading -->
        <?php if(isset($header)): ?>
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <?php echo e($header); ?>

                </div>
            </header>
        <?php endif; ?>

        <!-- Page Content -->
        <main class="flex-grow">
            <?php echo $__env->yieldContent('content'); ?>
        </main>

        <!-- FOOTER -->
        <footer class="bg-blue-600 text-white shadow-lg mt-auto">
            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    
                         <?php echo e(date('Y')); ?> Шарафутдинов Роман
                    
                    <div class="flex space-x-4 mt-2 md:mt-0">
                        <a href="https://vk.com" target="_blank" rel="noopener" 
                           class="text-white hover:text-blue-200 transition"
                           title="ВКонтакте">
                            <i class="fab fa-vk text-xl"></i>
                        </a>
                        <a href="https://t.me" target="_blank" rel="noopener" 
                           class="text-white hover:text-blue-200 transition"
                           title="Telegram">
                            <i class="fab fa-telegram text-xl"></i>
                        </a>
                        <a href="https://github.com" target="_blank" rel="noopener" 
                           class="text-white hover:text-blue-200 transition"
                           title="GitHub">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html><?php /**PATH C:\Users\User\Desktop\lab33\resources\views/layouts/app.blade.php ENDPATH**/ ?>