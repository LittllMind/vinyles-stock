<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> - Vinyles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php echo $__env->yieldPushContent('head'); ?>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

    <!-- Navigation Admin -->
    <nav class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <a href="/dashboard" class="text-xl font-bold">🎧 Admin Vinyles</a>
                <div class="flex items-center gap-4">
                    <a href="/kiosque" class="hover:text-gray-300">Kiosque</a>
                    <a href="/" class="hover:text-gray-300">Site</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="container mx-auto px-4 py-8">
        <?php if(session('success')): ?>
            <div class="bg-green-500 text-white px-4 py-3 rounded mb-4">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="bg-red-500 text-white px-4 py-3 rounded mb-4">
                <?php echo e(session('error')); ?>

            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>

</body>
</html>
<?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/layouts/admin.blade.php ENDPATH**/ ?>