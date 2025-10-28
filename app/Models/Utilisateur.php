<?php

/**
 * Modèle Utilisateur
 * Gère la logique métier liée aux utilisateurs
 */

class Utilisateur extends Model
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'id_utilisateur';

    protected $fillable = [
        'id_role',
        'login',
        'password_hash',
        'nom',
        'prenom',
        'email',
        'telephone',
        'actif',
        'derniere_connexion',      // ← AJOUTÉ
        'date_modification'        // ← AJOUTÉ (car tu l'utilises aussi)
    ];

    // =========================================================================
    // 🔍 Requêtes de base avec jointure rôle
    // =========================================================================

    /**
     * Obtenir tous les utilisateurs actifs avec leur rôle
     */
    public function getAllAvecRole()
    {
        $sql = "SELECT u.*, r.libelle as role_libelle, r.code as role_code
                FROM {$this->table} u
                INNER JOIN role r ON u.id_role = r.id_role
                ORDER BY u.nom, u.prenom";
        return Database::query($sql);
    }

    /**
     * Obtenir un utilisateur par login (avec rôle)
     */
    public function findByLogin($login)
    {
        $sql = "SELECT u.*, r.libelle as role_libelle, r.code as role_code, r.permissions
                FROM {$this->table} u
                INNER JOIN role r ON u.id_role = r.id_role
                WHERE u.login = ?";
        return Database::queryOne($sql, [$login]);
    }

    /**
     * Obtenir un utilisateur par email (avec rôle)
     */
    public function findByEmail($email)
    {
        $sql = "SELECT u.*, r.libelle as role_libelle, r.code as role_code
                FROM {$this->table} u
                INNER JOIN role r ON u.id_role = r.id_role
                WHERE u.email = ?";
        return Database::queryOne($sql, [$email]);
    }

    // =========================================================================
    // 🔐 Authentification
    // =========================================================================

    /**
     * Authentifier un utilisateur
     */
    public function authentifier($login, $password)
    {
        $user = $this->findByLogin($login);

        if (!$user || !$user->actif) {
            return false;
        }

        if (password_verify($password, $user->password_hash)) {
            // Mettre à jour la dernière connexion
            $this->update($user->id_utilisateur, [
                'derniere_connexion' => date('Y-m-d H:i:s'),
                'date_modification' => date('Y-m-d H:i:s')
            ]);

            return $user;
        }

        return false;
    }

    // =========================================================================
    // ➕ Création & mise à jour
    // =========================================================================

    /**
     * Créer un utilisateur à partir de données brutes (ex: formulaire)
     * Le mot de passe est automatiquement hashé
     */
    public function creerUtilisateur($data)
    {
        // Vérifier que le mot de passe est présent
        if (!isset($data['password']) || empty($data['password'])) {
            throw new InvalidArgumentException("Le mot de passe est requis pour la création.");
        }

        // Hasher le mot de passe
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);

        // Valeurs par défaut
        $data['actif'] = $data['actif'] ?? 1;
        $data['date_creation'] = date('Y-m-d H:i:s');
        $data['date_modification'] = date('Y-m-d H:i:s');

        // Filtrer les champs autorisés
        $insertData = array_intersect_key($data, array_flip($this->fillable));

        return $this->create($insertData);
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword($id_utilisateur, $newPassword)
    {
        if (empty($newPassword)) {
            throw new InvalidArgumentException("Le nouveau mot de passe ne peut pas être vide.");
        }

        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id_utilisateur, [
            'password_hash' => $passwordHash,
            'date_modification' => date('Y-m-d H:i:s')
        ]);
    }

    // =========================================================================
    // ✅ Vérifications d’unicité (utile pour l’inscription et l’édition)
    // =========================================================================

    /**
     * Vérifier si un login existe déjà (optionnellement exclure un ID)
     */
    public function loginExists($login, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE login = ?";
        $params = [$login];

        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }

        $result = Database::queryOne($sql, $params);
        return $result && $result->count > 0;
    }

    /**
     * Vérifier si un email existe déjà (optionnellement exclure un ID)
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = ?";
        $params = [$email];

        if ($excludeId) {
            $sql .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }

        $result = Database::queryOne($sql, $params);
        return $result && $result->count > 0;
    }

    // =========================================================================
    // 🔑 Permissions
    // =========================================================================

    /**
     * Vérifier si un utilisateur a une permission spécifique
     */
    public function hasPermission($id_utilisateur, $permission)
    {
        $sql = "SELECT r.permissions 
                FROM {$this->table} u
                INNER JOIN role r ON u.id_role = r.id_role
                WHERE u.id_utilisateur = ?";
        $result = Database::queryOne($sql, [$id_utilisateur]);

        if (!$result || empty($result->permissions)) {
            return false;
        }

        $permissions = json_decode($result->permissions, true);

        // Accès total ?
        if (isset($permissions['all']) && $permissions['all'] === true) {
            return true;
        }

        // Permission spécifique
        return isset($permissions[$permission]) && $permissions[$permission] === true;
    }

    // =========================================================================
    // 📊 Statistiques
    // =========================================================================

    /**
     * Obtenir les statistiques d'un utilisateur
     */
    public function getStatistiques($id_utilisateur)
    {
        $stats = [];

        // Factures clients
        $sql = "SELECT COUNT(*) as total FROM facture_client WHERE id_utilisateur = ?";
        $result = Database::queryOne($sql, [$id_utilisateur]);
        $stats['factures'] = $result ? (int)$result->total : 0;

        // Devis
        $sql = "SELECT COUNT(*) as total FROM devis WHERE id_utilisateur = ?";
        $result = Database::queryOne($sql, [$id_utilisateur]);
        $stats['devis'] = $result ? (int)$result->total : 0;

        // Chiffre d'affaires (factures validées ou payées)
        $sql = "SELECT COALESCE(SUM(montant_ttc), 0) as total 
                FROM facture_client 
                WHERE id_utilisateur = ? AND statut IN ('validée', 'payée')";
        $result = Database::queryOne($sql, [$id_utilisateur]);
        $stats['ca'] = $result ? (float)$result->total : 0.0;

        return $stats;
    }
}
