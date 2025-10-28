<?php $layout = 'main';
$title = 'Nouvel article'; ?>

<div class="page-header d-flex justify-content-between align-items-center mb-3">
    <h1><i class="fas fa-box-open"></i> Nouvel article</h1>
    <a href="<?= url('/articles') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Retour
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= url('/articles') ?>">
            <?= csrf_field() ?>

            <!-- Informations générales -->
            <div class="form-section mb-3">
                <h3>Informations générales</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="reference">Référence <span class="required">*</span></label>
                            <input type="text" id="reference" name="reference" class="form-control" value="<?= old('reference', $reference) ?>" required>
                            <?= error('reference') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="designation">Désignation <span class="required">*</span></label>
                            <input type="text" id="designation" name="designation" class="form-control" value="<?= old('designation') ?>" required>
                            <?= error('designation') ?>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control" rows="3"><?= old('description') ?></textarea>
                </div>

                <div class="form-group mt-2">
                    <label for="id_categorie">Catégorie</label>
                    <select id="id_categorie" name="id_categorie" class="form-control">
                        <option value="">Sans catégorie</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat->id_categorie ?>" <?= old('id_categorie') == $cat->id_categorie ? 'selected' : '' ?>>
                                <?= e($cat->libelle) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Prix et TVA -->
            <div class="form-section mb-3">
                <h3>Prix et TVA</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="prix_achat_ht">Prix d'achat HT</label>
                            <input type="number" id="prix_achat_ht" name="prix_achat_ht" class="form-control" value="<?= old('prix_achat_ht', 0) ?>" step="0.001">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gain_pourcentage">Gain (%)</label>
                            <input type="number" id="gain_pourcentage" name="gain_pourcentage" class="form-control" value="<?= old('gain_pourcentage', 0) ?>" step="0.01">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="taux_tva">Taux TVA (%)</label>
                            <select id="taux_tva" name="taux_tva" class="form-control">
                                <option value="0" <?= old('taux_tva') == 0 ? 'selected' : '' ?>>0%</option>
                                <option value="7" <?= old('taux_tva') == 7 ? 'selected' : '' ?>>7%</option>
                                <option value="13" <?= old('taux_tva') == 13 ? 'selected' : '' ?>>13%</option>
                                <option value="19" <?= old('taux_tva', 19) == 19 ? 'selected' : '' ?>>19%</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-2">
                    <label for="unite">Unité</label>
                    <select id="unite" name="unite" class="form-control">
                        <option value="U" <?= old('unite') == 'U' ? 'selected' : '' ?>>Unité</option>
                        <option value="Kg" <?= old('unite') == 'Kg' ? 'selected' : '' ?>>Kilogramme</option>
                        <option value="L" <?= old('unite') == 'L' ? 'selected' : '' ?>>Litre</option>
                        <option value="m" <?= old('unite') == 'm' ? 'selected' : '' ?>>Mètre</option>
                        <option value="m²" <?= old('unite') == 'm²' ? 'selected' : '' ?>>Mètre carré</option>
                        <option value="m³" <?= old('unite') == 'm³' ? 'selected' : '' ?>>Mètre cube</option>
                        <option value="Boîte" <?= old('unite') == 'Boîte' ? 'selected' : '' ?>>Boîte</option>
                        <option value="Carton" <?= old('unite') == 'Carton' ? 'selected' : '' ?>>Carton</option>
                        <option value="Palette" <?= old('unite') == 'Palette' ? 'selected' : '' ?>>Palette</option>
                    </select>
                </div>
            </div>

            <!-- Statut -->
            <div class="form-section mb-3">
                <h3>Statut</h3>
                <div class="form-group">
                    <label>
                        <input type="checkbox" name="actif" value="1" checked> Article actif
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Enregistrer</button>
                <a href="<?= url('/articles') ?>" class="btn btn-secondary"><i class="fas fa-times"></i> Annuler</a>
            </div>
        </form>
    </div>
</div>
