<?php $layout = 'main';
$title = 'Nouvelle facture'; ?>

<div class="page-header">
    <h1><i class="fas fa-file-invoice-dollar"></i> Nouvelle facture</h1>
    <div class="page-actions">
        <a href="<?= url('/factures') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>
</div>

<form method="POST" action="<?= url('/factures') ?>" id="formFacture">
    <?= csrf_field() ?>

    <!-- Informations générales et Totaux -->
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
                                <label for="numero_facture">N° Facture <span class="required">*</span></label>
                                <input
                                    type="text"
                                    id="numero_facture"
                                    name="numero_facture"
                                    class="form-control"
                                    value="<?= e($numero_facture) ?>"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_facture">Date facture <span class="required">*</span></label>
                                <input
                                    type="date"
                                    id="date_facture"
                                    name="date_facture"
                                    class="form-control"
                                    value="<?= e($date_facture) ?>"
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
                                    value="<?= e($date_echeance) ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_client">Client <span class="required">*</span></label>
                                <select id="id_client" name="id_client" class="form-control" required>
                                    <option value="">-- Sélectionner un client --</option>
                                    <?php foreach ($clients as $client): ?>
                                        <option value="<?= $client->id_client ?>">
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
                                    <option value="brouillon">Brouillon</option>
                                    <option value="validée">Validée</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mode_reglement">Mode de règlement</label>
                                <select id="mode_reglement" name="mode_reglement" class="form-control">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="espèces">Espèces</option>
                                    <option value="chèque">Chèque</option>
                                    <option value="virement">Virement</option>
                                    <option value="carte">Carte bancaire</option>
                                    <option value="traite">Traite</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
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
                        <i class="fas fa-save"></i> Enregistrer la facture
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Lignes de facture -->
    <div class="card mt-3">
        <div class="card-header">
            <h3>Lignes de facture</h3>
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
                            <th style="width: 8%;">Gain %</th>
                            <th style="width: 10%;">TVA %</th>
                            <th style="width: 10%;">Remise %</th>
                            <th style="width: 15%;">Total TTC</th>
                            <th style="width: 10%; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="lignesFacture">
                        <!-- Les lignes seront ajoutées ici dynamiquement -->
                    </tbody>
                </table>
            </div>
            <div class="alert alert-info mt-2" id="alertLignes">
                <i class="fas fa-info-circle"></i> Cliquez sur "Ajouter une ligne" pour commencer à créer votre facture
            </div>
        </div>
    </div>
</form>

