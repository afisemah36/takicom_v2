<?php $layout = 'main';
$title = 'Catégories'; ?>

<div class="page-header">
    <h1><i class="fas fa-tags"></i> Gestion des catégories</h1>
    <div class="page-actions">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategorie" onclick="openModalCategorie()">
            <i class="fas fa-plus"></i> Nouvelle catégorie
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($arborescence)): ?>
            <div class="empty-state">
                <i class="fas fa-tags fa-3x"></i>
                <h3>Aucune catégorie</h3>
                <p>Créez votre première catégorie d'articles</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCategorie" onclick="openModalCategorie()">
                    <i class="fas fa-plus"></i> Créer une catégorie
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Libellé</th>
                            <th>Articles</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arborescence as $categorie): ?>
                            <tr>
                                <td><strong><?= e($categorie->code) ?></strong></td>
                                <td><?= e($categorie->libelle) ?></td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?= (new CategorieArticle())->countArticles($categorie->id_categorie) ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button onclick='editCategorie(<?= json_encode($categorie) ?>)' class="btn btn-sm btn-warning" title="Modifier" data-bs-toggle="modal" data-bs-target="#modalCategorie">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deleteCategorie(<?= $categorie->id_categorie ?>)" class="btn btn-sm btn-danger" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php if (!empty($categorie->sous_categories)): ?>
                                <?php foreach ($categorie->sous_categories as $sous): ?>
                                    <tr style="background: #f8f9fa;">
                                        <td><i class="fas fa-arrow-right text-muted"></i> <?= e($sous->code) ?></td>
                                        <td><?= e($sous->libelle) ?></td>
                                        <td>
                                            <span class="badge badge-secondary">
                                                <?= (new CategorieArticle())->countArticles($sous->id_categorie) ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button onclick='editCategorie(<?= json_encode($sous) ?>)' class="btn btn-sm btn-warning" title="Modifier" data-bs-toggle="modal" data-bs-target="#modalCategorie">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="deleteCategorie(<?= $sous->id_categorie ?>)" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="modalCategorie" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nouvelle catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formCategorie">
                    <input type="hidden" id="categorie_id" name="id">

                    <div class="mb-3">
                        <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                        <input type="text" id="code" name="code" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="libelle" class="form-label">Libellé <span class="text-danger">*</span></label>
                        <input type="text" id="libelle" name="libelle" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_categorie_parent" class="form-label">Catégorie parente</label>
                        <select id="id_categorie_parent" name="id_categorie_parent" class="form-select">
                            <option value="">Aucune (catégorie principale)</option>
                            <?php foreach ($arborescence as $cat): ?>
                                <option value="<?= $cat->id_categorie ?>"><?= e($cat->libelle) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" class="btn btn-primary" onclick="submitForm()">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let editMode = false;
    let modalInstance;

    document.addEventListener('DOMContentLoaded', function() {
        const modalElement = document.getElementById('modalCategorie');
        modalInstance = new bootstrap.Modal(modalElement);

        modalElement.addEventListener('hidden.bs.modal', function() {
            document.getElementById('formCategorie').reset();
            editMode = false;
        });
    });

    function openModalCategorie() {
        editMode = false;
        document.getElementById('modalTitle').textContent = 'Nouvelle catégorie';
        document.getElementById('formCategorie').reset();
        document.getElementById('categorie_id').value = '';
    }

    function editCategorie(categorie) {
        editMode = true;
        document.getElementById('modalTitle').textContent = 'Modifier catégorie';
        document.getElementById('categorie_id').value = categorie.id_categorie;
        document.getElementById('code').value = categorie.code;
        document.getElementById('libelle').value = categorie.libelle;
        document.getElementById('id_categorie_parent').value = categorie.id_categorie_parent || '';
    }

    function deleteCategorie(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) {
            fetch('<?= url('/categories') ?>/' + id + '/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ csrf_token: '<?= csrf_token() ?>' })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    App.notify(data.message, 'success');
                    location.reload();
                } else {
                    App.notify(data.message, 'danger');
                }
            }).catch(() => App.notify('Erreur lors de la suppression', 'danger'));
        }
    }

    function submitForm() {
        const form = document.getElementById('formCategorie');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        const data = Object.fromEntries(new FormData(form).entries());
        data.csrf_token = '<?= csrf_token() ?>';

        const url = editMode ?
            '<?= url('/categories') ?>/' + document.getElementById('categorie_id').value + '/update' :
            '<?= url('/categories') ?>';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                App.notify(data.message, 'success');
                modalInstance.hide();
                location.reload();
            } else {
                App.notify(data.message, 'danger');
            }
        }).catch(() => App.notify('Erreur lors de l\'enregistrement', 'danger'));
    }
</script>
