<?php

/**
 * Contrôleur FactureClient
 */

class FactureClientController extends Controller
{
    private $factureModel;
    private $clientModel;
    private $articleModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->factureModel = new FactureClient();
        $this->clientModel = new Client();
        $this->articleModel = new Article();
        $this->historiqueModel = new Historique();
    }

    /**
     * Liste des factures
     */
    public function index()
    {
        $statut = $this->request->get('statut', '');

        if ($statut) {
            $factures = $this->factureModel->getByStatut($statut);
        } else {
            $factures = $this->factureModel->getAllAvecClient();
        }
    // AJOUTER CE BLOC :
        foreach ($factures as $facture) {
            $totaux = $this->factureModel->calculerTotaux($facture->id_facture_client);
            $facture->montant_ht = $totaux['total_ht'];
            $facture->montant_tva = $totaux['total_tva'];
            $facture->montant_ttc = $totaux['total_ttc'];
            $facture->total_remise = $totaux['total_remise'];
        }
        $this->view('factures.index', [
            'factures' => $factures,
            'statut_selected' => $statut
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $numero = $this->factureModel->genererNumero();
        $clients = $this->clientModel->getActifs();
        $articles = $this->articleModel->getActifsAvecPrixVente();


        $this->view('factures.create', [
            'numero_facture' => $numero,
            'clients' => $clients,
            'articles' => $articles,
            'date_facture' => date('Y-m-d'),
            'date_echeance' => date('Y-m-d', strtotime('+30 days'))
        ]);
    }

    /**
     * Enregistrer une nouvelle facture
     */
    public function store()
    {
        $valid = $this->request->validate([
            'id_client' => 'required|numeric',
            'numero_facture' => 'required',
            'date_facture' => 'required'
        ]);

        if (!$valid) {
            $this->redirect('/factures/create');
            return;
        }

        $dataFacture = [
            'id_client' => $this->request->post('id_client'),
            'id_utilisateur' => Session::get('user_id'),
            'numero_facture' => $this->request->post('numero_facture'),
            'date_facture' => $this->request->post('date_facture'),
            'date_echeance' => $this->request->post('date_echeance'),
            'montant_ht' => $this->request->post('montant_ht', 0),
            'montant_tva' => $this->request->post('montant_tva', 0),
            'montant_ttc' => $this->request->post('montant_ttc', 0),
            'total_remise' => $this->request->post('total_remise', 0),
            'statut' => $this->request->post('statut', 'brouillon'),
            'mode_reglement' => $this->request->post('mode_reglement'),
            'notes' => $this->request->post('notes'),
            'conditions_reglement' => $this->request->post('conditions_reglement')
        ];

        $lignes = json_decode($this->request->post('lignes', '[]'), true);

        if (empty($lignes)) {
            $this->error('La facture doit contenir au moins une ligne', '/factures/create');
            return;
        }

        try {
            $id = $this->factureModel->creerAvecLignes($dataFacture, $lignes);
            $this->historiqueModel->log('facture_client', $id, 'CREATE', null, $dataFacture);

            $this->success('Facture créée avec succès', '/factures/' . $id);
        } catch (Exception $e) {
            $this->error('Erreur lors de la création: ' . $e->getMessage(), '/factures/create');
        }
    }

    /**
     * Afficher une facture
     */
    public function show($id)
{
    $facture = $this->factureModel->getAvecDetails($id);
    
    if (!$facture) {
        redirect('/factures', 'error', 'Facture non trouvée');
        return;
    }

    $lignes = $this->factureModel->getLignes($id);

    // AJOUTER CES 5 LIGNES :
    $totaux = $this->factureModel->calculerTotaux($id);
    $facture->montant_ht = $totaux['total_ht'];
    $facture->montant_tva = $totaux['total_tva'];
    $facture->montant_ttc = $totaux['total_ttc'];
    $facture->total_remise = $totaux['total_remise'];

    $this->view('factures/show', [
        'facture' => $facture,
        'lignes' => $lignes
    ]);
}

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $facture = $this->factureModel->getAvecDetails($id);

        if (!$facture) {
            $this->error('Facture introuvable', '/factures');
            return;
        }

        if ($facture->statut === 'payée') {
            $this->error('Impossible de modifier une facture payée', '/factures/' . $id);
            return;
        }

        $lignes = $this->factureModel->getLignes($id);
        $clients = $this->clientModel->getActifs();
        $articles = $this->articleModel->getActifs();

        $this->view('factures.edit', [
            'facture' => $facture,
            'lignes' => $lignes,
            'clients' => $clients,
            'articles' => $articles
        ]);
    }

    /**
     * Mettre à jour une facture
     */
    public function update($id)
    {
        $facture = $this->factureModel->find($id);

        if (!$facture) {
            $this->error('Facture introuvable', '/factures');
            return;
        }

        if ($facture->statut === 'payée') {
            $this->error('Impossible de modifier une facture payée', '/factures/' . $id);
            return;
        }

        $dataFacture = [
            'id_client' => $this->request->post('id_client'),
            'date_facture' => $this->request->post('date_facture'),
            'date_echeance' => $this->request->post('date_echeance'),
            'montant_ht' => $this->request->post('montant_ht', 0),
            'montant_tva' => $this->request->post('montant_tva', 0),
            'montant_ttc' => $this->request->post('montant_ttc', 0),
            'total_remise' => $this->request->post('total_remise', 0),
            'statut' => $this->request->post('statut'),
            'mode_reglement' => $this->request->post('mode_reglement'),
            'notes' => $this->request->post('notes'),
            'conditions_reglement' => $this->request->post('conditions_reglement')
        ];

        $lignes = json_decode($this->request->post('lignes', '[]'), true);

        try {
            $this->factureModel->updateAvecLignes($id, $dataFacture, $lignes);
            $this->historiqueModel->log('facture_client', $id, 'UPDATE', (array)$facture, $dataFacture);

            $this->success('Facture modifiée avec succès', '/factures/' . $id);
        } catch (Exception $e) {
            $this->error('Erreur: ' . $e->getMessage(), '/factures/' . $id . '/edit');
        }
    }

    /**
     * Supprimer une facture
     */
    public function delete($id)
    {
        $facture = $this->factureModel->find($id);

        if (!$facture) {
            $this->error('Facture introuvable', '/factures');
            return;
        }

        if ($facture->statut === 'payée') {
            $this->error('Impossible de supprimer une facture payée', '/factures');
            return;
        }

        try {
            $this->factureModel->supprimerAvecStock($id);
            $this->historiqueModel->log('facture_client', $id, 'DELETE', (array)$facture, null);

            $this->success('Facture supprimée avec succès', '/factures');
        } catch (Exception $e) {
            $this->error('Erreur: ' . $e->getMessage(), '/factures');
        }
    }

    /**
     * Générer le PDF d'une facture
     */
    public function generatePdf($id)
    {
        $facture = $this->factureModel->getAvecDetails($id);

        if (!$facture) {
            $this->error('Facture introuvable', '/factures');
            return;
        }

        $lignes = $this->factureModel->getLignes($id);
        $entreprise = (new Entreprise())->getInfo();
// AJOUTER CES 5 LIGNES :
            $totaux = $this->factureModel->calculerTotaux($id);
            $facture->montant_ht = $totaux['total_ht'];
            $facture->montant_tva = $totaux['total_tva'];
            $facture->montant_ttc = $totaux['total_ttc'];
            $facture->total_remise = $totaux['total_remise'];

        // TODO: Implémenter la génération PDF
        $this->view('factures.pdf', [
            'facture' => $facture,
            'lignes' => $lignes,
            'entreprise' => $entreprise
        ]);
    }
}
