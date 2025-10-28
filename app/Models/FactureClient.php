<?php

/**
 * Modèle FactureClient
 */

class FactureClient extends Model
{
    protected $table = 'facture_client';
    protected $primaryKey = 'id_facture_client';

    // Champs essentiels seulement
    protected $fillable = [
        'id_client',
        'id_utilisateur',
        'numero_facture',
        'date_facture',
        'date_echeance',
        'statut',
        'mode_reglement',
        'notes',
        'conditions_reglement'
    ];

    /**
     * Obtenir toutes les factures avec informations client
     */
    public function getAllAvecClient()
    {
        $sql = "SELECT f.*, c.raison_sociale, c.nom, c.prenom, u.nom as utilisateur_nom
                FROM {$this->table} f
                INNER JOIN client c ON f.id_client = c.id_client
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
                       c.code_client, c.raison_sociale, c.nom, c.prenom, c.adresse, 
                       c.code_postal, c.ville, c.gouvernorat, c.telephone, c.email,
                       c.matricule_fiscale, c.code_tva,
                       u.nom as utilisateur_nom, u.prenom as utilisateur_prenom
                FROM {$this->table} f
                INNER JOIN client c ON f.id_client = c.id_client
                LEFT JOIN utilisateur u ON f.id_utilisateur = u.id_utilisateur
                WHERE f.id_facture_client = ?";
        return Database::queryOne($sql, [$id_facture]);
    }

    /**
     * Obtenir les lignes d'une facture avec infos article
     */
    public function getLignes($id_facture)
    {
        $sql = "SELECT l.*, a.reference, a.designation, a.unite, a.prix_achat_ht, a.taux_tva
        FROM ligne_facture_client l
        LEFT JOIN article a ON l.id_article = a.id_article
        WHERE l.id_facture_client = ?
        ORDER BY l.ordre";
        return $this->query($sql, [$id_facture]);
    }

    /**
     * Calculer les totaux dynamiquement depuis les lignes
     */
    public function calculerTotaux($id_facture)
    {
        $lignes = $this->getLignes($id_facture);

        $total_ht = 0;
        $total_tva = 0;
        $total_remise = 0;

        foreach ($lignes as $ligne) {
            $prix_unitaire = $ligne->prix_unitaire_ht ?? 0;
            $quantite = $ligne->quantite;
            $remise = $ligne->remise ?? 0;
            $tva = $ligne->taux_tva ?? 0;

            $ligne_ht = ($prix_unitaire * $quantite) - $remise;
            $ligne_tva = $ligne_ht * $tva / 100;

            $total_ht += $ligne_ht;
            $total_tva += $ligne_tva;
            $total_remise += $remise;
        }

        return [
            'total_ht' => $total_ht,
            'total_tva' => $total_tva,
            'total_ttc' => $total_ht + $total_tva,
            'total_remise' => $total_remise
        ];
    }

