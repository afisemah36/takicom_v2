<?php

/**
 * Contrôleur Client
 */

class ClientController extends Controller
{
    private $clientModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->clientModel = new Client();
        $this->historiqueModel = new Historique();
    }

    /**
     * Liste des clients
     */
    public function index()
    {
        $page = $this->request->get('page', 1);
        $search = $this->request->get('search', '');

        if ($search) {
            $clients = $this->clientModel->rechercher($search);
        } else {
            $clients = $this->clientModel->getActifs();
        }

        $this->view('clients.index', [
            'clients' => $clients,
            'search' => $search
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $codeClient = $this->clientModel->genererCodeClient();

        $this->view('clients.create', [
            'code_client' => $codeClient
        ]);
    }

    /**
     * Enregistrer un nouveau client
     */
    public function store()
    {
        // Validation
        $valid = $this->request->validate([
            'code_client' => 'required',
            'email' => 'email'
        ]);

        if (!$valid) {
            $this->redirect('/clients/create');
            return;
        }

        $data = $this->request->post();

        // Vérifier si le code existe
        if ($this->clientModel->codeExists($data['code_client'])) {
            $this->error('Ce code client existe déjà', '/clients/create');
            return;
        }

        // Créer le client
        $id = $this->clientModel->create($data);

        // Logger
        $this->historiqueModel->log('client', $id, 'CREATE', null, $data);

        $this->success('Client créé avec succès', '/clients');
    }

    /**
     * Afficher un client
     */
    public function show($id)
    {
        $client = $this->clientModel->find($id);

        if (!$client) {
            $this->error('Client introuvable', '/clients');
            return;
        }

        // Récupérer les factures et devis du client
        $factures = $this->clientModel->getFactures($id);
        $devis = $this->clientModel->getDevis($id);
        $total = $this->clientModel->getTotalFactures($id);

        $this->view('clients.show', [
            'client' => $client,
            'factures' => $factures,
            'devis' => $devis,
            'total' => $total
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $client = $this->clientModel->find($id);

        if (!$client) {
            $this->error('Client introuvable', '/clients');
            return;
        }

        $this->view('clients.edit', [
            'client' => $client
        ]);
    }

    /**
     * Mettre à jour un client
     */
    public function update($id)
    {
        $client = $this->clientModel->find($id);

        if (!$client) {
            $this->error('Client introuvable', '/clients');
            return;
        }

        // Validation
        $valid = $this->request->validate([
            'code_client' => 'required',
            'email' => 'email'
        ]);

        if (!$valid) {
            $this->redirect('/clients/' . $id . '/edit');
            return;
        }

        $data = $this->request->post();

        // Vérifier si le code existe (sauf pour ce client)
        if ($this->clientModel->codeExists($data['code_client'], $id)) {
            $this->error('Ce code client existe déjà', '/clients/' . $id . '/edit');
            return;
        }

        // Logger avant modification
        $this->historiqueModel->log('client', $id, 'UPDATE', (array)$client, $data);

        // Mettre à jour
        $this->clientModel->update($id, $data);

        $this->success('Client modifié avec succès', '/clients/' . $id);
    }

    /**
     * Supprimer un client
     */
    public function delete($id)
    {
        $client = $this->clientModel->find($id);

        if (!$client) {
            $this->error('Client introuvable', '/clients');
            return;
        }

        // Vérifier s'il a des factures ou devis
        $factures = $this->clientModel->getFactures($id);
        $devis = $this->clientModel->getDevis($id);

        if (count($factures) > 0 || count($devis) > 0) {
            $this->error('Impossible de supprimer ce client car il a des documents associés', '/clients');
            return;
        }

        // Logger avant suppression
        $this->historiqueModel->log('client', $id, 'DELETE', (array)$client, null);

        // Supprimer
        $this->clientModel->delete($id);

        $this->success('Client supprimé avec succès', '/clients');
    }
}
