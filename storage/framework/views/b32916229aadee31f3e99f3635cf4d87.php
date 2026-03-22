<?php $__env->startSection('title', 'Historique Mouvements - Fundisc'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            📦 Historique des Mouvements
        </h1>
        <p class="text-gray-400 mt-2">Suivi des entrées et sorties de stock</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-green-900/50 to-green-800/30 border border-green-700/50 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-300 text-sm font-medium">Total Entrées</p>
                    <p class="text-3xl font-bold text-green-400 mt-1">+<?php echo e($stats['total_entrees']); ?></p>
                </div>
                <div class="text-4xl">📥</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-900/50 to-red-800/30 border border-red-700/50 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-300 text-sm font-medium">Total Sorties</p>
                    <p class="text-3xl font-bold text-red-400 mt-1">-<?php echo e($stats['total_sorties']); ?></p>
                </div>
                <div class="text-4xl">📤</div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-900/50 to-purple-800/30 border border-purple-700/50 rounded-2xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-300 text-sm font-medium">Aujourd'hui</p>
                    <p class="text-3xl font-bold text-purple-400 mt-1"><?php echo e($stats['aujourdhui']); ?></p>
                </div>
                <div class="text-4xl">📅</div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-gray-800/50 border border-gray-700/50 rounded-2xl p-6 mb-8">
        <form method="GET" action="<?php echo e(route('mouvements.index')); ?>" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Type</label>
                <select name="type" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-2 text-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    <option value="">Tous</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e(request('type') == $value ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Produit Type -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Produit</label>
                <select name="produit_type" class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-2 text-gray-300 focus:border-purple-500 focus:ring-purple-500">
                    <option value="">Tous</option>
                    <?php $__currentLoopData = $produitTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>" <?php echo e(request('produit_type') == $value ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <!-- Date début -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Du</label>
                <input type="date" name="date_debut" value="<?php echo e(request('date_debut')); ?>"
                       class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-2 text-gray-300 focus:border-purple-500 focus:ring-purple-500">
            </div>

            <!-- Date fin -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Au</label>
                <input type="date" name="date_fin" value="<?php echo e(request('date_fin')); ?>"
                       class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-2 text-gray-300 focus:border-purple-500 focus:ring-purple-500">
            </div>

            <!-- Recherche -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Référence</label>
                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Réf..."
                       class="w-full bg-gray-900/50 border border-gray-700 rounded-xl px-4 py-2 text-gray-300 focus:border-purple-500 focus:ring-purple-500">
            </div>

            <!-- Boutons -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Filtrer
                </button>
                <a href="<?php echo e(route('mouvements.index')); ?>" class="px-4 py-2 bg-gray-700 text-gray-300 rounded-xl hover:bg-gray-600 transition">
                    Réinit
                </a>
            </div>
        </form>
    </div>

    <!-- Actions -->
    <div class="mb-6 flex justify-end">
        <a href="<?php echo e(route('mouvements.export', request()->all())); ?>" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:from-emerald-700 hover:to-teal-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            Exporter CSV
        </a>
    </div>

    <!-- Tableau -->
    <div class="bg-gray-800/50 border border-gray-700 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Produit</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Qté</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Par</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Référence</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $mouvements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mouvement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-purple-400">
                                    <?php echo e($mouvement->date_mouvement->format('d/m/Y')); ?>

                                </div>
                                <div class="text-xs text-gray-500">
                                    <?php echo e($mouvement->date_mouvement->format('H:i')); ?>

                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($mouvement->type === 'entree'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/50 text-green-400 border border-green-700">
                                        📥 Entrée
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-900/50 text-red-400 border border-red-700">
                                        📤 Sortie
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-300"><?php echo e($mouvement->produit_libelle); ?></span>
                                <div class="text-xs text-gray-500">ID: <?php echo e($mouvement->produit_id); ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-semibold <?php echo e($mouvement->type === 'entree' ? 'text-green-400' : 'text-red-400'); ?>">
                                    <?php echo e($mouvement->type === 'entree' ? '+' : '-'); ?><?php echo e($mouvement->quantite); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-pink-600 flex items-center justify-center text-white text-xs font-bold">
                                        <?php echo e(strtoupper(substr($mouvement->user?->name ?? 'S', 0, 1))); ?>

                                    </div>
                                    <span class="text-sm text-gray-300"><?php echo e($mouvement->user?->name ?? 'Système'); ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-400 font-mono"><?php echo e($mouvement->reference ?? '-'); ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-400"><?php echo e($mouvement->notes ?? '-'); ?></span>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-gray-400 text-lg">Aucun mouvement trouvé</p>
                                    <p class="text-gray-500 text-sm mt-1">Les mouvements apparaîtront ici lors des entrées/sorties de stock</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if($mouvements->hasPages()): ?>
            <div class="px-6 py-4 border-t border-gray-700">
                <?php echo e($mouvements->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/mouvements/index.blade.php ENDPATH**/ ?>