<?php $layout = 'main';
$title = 'Fournisseurs'; ?>

<div class="page-header">
    <h1><i class="fas fa-truck"></i> Gestion des fournisseurs</h1>
    <div class="page-actions">
        <a href="<?= url('/fournisseurs/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau fournisseur
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-box">
            <form method="GET" action="<?= url('/fournisseurs') ?>" class="search-form">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Rechercher un fournisseur..."
                    value="<?= e($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <?php if (empty($fournisseurs)): ?>
            <div class="empty-state">
                <i class="fas fa-truck fa-3x"></i>
                <h3>Aucun fournisseur trouvé</h3>
                <p>Commencez par ajouter votre premier fournisseur</p>
                <a href="<?= url('/fournisseurs/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un fournisseur
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Raison sociale</th>
                            <th>Contact</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Ville</th>
                            <th>Matricule fiscale</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fournisseurs as $fournisseur): ?>
                            <tr>
                                <td><strong><?= e($fournisseur->code_fournisseur) ?></strong></td>
                                <td>
                                    <a href="<?= url('/fournisseurs/' . $fournisseur->id_fournisseur) ?>">
                                        <?= e($fournisseur->raison_sociale) ?>
                                    </a>
                                </td>
                                <td><?= e($fournisseur->nom_contact) ?></td>
                                <td><?= e($fournisseur->telephone) ?></td>
                                <td><?= e($fournisseur->email) ?></td>
                                <td><?= e($fournisseur->ville) ?></td>
                                <td><?= e($fournisseur->matricule_fiscale) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= url('/fournisseurs/' . $fournisseur->id_fournisseur) ?>" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= url('/fournisseurs/' . $fournisseur->id_fournisseur . '/edit') ?>" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteFournisseur(<?= $fournisseur->id_fournisseur ?>)" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
    function deleteFournisseur(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= url('/fournisseurs') ?>/' + id + '/delete';

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