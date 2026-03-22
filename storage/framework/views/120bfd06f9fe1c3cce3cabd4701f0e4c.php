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
        <div class="header-actions"
            style="display: flex; justify-content: space-between; align-items: center; gap: 1rem;">
            <div>
                <h2 style="margin: 0;">
                    Ventes du <?php echo e($currentDate->format('d/m/Y')); ?>

                </h2>
                <div style="font-size: 0.9rem; color: #6b7280;">
                    <?php if($previousDate): ?>
                        <a href="<?php echo e(route('ventes.index', ['date' => $previousDate])); ?>">⟵ Jour précédent</a>
                    <?php else: ?>
                        ⟵ Jour précédent
                    <?php endif; ?>
                    |
                    <?php if($nextDate): ?>
                        <a href="<?php echo e(route('ventes.index', ['date' => $nextDate])); ?>">Jour suivant ⟶</a>
                    <?php else: ?>
                        Jour suivant ⟶
                    <?php endif; ?>
                </div>
            </div>

            <a href="<?php echo e(route('ventes.create')); ?>" class="btn btn-primary">
                + Nouvelle vente
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="page-content">

        
        <div class="stats-bar"
            style="margin-bottom: 1.5rem; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 0.5rem;">
            <div
                style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
                <div>
                    <div>CA total du jour</div>
                    <div style="font-size: 1.5rem; font-weight: bold;">
                        <?php echo e(number_format($caTotal, 2, ',', ' ')); ?> €
                    </div>
                </div>

                <div>
                    <div>Vinyles vendus</div>
                    <div>
                        <strong><?php echo e($nbVinylesTotal); ?></strong>
                        <?php if($nbVinylesTotal > 0): ?>
                            (dont <strong><?php echo e($nbMiroirs); ?></strong> miroir)
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <div>Répartition par mode de paiement</div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.25rem;">
                        <?php $__empty_1 = true; $__currentLoopData = $caParMode; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode => $montant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <span class="badge badge-info">
                                <?php echo e(ucfirst($mode)); ?> :
                                <?php echo e(number_format($montant, 2, ',', ' ')); ?> €
                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <span class="text-muted">Aucun paiement ce jour</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="table-responsive" style="margin-bottom: 2rem;">
            <h3>Ventes du jour</h3>
            <table class="vinyle-table">
                <thead>
                    <tr>
                        <th>Heure</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $ventes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($vente->created_at->format('H:i')); ?></td>
                            <td><?php echo e($vente->lignes->count()); ?> ligne(s)</td>
                            <td><strong><?php echo e(number_format($vente->total, 2, ',', ' ')); ?> €</strong></td>
                            <td>
                                <span class="badge badge-info">
                                    <?php echo e(ucfirst($vente->mode_paiement)); ?>

                                </span>
                            </td>
                            <td style="display: flex; gap: 0.5rem;">
                                <a href="<?php echo e(route('ventes.show', $vente)); ?>" class="btn btn-sm btn-secondary">
                                    Détails
                                </a>

                                <form method="POST" action="<?php echo e(route('ventes.destroy', $vente)); ?>"
                                    onsubmit="return confirm('Annuler cette vente ? Les stocks seront restaurés.');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        Annuler
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>
                            <td colspan="5" class="text-center">Aucune vente pour ce jour</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($parArtiste->count()): ?>
            <div class="table-responsive" style="margin-bottom: 2rem;">
                <h3>Statistiques par artiste / modèle</h3>
                <table class="vinyle-table">
                    <thead>
                        <tr>
                            <th>Artiste / Modèle</th>
                            <th>Quantité</th>
                            <th>CA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $parArtiste; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $libelle => $stats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($libelle); ?></td>
                                <td><?php echo e($stats['quantite']); ?></td>
                                <td><?php echo e(number_format($stats['ca'], 2, ',', ' ')); ?> €</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        
        <?php if($parFond->count()): ?>
            <div class="table-responsive">
                <h3>Statistiques par type de fond</h3>
                <table class="vinyle-table">
                    <thead>
                        <tr>
                            <th>Fond</th>
                            <th>Quantité</th>
                            <th>CA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $parFond; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fond => $stats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e(ucfirst($fond)); ?></td>
                                <td><?php echo e($stats['quantite']); ?></td>
                                <td><?php echo e(number_format($stats['ca'], 2, ',', ' ')); ?> €</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        
        <div class="pagination-wrapper" style="margin-top: 2rem; display: flex; justify-content: center; gap: 1rem;">
            <?php if($previousDate): ?>
                <a href="<?php echo e(route('ventes.index', ['date' => $previousDate])); ?>" class="btn btn-secondary">
                    ⟵ Jour précédent
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>⟵ Jour précédent</button>
            <?php endif; ?>

            <?php if($nextDate): ?>
                <a href="<?php echo e(route('ventes.index', ['date' => $nextDate])); ?>" class="btn btn-secondary">
                    Jour suivant ⟶
                </a>
            <?php else: ?>
                <button class="btn btn-secondary" disabled>Jour suivant ⟶</button>
            <?php endif; ?>
        </div>
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
<?php endif; ?>
<?php /**PATH /home/aur-lien/.picoclaw/workspace/bougies-stock/resources/views/ventes/index.blade.php ENDPATH**/ ?>