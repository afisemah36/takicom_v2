<?php

/**
 * Modèle CategorieArticle
 */

class CategorieArticle extends Model
{
    protected $table = 'categorie_article';
    protected $primaryKey = 'id_categorie';

    // Champs essentiels seulement
    protected $fillable = [
        'code',
        'libelle',
        'id_categorie_parent'
    ];

    /**
     * Obtenir toutes les catégories
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY libelle";
        return $this->query($sql);
    }

    /**
     * Obtenir les catégories principales (sans parent)
     */
    public function getCategoriesPrincipales()
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_categorie_parent IS NULL
                ORDER BY libelle";
        return $this->query($sql);
    }

    /**
     * Obtenir les sous-catégories d'une catégorie
     */
    public function getSousCategories($id_categorie)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id_categorie_parent = ? 
                ORDER BY libelle";
        return $this->query($sql, [$id_categorie]);
    }

    /**
     * Obtenir la catégorie parent
     */
    public function getParent($id_categorie)
    {
        $sql = "SELECT c2.* FROM {$this->table} c1
                INNER JOIN {$this->table} c2 ON c1.id_categorie_parent = c2.id_categorie
                WHERE c1.id_categorie = ?";
        return Database::queryOne($sql, [$id_categorie]);
    }

    /**
     * Obtenir l'arborescence complète
     */
    public function getArborescence()
    {
        $categories = $this->getCategoriesPrincipales();
        $result = [];

        foreach ($categories as $categorie) {
            $categorie->sous_categories = $this->getSousCategories($categorie->id_categorie);
            $result[] = $categorie;
        }

        return $result;
    }

    /**
     * Compter les articles d'une catégorie
     */
    public function countArticles($id_categorie)
    {
        $sql = "SELECT COUNT(*) as total FROM article 
                WHERE id_categorie = ?";
        $result = Database::queryOne($sql, [$id_categorie]);
        return $result->total;
    }

    /**
     * Vérifier si le code existe déjà
     */
    public function codeExists($code, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE code = ?";
        $params = [$code];

        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }

        $result = Database::queryOne($sql, $params);
        return $result->count > 0;
    }
}
