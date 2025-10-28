<?php $layout = 'main';
$title = 'Modifier facture ' . $facture->numero_facture; ?>

<div class="page-header">
    <h1><i class="fas fa-edit"></i> Modifier facture <?= e($facture->numero_facture) ?></h1>
    <div class="page-actions">
        <a href="<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur) ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<form method="POST" action="<?= url('/factures-fournisseur/' . $facture->id_facture_fournisseur . '/update') ?>" id="formFacture">
    <?= csrf_field() ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Informations générales</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_fournisseur">Fournisseur <span class="required">*</span></label>
                                <select id="id_fournisseur" name="id_fournisseur" class="form-control" required>
                                    <option value="">-- Sélectionner un fournisseur --</option>
                                    <?php foreach ($fournisseurs as $f): ?>
                                        <option value="<?= $f->id_fournisseur ?>" <?= $f->id_fournisseur == $facture->id_fournisseur ? 'selected' : '' ?>>
                                            <?= e($f->code_fournisseur) ?> - <?= e($f->raison_sociale) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="numero_facture">N° Facture <span class="required">*</span></label>
                                <input
                                    type="text"
                                    id="numero_facture"
                                    name="numero_facture"
                                    class="form-control"
                                    value="<?= e($facture->numero_facture) ?>"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_facture">Date facture <span class="required">*</span></label>
                                <input
                                    type="date"
                                    id="date_facture"
                                    name="date_facture"
                                    class="form-control"
                                    value="<?= e($facture->date_facture) ?>"
                                    required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_echeance">Date échéance</label>
                                <input
                                    type="date"
                                    id="date_echeance"
                                    name="date_echeance"
                                    class="form-control"
                                    value="<?= e($facture->date_echeance) ?>">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="mode_reglement">Mode de règlement</label>
                                <select id="mode_reglement" name="mode_reglement" class="form-control">
                                    <option value="espèces" <?= $facture->mode_reglement === 'espèces' ? 'selected' : '' ?>>Espèces</option>
                                    <option value="chèque" <?= $facture->mode_reglement === 'chèque' ? 'selected' : '' ?>>Chèque</option>
                                    <option value="virement" <?= $facture->mode_reglement === 'virement' ? 'selected' : '' ?>>Virement</option>
                                    <option value="carte" <?= $facture->mode_reglement === 'carte' ? 'selected' : '' ?>>Carte bancaire</option>
                                    <option value="traite" <?= $facture->mode_reglement === 'traite' ? 'selected' : '' ?>>Traite</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"><?= e($facture->notes) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Lignes de facture -->
            <div class="card">
                <div class="card-header">
                    <h3>Articles</h3>
                    <button type="button" class="btn btn-primary btn-sm" onclick="ajouterLigne()">
                        <i class="fas fa-plus"></i> Ajouter un article
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="tableLignes">
                            <thead>
                                <tr>
                                    <th style="width: 35%">Article / Description</th>
                                    <th style="width: 12%">Quantité</th>
                                    <th style="width: 15%">Prix unitaire HT</th>
                                    <th style="width: 10%">TVA (%)</th>
                                    <th style="width: 10%">Remise (%)</th>
                                    <th style="width: 15%">Total HT</th>
                                    <th style="width: 3%"></th>
                                </tr>
                            </thead>
                            <tbody id="lignesFacture">
                                <!-- Les lignes existantes seront chargées via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Récapitulatif -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Récapitulatif</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Total HT</label>
                        <p class="text-bold" id="affichageTotalHT">0.00 TND</p>
                        <input type="hidden" name="montant_ht" id="montant_ht" value="0">
                    </div>

                    <div class="info-group">
                        <label>Total Remise</label>
                        <p class="text-bold text-danger" id="affichageTotalRemise">0.00 TND</p>
                        <input type="hidden" name="total_remise" id="total_remise" value="0">
                    </div>

                    <div class="info-group">
                        <label>Total TVA</label>
                        <p class="text-bold" id="affichageTotalTVA">0.00 TND</p>
                        <input type="hidden" name="montant_tva" id="montant_tva" value="0">
                    </div>

                    <div class="info-group">
                        <label>Total TTC</label>
                        <p class="text-bold text-primary" style="font-size: 24px;" id="affichageTotalTTC">0.00 TND</p>
                        <input type="hidden" name="montant_ttc" id="montant_ttc" value="0">
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    let ligneIndex = 0;
    const articles = <?= json_encode($articles) ?>;
    const lignesExistantes = <?= json_encode($lignes) ?>;

    // Ajouter une ligne
    function ajouterLigne(data = null) {
        ligneIndex++;
        const html = `
            <tr id="ligne_${ligneIndex}">
                <td>
                    <select name="lignes[${ligneIndex}][id_article]" class="form-control article-select" onchange="chargerArticle(this, ${ligneIndex})">
                        <option value="">-- Article ou saisie libre --</option>
                        ${articles.map(a => `<option value="${a.id_article}" ${data && a.id_article == data.id_article ? 'selected' : ''} data-prix="${a.prix_achat}" data-tva="${a.taux_tva}">${a.reference} - ${a.designation}</option>`).join('')}
                    </select>
                    <input type="text" name="lignes[${ligneIndex}][designation]" class="form-control mt-1" placeholder="Description (facultatif)" value="${data?.designation || ''}">
                </td>
                <td>
                    <input type="number" name="lignes[${ligneIndex}][quantite]" class="form-control quantite" value="${data?.quantite || 1}" min="0.01" step="0.01" onchange="calculerLigne(${ligneIndex})" required>
                </td>
                <td>
                    <input type="number" name="lignes[${ligneIndex}][prix_unitaire]" class="form-control prix-unitaire" value="${data?.prix_unitaire || 0}" min="0" step="0.01" onchange="calculerLigne(${ligneIndex})" required>
                </td>
                <td>
                    <input type="number" name="lignes[${ligneIndex}][taux_tva]" class="form-control taux-tva" value="${data?.taux_tva || 19}" min="0" step="0.01" onchange="calculerLigne(${ligneIndex})">
                </td>
                <td>
                    <input type="number" name="lignes[${ligneIndex}][taux_remise]" class="form-control taux-remise" value="${data?.taux_remise || 0}" min="0" max="100" step="0.01" onchange="calculerLigne(${ligneIndex})">
                </td>
                <td>
                    <input type="text" class="form-control total-ligne" readonly value="0.00">
                    <input type="hidden" name="lignes[${ligneIndex}][montant_ht]" value="0">
                    <input type="hidden" name="lignes[${ligneIndex}][montant_tva]" value="0">
                    <input type="hidden" name="lignes[${ligneIndex}][montant_ttc]" value="0">
                    <input type="hidden" name="lignes[${ligneIndex}][montant_remise]" value="0">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="supprimerLigne(${ligneIndex})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        document.getElementById('lignesFacture').insertAdjacentHTML('beforeend', html);

        if (data) {
            calculerLigne(ligneIndex);
        }
    }

    // Charger les données d'un article
    function chargerArticle(select, index) {
        const option = select.options[select.selectedIndex];
        if (option.value) {
            const ligne = document.getElementById(`ligne_${index}`);
            ligne.querySelector('.prix-unitaire').value = option.dataset.prix || 0;
            ligne.querySelector('.taux-tva').value = option.dataset.tva || 19;
            calculerLigne(index);
        }
    }

    // Calculer une ligne
    function calculerLigne(index) {
        const ligne = document.getElementById(`ligne_${index}`);
        const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
        const prixUnitaire = parseFloat(ligne.querySelector('.prix-unitaire').value) || 0;
        const tauxTva = parseFloat(ligne.querySelector('.taux-tva').value) || 0;
        const tauxRemise = parseFloat(ligne.querySelector('.taux-remise').value) || 0;

        const montantBrut = quantite * prixUnitaire;
        const montantRemise = montantBrut * (tauxRemise / 100);
        const montantHT = montantBrut - montantRemise;
        const montantTVA = montantHT * (tauxTva / 100);
        const montantTTC = montantHT + montantTVA;

        ligne.querySelector('.total-ligne').value = montantHT.toFixed(2);
        ligne.querySelector('input[name$="[montant_ht]"]').value = montantHT.toFixed(2);
        ligne.querySelector('input[name$="[montant_tva]"]').value = montantTVA.toFixed(2);
        ligne.querySelector('input[name$="[montant_ttc]"]').value = montantTTC.toFixed(2);
        ligne.querySelector('input[name$="[montant_remise]"]').value = montantRemise.toFixed(2);

        calculerTotaux();
    }

    // Supprimer une ligne
    function supprimerLigne(index) {
        document.getElementById(`ligne_${index}`).remove();
        calculerTotaux();
    }

    // Calculer les totaux
    function calculerTotaux() {
        let totalHT = 0;
        let totalTVA = 0;
        let totalRemise = 0;

        document.querySelectorAll('#lignesFacture tr').forEach(ligne => {
            totalHT += parseFloat(ligne.querySelector('input[name$="[montant_ht]"]').value) || 0;
            totalTVA += parseFloat(ligne.querySelector('input[name$="[montant_tva]"]').value) || 0;
            totalRemise += parseFloat(ligne.querySelector('input[name$="[montant_remise]"]').value) || 0;
        });

        const totalTTC = totalHT + totalTVA;

        document.getElementById('montant_ht').value = totalHT.toFixed(2);
        document.getElementById('montant_tva').value = totalTVA.toFixed(2);
        document.getElementById('montant_ttc').value = totalTTC.toFixed(2);
        document.getElementById('total_remise').value = totalRemise.toFixed(2);

        document.getElementById('affichageTotalHT').textContent = totalHT.toFixed(2) + ' TND';
        document.getElementById('affichageTotalTVA').textContent = totalTVA.toFixed(2) + ' TND';
        document.getElementById('affichageTotalTTC').textContent = totalTTC.toFixed(2) + ' TND';
        document.getElementById('affichageTotalRemise').textContent = totalRemise.toFixed(2) + ' TND';
    }

    // Validation du formulaire
    document.getElementById('formFacture').addEventListener('submit', function(e) {
        const nbLignes = document.querySelectorAll('#lignesFacture tr').length;
        if (nbLignes === 0) {
            e.preventDefault();
            alert('Veuillez ajouter au moins un article à la facture');
            return false;
        }
    });

    // Charger les lignes existantes
    lignesExistantes.forEach(ligne => {
        ajouterLigne(ligne);
    });

    // Si aucune ligne, en ajouter une vide
    if (lignesExistantes.length === 0) {
        ajouterLigne();
    }
</script>