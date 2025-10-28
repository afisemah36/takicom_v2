<?php $layout = 'main';
$title = 'Modifier devis ' . $devis->numero_devis; ?>

<div class="page-header">
    <h1><i class="fas fa-edit"></i> Modifier devis <?= e($devis->numero_devis) ?></h1>
    <div class="page-actions">
        <a href="<?= url('/devis/' . $devis->id_devis) ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<form method="POST" action="<?= url('/devis/' . $devis->id_devis . '/update') ?>" id="formDevis">
    <?= csrf_field() ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Informations générales</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="numero_devis">N° Devis</label>
                                <input type="text" id="numero_devis" name="numero_devis" class="form-control" value="<?= e($devis->numero_devis) ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_devis">Date devis <span class="required">*</span></label>
                                <input type="date" id="date_devis" name="date_devis" class="form-control" value="<?= e($devis->date_devis) ?>" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_validite">Date validité</label>
                                <input type="date" id="date_validite" name="date_validite" class="form-control" value="<?= e($devis->date_validite) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="id_client">Client <span class="required">*</span></label>
                                <select id="id_client" name="id_client" class="form-control" required>
                                    <option value="">-- Sélectionner un client --</option>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?= $client->id_client ?>" <?= $client->id_client == $devis->id_client ? 'selected' : '' ?>>
                                            <?= e($client->raison_sociale ?: ($client->nom . ' ' . $client->prenom)) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="statut">Statut</label>
                                <select id="statut" name="statut" class="form-control">
                                    <option value="brouillon" <?= $devis->statut === 'brouillon' ? 'selected' : '' ?>>Brouillon</option>
                                    <option value="en_attente" <?= $devis->statut === 'en_attente' ? 'selected' : '' ?>>En attente</option>
                                    <option value="accepté" <?= $devis->statut === 'accepté' ? 'selected' : '' ?>>Accepté</option>
                                    <option value="refusé" <?= $devis->statut === 'refusé' ? 'selected' : '' ?>>Refusé</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"><?= e($devis->notes) ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="conditions">Conditions</label>
                        <textarea id="conditions" name="conditions" class="form-control" rows="2"><?= e($devis->conditions) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Totaux</h3>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <label>Total HT</label>
                        <p><strong id="totalHT">0.000 DT</strong></p>
                        <input type="hidden" name="montant_ht" id="montant_ht" value="0">
                    </div>

                    <div class="info-group">
                        <label>Total Remise</label>
                        <p><strong id="totalRemise" class="text-danger">0.000 DT</strong></p>
                        <input type="hidden" name="total_remise" id="total_remise" value="0">
                    </div>

                    <div class="info-group">
                        <label>Total TVA</label>
                        <p><strong id="totalTVA">0.000 DT</strong></p>
                        <input type="hidden" name="montant_tva" id="montant_tva" value="0">
                    </div>

                    <div class="info-group">
                        <label>Total TTC</label>
                        <p><strong id="totalTTC" style="font-size: 28px; color: var(--primary);">0.000 DT</strong></p>
                        <input type="hidden" name="montant_ttc" id="montant_ttc" value="0">
                    </div>

                    <input type="hidden" name="lignes" id="lignes">

                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lignes de devis -->
    <div class="card mt-3">
        <div class="card-header">
            <h3>Lignes de devis</h3>
            <button type="button" class="btn btn-sm btn-primary" onclick="ajouterLigne()">
                <i class="fas fa-plus"></i> Ajouter une ligne
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="tableLignes">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Article/Désignation</th>
                            <th style="width: 10%;">Qté</th>
                            <th style="width: 15%;">Prix HT</th>
                            <th style="width: 10%;">TVA %</th>
                            <th style="width: 10%;">Remise %</th>
                            <th style="width: 15%;">Total TTC</th>
                            <th style="width: 10%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="lignesDevis">
                        <!-- Les lignes seront chargées ici -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

