<?php $layout = 'main';
$title = 'Facture ' . $facture->numero_facture; ?>

<div class="page-header">
    <h1>
        <i class="fas fa-file-invoice-dollar"></i>
        Facture <?= e($facture->numero_facture) ?>
    </h1>
    <div class="page-actions">
        <?php if ($facture->statut !== 'payée'): ?>
            <a href="<?= url('/factures/' . $facture->id_facture_client . '/edit') ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
        <?php endif; ?>
        <a href="<?= url('/factures/' . $facture->id_facture_client . '/pdf') ?>" class="btn btn-secondary" target="_blank">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a href="<?= url('/factures') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<!-- Informations Client et Facture sur une ligne -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Informations Client</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Client</label>
                    <p>
                        <strong><?= e($facture->raison_sociale ?: ($facture->nom . ' ' . $facture->prenom)) ?></strong><br>
                        <?= nl2br(e($facture->adresse)) ?><br>
                        <?= e($facture->code_postal) ?> <?= e($facture->ville) ?><br>
                        <?php if ($facture->telephone): ?>
                            Tél: <?= e($facture->telephone) ?><br>
                        <?php endif; ?>
                        <?php if ($facture->email): ?>
                            Email: <?= e($facture->email) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3>Informations Facture</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Statut</label>
                    <p>
                        <span class="badge badge-<?= $facture->statut === 'payée' ? 'success' : 'warning' ?>" style="font-size: 14px;">
                            <?= e(ucfirst($facture->statut)) ?>
                        </span>
                    </p>
                </div>
                <div class="info-group">
                    <label>Date facture</label>
                    <p><?= formatDate($facture->date_facture) ?></p>
                </div>
                <div class="info-group">
                    <label>Date échéance</label>
                    <p><?= formatDate($facture->date_echeance) ?></p>
                </div>
                <?php if ($facture->mode_reglement): ?>
                    <div class="info-group">
                        <label>Mode de règlement</label>
                        <p><?= e(ucfirst($facture->mode_reglement)) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3>Totaux</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Total HT</label>
                    <p><strong><?= formatMoney($facture->montant_ht) ?></strong></p>
                </div>

                <?php if ($facture->total_remise > 0): ?>
                    <div class="info-group">
                        <label>Remise</label>
                        <p><strong class="text-danger">- <?= formatMoney($facture->total_remise) ?></strong></p>
                    </div>
                <?php endif; ?>

                <div class="info-group">
                    <label>Total TVA</label>
                    <p><strong><?= formatMoney($facture->montant_tva) ?></strong></p>
                </div>

                <div class="info-group">
                    <label>Total TTC</label>
                    <p><strong style="font-size: 24px; color: var(--primary);">
                            <?= formatMoney($facture->montant_ttc) ?>
                        </strong></p>
                </div>

                <?php if ($facture->statut === 'validée'): ?>
                    <button class="btn btn-success btn-block mt-3" onclick="marquerPayee()">
                        <i class="fas fa-check"></i> Marquer comme payée
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Lignes de facture sur toute la largeur -->
<div class="card mt-3">
    <div class="card-header">
        <h3>Lignes de facture</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Désignation</th>
                        <th style="width: 8%;">Qté</th>
                        <th style="width: 12%;">Prix HT</th>
                        <th style="width: 8%;">TVA</th>
                        <th style="width: 8%;">Remise</th>
                        <th style="width: 12%;">Total HT</th>
                        <th style="width: 12%;">Total TTC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $index => $ligne): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <?php if ($ligne->reference): ?>
                                    <small class="text-muted"><strong><?= e($ligne->reference) ?></strong></small><br>
                                <?php endif; ?>
                                <?= e($ligne->designation) ?>
                            </td>
                            <td><?= $ligne->quantite ?></td>
                            <td><?= formatMoney($ligne->prix_unitaire_ht) ?></td>
                            
                            <td class="text-center"><?= $ligne->taux_tva ?>%</td>
                            <td class="text-center"><?= $ligne->taux_remise ?>%</td>
                            <td><?= formatMoney($ligne->montant_ht) ?></td>
                            <td><strong><?= formatMoney($ligne->montant_ttc) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td colspan="7" class="text-right">TOTAL</td>
                        <td><?= formatMoney($facture->montant_ht) ?></td>
                        <td><?= formatMoney($facture->montant_ttc) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if ($facture->notes): ?>
            <div class="info-group mt-4" style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #3498db;">
                <label><strong>Notes</strong></label>
                <p class="mb-0"><?= nl2br(e($facture->notes)) ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function marquerPayee() {
        if (confirm('Marquer cette facture comme payée ?')) {
            // TODO: Implémenter l'action
            alert('Fonctionnalité à implémenter');
        }
    }
</script>