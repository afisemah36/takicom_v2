<?php

/**
 * Contrôleur Fournisseur
 */

class FournisseurController extends Controller
{
    private $fournisseurModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->fournisseurModel = new Fournisseur();
        $this->historiqueModel = new Historique();
    }

    /**
     * Liste des fournisseurs
     */
    public function index()
    {
        $search = $this->request->get('search', '');

        if ($search) {
            $fournisseurs = $this->fournisseurModel->rechercher($search);
        } else {
            $fournisseurs = $this->fournisseurModel->getActifs();
        }

        $this->view('fournisseurs.index', [
            'fournisseurs' => $fournisseurs,
            'search' => $search
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $codeFournisseur = $this->fournisseurModel->genererCodeFournisseur();

        $this->view('fournisseurs.create', [
            'code_fournisseur' => $codeFournisseur
        ]);
    }

    /**
     * Enregistrer un nouveau fournisseur
     */
    public function store()
    {
        $valid = $this->request->validate([
            'code_fournisseur' => 'required',
            'raison_sociale' => 'required',
            'email' => 'email'
        ]);

        if (!$valid) {
            $this->redirect('/fournisseurs/create');
            return;
        }

        $data = $this->request->post();

        if ($this->fournisseurModel->codeExists($data['code_fournisseur'])) {
            $this->error('Ce code fournisseur existe déjà', '/fournisseurs/create');
            return;
        }

        $id = $this->fournisseurModel->create($data);
        $this->historiqueModel->log('fournisseur', $id, 'CREATE', null, $data);

        $this->success('Fournisseur créé avec succès', '/fournisseurs');
    }

    /**
     * Afficher un fournisseur
     */
    public function show($id)
    {
        $fournisseur = $this->fournisseurModel->find($id);

        if (!$fournisseur) {
            $this->error('Fournisseur introuvable', '/fournisseurs');
            return;
        }

        $factures = $this->fournisseurModel->getFactures($id);
        $total = $this->fournisseurModel->getTotalFactures($id);

        $this->view('fournisseurs.show', [
            'fournisseur' => $fournisseur,
            'factures' => $factures,
            'total' => $total
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $fournisseur = $this->fournisseurModel->find($id);

        if (!$fournisseur) {
            $this->error('Fournisseur introuvable', '/fournisseurs');
            return;
        }

        $this->view('fournisseurs.edit', [
            'fournisseur' => $fournisseur
        ]);
    }

    /**
     * Mettre à jour un fournisseur
     */
    public function update($id)
    {
        $fournisseur = $this->fournisseurModel->find($id);

        if (!$fournisseur) {
            $this->error('Fournisseur introuvable', '/fournisseurs');
            return;
        }

        $valid = $this->request->validate([
            'code_fournisseur' => 'required',
            'raison_sociale' => 'required',
            'email' => 'email'
        ]);

        if (!$valid) {
            $this->redirect('/fournisseurs/' . $id . '/edit');
            return;
        }

        $data = $this->request->post();

        if ($this->fournisseurModel->codeExists($data['code_fournisseur'], $id)) {
            $this->error('Ce code fournisseur existe déjà', '/fournisseurs/' . $id . '/edit');
            return;
        }

        $this->historiqueModel->log('fournisseur', $id, 'UPDATE', (array)$fournisseur, $data);
        $this->fournisseurModel->update($id, $data);

        $this->success('Fournisseur modifié avec succès', '/fournisseurs/' . $id);
    }

    /**
     * Supprimer un fournisseur
     */
    public function delete($id)
    {
        $fournisseur = $this->fournisseurModel->find($id);

        if (!$fournisseur) {
            $this->error('Fournisseur introuvable', '/fournisseurs');
            return;
        }

        $factures = $this->fournisseurModel->getFactures($id);

        if (count($factures) > 0) {
            $this->error('Impossible de supprimer ce fournisseur car il a des factures associées', '/fournisseurs');
            return;
        }

        $this->historiqueModel->log('fournisseur', $id, 'DELETE', (array)$fournisseur, null);
        $this->fournisseurModel->delete($id);

        $this->success('Fournisseur supprimé avec succès', '/fournisseurs');
    }
}
