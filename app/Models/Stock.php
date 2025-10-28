<?php

/**
 * Modèle Stock
 */

class Stock extends Model
{
    protected $table = 'stock';
    protected $primaryKey = 'id_stock';

    protected $fillable = [
        'id_article',
        'quantite_stock',
        'quantite_reservee',
        'quantite_minimum',
        'seuil_alerte',
        'emplacement'
    ];

    /**
     * Obtenir le stock d'un article
     */
    public function getByArticle($id_article)
    {
        $sql = "SELECT s.*, a.reference, a.designation
                FROM {$this->table} s
                INNER JOIN article a ON s.id_article = a.id_article
                WHERE s.id_article = ?";
        return Database::queryOne($sql, [$id_article]);
    }

    /**
     * Obtenir tous les stocks avec détails des articles
     */
    public function getAllAvecArticles()
    {
        $sql = "SELECT s.*, a.reference, a.designation, a.unite, c.libelle as categorie_libelle
                FROM {$this->table} s
                INNER JOIN article a ON s.id_article = a.id_article
                LEFT JOIN categorie_article c ON a.id_categorie = c.id_categorie
                WHERE a.actif = 1
                ORDER BY a.designation";
        return $this->query($sql);
    }

    /**
     * Augmenter le stock
     */
    public function augmenter($id_article, $quantite)
    {
        $sql = "UPDATE {$this->table} 
                SET quantite_stock = quantite_stock + ?,
                    derniere_maj = NOW()
                WHERE id_article = ?";
        return $this->execute($sql, [$quantite, $id_article]);
    }

    /**
     * Diminuer le stock
     */
    public function diminuer($id_article, $quantite)
    {
        $sql = "UPDATE {$this->table} 
                SET quantite_stock = quantite_stock - ?,
                    derniere_maj = NOW()
                WHERE id_article = ?";
        return $this->execute($sql, [$quantite, $id_article]);
    }

    /**
     * Réserver du stock
     */
    public function reserver($id_article, $quantite)
    {
        $sql = "UPDATE {$this->table} 
                SET quantite_reservee = quantite_reservee + ?,
                    derniere_maj = NOW()
                WHERE id_article = ?";
        return $this->execute($sql, [$quantite, $id_article]);
    }

    /**
     * Libérer du stock réservé
     */
    public function liberer($id_article, $quantite)
    {
        $sql = "UPDATE {$this->table} 
                SET quantite_reservee = quantite_reservee - ?,
                    derniere_maj = NOW()
                WHERE id_article = ?";
        return $this->execute($sql, [$quantite, $id_article]);
    }

    /**
     * Obtenir les articles en alerte stock
     */
    public function getAlertes()
    {
        $sql = "SELECT s.*, a.reference, a.designation, c.libelle as categorie_libelle
                FROM {$this->table} s
                INNER JOIN article a ON s.id_article = a.id_article
                LEFT JOIN categorie_article c ON a.id_categorie = c.id_categorie
                WHERE a.actif = 1
                AND s.quantite_stock <= s.seuil_alerte
                ORDER BY s.quantite_stock";
        return $this->query($sql);
    }

    /**
     * Obtenir les articles en rupture
     */
    public function getRuptures()
    {
        $sql = "SELECT s.*, a.reference, a.designation, c.libelle as categorie_libelle
                FROM {$this->table} s
                INNER JOIN article a ON s.id_article = a.id_article
                LEFT JOIN categorie_article c ON a.id_categorie = c.id_categorie
                WHERE a.actif = 1
                AND s.quantite_stock <= 0
                ORDER BY a.designation";
        return $this->query($sql);
    }

    /**
     * Vérifier si le stock est suffisant
     */
    public function isSuffisant($id_article, $quantite)
    {
        $sql = "SELECT quantite_stock FROM {$this->table} WHERE id_article = ?";
        $result = Database::queryOne($sql, [$id_article]);

        if ($result) {
            return $result->quantite_stock >= $quantite;
        }

        return false;
    }

    /**
     * Créer un stock pour un nouvel article
     */
    public function creerPourArticle($id_article, $quantite_initiale = 0)
    {
        $data = [
            'id_article' => $id_article,
            'quantite_stock' => $quantite_initiale,
            'quantite_reservee' => 0,
            'quantite_minimum' => 0,
            'seuil_alerte' => 10
        ];

        return $this->create($data);
    }

    /**
     * Obtenir la valeur totale du stock
     */
    public function getValeurTotale()
    {
        $sql = "SELECT SUM(s.quantite_stock * a.prix_achat_ht) as valeur_totale
                FROM {$this->table} s
                INNER JOIN article a ON s.id_article = a.id_article
                WHERE a.actif = 1";
        $result = Database::queryOne($sql);
        return $result->valeur_totale ?? 0;
    }
}
