<?php $layout = 'main';
$title = 'Détail client'; ?>

<div class="page-header">
    <h1>
        <i class="fas fa-user"></i>
        <?= e($client->raison_sociale ?: ($client->nom . ' ' . $client->prenom)) ?>
    </h1>
    <div class="page-actions">
        <a href="<?= url('/clients/' . $client->id_client . '/edit') ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="<?= url('/factures/create?client=' . $client->id_client) ?>" class="btn btn-primary">
            <i class="fas fa-file-invoice-dollar"></i> Nouvelle facture
        </a>
        <a href="<?= url('/clients') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3>Informations générales</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Code client</label>
                    <p><strong><?= e($client->code_client) ?></strong></p>
                </div>

                <?php if ($client->raison_sociale): ?>
                    <div class="info-group">
                        <label>Raison sociale</label>
                        <p><?= e($client->raison_sociale) ?></p>
                    </div>
                <?php else: ?>
                    <div class="info-group">
                        <label>Nom complet</label>
                        <p><?= e($client->nom . ' ' . $client->prenom) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($client->matricule_fiscale): ?>
                    <div class="info-group">
                        <label>Matricule fiscale</label>
                        <p><?= e($client->matricule_fiscale) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($client->code_tva): ?>
                    <div class="info-group">
                        <label>Code TVA</label>
                        <p><?= e($client->code_tva) ?></p>
                    </div>
                <?php endif; ?>

                <div class="info-group">
                    <label>Statut</label>
                    <p>
                        <span class="badge badge-<?= $client->actif ? 'success' : 'danger' ?>">
                            <?= $client->actif ? 'Actif' : 'Inactif' ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Coordonnées</h3>
            </div>
            <div class="card-body">
                <?php if ($client->adresse): ?>
                    <div class="info-group">
                        <label><i class="fas fa-map-marker-alt"></i> Adresse</label>
                        <p><?= nl2br(e($client->adresse)) ?></p>
                        <p>
                            <?= e($client->code_postal) ?> <?= e($client->ville) ?>
                            <?php if ($client->gouvernorat): ?>
                                <br><?= e($client->gouvernorat) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($client->telephone): ?>
                    <div class="info-group">
                        <label><i class="fas fa-phone"></i> Téléphone</label>
                        <p><a href="tel:<?= e($client->telephone) ?>"><?= e($client->telephone) ?></a></p>
                    </div>
                <?php endif; ?>

                <?php if ($client->mobile): ?>
                    <div class="info-group">
                        <label><i class="fas fa-mobile-alt"></i> Mobile</label>
                        <p><a href="tel:<?= e($client->mobile) ?>"><?= e($client->mobile) ?></a></p>
                    </div>
                <?php endif; ?>

                <?php if ($client->email): ?>
                    <div class="info-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <p><a href="mailto:<?= e($client->email) ?>"><?= e($client->email) ?></a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Statistiques -->
        <div class="stats-row">
            <div class="stat-card stat-primary">
                <div class="stat-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="stat-details">
                    <h3><?= count($factures) ?></h3>
                    <p>Factures</p>
                </div>
            </div>

            <div class="stat-card stat-success">
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-details">
                    <h3><?= formatMoney($total) ?></h3>
                    <p>Chiffre d'affaires</p>
                </div>
            </div>

            <div class="stat-card stat-warning">
                <div class="stat-icon"><i class="fas fa-file-invoice"></i></div>
                <div class="stat-details">
                    <h3><?= count($devis) ?></h3>
                    <p>Devis</p>
                </div>
            </div>
        </div>

        <!-- Onglets -->
        <div class="tabs">
            <ul class="tab-list">
                <li class="tab-item active" data-tab="factures">
                    <i class="fas fa-file-invoice-dollar"></i> Factures
                </li>
                <li class="tab-item" data-tab="devis">
                    <i class="fas fa-file-invoice"></i> Devis
                </li>
                <li class="tab-item" data-tab="notes">
                    <i class="fas fa-sticky-note"></i> Notes
                </li>
            </ul>

            <div class="tab-content active" id="factures">
                <div class="card">
                    <div class="card-header">
                        <h3>Factures</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($factures)): ?>
                            <p class="text-muted text-center">Aucune facture</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>N° Facture</th>
                                            <th>Date</th>
                                            <th>Échéance</th>
                                            <th>Montant TTC</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($factures as $facture): ?>
                                            <tr>
                                                <td><?= e($facture->numero_facture) ?></td>
                                                <td><?= formatDate($facture->date_facture) ?></td>
                                                <td><?= formatDate($facture->date_echeance) ?></td>
                                                <td><strong><?= formatMoney($facture->montant_ttc) ?></strong></td>
                                                <td>
                                                    <span class="badge badge-<?= $facture->statut === 'payée' ? 'success' : 'warning' ?>">
                                                        <?= e(ucfirst($facture->statut)) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?= url('/factures/' . $facture->id_facture_client) ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
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
            </div>

            <div class="tab-content" id="devis">
                <div class="card">
                    <div class="card-header">
                        <h3>Devis</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($devis)): ?>
                            <p class="text-muted text-center">Aucun devis</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>N° Devis</th>
                                            <th>Date</th>
                                            <th>Validité</th>
                                            <th>Montant TTC</th>
                                            <th>Statut</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($devis as $d): ?>
                                            <tr>
                                                <td><?= e($d->numero_devis) ?></td>
                                                <td><?= formatDate($d->date_devis) ?></td>
                                                <td><?= formatDate($d->date_validite) ?></td>
                                                <td><strong><?= formatMoney($d->montant_ttc) ?></strong></td>
                                                <td>
                                                    <span class="badge badge-<?= $d->statut === 'accepté' ? 'success' : 'warning' ?>">
                                                        <?= e(ucfirst(str_replace('_', ' ', $d->statut))) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="<?= url('/devis/' . $d->id_devis) ?>" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
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
            </div>

            <div class="tab-content" id="notes">
                <div class="card">
                    <div class="card-header">
                        <h3>Notes</h3>
                    </div>
                    <div class="card-body">
                        <?php if ($client->notes): ?>
                            <p><?= nl2br(e($client->notes)) ?></p>
                        <?php else: ?>
                            <p class="text-muted">Aucune note</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Gestion des onglets
    document.querySelectorAll('.tab-item').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Désactiver tous les onglets
            document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            // Activer l'onglet sélectionné
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });
</script>