<script>
    let ligneIndex = 0;
    const articles = <?= json_encode($articles) ?>;
    const lignesExistantes = <?= json_encode($lignes) ?>;

    function ajouterLigne(ligneData = null) {
        const tbody = document.getElementById('lignesDevis');
        const tr = document.createElement('tr');
        tr.id = 'ligne-' + ligneIndex;

        const idArticle = ligneData ? ligneData.id_article : '';
        const designation = ligneData ? ligneData.designation : '';
        const quantite = ligneData ? ligneData.quantite : 1;
        const prixUnitaire = ligneData ? ligneData.prix_unitaire_ht : 0;
        const tauxTVA = ligneData ? ligneData.taux_tva : 19;
        const tauxRemise = ligneData ? ligneData.taux_remise : 0;

        tr.innerHTML = `
            <td>
                <select class="form-control form-control-sm article-select mb-1" onchange="selectionnerArticle(${ligneIndex}, this.value)">
                    <option value="">-- Sélectionner un article --</option>
                    ${articles.map(a => {
                        const prixHT = parseFloat(a.prix_vente_ht) || 0;
                        const tvaTaux = parseFloat(a.taux_tva) || 19;
                        const selected = idArticle == a.id_article ? 'selected' : '';
                        return `<option value="${a.id_article}" ${selected}
                            data-prix-ht="${prixHT.toFixed(3)}"
                            data-tva="${tvaTaux}"
                            data-designation="${a.designation}">
                            ${a.reference ? a.reference + ' - ' : ''}${a.designation} (${prixHT.toFixed(3)} DT HT)
                        </option>`;
                    }).join('')}
                </select>
                <input type="text" class="form-control form-control-sm designation" 
                       placeholder="Ou saisir une désignation personnalisée"
                       value="${designation}">
                <input type="hidden" class="id-article" value="${idArticle}">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm quantite" 
                       value="${quantite}" min="0" step="0.001" onchange="calculerLigne(${ligneIndex})">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm prix-unitaire" 
                       value="${prixUnitaire}" min="0" step="0.001" onchange="calculerLigne(${ligneIndex})">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm taux-tva" 
                       value="${tauxTVA}" min="0" max="100" step="0.01" onchange="calculerLigne(${ligneIndex})">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm taux-remise" 
                       value="${tauxRemise}" min="0" max="100" step="0.01" onchange="calculerLigne(${ligneIndex})">
            </td>
            <td class="text-right">
                <strong class="montant-ttc">0.000 DT</strong>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="supprimerLigne(${ligneIndex})" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        calculerLigne(ligneIndex);
        ligneIndex++;
    }

    function selectionnerArticle(index, idArticle) {
        const ligne = document.getElementById('ligne-' + index);
        if (!ligne) return;

        if (!idArticle) {
            ligne.querySelector('.designation').value = '';
            ligne.querySelector('.prix-unitaire').value = 0;
            ligne.querySelector('.taux-tva').value = 19;
            ligne.querySelector('.id-article').value = '';
            calculerLigne(index);
            return;
        }

        const article = articles.find(a => a.id_article == idArticle);
        if (article) {
            ligne.querySelector('.id-article').value = idArticle;
            ligne.querySelector('.designation').value = article.designation;
            ligne.querySelector('.prix-unitaire').value = parseFloat(article.prix_vente_ht || 0).toFixed(3);
            ligne.querySelector('.taux-tva').value = parseFloat(article.taux_tva || 19);
            calculerLigne(index);
        }
    }

    function calculerLigne(index) {
        const ligne = document.getElementById('ligne-' + index);
        if (!ligne) return;

        const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
        const prixUnitaire = parseFloat(ligne.querySelector('.prix-unitaire').value) || 0;
        const tauxTVA = parseFloat(ligne.querySelector('.taux-tva').value) || 0;
        const tauxRemise = parseFloat(ligne.querySelector('.taux-remise').value) || 0;

        const montantBrut = quantite * prixUnitaire;
        const montantRemise = montantBrut * (tauxRemise / 100);
        const montantHT = montantBrut - montantRemise;
        const montantTVA = montantHT * (tauxTVA / 100);
        const montantTTC = montantHT + montantTVA;

        ligne.querySelector('.montant-ttc').textContent = montantTTC.toFixed(3) + ' DT';
        calculerTotaux();
    }

    function supprimerLigne(index) {
        if (confirm('Supprimer cette ligne ?')) {
            const ligne = document.getElementById('ligne-' + index);
            if (ligne) {
                ligne.remove();
                calculerTotaux();
            }
        }
    }

    function calculerTotaux() {
        let totalHT = 0;
        let totalRemise = 0;
        let totalTVA = 0;
        let totalTTC = 0;

        document.querySelectorAll('#lignesDevis tr').forEach(ligne => {
            const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
            const prixUnitaire = parseFloat(ligne.querySelector('.prix-unitaire').value) || 0;
            const tauxTVA = parseFloat(ligne.querySelector('.taux-tva').value) || 0;
            const tauxRemise = parseFloat(ligne.querySelector('.taux-remise').value) || 0;

            const montantBrut = quantite * prixUnitaire;
            const montantRemise = montantBrut * (tauxRemise / 100);
            const montantHT = montantBrut - montantRemise;
            const montantTVA = montantHT * (tauxTVA / 100);
            const montantTTC = montantHT + montantTVA;

            totalRemise += montantRemise;
            totalHT += montantHT;
            totalTVA += montantTVA;
            totalTTC += montantTTC;
        });

        document.getElementById('totalHT').textContent = totalHT.toFixed(3) + ' DT';
        document.getElementById('totalRemise').textContent = totalRemise.toFixed(3) + ' DT';
        document.getElementById('totalTVA').textContent = totalTVA.toFixed(3) + ' DT';
        document.getElementById('totalTTC').textContent = totalTTC.toFixed(3) + ' DT';

        document.getElementById('montant_ht').value = totalHT.toFixed(3);
        document.getElementById('total_remise').value = totalRemise.toFixed(3);
        document.getElementById('montant_tva').value = totalTVA.toFixed(3);
        document.getElementById('montant_ttc').value = totalTTC.toFixed(3);
    }

    document.getElementById('formDevis').addEventListener('submit', function(e) {
        const lignes = [];
        document.querySelectorAll('#lignesDevis tr').forEach((ligne, index) => {
            const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
            const prixUnitaire = parseFloat(ligne.querySelector('.prix-unitaire').value) || 0;
            const tauxTVA = parseFloat(ligne.querySelector('.taux-tva').value) || 0;
            const tauxRemise = parseFloat(ligne.querySelector('.taux-remise').value) || 0;

            const montantBrut = quantite * prixUnitaire;
            const montantRemise = montantBrut * (tauxRemise / 100);
            const montantHT = montantBrut - montantRemise;
            const montantTVA = montantHT * (tauxTVA / 100);
            const montantTTC = montantHT + montantTVA;

            lignes.push({
                id_article: ligne.querySelector('.id-article').value || null,
                designation: ligne.querySelector('.designation').value,
                quantite: quantite,
                prix_unitaire_ht: prixUnitaire,
                taux_tva: tauxTVA,
                taux_remise: tauxRemise,
                montant_remise: montantRemise.toFixed(3),
                montant_ht: montantHT.toFixed(3),
                montant_tva: montantTVA.toFixed(3),
                montant_ttc: montantTTC.toFixed(3)
            });
        });

        if (lignes.length === 0) {
            e.preventDefault();
            alert('Veuillez ajouter au moins une ligne au devis');
            return false;
        }

        document.getElementById('lignes').value = JSON.stringify(lignes);
    });

    // Charger les lignes existantes au démarrage
    window.addEventListener('DOMContentLoaded', function() {
        lignesExistantes.forEach(ligne => {
            ajouterLigne(ligne);
        });
    });
</script>