<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture Fournisseur <?= e($facture->numero_facture) ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #e74c3c;
        }

        .entreprise {
            width: 45%;
        }

        .entreprise h1 {
            color: #e74c3c;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .entreprise p {
            margin: 3px 0;
            font-size: 11px;
        }

        .facture-info {
            width: 45%;
            text-align: right;
        }

        .facture-numero {
            background: #e74c3c;
            color: white;
            padding: 10px 15px;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .facture-info p {
            margin: 5px 0;
        }

        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .fournisseur-info {
            width: 45%;
            background: #fff5f5;
            padding: 15px;
            border-left: 4px solid #e74c3c;
        }

        .fournisseur-info h3 {
            color: #e74c3c;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .fournisseur-info p {
            margin: 3px 0;
            font-size: 11px;
        }

        .facture-details {
            width: 45%;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table th {
            background: #fee;
            padding: 8px;
            text-align: left;
            font-size: 11px;
            border: 1px solid #ddd;
        }

        .details-table td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }

        .lignes-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .lignes-table thead th {
            background: #e74c3c;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
        }

        .lignes-table tbody td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        .lignes-table tbody tr:hover {
            background: #fff5f5;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totaux {
            float: right;
            width: 300px;
            margin-top: 20px;
        }

        .totaux-table {
            width: 100%;
            border-collapse: collapse;
        }

        .totaux-table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .totaux-table .label {
            text-align: right;
            font-weight: normal;
            width: 60%;
        }

        .totaux-table .montant {
            text-align: right;
            font-weight: bold;
            width: 40%;
        }

        .total-ttc {
            background: #e74c3c;
            color: white;
            font-size: 16px;
        }

        .notes {
            clear: both;
            margin-top: 40px;
            padding: 15px;
            background: #fff5f5;
            border-left: 4px solid #e74c3c;
        }

        .notes h4 {
            margin-bottom: 10px;
            color: #e74c3c;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #fee;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }

        .statut-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .statut-en-attente {
            background: #f39c12;
            color: white;
        }

        .statut-validee {
            background: #3498db;
            color: white;
        }

        .statut-payee {
            background: #27ae60;
            color: white;
        }

        .statut-annulee {
            background: #95a5a6;
            color: white;
        }

        .type-badge {
            display: inline-block;
            padding: 3px 10px;
            background: #e74c3c;
            color: white;
            border-radius: 3px;
            font-size: 10px;
            margin-left: 10px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="entreprise">
                <h1><?= e($entreprise->raison_sociale ?? 'Entreprise') ?></h1>
                <?php if (!empty($entreprise->adresse)): ?>
                    <p><?= e($entreprise->adresse) ?></p>
                <?php endif; ?>
                <?php if (!empty($entreprise->code_postal) || !empty($entreprise->ville)): ?>
                    <p><?= e($entreprise->code_postal) ?> <?= e($entreprise->ville) ?></p>
                <?php endif; ?>
                <?php if (!empty($entreprise->telephone)): ?>
                    <p>Tél: <?= e($entreprise->telephone) ?></p>
                <?php endif; ?>
                <?php if (!empty($entreprise->email)): ?>
                    <p>Email: <?= e($entreprise->email) ?></p>
                <?php endif; ?>
                <?php if (!empty($entreprise->matricule_fiscale)): ?>
                    <p>MF: <?= e($entreprise->matricule_fiscale) ?></p>
                <?php endif; ?>
            </div>

            <div class="facture-info">
                <div class="facture-numero">
                    FACTURE FOURNISSEUR N° <?= e($facture->numero_facture) ?>
                </div>
                <p>
                    <span class="statut-badge statut-<?= e($facture->statut) ?>">
                        <?= e(ucfirst($facture->statut)) ?>
                    </span>
                    <?php if (!empty($facture->type_facture)): ?>
                        <span class="type-badge"><?= e(strtoupper($facture->type_facture)) ?></span>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Informations Fournisseur et Facture -->
        <div class="parties">
            <div class="fournisseur-info">
                <h3>FOURNISSEUR</h3>
                <p><strong><?= e($facture->raison_sociale ?: ($facture->nom . ' ' . $facture->prenom)) ?></strong></p>
                <?php if (!empty($facture->adresse)): ?>
                    <p><?= nl2br(e($facture->adresse)) ?></p>
                <?php endif; ?>
                <?php if (!empty($facture->code_postal) || !empty($facture->ville)): ?>
                    <p><?= e($facture->code_postal) ?> <?= e($facture->ville) ?></p>
                <?php endif; ?>
                <?php if (!empty($facture->telephone)): ?>
                    <p>Tél: <?= e($facture->telephone) ?></p>
                <?php endif; ?>
                <?php if (!empty($facture->email)): ?>
                    <p>Email: <?= e($facture->email) ?></p>
                <?php endif; ?>
                <?php if (!empty($facture->matricule_fiscale)): ?>
                    <p>MF: <?= e($facture->matricule_fiscale) ?></p>
                <?php endif; ?>
            </div>

            <div class="facture-details">
                <table class="details-table">
                    <tr>
                        <th>Date de facture</th>
                        <td><?= formatDate($facture->date_facture) ?></td>
                    </tr>
                    <tr>
                        <th>Date d'échéance</th>
                        <td><?= formatDate($facture->date_echeance) ?></td>
                    </tr>
                    <?php if (!empty($facture->date_reception)): ?>
                        <tr>
                            <th>Date de réception</th>
                            <td><?= formatDate($facture->date_reception) ?></td>
                        </tr>
                    <?php endif; ?>
                    <?php if (!empty($facture->mode_reglement)): ?>
                        <tr>
                            <th>Mode de règlement</th>
                            <td><?= e(ucfirst($facture->mode_reglement)) ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Lignes de facture -->
        <table class="lignes-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Désignation</th>
                    <th style="width: 10%;" class="text-center">Qté</th>
                    <th style="width: 15%;" class="text-right">Prix HT</th>
                    <th style="width: 10%;" class="text-center">TVA</th>
                    <th style="width: 10%;" class="text-center">Remise</th>
                    <th style="width: 15%;" class="text-right">Total TTC</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $index => $ligne): ?>
                    <tr>
                        <td>
                            <?php if (!empty($ligne->reference)): ?>
                                <small style="color: #7f8c8d;"><?= e($ligne->reference) ?></small><br>
                            <?php endif; ?>
                            <strong><?= e($ligne->designation) ?></strong>
                        </td>
                        <td class="text-center"><?= $ligne->quantite ?></td>
                        <td class="text-right"><?= formatMoney($ligne->prix_unitaire_ht) ?></td>
                        <td class="text-center"><?= $ligne->taux_tva ?>%</td>
                        <td class="text-center"><?= $ligne->taux_remise ?>%</td>
                        <td class="text-right"><strong><?= formatMoney($ligne->montant_ttc) ?></strong></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Totaux -->
        <div class="totaux">
            <table class="totaux-table">
                <tr>
                    <td class="label">Total HT</td>
                    <td class="montant"><?= formatMoney($facture->montant_ht) ?></td>
                </tr>
                <?php if ($facture->total_remise > 0): ?>
                    <tr>
                        <td class="label">Remise</td>
                        <td class="montant" style="color: #27ae60;">- <?= formatMoney($facture->total_remise) ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <td class="label">Total TVA</td>
                    <td class="montant"><?= formatMoney($facture->montant_tva) ?></td>
                </tr>
                <tr class="total-ttc">
                    <td class="label">TOTAL TTC</td>
                    <td class="montant"><?= formatMoney($facture->montant_ttc) ?></td>
                </tr>
            </table>
        </div>

        <!-- Notes -->
        <?php if (!empty($facture->notes)): ?>
            <div class="notes">
                <h4>Notes</h4>
                <p><?= nl2br(e($facture->notes)) ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($facture->commentaire_interne)): ?>
            <div class="notes">
                <h4>Commentaire interne</h4>
                <p><?= nl2br(e($facture->commentaire_interne)) ?></p>
            </div>
        <?php endif; ?>

        <!-- Pied de page -->
        <div class="footer">
            <p><?= e($entreprise->raison_sociale ?? 'Entreprise') ?> - Document généré le <?= date('d/m/Y à H:i') ?></p>
            <p><strong>FACTURE FOURNISSEUR</strong> - Document interne</p>
            <?php if (!empty($entreprise->site_web)): ?>
                <p><?= e($entreprise->site_web) ?></p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Imprimer automatiquement au chargement
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>