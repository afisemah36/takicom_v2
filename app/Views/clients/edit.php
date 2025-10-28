<?php $layout = 'main';
$title = 'Modifier client'; ?>

<div class="page-header">
    <h1><i class="fas fa-user-edit"></i> Modifier client</h1>
    <div class="page-actions">
        <a href="<?= url('/clients/' . $client->id_client) ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= url('/clients/' . $client->id_client . '/update') ?>" class="form-horizontal">
            <?= csrf_field() ?>

            <div class="form-section">
                <h3>Informations générales</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code_client">Code client <span class="required">*</span></label>
                            <input
                                type="text"
                                id="code_client"
                                name="code_client"
                                class="form-control"
                                value="<?= old('code_client', $client->code_client) ?>"
                                required>
                            <?= error('code_client') ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Type de client</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="type_client" value="particulier" <?= !$client->raison_sociale ? 'checked' : '' ?> onchange="toggleClientType()"> Particulier
                                </label>
                                <label>
                                    <input type="radio" name="type_client" value="entreprise" <?= $client->raison_sociale ? 'checked' : '' ?> onchange="toggleClientType()"> Entreprise
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="particulier-fields" style="display: <?= !$client->raison_sociale ? 'block' : 'none' ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nom">Nom</label>
                                <input
                                    type="text"
                                    id="nom"
                                    name="nom"
                                    class="form-control"
                                    value="<?= old('nom', $client->nom) ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prenom">Prénom</label>
                                <input
                                    type="text"
                                    id="prenom"
                                    name="prenom"
                                    class="form-control"
                                    value="<?= old('prenom', $client->prenom) ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="entreprise-fields" style="display: <?= $client->raison_sociale ? 'block' : 'none' ?>">
                    <div class="form-group">
                        <label for="raison_sociale">Raison sociale</label>
                        <input
                            type="text"
                            id="raison_sociale"
                            name="raison_sociale"
                            class="form-control"
                            value="<?= old('raison_sociale', $client->raison_sociale) ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="matricule_fiscale">Matricule fiscale</label>
                                <input
                                    type="text"
                                    id="matricule_fiscale"
                                    name="matricule_fiscale"
                                    class="form-control"
                                    value="<?= old('matricule_fiscale', $client->matricule_fiscale) ?>">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="code_tva">Code TVA</label>
                                <input
                                    type="text"
                                    id="code_tva"
                                    name="code_tva"
                                    class="form-control"
                                    value="<?= old('code_tva', $client->code_tva) ?>">
                            </div>
                        </div>
                    </div>
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
                        rows="2"><?= old('adresse', $client->adresse) ?></textarea>
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
                                value="<?= old('code_postal', $client->code_postal) ?>">
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
                                value="<?= old('ville', $client->ville) ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="gouvernorat">Gouvernorat</label>
                            <select id="gouvernorat" name="gouvernorat" class="form-control">
                                <option value="">Sélectionner...</option>
                                <?php
                                $gouvernorats = ['Tunis', 'Ariana', 'Ben Arous', 'Manouba', 'Nabeul', 'Zaghouan', 'Bizerte', 'Béja', 'Jendouba', 'Kef', 'Siliana', 'Sousse', 'Monastir', 'Mahdia', 'Sfax', 'Kairouan', 'Kasserine', 'Sidi Bouzid', 'Gabès', 'Médenine', 'Tataouine', 'Gafsa', 'Tozeur', 'Kébili'];
                                foreach ($gouvernorats as $g):
                                ?>
                                    <option value="<?= $g ?>" <?= $client->gouvernorat === $g ? 'selected' : '' ?>><?= $g ?></option>
                                <?php endforeach; ?>
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
                                value="<?= old('telephone', $client->telephone) ?>">
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
                                value="<?= old('mobile', $client->mobile) ?>">
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
                                value="<?= old('email', $client->email) ?>">
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
                        rows="3"><?= old('notes', $client->notes) ?></textarea>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="actif" value="1" <?= $client->actif ? 'checked' : '' ?>> Client actif
                    </label>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
                <a href="<?= url('/clients/' . $client->id_client) ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleClientType() {
        const type = document.querySelector('input[name="type_client"]:checked').value;
        const particulierFields = document.getElementById('particulier-fields');
        const entrepriseFields = document.getElementById('entreprise-fields');

        if (type === 'particulier') {
            particulierFields.style.display = 'block';
            entrepriseFields.style.display = 'none';
        } else {
            particulierFields.style.display = 'none';
            entrepriseFields.style.display = 'block';
        }
    }
</script>