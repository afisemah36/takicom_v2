<?php $layout = 'main';
$title = 'Clients'; ?>

<div class="page-header">
    <h1><i class="fas fa-users"></i> Gestion des clients</h1>
    <div class="page-actions">
        <a href="<?= url('/clients/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau client
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="search-box">
            <form method="GET" action="<?= url('/clients') ?>" class="search-form">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Rechercher un client..."
                    value="<?= e($search) ?>">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="card-body">
        <?php if (empty($clients)): ?>
            <div class="empty-state">
                <i class="fas fa-users fa-3x"></i>
                <h3>Aucun client trouvé</h3>
                <p>Commencez par ajouter votre premier client</p>
                <a href="<?= url('/clients/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un client
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Nom / Raison sociale</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Ville</th>
                            <th>Matricule fiscale</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><strong><?= e($client->code_client) ?></strong></td>
                                <td>
                                    <a href="<?= url('/clients/' . $client->id_client) ?>">
                                        <?= e($client->raison_sociale ?: ($client->nom . ' ' . $client->prenom)) ?>
                                    </a>
                                </td>
                                <td><?= e($client->telephone) ?></td>
                                <td><?= e($client->email) ?></td>
                                <td><?= e($client->ville) ?></td>
                                <td><?= e($client->matricule_fiscale) ?></td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= url('/clients/' . $client->id_client) ?>" class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= url('/clients/' . $client->id_client . '/edit') ?>" class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteClient(<?= $client->id_client ?>)" class="btn btn-sm btn-danger" title="Supprimer">
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
    function deleteClient(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= url('/clients') ?>/' + id + '/delete';

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