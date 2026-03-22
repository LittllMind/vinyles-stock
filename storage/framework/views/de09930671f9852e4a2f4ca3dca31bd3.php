<?php $__env->startSection('title', 'Historique des Ventes du Jour'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Historique des Ventes</h1>
        
        <!-- Sélecteur de date -->
        <form method="GET" action="<?php echo e(route('marche.ventes-jour')); ?>" class="flex items-center gap-2">
            <label for="date" class="text-sm text-gray-600">Date :</label>
            <input 
                type="date" 
                id="date" 
                name="date" 
                value="<?php echo e($dateSelectionnee->format('Y-m-d')); ?>"
                class="border rounded px-3 py-1 text-sm"
            >
            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                Filtrer
            </button>
        </form>
    </div>

    <!-- Résumé du jour -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="p-4 bg-gray-50 rounded">
                <div class="text-2xl font-bold text-blue-600"><?php echo e($ventes->count()); ?></div>
                <div class="text-sm text-gray-600">Ventes</div>
            </div>
            <div class="p-4 bg-gray-50 rounded">
                <div class="text-2xl font-bold text-green-600"><?php echo e(number_format($totalJour, 2, ',', ' ')); ?> €</div>
                <div class="text-sm text-gray-600">Total du jour</div>
            </div>
            <div class="p-4 bg-gray-50 rounded">
                <div class="text-2xl font-bold text-purple-600"><?php echo e($ventes->sum(fn($v) => $v->items->sum('quantite'))); ?></div>
                <div class="text-sm text-gray-600">Articles vendus</div>
            </div>
        </div>
    </div>

    <!-- Liste des ventes -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <?php if($ventes->isEmpty()): ?>
            <div class="p-8 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <p>Aucune vente pour le <?php echo e($dateSelectionnee->format('d/m/Y')); ?></p>
            </div>
        <?php else: ?>
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">N°</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Articles</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mode de paiement</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__currentLoopData = $ventes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">#<?php echo e($vente->id); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo e($vente->created_at->format('H:i')); ?></td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <?php $__currentLoopData = $vente->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex justify-between items-center mb-1">
                                            <span><?php echo e($item->quantite); ?>x <?php echo e($item->titre_vinyle ?? $item->vinyle?->nom ?? 'Vinyle'); ?></span>
                                            <span class="text-gray-500 text-xs"><?php echo e(number_format($item->prix_unitaire, 2)); ?>€</span>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    <?php if($vente->mode_paiement === 'especes'): ?> bg-green-100 text-green-800
                                    <?php elseif($vente->mode_paiement === 'carte'): ?> bg-blue-100 text-blue-800
                                    <?php elseif($vente->mode_paiement === 'cheque'): ?> bg-purple-100 text-purple-800
                                    <?php else: ?> bg-gray-100 text-gray-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($vente->mode_paiement)); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-semibold">
                                <?php echo e(number_format($vente->total, 2, ',', ' ')); ?> €
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <a href="<?php echo e(route('ventes.show', $vente)); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                                    Détails
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Navigation dates -->
    <div class="flex justify-between mt-6">
        <?php if($datePrecedente): ?>
            <a href="<?php echo e(route('marche.ventes-jour', ['date' => $datePrecedente->format('Y-m-d')])); ?>" 
               class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                ← Jour précédent
            </a>
        <?php else: ?>
            <span class="text-gray-400 cursor-not-allowed">← Jour précédent</span>
        <?php endif; ?>

        <?php if($dateSuivante): ?>
            <a href="<?php echo e(route('marche.ventes-jour', ['date' => $dateSuivante->format('Y-m-d')])); ?>" 
               class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                Jour suivant →
            </a>
        <?php else: ?>
            <span class="text-gray-400 cursor-not-allowed">Jour suivant →</span>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/marche/ventes-jour.blade.php ENDPATH**/ ?>