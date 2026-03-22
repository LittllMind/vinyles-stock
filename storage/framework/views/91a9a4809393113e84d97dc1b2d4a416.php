<?php $__env->startSection('title', '🎵 Catalogue des Vinyles'); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            🎵 Catalogue des Vinyles
        </h2>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Vinyle::class)): ?>
            <a href="<?php echo e(route('vinyles.create')); ?>" class="btn btn-primary">
                + Nouveau vinyle
            </a>
        <?php endif; ?>
    </div>

    <div class="page-content" x-data="{ showModal: false, selectedVinyle: '', selectedId: null, confirmDelete(id, nom) { this.selectedId = id; this.selectedVinyle = nom; this.showModal = true; }, deleteVinyle() { if (this.selectedId) { fetch('/vinyles/' + this.selectedId, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' } }).then(() => window.location.reload()); } } }">
        
        <!-- Filtres et recherche -->
        <div class="mb-4 flex flex-wrap gap-4 items-end">
            <form method="GET" action="<?php echo e(route('vinyles.index')); ?>" class="flex gap-2 flex-1">
                <div class="flex-1 max-w-md">
                    <input type="text" name="search" value="<?php echo e($search); ?>" 
                           placeholder="Rechercher par titre, artiste ou référence..."
                           class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:outline-none focus:border-purple-500">
                </div>
                <button type="submit" class="btn btn-primary">Rechercher</button>
                <?php if($search): ?>
                    <a href="<?php echo e(route('vinyles.index')); ?>" class="btn btn-secondary">
                        Réinitialiser
                    </a>
                <?php endif; ?>
            </form>
            
            <!-- Filtres rapides -->
            <div class="flex gap-2">
                <a href="<?php echo e(route('vinyles.index', ['filter' => 'stock_bas'] + request()->except('filter', 'page'))); ?>" 
                   class="btn <?php echo e($filter === 'stock_bas' ? 'btn-warning' : 'btn-secondary'); ?> text-sm">
                    ⚠️ Stock bas
                </a>
                <a href="<?php echo e(route('vinyles.index', ['filter' => 'rupture'] + request()->except('filter', 'page'))); ?>" 
                   class="btn <?php echo e($filter === 'rupture' ? 'btn-danger' : 'btn-secondary'); ?> text-sm">
                    🚨 Rupture
                </a>
                <?php if($filter): ?>
                    <a href="<?php echo e(route('vinyles.index', request()->except('filter', 'page'))); ?>" class="btn btn-secondary text-sm">
                        ❌ Filtre
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Badge filtre actif -->
        <?php if($filter): ?>
            <div class="mb-4">
                <span class="badge <?php echo e($filter === 'stock_bas' ? 'badge-warning' : 'badge-danger'); ?>">
                    <?php echo e($filter === 'stock_bas' ? '⚠️ Stock bas uniquement' : '🚨 Ruptures de stock'); ?>

                </span>
            </div>
        <?php endif; ?>

        <!-- Tableau -->
        <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg border border-gray-700">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-700/50 text-purple-300">
                        <th class="px-4 py-3 font-semibold">Référence</th>
                        <th class="px-4 py-3 font-semibold">Titre</th>
                        <th class="px-4 py-3 font-semibold">Artiste</th>
                        <th class="px-4 py-3 font-semibold">Genre/Style</th>
                        <th class="px-4 py-3 font-semibold text-right">Prix</th>
                        <th class="px-4 py-3 font-semibold text-center">Stock</th>
                        <th class="px-4 py-3 font-semibold text-center">Statut</th>
                        <th class="px-4 py-3 font-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $vinyles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vinyle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-700/30 transition <?php echo e($vinyle->isOutOfStock() ? 'opacity-60' : ''); ?>">
                            <td class="px-4 py-3 text-gray-400 font-mono text-sm">
                                <?php echo e($vinyle->reference ?? 'N/A'); ?>

                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <?php if($vinyle->hasMedia('photo')): ?>
                                        <img src="<?php echo e($vinyle->getFirstMediaUrl('photo', 'thumb')); ?>"
                                             alt="<?php echo e($vinyle->modele); ?>" 
                                             class="w-10 h-10 rounded object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded bg-gray-600 flex items-center justify-center text-gray-400 text-xs">
                                            🎵
                                        </div>
                                    <?php endif; ?>
                                    <span class="font-medium text-white"><?php echo e($vinyle->modele); ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-300">
                                <?php echo e($vinyle->artiste ?? '—'); ?>

                            </td>
                            <td class="px-4 py-3">
                                <?php if($vinyle->genre || $vinyle->style): ?>
                                    <span class="text-gray-300 text-sm">
                                        <?php echo e($vinyle->genre); ?><?php echo e($vinyle->genre && $vinyle->style ? ' / ' : ''); ?><?php echo e($vinyle->style); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-500">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-green-300">
                                <?php echo e(number_format($vinyle->prix, 2, ',', ' ')); ?> €
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-lg font-bold <?php echo e($vinyle->isOutOfStock() ? 'text-red-400' : 'text-white'); ?>">
                                    <?php echo e($vinyle->quantite); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold <?php echo e($vinyle->stock_status_class); ?>">
                                    <?php echo e($vinyle->stock_status); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex gap-2 justify-center">
                                    <a href="<?php echo e(route('vinyles.edit', $vinyle)); ?>" class="btn btn-sm btn-secondary">
                                        Éditer
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete', $vinyle)): ?>
                                        <button @click="confirmDelete(<?php echo e($vinyle->id); ?>, '<?php echo e(addslashes($vinyle->nom)); ?>')"
                                                class="btn btn-sm btn-danger">
                                            Supprimer
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                Aucun vinyle trouvé
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            <?php echo e($vinyles->links()); ?>

        </div>

        <!-- Modal de confirmation -->
        <div x-show="showModal" x-cloak class="fixed inset-0 bg-black/70 flex items-center justify-center z-50" @click.away="showModal = false">
            <div class="bg-gray-800 p-6 rounded-lg border border-gray-600 max-w-md w-full mx-4" @click.stop>
                <h3 class="text-xl font-bold mb-4 text-white">Confirmer la suppression</h3>
                <p class="text-gray-300 mb-6">
                    Êtes-vous sûr de vouloir supprimer le vinyle <strong x-text="selectedVinyle" class="text-purple-400"></strong> ?
                </p>
                <div class="flex gap-3">
                    <button @click="showModal = false" class="flex-1 btn btn-secondary">Annuler</button>
                    <button @click="deleteVinyle()" class="flex-1 btn btn-danger">Supprimer</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/vinyles/index.blade.php ENDPATH**/ ?>