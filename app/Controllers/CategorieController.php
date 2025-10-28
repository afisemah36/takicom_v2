<?php

/**
 * Contrôleur Categorie
 */

class CategorieController extends Controller
{
    private $categorieModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->categorieModel = new CategorieArticle();
        $this->historiqueModel = new Historique();
    }

    /**
     * Liste des catégories
     */
    public function index()
    {
        $arborescence = $this->categorieModel->getArborescence();

        $this->view('categories.index', [
            'arborescence' => $arborescence
        ]);
    }

    /**
     * Créer une catégorie (AJAX)
     */
    public function store()
    {
        $data = [];

        if ($this->request->isAjax()) {
            $jsonData = $this->request->json();
            if ($jsonData) $data = $jsonData;
        }

        if (empty($data)) {
            $data = $this->request->post();
        }

        // Validation
        if (empty($data['code']) || empty($data['libelle'])) {
            $msg = 'Code et libellé sont requis';
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => $msg], 400);
            } else {
                $this->error($msg, '/categories');
            }
            return;
        }

        if ($this->categorieModel->codeExists($data['code'])) {
            $msg = 'Ce code existe déjà';
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => $msg], 400);
            } else {
                $this->error($msg, '/categories');
            }
            return;
        }

        $dataToInsert = [
            'code' => strtoupper($data['code']),
            'libelle' => $data['libelle'],
            'id_categorie_parent' => !empty($data['id_categorie_parent']) ? $data['id_categorie_parent'] : null
        ];

        $id = $this->categorieModel->create($dataToInsert);
        $this->historiqueModel->log('categorie_article', $id, 'CREATE', null, $dataToInsert);

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Catégorie créée', 'id' => $id]);
        } else {
            $this->success('Catégorie créée avec succès', '/categories');
        }
    }

    /**
     * Mettre à jour une catégorie (AJAX)
     */
    public function update($id)
    {
        $categorie = $this->categorieModel->find($id);

        if (!$categorie) {
            $msg = 'Catégorie introuvable';
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => $msg], 404);
            } else {
                $this->error($msg, '/categories');
            }
            return;
        }

        $data = [];
        if ($this->request->isAjax()) {
            $jsonData = $this->request->json();
            if ($jsonData) $data = $jsonData;
        }

        if (empty($data)) {
            $data = $this->request->post();
        }

        if ($this->categorieModel->codeExists($data['code'], $id)) {
            $msg = 'Ce code existe déjà';
            if ($this->request->isAjax()) {
                $this->json(['success' => false, 'message' => $msg], 400);
            } else {
                $this->error($msg, '/categories');
            }
            return;
        }

        $dataToUpdate = [
            'code' => strtoupper($data['code']),
            'libelle' => $data['libelle'],
            'id_categorie_parent' => !empty($data['id_categorie_parent']) ? $data['id_categorie_parent'] : null
        ];

        $this->historiqueModel->log('categorie_article', $id, 'UPDATE', (array)$categorie, $dataToUpdate);
        $this->categorieModel->update($id, $dataToUpdate);

        if ($this->request->isAjax()) {
            $this->json(['success' => true, 'message' => 'Catégorie modifiée']);
        } else {
            $this->success('Catégorie modifiée avec succès', '/categories');
        }
    }

    /**
     * Supprimer une catégorie (AJAX)
     */
    public function delete($id)
    {
        $categorie = $this->categorieModel->find($id);

        if (!$categorie) {
            $this->json(['success' => false, 'message' => 'Catégorie introuvable'], 404);
            return;
        }

        // Vérifier s'il y a des articles
        $count = $this->categorieModel->countArticles($id);

        if ($count > 0) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer, des articles sont liés à cette catégorie'], 400);
            return;
        }

        // Vérifier s'il y a des sous-catégories
        $sousCategories = $this->categorieModel->getSousCategories($id);

        if (count($sousCategories) > 0) {
            $this->json(['success' => false, 'message' => 'Impossible de supprimer, cette catégorie a des sous-catégories'], 400);
            return;
        }

        $this->historiqueModel->log('categorie_article', $id, 'DELETE', (array)$categorie, null);
        $this->categorieModel->delete($id);

        $this->json(['success' => true, 'message' => 'Catégorie supprimée']);
    }
}
