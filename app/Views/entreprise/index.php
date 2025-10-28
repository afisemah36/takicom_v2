<?php $layout = 'main';
$title = 'Configuration de l\'entreprise'; ?>

<div class="page-header">
    <h1><i class="fas fa-building"></i> Configuration de l'entreprise</h1>
</div>

<form method="POST" action="<?= url('/parametres/update') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row">
        <div class="col-md-8">
            <!-- Informations générales -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="raison_sociale">Raison sociale <span class="required">*</span></label>
                                <input type="text" id="raison_sociale" name="raison_sociale"
                                    class="form-control"
                                    value="<?= e($entreprise->raison_sociale ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="forme_juridique">Forme juridique</label>
                                <select id="forme_juridique" name="forme_juridique" class="form-control">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="SARL" <?= ($entreprise->forme_juridique ?? '') === 'SARL' ? 'selected' : '' ?>>SARL</option>
                                    <option value="SA" <?= ($entreprise->forme_juridique ?? '') === 'SA' ? 'selected' : '' ?>>SA</option>
                                    <option value="SUARL" <?= ($entreprise->forme_juridique ?? '') === 'SUARL' ? 'selected' : '' ?>>SUARL</option>
                                    <option value="SNC" <?= ($entreprise->forme_juridique ?? '') === 'SNC' ? 'selected' : '' ?>>SNC</option>
                                    <option value="Entreprise individuelle" <?= ($entreprise->forme_juridique ?? '') === 'Entreprise individuelle' ? 'selected' : '' ?>>Entreprise individuelle</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="matricule_fiscale">Matricule fiscal</label>
                                <input type="text" id="matricule_fiscale" name="matricule_fiscale"
                                    class="form-control"
                                    value="<?= e($entreprise->matricule_fiscale ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code_tva">Code TVA</label>
                                <input type="text" id="code_tva" name="code_tva"
                                    class="form-control"
                                    value="<?= e($entreprise->code_tva ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code_douane">Code en douane</label>
                                <input type="text" id="code_douane" name="code_douane"
                                    class="form-control"
                                    value="<?= e($entreprise->code_douane ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="registre_commerce">Registre de commerce</label>
                                <input type="text" id="registre_commerce" name="registre_commerce"
                                    class="form-control"
                                    value="<?= e($entreprise->registre_commerce ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="capital_social">Capital social</label>
                                <input type="number" id="capital_social" name="capital_social"
                                    class="form-control" step="0.001"
                                    value="<?= e($entreprise->capital_social ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-map-marker-alt"></i> Adresse</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="adresse">Adresse</label>
                        <textarea id="adresse" name="adresse" class="form-control" rows="2"><?= e($entreprise->adresse ?? '') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="code_postal">Code postal</label>
                                <input type="text" id="code_postal" name="code_postal"
                                    class="form-control"
                                    value="<?= e($entreprise->code_postal ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ville">Ville</label>
                                <input type="text" id="ville" name="ville"
                                    class="form-control"
                                    value="<?= e($entreprise->ville ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gouvernorat">Gouvernorat</label>
                                <input type="text" id="gouvernorat" name="gouvernorat"
                                    class="form-control"
                                    value="<?= e($entreprise->gouvernorat ?? '') ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-phone"></i> Contact</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telephone">Téléphone</label>
                                <input type="text" id="telephone" name="telephone"
                                    class="form-control"
                                    value="<?= e($entreprise->telephone ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fax">Fax</label>
                                <input type="text" id="fax" name="fax"
                                    class="form-control"
                                    value="<?= e($entreprise->fax ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email"
                                    class="form-control"
                                    value="<?= e($entreprise->email ?? '') ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="site_web">Site web</label>
                                <input type="url" id="site_web" name="site_web"
                                    class="form-control"
                                    value="<?= e($entreprise->site_web ?? '') ?>"
                                    placeholder="https://www.exemple.com">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="rib">RIB</label>
                        <input type="text" id="rib" name="rib"
                            class="form-control"
                            value="<?= e($entreprise->rib ?? '') ?>">
                    </div>
                </div>
            </div>

            <!-- Mentions légales -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-file-contract"></i> Mentions légales</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="mentions_legales">Mentions légales</label>
                        <textarea id="mentions_legales" name="mentions_legales"
                            class="form-control" rows="4"><?= e($entreprise->mentions_legales ?? '') ?></textarea>
                        <small class="text-muted">Ces mentions apparaîtront sur vos documents (factures, devis...)</small>
                    </div>

                    <div class="form-group">
                        <label for="conditions_generales">Conditions générales de vente</label>
                        <textarea id="conditions_generales" name="conditions_generales"
                            class="form-control" rows="6"><?= e($entreprise->conditions_generales ?? '') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fas fa-image"></i> Logo</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($entreprise->logo_url)): ?>
                        <div class="text-center mb-3">
                            <img src="<?= asset($entreprise->logo_url) ?>"
                                alt="Logo"
                                class="img-fluid"
                                style="max-height: 200px; border: 1px solid #ddd; padding: 10px; background: white;">
                        </div>
                        <form method="POST" action="<?= url('/configuration/delete-logo') ?>" class="mb-3">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-block"
                                onclick="return confirm('Supprimer le logo ?')">
                                <i class="fas fa-trash"></i> Supprimer le logo
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="text-center mb-3" style="padding: 40px; background: #f8f9fa; border: 2px dashed #ddd;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">Aucun logo</p>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="logo">Changer le logo</label>
                        <input type="file" id="logo" name="logo"
                            class="form-control"
                            accept="image/jpeg,image/png,image/gif,image/webp">
                        <small class="text-muted">Format: JPG, PNG, GIF, WEBP (max 2MB)</small>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-success btn-block btn-lg">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>