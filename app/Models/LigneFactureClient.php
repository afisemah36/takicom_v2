<?php

/**
 * Modèle LigneFactureClient
 */

class LigneFactureClient extends Model
{
    protected $table = 'ligne_facture_client';
    protected $primaryKey = 'id_ligne';

    protected $fillable = [
        'id_facture_client',
        'id_article',
        'ordre',
        'designation',
        'quantite',
        'prix_unitaire_ht',
        'taux_tva',
        'montant_tva',
        'taux_remise',
        'montant_remise',
        'montant_ht',
        'montant_ttc'
    ];

    /**
     * Calculer les montants d'une ligne
     */
    public static function calculerMontants($quantite, $prixUnitaire, $tauxTva, $tauxRemise = 0)
    {
        $montantBrut = $quantite * $prixUnitaire;
        $montantRemise = $montantBrut * ($tauxRemise / 100);
        $montantHT = $montantBrut - $montantRemise;
        $montantTVA = $montantHT * ($tauxTva / 100);
        $montantTTC = $montantHT + $montantTVA;

        return [
            'montant_remise' => round($montantRemise, 3),
            'montant_ht' => round($montantHT, 3),
            'montant_tva' => round($montantTVA, 3),
            'montant_ttc' => round($montantTTC, 3)
        ];
    }
}
