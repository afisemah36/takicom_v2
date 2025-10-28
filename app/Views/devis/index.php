<?php $layout = 'main';
$title = 'Devis clients'; ?>

<div class="page-header">
    <h1><i class="fas fa-file-invoice"></i> Gestion des devis</h1>
    <div class="page-actions">
        <a href="<?= url('/devis/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau devis
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-box">
            <form method="GET" action="<?= url('/devis') ?>" class="search-form">
                <select name="statut" class="form-control" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="brouillon" <?= $statut_selected === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="en_attente" <?= $statut_selected === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                    <option value="accepté" <?= $statut_selected === 'accepté' ? 'selected' : '' ?>>Accepté</option>
                    <option value="refusé" <?= $statut_selected === 'refusé' ? 'selected' : '' ?>>Refusé</option>
                    <option value="converti" <?= $statut_selected === 'converti' ? 'selected' : '' ?>>Converti</option>
                </select>
            </form>
        </div>
    </div>

    <div class="card-body">
        <?php if (empty($devis)): ?>
            <div class="empty-state">
                <i class="fas fa-file-invoice fa-3x"></i>
                <h3>Aucun devis trouvé</h3>
                <p>Commencez par créer votre premier devis</p>
                <a href="<?= url('/devis/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer un devis
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Devis</th>
                            <th>Client</th>
                            <th>Date</th>
                            <th>Validité</th>
                            <th>Montant TTC</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($devis as $d): ?>
                            <tr>
                                <td><strong><?= e($d->numero_devis) ?></strong></td>
                                <td><?= e($d->raison_sociale ?: ($d->nom . ' ' . $d->prenom)) ?></td>
                                <td><?= formatDate($d->date_devis) ?></td>
                                <td>
                                    <?php
                                    $isExpired = strtotime($d->date_validite) < time() && $d->statut === 'en_attente';
                                    ?>
                                    <span class="<?= $isExpired ? 'text-danger' : '' ?>">
                                        <?= formatDate($d->date_validite) ?>
                                    </span>
                                </td>
                                <td><strong><?= formatMoney($d->montant_ttc) ?></strong></td>
                                <td>
                                    <?php
                                    $statusColors = [
                                        'brouillon' => 'secondary',
                                        'en_attente' => 'warning',
                                        'accepté' => 'success',
                                        'refusé' => 'danger',
                                        'converti' => 'info'
                                    ];
                                    $color = $statusColors[$d->statut] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?= $color ?>">
                                        <?= e(ucfirst($d->statut)) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= url('/devis/' . $d->id_devis) ?>" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($d->statut !== 'converti'): ?>
                                            <a href="<?= url('/devis/' . $d->id_devis . '/edit') ?>" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteDevis(<?= $d->id_devis ?>)" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                        <a href="<?= url('/devis/' . $d->id_devis . '/pdf') ?>" class="btn btn-sm btn-secondary" title="PDF" target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
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
    function deleteDevis(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce devis ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= url('/devis') ?>/' + id + '/delete';

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