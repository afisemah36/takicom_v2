<?php $layout = 'main';
$title = 'Détail article'; ?>

<div class="page-header">
    <h1>
        <i class="fas fa-box"></i>
        <?= e($article->designation) ?>
    </h1>
    <div class="page-actions">
        <a href="<?= url('/articles/' . $article->id_article . '/edit') ?>" class="btn btn-warning">
            <i class="fas fa-edit"></i> Modifier
        </a>
        <a href="<?= url('/articles') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Informations générales</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Référence</label>
                    <p><strong><?= e($article->reference) ?></strong></p>
                </div>

                <?php if ($article->code_barre): ?>
                    <div class="info-group">
                        <label>Code barre</label>
                        <p><?= e($article->code_barre) ?></p>
                    </div>
                <?php endif; ?>

                <div class="info-group">
                    <label>Désignation</label>
                    <p><?= e($article->designation) ?></p>
                </div>

                <?php if ($article->description): ?>
                    <div class="info-group">
                        <label>Description</label>
                        <p><?= nl2br(e($article->description)) ?></p>
                    </div>
                <?php endif; ?>

                <div class="info-group">
                    <label>Catégorie</label>
                    <p><?= e($article->categorie_libelle ?? 'Sans catégorie') ?></p>
                </div>

                <div class="info-group">
                    <label>Unité</label>
                    <p><?= e($article->unite) ?></p>
                </div>

                <div class="info-group">
                    <label>Statut</label>
                    <p>
                        <span class="badge badge-<?= $article->actif ? 'success' : 'danger' ?>">
                            <?= $article->actif ? 'Actif' : 'Inactif' ?>
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3>Prix</h3>
            </div>
            <div class="card-body">
                <div class="info-group">
                    <label>Prix d'achat HT</label>
                    <p><strong><?= formatMoney($article->prix_achat_ht) ?></strong></p>
                </div>

                <div class="info-group">
                    <label>Prix de vente HT</label>
                    <p><strong><?= formatMoney($article->prix_vente_ht) ?></strong></p>
                </div>

                <div class="info-group">
                    <label>Taux TVA</label>
                    <p><?= $article->taux_tva ?>%</p>
                </div>

                <div class="info-group">
                    <label>Prix de vente TTC</label>
                    <p><strong style="color: var(--primary); font-size: 24px;">
                            <?= formatMoney($article->prix_vente_ht * (1 + $article->taux_tva / 100)) ?>
                        </strong></p>
                </div>

                <?php if ($article->prix_vente_ht > 0 && $article->prix_achat_ht > 0): ?>
                    <div class="info-group">
                        <label>Marge brute</label>
                        <p>
                            <?php
                            $marge = $article->prix_vente_ht - $article->prix_achat_ht;
                            $margePourcent = ($marge / $article->prix_achat_ht) * 100;
                            ?>
                            <strong><?= formatMoney($marge) ?></strong>
                            <span class="badge badge-<?= $marge > 0 ? 'success' : 'danger' ?>">
                                <?= number_format($margePourcent, 2) ?>%
                            </span>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($stock): ?>
            <div class="card">
                <div class="card-header">
                    <h3>Stock</h3>
                    <a href="<?= url('/stock/' . $article->id_article) ?>" class="btn btn-sm btn-primary">
                        <i class="fas fa-cog"></i> Gérer
                    </a>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Quantité disponible</label>
                        <p>
                            <strong style="font-size: 24px;">
                                <span class="badge badge-<?= $stock->quantite_disponible > $stock->quantite_minimum ? 'success' : 'danger' ?>" style="font-size: 20px;">
                                    <?= $stock->quantite_disponible ?>
                                </span>
                            </strong>
                        </p>
                    </div>

                    <?php if ($stock->quantite_reservee > 0): ?>
                        <div class="info-group">
                            <label>Quantité réservée</label>
                            <p><strong><?= $stock->quantite_reservee ?></strong></p>
                        </div>
                    <?php endif; ?>

                    <div class="info-group">
                        <label>Stock minimum</label>
                        <p><?= $stock->quantite_minimum ?></p>
                    </div>

                    <div class="info-group">
                        <label>Seuil d'alerte</label>
                        <p><?= $stock->seuil_alerte ?></p>
                    </div>

                    <?php if ($stock->emplacement): ?>
                        <div class="info-group">
                            <label>Emplacement</label>
                            <p><?= e($stock->emplacement) ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($stock->quantite_disponible <= $stock->seuil_alerte): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Alerte stock !</strong> Le stock est en dessous du seuil d'alerte.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>