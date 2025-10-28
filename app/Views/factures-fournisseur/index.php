<?php $layout = 'main';
$title = 'Factures fournisseurs'; ?>

<div class="page-header">
    <h1><i class="fas fa-receipt"></i> Gestion des factures fournisseurs</h1>
    <div class="page-actions">
        <a href="<?= url('/factures-fournisseur/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle facture
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-box">
            <form method="GET" action="<?= url('/factures-fournisseur') ?>" class="search-form">
                <select name="statut" class="form-control" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="brouillon" <?= $statut_selected === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="validée" <?= $statut_selected === 'validée' ? 'selected' : '' ?>>Validée</option>
                    <option value="payée" <?= $statut_selected === 'payée' ? 'selected' : '' ?>>Payée</option>
                    <option value="annulée" <?= $statut_selected === 'annulée' ? 'selected' : '' ?>>Annulée</option>
                </select>
            </form>
        </div>
    </div>

    <div class="card-body">
        <?php if (empty($factures)): ?>
            <div class="empty-state">
                <i class="fas fa-receipt fa-3x"></i>
                <h3>Aucune facture fournisseur</h3>
                <p>Commencez par enregistrer votre première facture fournisseur</p>
                <a href="<?= url('/factures-fournisseur/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer une facture
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Fournisseur</th>
                            <th>Date</th>
                            <th>Échéance</th>
                            <th>Montant TTC</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($factures as $facture): ?>
                            <tr>
                                <td><strong><?= e($facture->numero_facture) ?></strong></td>
                                <td><?= e($facture->raison_sociale) ?></td>
                                <td><?= formatDate($facture->date_facture) ?></td>
                                <td>
                                    <?php
                                    $isOverdue = strtotime($facture->date_echeance) < time() && $facture->statut === 'validée';
                                    ?>
                                    <span class="<?= $isOverdue ? 'text-danger' : '' ?>">
                                        <?= formatDate($facture->date_echeance) ?>
                                    </span>
                                </td>
                                <td><strong><?= formatMoney($facture->montant_ttc) ?></strong></td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'brouillon' => 'secondary',
                                        'validée' => 'warning',
                                        'payée' => 'success',
                                        'annulée' => 'danger'
                                    ];
                                    $color = $statusColors[$facture->statut] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $color ?>">
                                        <?= e(ucfirst($facture->statut)) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur) ?>" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($facture->statut !== 'payée'): ?>
                                            <a href="<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur . '/edit') ?>" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteFacture(<?= $facture->id_facture_fournisseur ?>)" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function deleteFacture(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette facture ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= url('/factures-fournisseur') ?>/' + id + '/delete';

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