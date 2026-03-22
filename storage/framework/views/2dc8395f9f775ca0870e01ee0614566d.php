<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\AppLayout::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
                📦 Gestion des Pochettes (Fonds)
            </h2>
            <?php if(auth()->user()->isAdmin()): ?>
                <span class="text-sm text-yellow-400">Mode Admin</span>
            <?php else: ?>
                <span class="text-sm text-blue-400">Mode Employé (lecture seule)</span>
            <?php endif; ?>
        </div>
        <p class="text-gray-400 mt-2">Gestion des pochettes miroir et dorées pour vinyles</p>
     <?php $__env->endSlot(); ?>

    <div class="page-content" x-data="{ 
        showStockModal: false, 
        selectedFond: null,
        stockAction: 'increment',
        stockQuantity: 1,
        
        openStockModal(fond) {
            this.selectedFond = fond;
            this.stockAction = 'increment';
            this.stockQuantity = 1;
            this.showStockModal = true;
        },
        
        submitStockForm() {
            if (this.selectedFond) {
                document.getElementById('stock-form-' + this.selectedFond.id).submit();
            }
        }
    }">
        <!-- Tableau des fonds -->
        <div class="overflow-x-auto bg-gray-800 rounded-lg shadow-lg border border-gray-700">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-700/50 text-purple-300">
                        <th class="px-4 py-3 font-semibold">Type</th>
                        <th class="px-4 py-3 font-semibold">Visuel</th>
                        <th class="px-4 py-3 font-semibold text-center">Qté</th>
                        <th class="px-4 py-3 font-semibold text-right">Prix achat</th>
                        <th class="px-4 py-3 font-semibold text-right">Montant stock</th>
                        <th class="px-4 py-3 font-semibold text-right">Prix vente</th>
                        <th class="px-4 py-3 font-semibold text-right">Valeur stock</th>
                        <th class="px-4 py-3 font-semibold text-center">Status</th>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <th class="px-4 py-3 font-semibold text-center">Actions</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php $__empty_1 = true; $__currentLoopData = $fonds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fond): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-700/30 transition <?php echo e($fond['status'] === 'Rupture' ? 'opacity-60' : ''); ?>">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-lg <?php echo e($fond['type'] === 'Miroir' ? 'text-blue-400' : 'text-yellow-400'); ?>">
                                    <?php echo e($fond['type']); ?>

                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-300 text-sm">
                                <?php echo e($fond['visuel']); ?>

                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-xl font-bold <?php echo e($fond['quantite'] === 0 ? 'text-red-400' : 'text-white'); ?>">
                                    <?php echo e($fond['quantite']); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-300">
                                <?php echo e(number_format($fond['prix_achat'], 2, ',', ' ')); ?> €
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-orange-300 font-semibold">
                                    <?php echo e(number_format($fond['montant_stock'], 2, ',', ' ')); ?> €
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right text-gray-300">
                                <?php echo e(number_format($fond['prix_vente'], 2, ',', ' ')); ?> €
                            </td>
                            <td class="px-4 py-3 text-right">
                                <span class="text-green-300 font-bold">
                                    <?php echo e(number_format($fond['valeur_stock'], 2, ',', ' ')); ?> €
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold <?php echo e($fond['status_class']); ?>">
                                    <?php echo e($fond['status']); ?>

                                </span>
                            </td>
                            <?php if(auth()->user()->isAdmin()): ?>
                                <td class="px-4 py-3 text-center">
                                    <button @click="openStockModal(<?php echo e(json_encode($fond)); ?>)" 
                                            class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded text-sm transition">
                                        Modifier stock
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(auth()->user()->isAdmin() ? 9 : 8); ?>" class="px-4 py-8 text-center text-gray-500">
                                Aucune pochette configurée
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot class="bg-gray-700/50 font-bold">
                    <tr>
                        <td class="px-4 py-3 text-purple-300" colspan="2">TOTAL</td>
                        <td class="px-4 py-3 text-center text-white"><?php echo e($totaux['quantite_totale']); ?></td>
                        <td class="px-4 py-3"></td>
                        <td class="px-4 py-3 text-right text-orange-300">
                            <?php echo e(number_format($totaux['montant_investi'], 2, ',', ' ')); ?> €
                        </td>
                        <td class="px-4 py-3"></td>
                        <td class="px-4 py-3 text-right text-green-300">
                            <?php echo e(number_format($totaux['valeur_totale'], 2, ',', ' ')); ?> €
                        </td>
                        <td class="px-4 py-3"></td>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <td class="px-4 py-3"></td>
                        <?php endif; ?>
                    </tr>
                    <tr class="border-t border-gray-600">
                        <td class="px-4 py-3 text-purple-300" colspan="6">MARGE POTENTIELLE</td>
                        <td class="px-4 py-3 text-right text-pink-300 font-bold text-lg">
                            +<?php echo e(number_format($totaux['marge_totale'], 2, ',', ' ')); ?> €
                        </td>
                        <td class="px-4 py-3" colspan="<?php echo e(auth()->user()->isAdmin() ? 2 : 1); ?>"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Résumé cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm">Stock total</div>
                <div class="text-2xl font-bold text-white"><?php echo e($totaux['quantite_totale']); ?></div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm">Investissement</div>
                <div class="text-2xl font-bold text-orange-300"><?php echo e(number_format($totaux['montant_investi'], 2, ',', ' ')); ?> €</div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm">Valeur stock</div>
                <div class="text-2xl font-bold text-green-300"><?php echo e(number_format($totaux['valeur_totale'], 2, ',', ' ')); ?> €</div>
            </div>
            <div class="bg-gray-800 p-4 rounded-lg border border-gray-700">
                <div class="text-gray-400 text-sm">Marge potentielle</div>
                <div class="text-2xl font-bold text-pink-300">+<?php echo e(number_format($totaux['marge_totale'], 2, ',', ' ')); ?> €</div>
            </div>
        </div>

        <!-- Modal modification stock (Admin uniquement) -->
        <?php if(auth()->user()->isAdmin()): ?>
            <div x-show="showStockModal" x-cloak class="fixed inset-0 bg-black/70 flex items-center justify-center z-50" @click.away="showStockModal = false">
                <div class="bg-gray-800 p-6 rounded-lg border border-gray-600 max-w-md w-full mx-4" @click.stop>
                    <h3 class="text-xl font-bold mb-4 text-white">
                        Modifier le stock : <span x-text="selectedFond?.type" class="text-purple-400"></span>
                    </h3>
                    <p class="text-gray-400 mb-4">
                        Stock actuel : <span x-text="selectedFond?.quantite" class="text-white font-bold"></span>
                    </p>
                    
                    <form :id="'stock-form-' + selectedFond?.id" :action="'/fonds/' + selectedFond?.id + '/stock'" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        
                        <div class="mb-4">
                            <label class="block text-gray-300 mb-2">Action</label>
                            <select x-model="stockAction" name="action" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                                <option value="increment">➕ Ajouter au stock</option>
                                <option value="decrement">➖ Retirer du stock</option>
                                <option value="set">📝 Définir le stock</option>
                            </select>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-gray-300 mb-2">Quantité</label>
                            <input type="number" x-model="stockQuantity" name="quantite" min="1" required
                                   class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white">
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" @click="showStockModal = false" 
                                    class="flex-1 bg-gray-600 hover:bg-gray-500 text-white py-2 rounded transition">
                                Annuler
                            </button>
                            <button type="submit" 
                                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 rounded transition">
                                Valider
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/fonds/index.blade.php ENDPATH**/ ?>