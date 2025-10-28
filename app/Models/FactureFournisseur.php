<?php

/**
 * Modèle FactureFournisseur
 */

class FactureFournisseur extends Model
{
    protected $table = 'facture_fournisseur';
    protected $primaryKey = 'id_facture_fournisseur';

    protected $fillable = [
        'id_fournisseur',
        'id_utilisateur',
        'numero_facture',
        'date_facture',
        'date_echeance',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'total_remise',
        'statut',
        'mode_reglement',
        'notes'
    ];

    /**
     * Obtenir toutes les factures avec informations fournisseur
     */
    public function getAllAvecFournisseur()
    {
        $sql = "SELECT f.*, fo.raison_sociale, u.nom as utilisateur_nom
                FROM {$this->table} f
                INNER JOIN fournisseur fo ON f.id_fournisseur = fo.id_fournisseur
                LEFT JOIN utilisateur u ON f.id_utilisateur = u.id_utilisateur
                ORDER BY f.date_facture DESC";
        return $this->query($sql);
    }

    /**
     * Obtenir une facture avec détails complets
     */
    public function getAvecDetails($id_facture)
    {
        $sql = "SELECT f.*, 
                       fo.code_fournisseur, fo.raison_sociale, fo.nom_contact, fo.adresse, 
                       fo.code_postal, fo.ville, fo.gouvernorat, fo.telephone, fo.email,
                       fo.matricule_fiscale, fo.code_tva,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom
                FROM {$this->table} f
                INNER JOIN fournisseur fo ON f.id_fournisseur = fo.id_fournisseur
                LEFT JOIN utilisateur u ON f.id_utilisateur = u.id_utilisateur
                WHERE f.id_facture_fournisseur = ?";
        return Database::queryOne($sql, [$id_facture]);
    }

    /**
     * Obtenir les lignes d'une facture
     */
    public function getLignes($id_facture)
    {
        $sql = "SELECT l.*, a.reference, a.unite
                FROM ligne_facture_fournisseur l
                LEFT JOIN article a ON l.id_article = a.id_article
                WHERE l.id_facture_fournisseur = ?
                ORDER BY l.ordre";
        return $this->query($sql, [$id_facture]);
    }

    /**
     * Créer une facture avec ses lignes
     */
    public function creerAvecLignes($dataFacture, $lignes)
    {
        Database::beginTransaction();

        try {
            // Créer la facture
            $id_facture = $this->create($dataFacture);

            // Créer les lignes et augmenter le stock
            $ligneModel = new LigneFactureFournisseur();
            $stockModel = new Stock();

            foreach ($lignes as $index => $ligne) {
                $ligne['id_facture_fournisseur'] = $id_facture;
                $ligne['ordre'] = $index + 1;
                $ligneModel->create($ligne);

                // Augmenter le stock si l'article existe et a la gestion de stock
                if (!empty($ligne['id_article'])) {
                    $article = (new Article())->find($ligne['id_article']);
                    if ($article && $article->gestion_stock) {
                        $stockModel->augmenter($ligne['id_article'], $ligne['quantite']);
                    }
                }
            }

            Database::commit();
            return $id_facture;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Mettre à jour une facture avec ses lignes
     */
    public function updateAvecLignes($id_facture, $dataFacture, $lignes)
    {
        Database::beginTransaction();

        try {
            // Récupérer les anciennes lignes pour ajuster le stock
            $anciennesLignes = $this->getLignes($id_facture);
            $stockModel = new Stock();
            $articleModel = new Article();

            // Diminuer le stock des anciennes lignes
            foreach ($anciennesLignes as $ancienneLigne) {
                if (!empty($ancienneLigne->id_article)) {
                    $article = $articleModel->find($ancienneLigne->id_article);
                    if ($article && $article->gestion_stock) {
                        $stockModel->diminuer($ancienneLigne->id_article, $ancienneLigne->quantite);
                    }
                }
            }

            // Mettre à jour la facture
            $this->update($id_facture, $dataFacture);

            // Supprimer les anciennes lignes
            $sql = "DELETE FROM ligne_facture_fournisseur WHERE id_facture_fournisseur = ?";
            Database::execute($sql, [$id_facture]);

            // Créer les nouvelles lignes et augmenter le stock
            $ligneModel = new LigneFactureFournisseur();
            foreach ($lignes as $index => $ligne) {
                $ligne['id_facture_fournisseur'] = $id_facture;
                $ligne['ordre'] = $index + 1;
                $ligneModel->create($ligne);

                // Augmenter le stock
                if (!empty($ligne['id_article'])) {
                    $article = $articleModel->find($ligne['id_article']);
                    if ($article && $article->gestion_stock) {
                        $stockModel->augmenter($ligne['id_article'], $ligne['quantite']);
                    }
                }
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
    public function changerStatut($id_facture, $statut)
    {
        return $this->update($id_facture, ['statut' => $statut]);
    }

    /**
     * Obtenir les factures par statut
     */
    public function getByStatut($statut)
    {
        $sql = "SELECT f.*, fo.raison_sociale
                FROM {$this->table} f
                INNER JOIN fournisseur fo ON f.id_fournisseur = fo.id_fournisseur
                WHERE f.statut = ?
                ORDER BY f.date_facture DESC";
        return $this->query($sql, [$statut]);
    }

    /**
     * Obtenir les factures impayées
     */
    public function getImpayees()
    {
        $sql = "SELECT f.*, fo.raison_sociale
                FROM {$this->table} f
                INNER JOIN fournisseur fo ON f.id_fournisseur = fo.id_fournisseur
                WHERE f.statut = 'validée' 
                AND f.date_echeance < CURDATE()
                ORDER BY f.date_echeance";
        return $this->query($sql);
    }

    /**
     * Obtenir le total des achats par période
     */
    public function getTotalAchats($dateDebut, $dateFin)
    {
        $sql = "SELECT SUM(montant_ttc) as total 
                FROM {$this->table} 
                WHERE date_facture BETWEEN ? AND ?
                AND statut IN ('validée', 'payée')";
        $result = Database::queryOne($sql, [$dateDebut, $dateFin]);
        return $result->total ?? 0;
    }

    /**
     * Supprimer une facture et ajuster le stock
     */
    public function supprimerAvecStock($id_facture)
    {
        Database::beginTransaction();

        try {
            // Récupérer les lignes pour ajuster le stock
            $lignes = $this->getLignes($id_facture);
            $stockModel = new Stock();
            $articleModel = new Article();

            foreach ($lignes as $ligne) {
                if (!empty($ligne->id_article)) {
                    $article = $articleModel->find($ligne->id_article);
                    if ($article && $article->gestion_stock) {
                        $stockModel->diminuer($ligne->id_article, $ligne->quantite);
                    }
                }
            }

            // Supprimer la facture (les lignes seront supprimées en cascade)
            $this->delete($id_facture);

            Database::commit();
            return true;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }
}
