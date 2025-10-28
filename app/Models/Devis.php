<?php

/**
 * Modèle Devis
 */

class Devis extends Model
{
    protected $table = 'devis';
    protected $primaryKey = 'id_devis';

    protected $fillable = [
        'id_client',
        'id_utilisateur',
        'numero_devis',
        'date_devis',
        'date_validite',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'total_remise',
        'statut',
        'notes',
        'conditions'
    ];

    /**
     * Obtenir tous les devis avec informations client
     */
    public function getAllAvecClient()
    {
        $sql = "SELECT d.*, c.raison_sociale, c.nom, c.prenom, u.nom as utilisateur_nom
                FROM {$this->table} d
                INNER JOIN client c ON d.id_client = c.id_client
                LEFT JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
                ORDER BY d.date_devis DESC";
        return $this->query($sql);
    }

    /**
     * Obtenir un devis avec détails complets
     */
    public function getAvecDetails($id_devis)
    {
        $sql = "SELECT d.*, 
                       c.code_client, c.raison_sociale, c.nom, c.prenom, c.adresse, 
                       c.code_postal, c.ville, c.gouvernorat, c.telephone, c.email,
                       c.matricule_fiscale, c.code_tva,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom
                FROM {$this->table} d
                INNER JOIN client c ON d.id_client = c.id_client
                LEFT JOIN utilisateur u ON d.id_utilisateur = u.id_utilisateur
                WHERE d.id_devis = ?";
        return Database::queryOne($sql, [$id_devis]);
    }

    /**
     * Obtenir les lignes d'un devis
     */
    public function getLignes($id_devis)
    {
        $sql = "SELECT l.*, a.reference, a.unite
                FROM ligne_devis l
                LEFT JOIN article a ON l.id_article = a.id_article
                WHERE l.id_devis = ?
                ORDER BY l.ordre";
        return $this->query($sql, [$id_devis]);
    }

    /**
     * Générer un numéro de devis
     */
    public function genererNumero()
    {
        $year = date('Y');
        $sql = "SELECT numero_devis FROM {$this->table} 
                WHERE numero_devis LIKE ? 
                ORDER BY numero_devis DESC 
                LIMIT 1";
        $result = Database::queryOne($sql, ["DEV-{$year}-%"]);

        if ($result && preg_match('/DEV-\d{4}-(\d+)/', $result->numero_devis, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'DEV-' . $year . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Créer un devis avec ses lignes
     */
    public function creerAvecLignes($dataDevis, $lignes)
    {
        Database::beginTransaction();

        try {
            // Créer le devis
            $id_devis = $this->create($dataDevis);

            // Créer les lignes
            $ligneModel = new LigneDevis();
            foreach ($lignes as $index => $ligne) {
                $ligne['id_devis'] = $id_devis;
                $ligne['ordre'] = $index + 1;
                $ligneModel->create($ligne);
            }

            Database::commit();
            return $id_devis;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Mettre à jour un devis avec ses lignes
     */
    public function updateAvecLignes($id_devis, $dataDevis, $lignes)
    {
        Database::beginTransaction();

        try {
            // Mettre à jour le devis
            $this->update($id_devis, $dataDevis);

            // Supprimer les anciennes lignes
            $sql = "DELETE FROM ligne_devis WHERE id_devis = ?";
            Database::execute($sql, [$id_devis]);

            // Créer les nouvelles lignes
            $ligneModel = new LigneDevis();
            foreach ($lignes as $index => $ligne) {
                $ligne['id_devis'] = $id_devis;
                $ligne['ordre'] = $index + 1;
                $ligneModel->create($ligne);
            }

            Database::commit();
            return true;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Changer le statut
     */
    public function changerStatut($id_devis, $statut)
    {
        return $this->update($id_devis, ['statut' => $statut]);
    }

    /**
     * Obtenir les devis par statut
     */
    public function getByStatut($statut)
    {
        $sql = "SELECT d.*, c.raison_sociale, c.nom, c.prenom
                FROM {$this->table} d
                INNER JOIN client c ON d.id_client = c.id_client
                WHERE d.statut = ?
                ORDER BY d.date_devis DESC";
        return $this->query($sql, [$statut]);
    }

    /**
     * Obtenir les devis expirés
     */
    public function getExpires()
    {
        $sql = "SELECT d.*, c.raison_sociale, c.nom, c.prenom
                FROM {$this->table} d
                INNER JOIN client c ON d.id_client = c.id_client
                WHERE d.date_validite < CURDATE() 
                AND d.statut = 'en_attente'
                ORDER BY d.date_validite";
        return $this->query($sql);
    }

    /**
     * Convertir un devis en facture
     */
    public function convertirEnFacture($id_devis)
    {
        $devis = $this->getAvecDetails($id_devis);
        $lignes = $this->getLignes($id_devis);

        if (!$devis) {
            return false;
        }

        Database::beginTransaction();

        try {
            // Créer la facture
            $factureModel = new FactureClient();
            $dataFacture = [
                'id_client' => $devis->id_client,
                'id_utilisateur' => $devis->id_utilisateur,
                'numero_facture' => $factureModel->genererNumero(),
                'date_facture' => date('Y-m-d'),
                'date_echeance' => date('Y-m-d', strtotime('+30 days')),
                'montant_ht' => $devis->montant_ht,
                'montant_tva' => $devis->montant_tva,
                'montant_ttc' => $devis->montant_ttc,
                'total_remise' => $devis->total_remise,
                'statut' => 'brouillon',
                'notes' => $devis->notes
            ];

            $id_facture = $factureModel->create($dataFacture);

            // Créer les lignes de facture
            $ligneFactureModel = new LigneFactureClient();
            foreach ($lignes as $ligne) {
                $dataLigne = [
                    'id_facture_client' => $id_facture,
                    'id_article' => $ligne->id_article,
                    'ordre' => $ligne->ordre,
                    'designation' => $ligne->designation,
                    'quantite' => $ligne->quantite,
                    'prix_unitaire_ht' => $ligne->prix_unitaire_ht,
                    'taux_tva' => $ligne->taux_tva,
                    'montant_tva' => $ligne->montant_tva,
                    'taux_remise' => $ligne->taux_remise,
                    'montant_remise' => $ligne->montant_remise,
                    'montant_ht' => $ligne->montant_ht,
                    'montant_ttc' => $ligne->montant_ttc
                ];
                $ligneFactureModel->create($dataLigne);
            }

            // Mettre à jour le statut du devis
            $this->changerStatut($id_devis, 'converti');

            Database::commit();
            return $id_facture;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Obtenir le total des devis par période
     */
    public function getTotalParPeriode($dateDebut, $dateFin)
    {
        $sql = "SELECT SUM(montant_ttc) as total 
                FROM {$this->table} 
                WHERE date_devis BETWEEN ? AND ?
                AND statut != 'annulé'";
        $result = Database::queryOne($sql, [$dateDebut, $dateFin]);
        return $result->total ?? 0;
    }
}
