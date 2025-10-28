<?php

/**
 * Modèle Parametres
 */

class Parametres extends Model
{
    protected $table = 'parametres';
    protected $primaryKey = 'id_parametre';

    protected $fillable = [
        'code',
        'libelle',
        'valeur',
        'type_donnee',
        'description'
    ];

    /**
     * Obtenir un paramètre par code
     */
    public function getByCode($code)
    {
        return $this->findWhere('code', $code);
    }

    /**
     * Obtenir la valeur d'un paramètre
     */
    public function getValeur($code, $default = null)
    {
        $param = $this->getByCode($code);

        if (!$param) {
            return $default;
        }

        // Convertir selon le type
        switch ($param->type_donnee) {
            case 'integer':
                return intval($param->valeur);
            case 'decimal':
            case 'float':
                return floatval($param->valeur);
            case 'boolean':
                return filter_var($param->valeur, FILTER_VALIDATE_BOOLEAN);
            default:
                return $param->valeur;
        }
    }

    /**
     * Définir la valeur d'un paramètre
     */
    public function setValeur($code, $valeur)
    {
        $param = $this->getByCode($code);

        if ($param) {
            return $this->update($param->id_parametre, ['valeur' => $valeur]);
        }

        return false;
    }

    /**
     * Obtenir tous les paramètres groupés par catégorie
     */
    public function getAllGrouped()
    {
        $params = $this->all();
        $grouped = [];

        foreach ($params as $param) {
            // Extraire la catégorie du code (ex: NUM_FACTURE -> NUM)
            $parts = explode('_', $param->code);
            $category = $parts[0];

            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }

            $grouped[$category][] = $param;
        }

        return $grouped;
    }

    /**
     * Mettre à jour plusieurs paramètres
     */
    public function updateMultiple($data)
    {
        Database::beginTransaction();

        try {
            foreach ($data as $code => $valeur) {
                $this->setValeur($code, $valeur);
            }

            Database::commit();
            return true;
        } catch (Exception $e) {
            Database::rollback();
            throw $e;
        }
    }
}
