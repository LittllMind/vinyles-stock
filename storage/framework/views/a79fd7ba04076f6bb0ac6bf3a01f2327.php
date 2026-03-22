<?php
$isEdit = isset($vinyle) && $vinyle->id !== null;
?>



<?php $__env->startSection('title', $isEdit ? '✏️ Modifier : ' . $vinyle->nom : '➕ Nouveau Vinyle'); ?>

<?php $__env->startSection('content'); ?>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">
            <?php echo e($isEdit ? '✏️ Modifier : ' . $vinyle->nom : '➕ Nouveau Vinyle'); ?>

        </h2>
        <a href="<?php echo e(route('vinyles.index')); ?>" class="btn btn-secondary">
            ← Retour
        </a>
    </div>

    <div class="page-content">
        <form action="<?php echo e($isEdit ? route('vinyles.update', $vinyle) : route('vinyles.store')); ?>" 
              method="POST" 
              enctype="multipart/form-data"
              class="max-w-4xl">
            <?php echo csrf_field(); ?>
            <?php if($isEdit): ?>
                <?php echo method_field('PUT'); ?>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-4">
                    
                    <div>
                        <label for="nom" class="block text-sm font-medium text-gray-300 mb-1">Nom du vinyle *</label>
                        <input type="text" name="nom" id="nom" value="<?php echo e(old('nom', $vinyle->nom ?? '')); ?>" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                        >
                        <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="artiste" class="block text-sm font-medium text-gray-300 mb-1">Artiste *</label>
                        <input type="text" name="artiste" id="artiste" value="<?php echo e(old('artiste', $vinyle->artiste ?? '')); ?>" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                        >
                        <?php $__errorArgs = ['artiste'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="modele" class="block text-sm font-medium text-gray-300 mb-1">Modèle *</label>
                        <input type="text" name="modele" id="modele" value="<?php echo e(old('modele', $vinyle->modele ?? '')); ?>" required
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                               placeholder="Ex: 33 tours, 45 tours..."
                        >
                        <?php $__errorArgs = ['modele'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="reference" class="block text-sm font-medium text-gray-300 mb-1">Référence</label>
                        <input type="text" name="reference" id="reference" value="<?php echo e(old('reference', $vinyle->reference ?? '')); ?>"
                               class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                        >
                        <?php $__errorArgs = ['reference'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="space-y-4">
                    
                    <div class="grid grid-cols-2 gap-4">
                        
                        <div>
                            <label for="prix" class="block text-sm font-medium text-gray-300 mb-1">Prix (€) *</label>
                            <input type="number" name="prix" id="prix" value="<?php echo e(old('prix', $vinyle->prix ?? '')); ?>" 
                                   step="0.01" min="0" required
                                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                            >
                            <?php $__errorArgs = ['prix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label for="quantite" class="block text-sm font-medium text-gray-300 mb-1">Quantité en stock *</label>
                            <input type="number" name="quantite" id="quantite" value="<?php echo e(old('quantite', $vinyle->quantite ?? '')); ?>" 
                                   min="0" required
                                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                            >
                            <?php $__errorArgs = ['quantite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="genre" class="block text-sm font-medium text-gray-300 mb-1">Genre</label>
                            <input type="text" name="genre" id="genre" value="<?php echo e(old('genre', $vinyle->genre ?? '')); ?>"
                                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                            >
                        </div>
                        <div>
                            <label for="style" class="block text-sm font-medium text-gray-300 mb-1">Style</label>
                            <input type="text" name="style" id="style" value="<?php echo e(old('style', $vinyle->style ?? '')); ?>"
                                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                            >
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Photos (max 3)</label>
                        
                        
                        <?php if($isEdit): ?>
                            <?php $photos = $vinyle->getMedia('photo'); ?>
                            <?php if($photos->count() > 0): ?>
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="relative group">
                                            <img src="<?php echo e($media->getUrl('thumb')); ?>" class="w-full h-24 object-cover rounded">
                                            <label class="absolute top-1 left-1 bg-red-600 text-white text-xs px-2 py-1 rounded cursor-pointer opacity-0 group-hover:opacity-100 transition">
                                                <input type="checkbox" name="delete_photos[]" value="<?php echo e($media->id); ?>" class="mr-1">
                                                Suppr.
                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        
                        <div>
                            <input type="file" name="photos[]" id="photos" multiple accept="image/*"
                                   class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded text-white focus:outline-none focus:border-purple-500"
                            >
                            <p class="text-gray-500 text-sm mt-1">Formats acceptés : JPG, PNG, WEBP. Max 5Mo par image.</p>
                            <?php $__errorArgs = ['photos'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php $__errorArgs = ['photos.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-400 text-sm mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="flex justify-end gap-4 mt-8">
                <a href="<?php echo e(route('vinyles.index')); ?>" class="btn btn-secondary">
                    Annuler
                </a>
                <button type="submit" class="btn btn-primary">
                    <?php echo e($isEdit ? '💾 Enregistrer' : '➕ Créer'); ?>

                </button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/vinyles/form.blade.php ENDPATH**/ ?>