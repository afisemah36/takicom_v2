<?php

/**
 * Modèle Historique
 */

class Historique extends Model
{
    protected $table = 'historique';
    protected $primaryKey = 'id_historique';

    protected $fillable = [
        'id_utilisateur',
        'table_concernee',
        'id_enregistrement',
        'action',
        'donnees_avant',
        'donnees_apres',
        'adresse_ip'
    ];

    /**
     * Enregistrer une action dans l'historique
     */
    public function log($table, $id_enregistrement, $action, $donneesAvant = null, $donneesApres = null)
    {
        $data = [
            'id_utilisateur' => Session::get('user_id'),
            'table_concernee' => $table,
            'id_enregistrement' => $id_enregistrement,
            'action' => $action,
            'donnees_avant' => $donneesAvant ? json_encode($donneesAvant, JSON_UNESCAPED_UNICODE) : null,
            'donnees_apres' => $donneesApres ? json_encode($donneesApres, JSON_UNESCAPED_UNICODE) : null,
            'adresse_ip' => $_SERVER['REMOTE_ADDR'] ?? null
        ];

        return $this->create($data);
    }

    /**
     * Obtenir l'historique d'une table
     */
    public function getByTable($table, $limit = 100)
    {
        $sql = "SELECT h.*, u.nom, u.prenom, u.login
                FROM {$this->table} h
                LEFT JOIN utilisateur u ON h.id_utilisateur = u.id_utilisateur
                WHERE h.table_concernee = ?
                ORDER BY h.date_action DESC
                LIMIT ?";
        return $this->query($sql, [$table, $limit]);
    }

    /**
     * Obtenir l'historique d'un enregistrement
     */
    public function getByEnregistrement($table, $id_enregistrement)
    {
        $sql = "SELECT h.*, u.nom, u.prenom, u.login
                FROM {$this->table} h
                LEFT JOIN utilisateur u ON h.id_utilisateur = u.id_utilisateur
                WHERE h.table_concernee = ? AND h.id_enregistrement = ?
                ORDER BY h.date_action DESC";
        return $this->query($sql, [$table, $id_enregistrement]);
    }

    /**
     * Obtenir l'historique d'un utilisateur
     */
    public function getByUtilisateur($id_utilisateur, $limit = 100)
    {
        $sql = "SELECT h.*
                FROM {$this->table} h
                WHERE h.id_utilisateur = ?
                ORDER BY h.date_action DESC
                LIMIT ?";
        return $this->query($sql, [$id_utilisateur, $limit]);
    }

    /**
     * Obtenir l'historique récent
     */
    public function getRecent($limit = 50)
    {
        $sql = "SELECT h.*, u.nom, u.prenom, u.login
                FROM {$this->table} h
                LEFT JOIN utilisateur u ON h.id_utilisateur = u.id_utilisateur
                ORDER BY h.date_action DESC
                LIMIT ?";
        return $this->query($sql, [$limit]);
    }

    /**
     * Obtenir l'historique par action
     */
    public function getByAction($action, $limit = 100)
    {
        $sql = "SELECT h.*, u.nom, u.prenom, u.login
                FROM {$this->table} h
                LEFT JOIN utilisateur u ON h.id_utilisateur = u.id_utilisateur
                WHERE h.action = ?
                ORDER BY h.date_action DESC
                LIMIT ?";
        return $this->query($sql, [$action, $limit]);
    }

    /**
     * Obtenir l'historique par période
     */
    public function getByPeriode($dateDebut, $dateFin)
    {
        $sql = "SELECT h.*, u.nom, u.prenom, u.login
                FROM {$this->table} h
                LEFT JOIN utilisateur u ON h.id_utilisateur = u.id_utilisateur
                WHERE DATE(h.date_action) BETWEEN ? AND ?
                ORDER BY h.date_action DESC";
        return $this->query($sql, [$dateDebut, $dateFin]);
    }

    /**
     * Nettoyer l'historique ancien
     */
    public function nettoyerAncien($joursAConserver = 365)
    {
        $dateLimit = date('Y-m-d H:i:s', strtotime("-{$joursAConserver} days"));

        $sql = "DELETE FROM {$this->table} WHERE date_action < ?";
        return $this->execute($sql, [$dateLimit]);
    }
}
