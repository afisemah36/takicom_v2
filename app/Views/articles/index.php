<?php $layout = 'main'; ?>
<?php $title = 'Articles'; ?>

<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-boxes"></i> Gestion des articles</h1>
    <a href="<?= url('/articles/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouvel article
    </a>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="<?= url('/articles') ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Rechercher un article..." value="<?= e($search) ?>">
            </div>
            <div class="col-md-5">
                <select name="categorie" class="form-control" onchange="this.form.submit()">
                    <option value="">Toutes les catégories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat->id_categorie ?>" <?= $categorie_selected == $cat->id_categorie ? 'selected' : '' ?>>
                            <?= e($cat->libelle) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="card-body">
        <?php if (empty($articles)): ?>
            <div class="text-center py-5">
                <i class="fas fa-boxes fa-3x mb-3"></i>
                <h3>Aucun article trouvé</h3>
                <p>Commencez par ajouter votre premier article</p>
                <a href="<?= url('/articles/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un article
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Réf</th>
                            <th>Désignation</th>
                            <th>Gain</th>
                            <th>Prix Achat</th>
                            <th>Prix Vente HT</th>
                            <th>Prix Vente TTC <small class="text-muted">(TVA)</small></th>
                            <th>Stock</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($articles as $article): 
                            $prix_achat = (float)$article->prix_achat_ht;
                            $gain = (float)$article->gain_pourcentage;
                            $tva = (float)($article->taux_tva ?? 0);
                            $prix_vente_ht = $prix_achat * (1 + $gain / 100);
                            $prix_vente_ttc = $prix_vente_ht * (1 + $tva / 100);
                        ?>
                        <tr>
                            <td><strong><?= e($article->reference) ?></strong></td>
                            <td>
                                <a href="<?= url('/articles/' . $article->id_article) ?>" class="text-decoration-none fw-semibold">
                                    <?= e($article->designation) ?>
                                </a>
                            </td>
                            <td><?= $gain ?>%</td>
                            <td><?= formatMoney($prix_achat) ?></td>
                            <td><?= formatMoney($prix_vente_ht) ?></td>
                            <td>
                                <span class="fw-bold"><?= formatMoney($prix_vente_ttc) ?></span><br>
                                <small class="text-muted"><?= $tva ?>% TVA</small>
                            </td>
                            <td>
                                <span class="badge bg-<?= ($article->quantite_stock ?? 0) > 0 ? 'success' : 'danger' ?>">
                                    <?= $article->quantite_stock ?? 0 ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= url('/articles/' . $article->id_article) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= url('/articles/' . $article->id_article . '/edit') ?>" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteArticle(<?= $article->id_article ?>)" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (isset($pagination) && $pagination->totalPages > 1): ?>
            <nav class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $pagination->totalPages; $i++): ?>
                        <li class="page-item <?= $i == $pagination->currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="<?= url('/articles?page=' . $i . ($search ? '&search=' . urlencode($search) : '') . ($categorie_selected ? '&categorie=' . $categorie_selected : '')) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function deleteArticle(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= url('/articles') ?>/' + id + '/delete';

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