    /**
     * Générer un numéro de facture
     */
    public function genererNumero()
    {
        $year = date('Y');
        $sql = "SELECT numero_facture FROM {$this->table} 
                WHERE numero_facture LIKE ? 
                ORDER BY numero_facture DESC 
                LIMIT 1";
        $result = Database::queryOne($sql, ["FAC-{$year}-%"]);

        if ($result && preg_match('/FAC-\d{4}-(\d+)/', $result->numero_facture, $matches)) {
            $lastNumber = intval($matches[1]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'FAC-' . $year . '-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Créer une facture avec ses lignes et mise à jour du stock
     */
    public function creerAvecLignes($dataFacture, $lignes)
    {
        Database::beginTransaction();

        try {
            $id_facture = $this->create($dataFacture);

            $ligneModel = new LigneFactureClient();
            $stockModel = new Stock();

            foreach ($lignes as $index => $ligne) {
                $ligne['id_facture_client'] = $id_facture;
                $ligne['ordre'] = $index + 1;
                $ligneModel->create($ligne);

                if (!empty($ligne['id_article'])) {
                    $article = (new Article())->find($ligne['id_article']);
                    if ($article && $article->gestion_stock) {
                        $stockModel->diminuer($ligne['id_article'], $ligne['quantite']);
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
            $anciennesLignes = $this->getLignes($id_facture);
            $stockModel = new Stock();
            $articleModel = new Article();

            // Restaurer le stock des anciennes lignes
            foreach ($anciennesLignes as $ancienneLigne) {
                if (!empty($ancienneLigne->id_article)) {
                    $article = $articleModel->find($ancienneLigne->id_article);
                    if ($article && $article->gestion_stock) {
                        $stockModel->augmenter($ancienneLigne->id_article, $ancienneLigne->quantite);
                    }
                }
            }

            $this->update($id_facture, $dataFacture);

            // Supprimer anciennes lignes
            $sql = "DELETE FROM ligne_facture_client WHERE id_facture_client = ?";
            Database::execute($sql, [$id_facture]);

            // Créer nouvelles lignes
            $ligneModel = new LigneFactureClient();
            foreach ($lignes as $index => $ligne) {
                $ligne['id_facture_client'] = $id_facture;
                $ligne['ordre'] = $index + 1;
                $ligneModel->create($ligne);

                if (!empty($ligne['id_article'])) {
                    $article = $articleModel->find($ligne['id_article']);
                    if ($article && $article->gestion_stock) {
                        $stockModel->diminuer($ligne['id_article'], $ligne['quantite']);
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
        $sql = "SELECT f.*, c.raison_sociale, c.nom, c.prenom
                FROM {$this->table} f
                INNER JOIN client c ON f.id_client = c.id_client
                WHERE f.statut = ?
                ORDER BY f.date_facture DESC";
        return $this->query($sql, [$statut]);
    }

    /**
     * Obtenir les factures impayées
     */
    public function getImpayees()
    {
        $sql = "SELECT f.*, c.raison_sociale, c.nom, c.prenom
                FROM {$this->table} f
                INNER JOIN client c ON f.id_client = c.id_client
                WHERE f.statut = 'validée' 
                AND f.date_echeance < CURDATE()
                ORDER BY f.date_echeance";
        return $this->query($sql);
    }

    /**
     * Supprimer une facture et restaurer le stock
     */
    public function supprimerAvecStock($id_facture)
    {
        Database::beginTransaction();

        try {
            $lignes = $this->getLignes($id_facture);
            $stockModel = new Stock();
            $articleModel = new Article();

            foreach ($lignes as $ligne) {
                if (!empty($ligne->id_article)) {
                    $article = $articleModel->find($ligne->id_article);
                    if ($article && $article->gestion_stock) {
                        $stockModel->augmenter($ligne->id_article, $ligne->quantite);
                    }
                }
            }

            $this->delete($id_facture);

            Database::commit();
            return true;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }

    /**
     * Obtenir le chiffre d'affaires
     *
     * Usage :
     * - getChiffreAffaires('2025-10-01', '2025-10-31')  => CA sur une période
     * - getChiffreAffaires(2025)                       => CA sur l'année 2025
     * - getChiffreAffaires()                            => CA total
     */
    public function getChiffreAffaires($start = null, $end = null): float
    {
        $sql = "SELECT COALESCE(SUM(total_ttc), 0) AS ca FROM {$this->table} WHERE 1=1";
        $params = [];

        // Si deux dates fournies, utiliser BETWEEN
        if ($start !== null && $end !== null) {
            $sql .= " AND date_facture BETWEEN ? AND ?";
            $params = [$start, $end];
        } elseif ($start !== null && (is_int($start) || (is_string($start) && preg_match('/^\d{4}$/', $start)))) {
            // Si un entier/année fourni, filtrer par YEAR()
            $sql .= " AND YEAR(date_facture) = ?";
            $params = [(int)$start];
        } elseif ($start !== null && is_string($start) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) {
            // Si une seule date fournie, filtrer sur la date
            $sql .= " AND date_facture = ?";
            $params = [$start];
        }

        $row = Database::queryOne($sql, $params);

        // Supporter retour en objet ou tableau
        $ca = 0;
        if ($row) {
            if (is_object($row) && isset($row->ca)) $ca = $row->ca;
            elseif (is_array($row) && isset($row['ca'])) $ca = $row['ca'];
        }

        return (float) $ca;
    }
}
