<?php $layout = 'main';
$title = 'Gestion du stock'; ?>

<div class="page-header">
    <h1><i class="fas fa-warehouse"></i> Gestion du stock</h1>
</div>

<!-- Statistiques -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <i class="fas fa-boxes"></i>
        </div>
        <div class="stat-details">
            <h3><?= count($stocks) ?></h3>
            <p>Articles en stock</p>
        </div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-details">
            <h3><?= count($alertes) ?></h3>
            <p>Alertes stock</p>
        </div>
    </div>

    <div class="stat-card stat-danger">
        <div class="stat-icon">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-details">
            <h3><?= count($ruptures) ?></h3>
            <p>Ruptures</p>
        </div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-details">
            <h3><?= formatMoney($valeur_totale) ?></h3>
            <p>Valeur du stock</p>
        </div>
    </div>
</div>

<!-- Alertes stock -->
<?php if (!empty($alertes)): ?>
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-triangle"></i> Alertes stock</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Désignation</th>
                            <th>Stock disponible</th>
                            <th>Stock minimum</th>
                            <th>Seuil d'alerte</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alertes as $alerte): ?>
                            <tr>
                                <td><?= e($alerte->reference) ?></td>
                                <td><?= e($alerte->designation) ?></td>
                                <td>
                                    <span class="badge badge-<?= $alerte->quantite_disponible <= 0 ? 'danger' : 'warning' ?>">
                                        <?= $alerte->quantite_disponible ?>
                                    </span>
                                </td>
                                <td><?= $alerte->quantite_minimum ?></td>
                                <td><?= $alerte->seuil_alerte ?></td>
                                <td>
                                    <a href="<?= url('/stock/' . $alerte->id_article) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-cog"></i> Gérer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Liste complète du stock -->
<div class="card">
    <div class="card-header">
        <h3>Tous les articles en stock</h3>
    </div>
    <div class="card-body">
        <?php if (empty($stocks)): ?>
            <div class="empty-state">
                <i class="fas fa-warehouse fa-3x"></i>
                <h3>Aucun article en stock</h3>
                <p>Ajoutez des articles avec gestion de stock activée</p>
                <a href="<?= url('/articles/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un article
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Référence</th>
                            <th>Désignation</th>
                            <th>Catégorie</th>
                            <th>Stock disponible</th>
                            <th>Stock réservé</th>
                            <th>Emplacement</th>
                            <th>Valeur</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stocks as $stock): ?>
                            <tr>
                                <td><strong><?= e($stock->reference) ?></strong></td>
                                <td>
                                    <a href="<?= url('/articles/' . $stock->id_article) ?>">
                                        <?= e($stock->designation) ?>
                                    </a>
                                </td>
                                <td><?= e($stock->categorie_libelle ?? '-') ?></td>
                                <td>
                                    <?php
                                    $badgeClass = 'success';
                                    if ($stock->quantite_disponible <= 0) {
                                        $badgeClass = 'danger';
                                    } elseif ($stock->quantite_disponible <= $stock->seuil_alerte) {
                                        $badgeClass = 'warning';
                                    }
                                    ?>
                                    <span class="badge badge-<?= $badgeClass ?>">
                                        <?= $stock->quantite_disponible ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($stock->quantite_reservee > 0): ?>
                                        <span class="badge badge-info"><?= $stock->quantite_reservee ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= e($stock->emplacement ?? '-') ?></td>
                                <td>
                                    <?php
                                    // Correction : utiliser prix_achat_ht au lieu de prix_achat
                                    $prixAchat = $stock->prix_achat_ht ?? 0;
                                    $valeur = $stock->quantite_disponible * $prixAchat;
                                    ?>
                                    <?= formatMoney($valeur) ?>
                                </td>
                                <td>
                                    <a href="<?= url('/stock/' . $stock->id_article) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>