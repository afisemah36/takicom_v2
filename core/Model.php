<?php

/**
 * Classe Model
 * Modèle de base avec méthodes CRUD
 */

class Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];

    /**
     * Trouver tous les enregistrements
     */
    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return Database::query($sql);
    }

    /**
     * Trouver par ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return Database::queryOne($sql, [$id]);
    }

    /**
     * Trouver avec condition WHERE
     */
    public function where($column, $operator, $value = null)
    {
        // Si 2 arguments, l'opérateur est '='
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        return Database::query($sql, [$value]);
    }

    /**
     * Trouver un seul enregistrement avec WHERE
     */
    public function findWhere($column, $operator, $value = null)
    {
        // Si 2 arguments, l'opérateur est '='
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }

        $sql = "SELECT * FROM {$this->table} WHERE {$column} {$operator} ?";
        return Database::queryOne($sql, [$value]);
    }

    /**
     * Créer un nouvel enregistrement
     */
    public function create($data)
    {
        // Filtrer les données selon fillable
        $data = $this->filterFillable($data);

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        Database::execute($sql, array_values($data));

        return Database::lastInsertId();
    }

    /**
     * Mettre à jour un enregistrement
     */
    public function update($id, $data)
    {
        // Filtrer les données selon fillable
        $data = $this->filterFillable($data);

        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = ?";
        }
        $setClause = implode(', ', $setParts);

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = ?";

        $values = array_values($data);
        $values[] = $id;

        return Database::execute($sql, $values);
    }

    /**
     * Supprimer un enregistrement
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return Database::execute($sql, [$id]);
    }

    /**
     * Compter les enregistrements
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = Database::queryOne($sql);
        return $result->total;
    }

    /**
     * Pagination
     */
    public function paginate($perPage = 10, $page = 1)
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT * FROM {$this->table} LIMIT ? OFFSET ?";
        $data = Database::query($sql, [$perPage, $offset]);

        $total = $this->count();
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_more' => $page < $totalPages
        ];
    }

    /**
     * Recherche
     */
    public function search($columns, $term)
    {
        $whereClauses = [];
        $params = [];

        foreach ($columns as $column) {
            $whereClauses[] = "{$column} LIKE ?";
            $params[] = "%{$term}%";
        }

        $whereClause = implode(' OR ', $whereClauses);

        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        return Database::query($sql, $params);
    }

    /**
     * Filtrer les données selon fillable
     */
    private function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }

        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Requête personnalisée
     */
    public function query($sql, $params = [])
    {
        return Database::query($sql, $params);
    }

    /**
     * Exécuter une requête personnalisée
     */
    public function execute($sql, $params = [])
    {
        return Database::execute($sql, $params);
    }
    /**
     * Obtenir tous les enregistrements
     */
    public function getAll()
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} DESC";
        return $this->query($sql);
    }
}
