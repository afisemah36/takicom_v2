<?php $layout = 'main';
$title = 'Devis ' . $devis->numero_devis; ?>

<div class="page-header">
    <h1>
        <i class="fas fa-file-invoice"></i>
        Devis <?= e($devis->numero_devis) ?>
    </h1>
    <div class="page-actions">
        <?php if ($devis->statut !== 'converti'): ?>
            <a href="<?= url('/devis/' . $devis->id_devis . '/edit') ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
        <?php endif; ?>
        <a href="<?= url('/devis/' . $devis->id_devis . '/pdf') ?>" class="btn btn-secondary" target="_blank">
            <i class="fas fa-file-pdf"></i> PDF
        </a>
        <a href="<?= url('/devis') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Informations</h3>
                <span class="badge badge-<?= $devis->statut === 'accepté' ? 'success' : ($devis->statut === 'en_attente' ? 'warning' : 'secondary') ?>" style="font-size: 14px;">
                    <?= e(ucfirst($devis->statut)) ?>
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-group">
                            <label>Client</label>
                            <p>
                                <strong><?= e($devis->raison_sociale ?: ($devis->nom . ' ' . $devis->prenom)) ?></strong><br>
                                <?= nl2br(e($devis->adresse)) ?><br>
                                <?= e($devis->code_postal) ?> <?= e($devis->ville) ?><br>
                                <?php if ($devis->telephone): ?>
                                    Tél: <?= e($devis->telephone) ?><br>
                                <?php endif; ?>
                                <?php if ($devis->email): ?>
                                    Email: <?= e($devis->email) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-group">
                            <label>Date devis</label>
                            <p><?= formatDate($devis->date_devis) ?></p>
                        </div>

                        <div class="info-group">
                            <label>Date validité</label>
                            <p><?= formatDate($devis->date_validite) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Lignes de devis</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Désignation</th>
                                <th>Qté</th>
                                <th>Prix HT</th>
                                <th>TVA</th>
                                <th>Remise</th>
                                <th>Total TTC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lignes as $index => $ligne): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <?php if ($ligne->reference): ?>
                                            <small class="text-muted"><?= e($ligne->reference) ?></small><br>
                                        <?php endif; ?>
                                        <?= e($ligne->designation) ?>
                                    </td>
                                    <td><?= $ligne->quantite ?></td>
                                    <td><?= formatMoney($ligne->prix_unitaire_ht) ?></td>
                                    <td><?= $ligne->taux_tva ?>%</td>
                                    <td><?= $ligne->taux_remise ?>%</td>
                                    <td><strong><?= formatMoney($ligne->montant_ttc) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($devis->notes): ?>
                    <div class="info-group mt-3">
                        <label>Notes</label>
                        <p><?= nl2br(e($devis->notes)) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($devis->conditions): ?>
                    <div class="info-group mt-3">
                        <label>Conditions</label>
                        <p><?= nl2br(e($devis->conditions)) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3>Totaux</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Total HT</label>
                    <p><strong><?= formatMoney($devis->montant_ht) ?></strong></p>
                </div>

                <?php if ($devis->total_remise > 0): ?>
                    <div class="info-group">
                        <label>Remise</label>
                        <p><strong class="text-danger">- <?= formatMoney($devis->total_remise) ?></strong></p>
                    </div>
                <?php endif; ?>

                <div class="info-group">
                    <label>Total TVA</label>
                    <p><strong><?= formatMoney($devis->montant_tva) ?></strong></p>
                </div>

                <div class="info-group">
                    <label>Total TTC</label>
                    <p><strong style="font-size: 28px; color: var(--primary);">
                            <?= formatMoney($devis->montant_ttc) ?>
                        </strong></p>
                </div>
            </div>
        </div>

        <?php if ($devis->statut === 'en_attente'): ?>
            <div class="card">
                <div class="card-header">
                    <h3>Actions</h3>
                </div>
                <div class="card-body">
                    <button class="btn btn-success btn-block mb-2" onclick="accepterDevis()">
                        <i class="fas fa-check"></i> Accepter le devis
                    </button>
                    <button class="btn btn-danger btn-block mb-2" onclick="refuserDevis()">
                        <i class="fas fa-times"></i> Refuser le devis
                    </button>
                    <button class="btn btn-info btn-block" onclick="convertirFacture()">
                        <i class="fas fa-file-invoice-dollar"></i> Convertir en facture
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function accepterDevis() {
        if (confirm('Marquer ce devis comme accepté ?')) {
            alert('Fonctionnalité à implémenter');
        }
    }

    function refuserDevis() {
        if (confirm('Marquer ce devis comme refusé ?')) {
            alert('Fonctionnalité à implémenter');
        }
    }

    function convertirFacture() {
        if (confirm('Convertir ce devis en facture ?')) {
            alert('Fonctionnalité à implémenter');
        }
    }
</script>