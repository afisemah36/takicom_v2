<?php

/**
 * Modèle Fournisseur
 */

class Fournisseur extends Model
{
    protected $table = 'fournisseur';
    protected $primaryKey = 'id_fournisseur';

    protected $fillable = [
        'code_fournisseur',
        'raison_sociale',
        'nom_contact',
        'adresse',
        'code_postal',
        'ville',
        'gouvernorat',
        'telephone',
        'mobile',
        'email',
        'matricule_fiscale',
        'code_tva',
        'notes',
        'actif'
    ];

    /**
     * Obtenir tous les fournisseurs actifs
     */
    public function getActifs()
    {
        $sql = "SELECT * FROM {$this->table} WHERE actif = 1 ORDER BY raison_sociale";
        return $this->query($sql);
    }

    /**
     * Rechercher des fournisseurs
     */
    public function rechercher($term)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (raison_sociale LIKE ? OR nom_contact LIKE ? OR code_fournisseur LIKE ?)
                AND actif = 1
                ORDER BY raison_sociale";
        $term = "%{$term}%";
        return $this->query($sql, [$term, $term, $term]);
    }

    /**
     * Obtenir les factures d'un fournisseur
     */
    public function getFactures($id_fournisseur)
    {
        $sql = "SELECT * FROM facture_fournisseur 
                WHERE id_fournisseur = ? 
                ORDER BY date_facture DESC";
        return $this->query($sql, [$id_fournisseur]);
    }

    /**
     * Générer un code fournisseur unique
     */
    public function genererCodeFournisseur()
    {
        $sql = "SELECT code_fournisseur FROM {$this->table} 
                WHERE code_fournisseur LIKE 'FOU%' 
                ORDER BY code_fournisseur DESC 
                LIMIT 1";
        $result = Database::queryOne($sql);

        if ($result) {
            $lastNumber = intval(substr($result->code_fournisseur, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'FOU' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Vérifier si le code fournisseur existe déjà
     */
    public function codeExists($code, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE code_fournisseur = ?";
        $params = [$code];

        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }

        $result = Database::queryOne($sql, $params);
        return $result->count > 0;
    }

    /**
     * Obtenir le total des factures d'un fournisseur
     */
    public function getTotalFactures($id_fournisseur)
    {
        $sql = "SELECT SUM(montant_ttc) as total 
                FROM facture_fournisseur 
                WHERE id_fournisseur = ? AND statut != 'annulée'";
        $result = Database::queryOne($sql, [$id_fournisseur]);
        return $result->total ?? 0;
    }
}
