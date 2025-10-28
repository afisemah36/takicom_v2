<?php

/**
 * Modèle Role
 */

class Role extends Model
{
    protected $table = 'role';
    protected $primaryKey = 'id_role';

    protected $fillable = [
        'code',
        'libelle',
        'description',
        'permissions',
        'actif'
    ];

    /**
     * Obtenir tous les rôles actifs
     */
    public function getActifs()
    {
        $sql = "SELECT * FROM {$this->table} WHERE actif = 1 ORDER BY libelle";
        return $this->query($sql);
    }

    /**
     * Obtenir un rôle par code
     */
    public function findByCode($code)
    {
        return $this->findWhere('code', $code);
    }

    /**
     * Compter les utilisateurs d'un rôle
     */
    public function countUtilisateurs($id_role)
    {
        $sql = "SELECT COUNT(*) as total FROM utilisateur WHERE id_role = ?";
        $result = Database::queryOne($sql, [$id_role]);
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
