<?php

/**
 * Modèle Article
 */

class Article extends Model
{
    protected $table = 'article';
    protected $primaryKey = 'id_article';

    protected $fillable = [
        'id_categorie',
        'reference',
        'designation',
        'description',
        'prix_achat_ht',
        'gain_pourcentage',
        'taux_tva',
        'unite',
        'actif'
    ];

    /**
     * Obtenir tous les articles actifs
     */
public function getActifs()
{
    $sql = "SELECT a.*, c.code AS categorie_code, c.libelle AS categorie_libelle
            FROM article a
            LEFT JOIN categorie_article c ON a.id_categorie = c.id_categorie
            ORDER BY a.designation";
    return $this->query($sql);
}
public function getActifsAvecPrixVente()
{
    $sql = "SELECT a.*,
           (a.prix_achat_ht + (a.prix_achat_ht * a.gain_pourcentage / 100)) AS prix_vente_ht
            FROM article a
            WHERE a.actif = 1
            ORDER BY a.designation";

    return $this->query($sql);
}

    /**
     * Obtenir un article avec sa catégorie et son stock
     */
    public function getAvecDetails($id_article)
    {
        $sql = "SELECT a.*, c.libelle as categorie_libelle, 
                       s.quantite_stock, s.quantite_reservee, s.quantite_minimum
                FROM {$this->table} a
                LEFT JOIN categorie_article c ON a.id_categorie = c.id_categorie
                LEFT JOIN stock s ON a.id_article = s.id_article
                WHERE a.id_article = ?";
        return Database::queryOne($sql, [$id_article]);
    }

    /**
     * Rechercher des articles
     */
    public function rechercher($term)
    {
        $sql = "SELECT a.*, c.libelle as categorie_libelle, s.quantite_stock
                FROM {$this->table} a
                LEFT JOIN categorie_article c ON a.id_categorie = c.id_categorie
                LEFT JOIN stock s ON a.id_article = s.id_article
                WHERE (a.reference LIKE ? OR a.designation LIKE ? )
                AND a.actif = 1
                ORDER BY a.designation";
        $term = "%{$term}%";
        return $this->query($sql, [$term, $term]);
    }

    /**
     * Obtenir les articles par catégorie
     */
    public function getByCategorie($id_categorie)
    {
        $sql = "SELECT a.*, s.quantite_stock
                FROM {$this->table} a
                LEFT JOIN stock s ON a.id_article = s.id_article
                WHERE a.id_categorie = ? AND a.actif = 1
                ORDER BY a.designation";
        return $this->query($sql, [$id_categorie]);
    }

    /**
     * Obtenir le stock d'un article
     */
    public function getStock($id_article)
    {
        $sql = "SELECT * FROM stock WHERE id_article = ?";
        return Database::queryOne($sql, [$id_article]);
    }

    /**
     * Générer une référence unique
     */
    public function genererReference()
    {
        $sql = "SELECT reference FROM {$this->table} 
                WHERE reference LIKE 'ART%' 
                ORDER BY reference DESC 
                LIMIT 1";
        $result = Database::queryOne($sql);

        if ($result) {
            $lastNumber = intval(substr($result->reference, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'ART' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Vérifier si la référence existe déjà
     */
    public function referenceExists($reference, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE reference = ?";
        $params = [$reference];

        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }

        $result = Database::queryOne($sql, $params);
        return $result->count > 0;
    }

    /**
     * Calculer le prix TTC à partir du prix d'achat et du gain
     */
    public function getPrixTTC($id_article)
    {
        $article = $this->find($id_article);
        if ($article) {
            $prix_vente_ht = $article->prix_achat_ht * (1 + $article->gain_pourcentage / 100);
            return $prix_vente_ht * (1 + $article->taux_tva / 100);
        }
        return 0;
    }
}
