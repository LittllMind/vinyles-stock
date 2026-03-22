<?php $__env->startSection('titre', "Historique Stock - {$bougie->nom}"); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <nav class="flex mb-2 text-sm text-gray-500">
                <a href="<?php echo e(route('admin.bougies.index')); ?>" class="hover:text-gray-700">Bougies</a>
                <span class="mx-2">/</span>
                <a href="<?php echo e(route('admin.bougies.edit', $bougie)); ?>" class="hover:text-gray-700"><?php echo e($bougie->reference); ?></a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Stock</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900">Historique Stock</h1>
            <p class="text-gray-600"><?php echo e($bougie->nom); ?> - Stock actuel : 
                <span class="<?php echo e($bougie->quantite <= $bougie->seuil_alerte ? 'text-red-600 font-semibold' : 'text-green-600 font-semibold'); ?>">
                    <?php echo e($bougie->quantite); ?> unités
                </span>
            </p>
        </div>
        
        <div class="flex space-x-3">
            <a href="<?php echo e(route('admin.bougies.mouvements.entree.create', $bougie)); ?>" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                <kbd class="mr-2 text-lg font-bold px-2 py-1 bg-green-700 rounded">+</kbd>
                Entrée de stock
            </a>
            <a href="<?php echo e(route('admin.bougies.mouvements.sortie.create', $bougie)); ?>" 
               class="inline-flex items-center px-4 py-2 bg-amber-600 text-white rounded-md hover:bg-amber-700">
                <kbd class="mr-2 text-lg font-bold px-2 py-1 bg-amber-700 rounded">-</kbd>
                Sortie de stock
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800">Mouvements enregistrés</h2>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Raison</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Par</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $mouvements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if($mouvement->type === 'entree'): ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                Entrée
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">
                                Sortie
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold <?php echo e($mouvement->type === 'entree' ? 'text-green-600' : 'text-red-600'); ?>">
                        <?php echo e($mouvement->type === 'entree' ? '+' : '-'); ?><?php echo e($mouvement->quantite); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        <?php echo e($mouvement->notes ?: '-'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                        <?php echo e($mouvement->user->name ?? 'Système'); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm">
                        <?php echo e($mouvement->created_at->format('d/m/Y H:i')); ?>

                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        Aucun mouvement enregistré pour cette bougie.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($mouvements->hasPages()): ?>
    <div class="mt-4">
        <?php echo e($mouvements->links()); ?>

    </div>
    <?php endif; ?>

    <div class="mt-6">
        <a href="<?php echo e(route('admin.bougies.edit', $bougie)); ?>" 
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour à la bougie
        </a>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/admin/bougies/mouvements/index.blade.php ENDPATH**/ ?>