<script>
    let ligneIndex = 0;
    const articles = <?= json_encode($articles) ?>;

    console.log('Articles chargés:', articles); // Debug

    function ajouterLigne() {
        console.log('Ajout ligne, index:', ligneIndex); // Debug

        // Cacher l'alerte
        const alertElement = document.getElementById('alertLignes');
        if (alertElement) {
            alertElement.style.display = 'none';
        }

        const tbody = document.getElementById('lignesFacture');
        if (!tbody) {
            console.error('Tbody lignesFacture introuvable');
            return;
        }

        const tr = document.createElement('tr');
        tr.id = 'ligne-' + ligneIndex;
        tr.innerHTML = `
            <td>
                <select class="form-control form-control-sm article-select mb-1" onchange="selectionnerArticle(${ligneIndex}, this.value)">
                    <option value="">-- Sélectionner un article --</option>
                    ${articles.map(a => {
                        const prixHT = parseFloat(a.prix_vente_ht) || 0;
                        const gain = parseFloat(a.gain_pourcentage) || 0;
                        const tauxTVA = parseFloat(a.taux_tva) || 19;
                        const prixTTC = prixHT * (1 + (tauxTVA / 100));
                        return `<option value="${a.id_article}" 
                            data-prix-ht="${prixHT.toFixed(3)}"
                            data-prix-ht="${prixHT.toFixed(3)}"
                            data-gain="${gain}" 
                            data-tva="${tauxTVA}"
                            data-designation="${a.designation}">
                            ${a.reference ? a.reference + ' - ' : ''}${a.designation} (${prixHT.toFixed(3)} DT HT)
                        </option>`;
                    }).join('')}
                </select>
                <input type="text" class="form-control form-control-sm designation" 
                       placeholder="Ou saisir une désignation personnalisée">
                <input type="hidden" class="id-article">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm quantite" 
                       value="1" min="0" step="0.001" onchange="calculerLigne(${ligneIndex})">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm prix-unitaire" 
                       value="0" min="0" step="0.001" onchange="calculerLigne(${ligneIndex})">
            </td>
            <td class="text-center">
                <span class="badge badge-secondary gain-pourcentage" style="font-size: 12px; padding: 5px 8px;">0%</span>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm taux-tva" 
                       value="19" min="0" max="100" step="0.01" onchange="calculerLigne(${ligneIndex})">
            </td>
            <td>
                <input type="number" class="form-control form-control-sm taux-remise" 
                       value="0" min="0" max="100" step="0.01" onchange="calculerLigne(${ligneIndex})">
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
        console.log('Ligne ajoutée:', tr.id); // Debug
        ligneIndex++;
    }

    function selectionnerArticle(index, idArticle) {
        console.log('Sélection article:', index, idArticle); // Debug

        const ligne = document.getElementById('ligne-' + index);
        if (!ligne) {
            console.error('Ligne introuvable:', index);
            return;
        }

        if (!idArticle) {
            // Réinitialiser si aucun article sélectionné
            ligne.querySelector('.designation').value = '';
            ligne.querySelector('.prix-unitaire').value = 0;
            ligne.querySelector('.taux-tva').value = 19;
            ligne.querySelector('.id-article').value = '';
            ligne.querySelector('.gain-pourcentage').textContent = '0%';
            ligne.querySelector('.gain-pourcentage').className = 'badge badge-secondary gain-pourcentage';
            calculerLigne(index);
            return;
        }

        const article = articles.find(a => a.id_article == idArticle);
        if (article) {
            console.log('Article trouvé:', article); // Debug

            ligne.querySelector('.id-article').value = idArticle;
            ligne.querySelector('.designation').value = article.designation;

            // Utiliser directement le prix HT de la base de données
            const prixVenteHT = parseFloat(article.prix_vente_ht) || 0;
            const prixAchatHT = parseFloat(article.prix_achat_ht) || 0;
            const tauxTVA = parseFloat(article.taux_tva) || 19;

            // Calculer le gain en pourcentage
            let gainPourcentage = 0;
            let badgeClass = 'badge-secondary';
            
            if (prixAchatHT > 0 && prixVenteHT > 0) {
                gainPourcentage = ((prixVenteHT - prixAchatHT) / prixAchatHT) * 100;
                
                // Définir la couleur selon le gain
                if (gainPourcentage < 0) {
                    badgeClass = 'badge-danger'; // Perte
                } else if (gainPourcentage < 10) {
                    badgeClass = 'badge-warning'; // Faible marge
                } else if (gainPourcentage < 30) {
                    badgeClass = 'badge-info'; // Marge moyenne
                } else {
                    badgeClass = 'badge-success'; // Bonne marge
                }
            }

            console.log('Prix Vente HT:', prixVenteHT, 'Prix Achat HT:', prixAchatHT, 'Gain:', gainPourcentage.toFixed(2) + '%'); // Debug

            // Afficher le gain
            const gainElement = ligne.querySelector('.gain-pourcentage');
            gainElement.textContent = gainPourcentage.toFixed(2) + '%';
            gainElement.className = 'badge ' + badgeClass + ' gain-pourcentage';
            gainElement.style.fontSize = '12px';
            gainElement.style.padding = '5px 8px';

            ligne.querySelector('.prix-unitaire').value = prixVenteHT.toFixed(3);
            ligne.querySelector('.taux-tva').value = tauxTVA;
            calculerLigne(index);
        } else {
            console.error('Article non trouvé dans la liste:', idArticle);
        }
    }

    function calculerLigne(index) {
        const ligne = document.getElementById('ligne-' + index);
        if (!ligne) return;

        const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
        const prixUnitaire = parseFloat(ligne.querySelector('.prix-unitaire').value) || 0;
        const tauxTVA = parseFloat(ligne.querySelector('.taux-tva').value) || 0;
        const tauxRemise = parseFloat(ligne.querySelector('.taux-remise').value) || 0;

        // Calcul du montant brut
        const montantBrut = quantite * prixUnitaire;

        // Calcul de la remise
        const montantRemise = montantBrut * (tauxRemise / 100);

        // Montant HT après remise
        const montantHT = montantBrut - montantRemise;

        // Montant TVA
        const montantTVA = montantHT * (tauxTVA / 100);

        // Montant TTC
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

                // Afficher l'alerte si plus aucune ligne
                if (document.querySelectorAll('#lignesFacture tr').length === 0) {
                    document.getElementById('alertLignes').style.display = 'block';
                }
            }
        }
    }

    function calculerTotaux() {
        let totalHT = 0;
        let totalRemise = 0;
        let totalTVA = 0;
        let totalTTC = 0;

        document.querySelectorAll('#lignesFacture tr').forEach(ligne => {
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

    document.getElementById('formFacture').addEventListener('submit', function(e) {
        const lignes = [];
        document.querySelectorAll('#lignesFacture tr').forEach((ligne, index) => {
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
            alert('Veuillez ajouter au moins une ligne à la facture');
            return false;
        }

        document.getElementById('lignes').value = JSON.stringify(lignes);
    });

    // Ajouter une première ligne au chargement
    window.addEventListener('DOMContentLoaded', function() {
        ajouterLigne();
    });
</script>