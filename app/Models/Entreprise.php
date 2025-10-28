<?php

/**
 * Modèle Entreprise
 */

class Entreprise extends Model
{
    protected $table = 'entreprise';
    protected $primaryKey = 'id_entreprise';

    protected $fillable = [
        'raison_sociale',
        'forme_juridique',
        'matricule_fiscale',
        'code_tva',
        'code_douane',
        'adresse',
        'code_postal',
        'ville',
        'gouvernorat',
        'telephone',
        'fax',
        'email',
        'site_web',
        'logo_url',
        'rib',
        'capital_social',
        'registre_commerce',
        'mentions_legales',
        'conditions_generales'
    ];

    /**
     * Obtenir les informations de l'entreprise
     * (Il ne devrait y avoir qu'un seul enregistrement)
     */
    public function getInfo()
    {
        $sql = "SELECT * FROM {$this->table} LIMIT 1";
        return Database::queryOne($sql);
    }

    /**
     * Mettre à jour les informations de l'entreprise
     */
    public function updateInfo($data)
    {
        $info = $this->getInfo();

        if ($info) {
            return $this->update($info->id_entreprise, $data);
        } else {
            return $this->create($data);
        }
    }
}
