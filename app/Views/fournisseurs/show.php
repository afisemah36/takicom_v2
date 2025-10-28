<?php $layout = 'main';
$title = 'Détail fournisseur'; ?>

<div class="page-header">
    <h1>
        <i class="fas fa-truck"></i>
        <?= e($fournisseur->raison_sociale) ?>
    </h1>
    <div class="page-actions">
        <a href="<?= url('/fournisseurs/' . $fournisseur->id_fournisseur . '/edit') ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="<?= url('/factures-fournisseur/create?fournisseur=' . $fournisseur->id_fournisseur) ?>" class="btn btn-primary">
            <i class="fas fa-receipt"></i> Nouvelle facture
        </a>
        <a href="<?= url('/fournisseurs') ?>" class="btn btn-secondary">
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
                    <label>Code fournisseur</label>
                    <p><strong><?= e($fournisseur->code_fournisseur) ?></strong></p>
                </div>

                <div class="info-group">
                    <label>Raison sociale</label>
                    <p><?= e($fournisseur->raison_sociale) ?></p>
                </div>

                <?php if ($fournisseur->nom_contact): ?>
                    <div class="info-group">
                        <label>Contact</label>
                        <p><?= e($fournisseur->nom_contact) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($fournisseur->matricule_fiscale): ?>
                    <div class="info-group">
                        <label>Matricule fiscale</label>
                        <p><?= e($fournisseur->matricule_fiscale) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($fournisseur->code_tva): ?>
                    <div class="info-group">
                        <label>Code TVA</label>
                        <p><?= e($fournisseur->code_tva) ?></p>
                    </div>
                <?php endif; ?>

                <div class="info-group">
                    <label>Statut</label>
                    <p>
                        <span class="badge badge-<?= $fournisseur->actif ? 'success' : 'danger' ?>">
                            <?= $fournisseur->actif ? 'Actif' : 'Inactif' ?>
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
                <?php if ($fournisseur->adresse): ?>
                    <div class="info-group">
                        <label><i class="fas fa-map-marker-alt"></i> Adresse</label>
                        <p><?= nl2br(e($fournisseur->adresse)) ?></p>
                        <p>
                            <?= e($fournisseur->code_postal) ?> <?= e($fournisseur->ville) ?>
                            <?php if ($fournisseur->gouvernorat): ?>
                                <br><?= e($fournisseur->gouvernorat) ?>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($fournisseur->telephone): ?>
                    <div class="info-group">
                        <label><i class="fas fa-phone"></i> Téléphone</label>
                        <p><a href="tel:<?= e($fournisseur->telephone) ?>"><?= e($fournisseur->telephone) ?></a></p>
                    </div>
                <?php endif; ?>

                <?php if ($fournisseur->mobile): ?>
                    <div class="info-group">
                        <label><i class="fas fa-mobile-alt"></i> Mobile</label>
                        <p><a href="tel:<?= e($fournisseur->mobile) ?>"><?= e($fournisseur->mobile) ?></a></p>
                    </div>
                <?php endif; ?>

                <?php if ($fournisseur->email): ?>
                    <div class="info-group">
                        <label><i class="fas fa-envelope"></i> Email</label>
                        <p><a href="mailto:<?= e($fournisseur->email) ?>"><?= e($fournisseur->email) ?></a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Statistiques -->
        <div class="stats-row">
            <div class="stat-card stat-primary">
                <div class="stat-icon"><i class="fas fa-receipt"></i></div>
                <div class="stat-details">
                    <h3><?= count($factures) ?></h3>
                    <p>Factures</p>
                </div>
            </div>

            <div class="stat-card stat-danger">
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-details">
                    <h3><?= formatMoney($total) ?></h3>
                    <p>Total achats</p>
                </div>
            </div>
        </div>

        <!-- Onglets -->
        <div class="tabs">
            <ul class="tab-list">
                <li class="tab-item active" data-tab="factures">
                    <i class="fas fa-receipt"></i> Factures
                </li>
                <li class="tab-item" data-tab="notes">
                    <i class="fas fa-sticky-note"></i> Notes
                </li>
            </ul>

            <div class="tab-content active" id="factures">
                <div class="card">
                    <div class="card-header">
                        <h3>Factures fournisseur</h3>
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
                                                    <a href="<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur) ?>" class="btn btn-sm btn-info">
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
                        <?php if ($fournisseur->notes): ?>
                            <p><?= nl2br(e($fournisseur->notes)) ?></p>
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