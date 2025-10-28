<?php $layout = 'main';
$title = 'Facture ' . $facture->numero_facture; ?>

<div class="page-header">
    <h1><i class="fas fa-receipt"></i> Facture <?= e($facture->numero_facture) ?></h1>
    <div class="page-actions">
        <a href="<?= url('/factures-fournisseur') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
        <?php if ($facture->statut === 'brouillon'): ?>
            <a href="<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur . '/edit') ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
        <?php endif; ?>
        <a href="<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur . '/imprimer') ?>" class="btn btn-primary" target="_blank">
            <i class="fas fa-print"></i> Imprimer
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Informations fournisseur -->
        <div class="card">
            <div class="card-header">
                <h3>Informations fournisseur</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <label>Raison sociale</label>
                            <p><strong><?= e($facture->raison_sociale) ?></strong></p>
                        </div>
                        <div class="info-group">
                            <label>Contact</label>
                            <p><?= e($facture->nom_contact) ?></p>
                        </div>
                        <div class="info-group">
                            <label>Adresse</label>
                            <p>
                                <?= e($facture->adresse) ?><br>
                                <?= e($facture->code_postal) ?> <?= e($facture->ville) ?><br>
                                <?= e($facture->gouvernorat) ?>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-group">
                            <label>Téléphone</label>
                            <p><?= e($facture->telephone) ?></p>
                        </div>
                        <div class="info-group">
                            <label>Email</label>
                            <p><?= e($facture->email) ?></p>
                        </div>
                        <div class="info-group">
                            <label>Matricule fiscal</label>
                            <p><?= e($facture->matricule_fiscale) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Détails facture -->
        <div class="card">
            <div class="card-header">
                <h3>Détails de la facture</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th class="text-center">Qté</th>
                                <th class="text-right">P.U HT</th>
                                <th class="text-center">TVA</th>
                                <th class="text-center">Remise</th>
                                <th class="text-right">Total HT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lignes as $ligne): ?>
                                <tr>
                                    <td>
                                        <?php if ($ligne->reference): ?>
                                            <strong><?= e($ligne->reference) ?></strong><br>
                                        <?php endif; ?>
                                        <?= e($ligne->designation) ?>
                                    </td>
                                    <td class="text-center"><?= e($ligne->quantite) ?> <?= e($ligne->unite) ?></td>
                                    <td class="text-right"><?= formatMoney($ligne->prix_unitaire_ht) ?></td>
                                    <td class="text-center"><?= e($ligne->taux_tva) ?>%</td>
                                    <td class="text-center"><?= e($ligne->taux_remise) ?>%</td>
                                    <td class="text-right"><strong><?= formatMoney($ligne->montant_ht) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Total HT :</strong></td>
                                <td class="text-right"><strong><?= formatMoney($facture->montant_ht) ?></strong></td>
                            </tr>
                            <?php if ($facture->total_remise > 0): ?>
                                <tr>
                                    <td colspan="5" class="text-right text-danger">Remise totale :</td>
                                    <td class="text-right text-danger">-<?= formatMoney($facture->total_remise) ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td colspan="5" class="text-right">TVA :</td>
                                <td class="text-right"><?= formatMoney($facture->montant_tva) ?></td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="5" class="text-right"><strong>Total TTC :</strong></td>
                                <td class="text-right"><strong style="font-size: 18px;"><?= formatMoney($facture->montant_ttc) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <?php if ($facture->notes): ?>
                    <div class="mt-3">
                        <label><strong>Notes :</strong></label>
                        <p><?= nl2br(e($facture->notes)) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Informations facture -->
        <div class="card">
            <div class="card-header">
                <h3>Informations</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Statut</label>
                    <?php
                    $statusColors = [
                        'brouillon' => 'secondary',
                        'validée' => 'warning',
                        'payée' => 'success',
                        'annulée' => 'danger'
                    ];
                    $color = $statusColors[$facture->statut] ?? 'secondary';
                    ?>
                    <p>
                        <span class="badge badge-<?= $color ?>" style="font-size: 14px;">
                            <?= e(ucfirst($facture->statut)) ?>
                        </span>
                    </p>
                </div>

                <div class="info-group">
                    <label>Date facture</label>
                    <p><?= formatDate($facture->date_facture) ?></p>
                </div>

                <?php if ($facture->date_echeance): ?>
                    <div class="info-group">
                        <label>Date échéance</label>
                        <?php $isOverdue = strtotime($facture->date_echeance) < time() && $facture->statut === 'validée'; ?>
                        <p class="<?= $isOverdue ? 'text-danger' : '' ?>">
                            <?= formatDate($facture->date_echeance) ?>
                            <?php if ($isOverdue): ?>
                                <br><small><i class="fas fa-exclamation-triangle"></i> En retard</small>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="info-group">
                    <label>Mode de règlement</label>
                    <p><?= e(ucfirst($facture->mode_reglement)) ?></p>
                </div>

                <div class="info-group">
                    <label>Créée par</label>
                    <p><?= e($facture->utilisateur_nom . ' ' . $facture->utilisateur_prenom) ?></p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h3>Actions</h3>
            </div>
            <div class="card-body">
                <?php if ($facture->statut === 'brouillon'): ?>
                    <button onclick="changerStatut('validée')" class="btn btn-success btn-block mb-2">
                        <i class="fas fa-check"></i> Valider
                    </button>
                <?php endif; ?>

                <?php if ($facture->statut === 'validée'): ?>
                    <button onclick="changerStatut('payée')" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-money-bill-wave"></i> Marquer comme payée
                    </button>
                <?php endif; ?>

                <?php if ($facture->statut !== 'annulée' && $facture->statut !== 'payée'): ?>
                    <button onclick="changerStatut('annulée')" class="btn btn-danger btn-block mb-2">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                <?php endif; ?>

                <?php if ($facture->statut === 'brouillon'): ?>
                    <button onclick="supprimerFacture()" class="btn btn-danger btn-block">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    function changerStatut(statut) {
        const messages = {
            'validée': 'Voulez-vous valider cette facture ?',
            'payée': 'Confirmer le paiement de cette facture ?',
            'annulée': 'Voulez-vous annuler cette facture ?'
        };

        if (confirm(messages[statut] || 'Confirmer cette action ?')) {
            fetch('<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur . '/statut') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        statut: statut,
                        csrf_token: '<?= csrf_token() ?>'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Erreur: ' + data.message);
                    }
                })
                .catch(err => {
                    alert('Erreur lors du changement de statut');
                });
        }
    }

    function supprimerFacture() {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur . '/delete') ?>';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = 'csrf_token';
            csrfInput.value = '<?= csrf_token() ?>';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        }
    }
</script>