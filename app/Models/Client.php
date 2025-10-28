<?php

/**
 * Modèle Client
 */

class Client extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'id_client';

    protected $fillable = [
        'code_client',
        'nom',
        'prenom',
        'raison_sociale',
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
     * Obtenir tous les clients actifs
     */
    public function getActifs()
    {
        $sql = "SELECT * FROM {$this->table} WHERE actif = 1 ORDER BY raison_sociale, nom";
        return $this->query($sql);
    }

    /**
     * Rechercher des clients
     */
    public function rechercher($term)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (raison_sociale LIKE ? OR nom LIKE ? OR prenom LIKE ? OR code_client LIKE ?)
                AND actif = 1
                ORDER BY raison_sociale, nom";
        $term = "%{$term}%";
        return $this->query($sql, [$term, $term, $term, $term]);
    }

    /**
     * Obtenir les factures d'un client
     */
    public function getFactures($id_client)
    {
        $sql = "SELECT * FROM facture_client 
                WHERE id_client = ? 
                ORDER BY date_facture DESC";
        return $this->query($sql, [$id_client]);
    }

    /**
     * Obtenir les devis d'un client
     */
    public function getDevis($id_client)
    {
        $sql = "SELECT * FROM devis 
                WHERE id_client = ? 
                ORDER BY date_devis DESC";
        return $this->query($sql, [$id_client]);
    }

    /**
     * Générer un code client unique
     */
    public function genererCodeClient()
    {
        $sql = "SELECT code_client FROM {$this->table} 
                WHERE code_client LIKE 'CLI%' 
                ORDER BY code_client DESC 
                LIMIT 1";
        $result = Database::queryOne($sql);

        if ($result) {
            $lastNumber = intval(substr($result->code_client, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'CLI' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Vérifier si le code client existe déjà
     */
    public function codeExists($code, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE code_client = ?";
        $params = [$code];

        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }

        $result = Database::queryOne($sql, $params);
        return $result->count > 0;
    }

    /**
     * Obtenir le total des factures d'un client
     */
    public function getTotalFactures($id_client)
    {
        $sql = "SELECT SUM(montant_ttc) as total 
                FROM facture_client 
                WHERE id_client = ? AND statut != 'annulée'";
        $result = Database::queryOne($sql, [$id_client]);
        return $result->total ?? 0;
    }
}
