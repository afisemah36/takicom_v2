<?php $layout = 'main';
$title = 'Nouveau fournisseur'; ?>

<div class="page-header">
    <h1><i class="fas fa-truck-loading"></i> Nouveau fournisseur</h1>
    <div class="page-actions">
        <a href="<?= url('/fournisseurs') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= url('/fournisseurs') ?>" class="form-horizontal">
            <?= csrf_field() ?>

            <div class="form-section">
                <h3>Informations générales</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code_fournisseur">Code fournisseur <span class="required">*</span></label>
                            <input
                                type="text"
                                id="code_fournisseur"
                                name="code_fournisseur"
                                class="form-control"
                                value="<?= old('code_fournisseur', $code_fournisseur) ?>"
                                required>
                            <?= error('code_fournisseur') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="raison_sociale">Raison sociale <span class="required">*</span></label>
                            <input
                                type="text"
                                id="raison_sociale"
                                name="raison_sociale"
                                class="form-control"
                                value="<?= old('raison_sociale') ?>"
                                required>
                            <?= error('raison_sociale') ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nom_contact">Nom du contact</label>
                            <input
                                type="text"
                                id="nom_contact"
                                name="nom_contact"
                                class="form-control"
                                value="<?= old('nom_contact') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="matricule_fiscale">Matricule fiscale</label>
                            <input
                                type="text"
                                id="matricule_fiscale"
                                name="matricule_fiscale"
                                class="form-control"
                                value="<?= old('matricule_fiscale') ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="code_tva">Code TVA</label>
                    <input
                        type="text"
                        id="code_tva"
                        name="code_tva"
                        class="form-control"
                        value="<?= old('code_tva') ?>">
                </div>
            </div>

            <div class="form-section">
                <h3>Coordonnées</h3>

                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <textarea
                        id="adresse"
                        name="adresse"
                        class="form-control"
                        rows="2"><?= old('adresse') ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="code_postal">Code postal</label>
                            <input
                                type="text"
                                id="code_postal"
                                name="code_postal"
                                class="form-control"
                                value="<?= old('code_postal') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="ville">Ville</label>
                            <input
                                type="text"
                                id="ville"
                                name="ville"
                                class="form-control"
                                value="<?= old('ville') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gouvernorat">Gouvernorat</label>
                            <select id="gouvernorat" name="gouvernorat" class="form-control">
                                <option value="">Sélectionner...</option>
                                <option value="Tunis">Tunis</option>
                                <option value="Ariana">Ariana</option>
                                <option value="Ben Arous">Ben Arous</option>
                                <option value="Manouba">Manouba</option>
                                <option value="Nabeul">Nabeul</option>
                                <option value="Zaghouan">Zaghouan</option>
                                <option value="Bizerte">Bizerte</option>
                                <option value="Béja">Béja</option>
                                <option value="Jendouba">Jendouba</option>
                                <option value="Kef">Kef</option>
                                <option value="Siliana">Siliana</option>
                                <option value="Sousse">Sousse</option>
                                <option value="Monastir">Monastir</option>
                                <option value="Mahdia">Mahdia</option>
                                <option value="Sfax">Sfax</option>
                                <option value="Kairouan">Kairouan</option>
                                <option value="Kasserine">Kasserine</option>
                                <option value="Sidi Bouzid">Sidi Bouzid</option>
                                <option value="Gabès">Gabès</option>
                                <option value="Médenine">Médenine</option>
                                <option value="Tataouine">Tataouine</option>
                                <option value="Gafsa">Gafsa</option>
                                <option value="Tozeur">Tozeur</option>
                                <option value="Kébili">Kébili</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="telephone">Téléphone</label>
                            <input
                                type="text"
                                id="telephone"
                                name="telephone"
                                class="form-control"
                                value="<?= old('telephone') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="mobile">Mobile</label>
                            <input
                                type="text"
                                id="mobile"
                                name="mobile"
                                class="form-control"
                                value="<?= old('mobile') ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="<?= old('email') ?>">
                            <?= error('email') ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Informations complémentaires</h3>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea
                        id="notes"
                        name="notes"
                        class="form-control"
                        rows="3"><?= old('notes') ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="actif" value="1" checked> Fournisseur actif
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= url('/fournisseurs') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>