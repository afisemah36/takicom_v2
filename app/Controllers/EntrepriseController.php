<?php

/**
 * Contrôleur Entreprise
 */

class EntrepriseController extends Controller
{
    private $entrepriseModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->entrepriseModel = new Entreprise();
        $this->historiqueModel = new Historique();
    }

    /**
     * Afficher la configuration de l'entreprise
     */
    public function index()
    {
        $entreprise = $this->entrepriseModel->getInfo();

        $this->view('entreprise.index', [
            'entreprise' => $entreprise
        ]);
    }

    /**
     * Mettre à jour les informations de l'entreprise
     */
    public function update()
    {
        $valid = $this->request->validate([
            'raison_sociale' => 'required',
            'email' => 'email'
        ]);

        if (!$valid) {
            $this->redirect('/parametres');
            return;
        }

        $data = [
            'raison_sociale' => $this->request->post('raison_sociale'),
            'forme_juridique' => $this->request->post('forme_juridique'),
            'matricule_fiscale' => $this->request->post('matricule_fiscale'),
            'code_tva' => $this->request->post('code_tva'),
            'code_douane' => $this->request->post('code_douane'),
            'adresse' => $this->request->post('adresse'),
            'code_postal' => $this->request->post('code_postal'),
            'ville' => $this->request->post('ville'),
            'gouvernorat' => $this->request->post('gouvernorat'),
            'telephone' => $this->request->post('telephone'),
            'fax' => $this->request->post('fax'),
            'email' => $this->request->post('email'),
            'site_web' => $this->request->post('site_web'),
            'rib' => $this->request->post('rib'),
            'capital_social' => $this->request->post('capital_social'),
            'registre_commerce' => $this->request->post('registre_commerce'),
            'mentions_legales' => $this->request->post('mentions_legales'),
            'conditions_generales' => $this->request->post('conditions_generales')
        ];

        // Gestion de l'upload du logo
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->uploadLogo($_FILES['logo']);
            if ($uploadResult['success']) {
                $data['logo_url'] = $uploadResult['path'];
            } else {
                $this->error($uploadResult['message'], '/parametres');
                return;
            }
        }

        try {
            $entreprise = $this->entrepriseModel->getInfo();
            $this->entrepriseModel->updateInfo($data);

            $this->historiqueModel->log(
                'entreprise',
                $entreprise ? $entreprise->id_entreprise : 1,
                'UPDATE',
                $entreprise ? (array)$entreprise : [],
                $data
            );

            $this->success('Informations de l\'entreprise mises à jour avec succès', '/parametres');
        } catch (Exception $e) {
            $this->error('Erreur lors de la mise à jour: ' . $e->getMessage(), '/parametres');
        }
    }

    /**
     * Upload du logo
     */
    private function uploadLogo($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Vérifier le type
        if (!in_array($file['type'], $allowedTypes)) {
            return [
                'success' => false,
                'message' => 'Format de fichier non autorisé. Utilisez JPG, PNG, GIF ou WEBP.'
            ];
        }

        // Vérifier la taille
        if ($file['size'] > $maxSize) {
            return [
                'success' => false,
                'message' => 'Le fichier est trop volumineux. Taille maximale: 2MB.'
            ];
        }

        // Créer le dossier uploads s'il n'existe pas
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Générer un nom unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'logo_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        // Déplacer le fichier
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return [
                'success' => true,
                'path' => '/uploads/' . $filename
            ];
        }

        return [
            'success' => false,
            'message' => 'Erreur lors de l\'upload du fichier.'
        ];
    }

    /**
     * Supprimer le logo
     */
    public function deleteLogo()
    {
        try {
            $entreprise = $this->entrepriseModel->getInfo();

            if ($entreprise && $entreprise->logo_url) {
                // Supprimer le fichier physique
                $filepath = __DIR__ . '/../../public' . $entreprise->logo_url;
                if (file_exists($filepath)) {
                    unlink($filepath);
                }

                // Mettre à jour la base de données
                $this->entrepriseModel->updateInfo(['logo_url' => null]);

                $this->success('Logo supprimé avec succès', '/parametres');
            } else {
                $this->error('Aucun logo à supprimer', '/parametres');
            }
        } catch (Exception $e) {
            $this->error('Erreur lors de la suppression: ' . $e->getMessage(), '/parametres');
        }
    }
}
