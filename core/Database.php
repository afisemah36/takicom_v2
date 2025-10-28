<?php

/**
 * Classe Database
 * Gestion de la connexion à la base de données avec PDO et préfixe de tables
 */

class Database
{
    private static $instance = null;
    private $pdo;
    private static $tablePrefix = '';

    /**
     * Initialisation de la connexion
     */
    public static function init($config)
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    /**
     * Constructeur privé (Singleton)
     */
    private function __construct($config)
    {
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";

        // Définir le préfixe si fourni
        if (isset($config['prefix'])) {
            self::$tablePrefix = $config['prefix'];
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $options);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    /**
     * Obtenir l'instance PDO
     */
    public static function getConnection()
    {
        return self::$instance->pdo;
    }

    /**
     * Ajouter le préfixe aux noms de tables dans une requête SQL
     * Remplace {table} par prefix_table
     */
    private static function addPrefix($sql)
    {
        return preg_replace_callback('/\{(\w+)\}/', function ($matches) {
            return self::$tablePrefix . $matches[1];
        }, $sql);
    }

    /**
     * Obtenir le nom complet d'une table avec préfixe
     */
    public static function table($tableName)
    {
        return self::$tablePrefix . $tableName;
    }

    /**
     * Exécuter une requête SELECT
     */
    public static function query($sql, $params = [])
    {
        $sql = self::addPrefix($sql);
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Exécuter une requête SELECT et retourner une seule ligne
     */
    public static function queryOne($sql, $params = [])
    {
        $sql = self::addPrefix($sql);
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Exécuter une requête INSERT, UPDATE, DELETE
     */
    public static function execute($sql, $params = [])
    {
        $sql = self::addPrefix($sql);
        $stmt = self::getConnection()->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Obtenir le dernier ID inséré
     */
    public static function lastInsertId()
    {
        return self::getConnection()->lastInsertId();
    }

    /**
     * Démarrer une transaction
     */
    public static function beginTransaction()
    {
        return self::getConnection()->beginTransaction();
    }

    /**
     * Valider une transaction
     */
    public static function commit()
    {
        return self::getConnection()->commit();
    }

    /**
     * Annuler une transaction
     */
    public static function rollback()
    {
        return self::getConnection()->rollBack();
    }
}
