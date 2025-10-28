<?php $layout = 'main';
$title = 'Dashboard'; ?>

<div class="page-header">
    <h1><i class="fas fa-th-large"></i> Tableau de bord</h1>
    <div class="page-actions">
        <button class="btn btn-primary" onclick="window.location.href='<?= url('/factures/create') ?>'">
            <i class="fas fa-plus"></i> Nouvelle facture
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card stat-primary">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-details">
            <h3><?= number_format($stats['total_clients']) ?></h3>
            <p>Clients</p>
        </div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="stat-details">
            <h3><?= formatMoney($stats['ca_mois']) ?></h3>
            <p>CA du mois</p>
        </div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="fas fa-file-invoice"></i>
        </div>
        <div class="stat-details">
            <h3><?= $stats['devis_en_attente'] ?></h3>
            <p>Devis en attente</p>
        </div>
    </div>

    <div class="stat-card stat-danger">
        <div class="stat-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stat-details">
            <h3><?= $stats['alertes_stock'] ?></h3>
            <p>Alertes stock</p>
        </div>
    </div>
</div>

<div class="dashboard-row">
    <!-- Factures impayées -->
    <div class="dashboard-col">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-exclamation-circle"></i> Factures impayées</h3>
                <span class="badge badge-danger"><?= count($factures_impayees) ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($factures_impayees)): ?>
                    <p class="text-muted text-center">Aucune facture impayée</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>N° Facture</th>
                                    <th>Client</th>
                                    <th>Montant</th>
                                    <th>Échéance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($factures_impayees, 0, 5) as $facture): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url('/factures/' . $facture->id_facture_client) ?>">
                                                <?= e($facture->numero_facture) ?>
                                            </a>
                                        </td>
                                        <td><?= e($facture->raison_sociale ?: $facture->nom . ' ' . $facture->prenom) ?></td>
                                        <td><strong><?= formatMoney($facture->montant_ttc) ?></strong></td>
                                        <td>
                                            <span class="text-danger">
                                                <?= formatDate($facture->date_echeance) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($factures_impayees) > 5): ?>
                        <a href="<?= url('/factures?statut=validée') ?>" class="btn btn-sm btn-link">
                            Voir toutes les factures impayées
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alertes stock -->
    <div class="dashboard-col">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-warehouse"></i> Alertes stock</h3>
                <span class="badge badge-warning"><?= count($alertes_stock) ?></span>
            </div>
            <div class="card-body">
                <?php if (empty($alertes_stock)): ?>
                    <p class="text-muted text-center">Aucune alerte stock</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>Stock</th>
                                    <th>Minimum</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($alertes_stock, 0, 5) as $stock): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= url('/articles/' . $stock->id_article) ?>">
                                                <?= e($stock->designation) ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-danger">
                                                <?= $stock->quantite_disponible ?>
                                            </span>
                                        </td>
                                        <td><?= $stock->quantite_minimum ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (count($alertes_stock) > 5): ?>
                        <a href="<?= url('/stock') ?>" class="btn btn-sm btn-link">
                            Voir toutes les alertes
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Dernières factures et devis -->
<div class="dashboard-row">
    <div class="dashboard-col">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-file-invoice-dollar"></i> Dernières factures</h3>
                <a href="<?= url('/factures') ?>" class="btn btn-sm btn-link">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dernieres_factures as $facture): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url('/factures/' . $facture->id_facture_client) ?>">
                                            <?= e($facture->numero_facture) ?>
                                        </a>
                                    </td>
                                    <td><?= e($facture->raison_sociale ?: $facture->nom) ?></td>
                                    <td><?= formatDate($facture->date_facture) ?></td>
                                    <td><?= formatMoney($facture->montant_ttc) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $facture->statut === 'payée' ? 'success' : ($facture->statut === 'validée' ? 'warning' : 'secondary') ?>">
                                            <?= e(ucfirst($facture->statut)) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-col">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-file-invoice"></i> Derniers devis</h3>
                <a href="<?= url('/devis') ?>" class="btn btn-sm btn-link">Voir tout</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>N° Devis</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($derniers_devis as $devis): ?>
                                <tr>
                                    <td>
                                        <a href="<?= url('/devis/' . $devis->id_devis) ?>">
                                            <?= e($devis->numero_devis) ?>
                                        </a>
                                    </td>
                                    <td><?= e($devis->raison_sociale ?: $devis->nom) ?></td>
                                    <td><?= formatDate($devis->date_devis) ?></td>
                                    <td><?= formatMoney($devis->montant_ttc) ?></td>
                                    <td>
                                        <span class="badge badge-<?= $devis->statut === 'accepté' ? 'success' : ($devis->statut === 'en_attente' ? 'warning' : 'secondary') ?>">
                                            <?= e(ucfirst(str_replace('_', ' ', $devis->statut))) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>