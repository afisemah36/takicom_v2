<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture <?= e($facture->numero_facture) ?></title>
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
            border-bottom: 3px solid #2c3e50;
        }

        .entreprise {
            width: 45%;
        }

        .entreprise h1 {
            color: #2c3e50;
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
            background: #2c3e50;
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

        .client-info {
            width: 45%;
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #3498db;
        }

        .client-info h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .client-info p {
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
            background: #ecf0f1;
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
            background: #2c3e50;
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
            background: #f8f9fa;
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
            background: #2c3e50;
            color: white;
            font-size: 16px;
        }

        .notes {
            clear: both;
            margin-top: 40px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #3498db;
        }

        .notes h4 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #ecf0f1;
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

        .statut-brouillon {
            background: #95a5a6;
            color: white;
        }

        .statut-validee {
            background: #f39c12;
            color: white;
        }

        .statut-payee {
            background: #27ae60;
            color: white;
        }

        .statut-annulee {
            background: #e74c3c;
            color: white;
        }

        .gain-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .gain-danger {
            background: #e74c3c;
            color: white;
        }

        .gain-warning {
            background: #f39c12;
            color: white;
        }

        .gain-info {
            background: #3498db;
            color: white;
        }

        .gain-success {
            background: #27ae60;
            color: white;
        }

        .gain-secondary {
            background: #95a5a6;
            color: white;
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
                    FACTURE N° <?= e($facture->numero_facture) ?>
                </div>
                <p>
                    <span class="statut-badge statut-<?= e($facture->statut) ?>">
                        <?= e(ucfirst($facture->statut)) ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Informations Client et Facture -->
        <div class="parties">
            <div class="client-info">
                <h3>FACTURÉ À</h3>
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
                    <th style="width: 30%;">Désignation</th>
                    <th style="width: 8%;" class="text-center">Qté</th>
                    <th style="width: 13%;" class="text-right">Prix HT</th>
                    <th style="width: 10%;" class="text-center">Gain %</th>
                    <th style="width: 8%;" class="text-center">TVA</th>
                    <th style="width: 8%;" class="text-center">Remise</th>
                    <th style="width: 13%;" class="text-right">Total TTC</th>
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
                        <td class="text-center">
                            <?php
                            // Calculer le gain en pourcentage
                            $gainPourcentage = 0;
                            $gainClass = 'gain-secondary';
                            
                            if (isset($ligne->prix_achat_ht) && $ligne->prix_achat_ht > 0 && $ligne->prix_unitaire_ht > 0) {
                                $gainPourcentage = (($ligne->prix_unitaire_ht - $ligne->prix_achat_ht) / $ligne->prix_achat_ht) * 100;
                                
                                if ($gainPourcentage < 0) {
                                    $gainClass = 'gain-danger';
                                } elseif ($gainPourcentage < 10) {
                                    $gainClass = 'gain-warning';
                                } elseif ($gainPourcentage < 30) {
                                    $gainClass = 'gain-info';
                                } else {
                                    $gainClass = 'gain-success';
                                }
                            }
                            ?>
                            <span class="gain-badge <?= $gainClass ?>">
                                <?= number_format($gainPourcentage, 2) ?>%
                            </span>
                        </td>
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
                        <td class="montant" style="color: #e74c3c;">- <?= formatMoney($facture->total_remise) ?></td>
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

        <?php if (!empty($facture->conditions_reglement)): ?>
            <div class="notes">
                <h4>Conditions de règlement</h4>
                <p><?= nl2br(e($facture->conditions_reglement)) ?></p>
            </div>
        <?php endif; ?>

        <!-- Pied de page -->
        <div class="footer">
            <p><?= e($entreprise->raison_sociale ?? 'Entreprise') ?> - Facture générée le <?= date('d/m/Y à H:i') ?></p>
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