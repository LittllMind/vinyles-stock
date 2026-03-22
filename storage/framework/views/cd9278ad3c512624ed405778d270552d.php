<?php $__env->startSection('titre', "Sortie Stock - {$bougie->nom}"); ?>

<?php $__env->startSection('contenu'); ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

    <div class="mb-6">
        <nav class="flex text-sm text-gray-500">
            <a href="<?php echo e(route('admin.bougies.index')); ?>" class="hover:text-gray-700">Bougies</a>
            <span class="mx-2">/</span>
            <a href="<?php echo e(route('admin.bougies.edit', $bougie)); ?>" class="hover:text-gray-700"><?php echo e($bougie->reference); ?></a>
            <span class="mx-2">/</span>
            <a href="<?php echo e(route('admin.bougies.mouvements.index', $bougie)); ?>" class="hover:text-gray-700">Stock</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Sortie</span>
        </nav>
    </div>

    <div class="bg-white shadow-sm rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 bg-amber-50">
            <h1 class="text-xl font-bold text-gray-900">
                <kbd class="mr-2 text-xl font-bold px-3 py-1 bg-amber-600 text-white rounded">-</kbd>
                Retirer du stock
            </h1>
        </div>

        <div class="p-6">
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-600 mb-1"><strong>Bougie :</strong> <?php echo e($bougie->nom); ?> (<?php echo e($bougie->reference); ?>)</p>
                <p class="text-sm text-gray-600 mb-1"><strong>Parfum :</strong> <?php echo e($bougie->parfum); ?></p>
                <p class="text-sm text-gray-600"><strong>Stock disponible :</strong> 
                    <span class="font-semibold <?php echo e($bougie->quantite <= $bougie->seuil_alerte ? 'text-red-600' : 'text-green-600'); ?>">
                        <?php echo e($bougie->quantite); ?> unités
                    </span>
                </p>
            </div>

            <?php if($bougie->quantite === 0): ?>
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                    Impossible de retirer du stock : stock actuel à 0.
                </div>
                <div class="flex justify-end">
                    <a href="<?php echo e(route('admin.bougies.mouvements.index', $bougie)); ?>" 
                       class="text-gray-600 hover:text-gray-800">Retour</a>
                </div>
            <?php else: ?>
                <form method="POST" action="<?php echo e(route('admin.bougies.mouvements.sortie.store', $bougie)); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Quantité à retirer (<strong>-</strong>)
                        </label>
                        <input type="number" name="quantite" value="<?php echo e(old('quantite', 1)); ?>" 
                               min="1" max="<?php echo e($bougie->quantite); ?>" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        >
                        <p class="mt-1 text-sm text-gray-500">Maximum possible : <?php echo e($bougie->quantite); ?> unités</p>
                        <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Raison</label>
                        <input type="text" name="raison" value="<?php echo e(old('raison')); ?>" required
                               placeholder="Ex: Vente client #456, Perte, Destruction..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        >
                        <?php $__errorArgs = ['raison'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="<?php echo e(route('admin.bougies.mouvements.index', $bougie)); ?>" 
                           class="text-gray-600 hover:text-gray-800">
                            Annuler
                        </a>
                        
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700"
                        >
                            <kbd class="mr-2 text-lg px-2 py-1 bg-amber-700 rounded">-</kbd>
                            Enregistrer la sortie
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/admin/bougies/mouvements/create-sortie.blade.php ENDPATH**/ ?>