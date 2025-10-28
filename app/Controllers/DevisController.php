<?php

/**
 * Contrôleur Devis
 */

class DevisController extends Controller
{
    private $devisModel;
    private $clientModel;
    private $articleModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->devisModel = new Devis();
        $this->clientModel = new Client();
        $this->articleModel = new Article();
        $this->historiqueModel = new Historique();
    }

    /**
     * Liste des devis
     */
    public function index()
    {
        $statut = $this->request->get('statut', '');

        if ($statut) {
            $devis = $this->devisModel->getByStatut($statut);
        } else {
            $devis = $this->devisModel->getAllAvecClient();
        }

        $this->view('devis.index', [
            'devis' => $devis,
            'statut_selected' => $statut
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $numero = $this->devisModel->genererNumero();
        $clients = $this->clientModel->getActifs();
        $articles = $this->articleModel->getActifs();

        $this->view('devis.create', [
            'numero_devis' => $numero,
            'clients' => $clients,
            'articles' => $articles,
            'date_devis' => date('Y-m-d'),
            'date_validite' => date('Y-m-d', strtotime('+30 days'))
        ]);
    }

    /**
     * Enregistrer un nouveau devis
     */
    public function store()
    {
        $valid = $this->request->validate([
            'id_client' => 'required|numeric',
            'numero_devis' => 'required',
            'date_devis' => 'required'
        ]);

        if (!$valid) {
            $this->redirect('/devis/create');
            return;
        }

        // Récupérer les données du devis
        $dataDevis = [
            'id_client' => $this->request->post('id_client'),
            'id_utilisateur' => Session::get('user_id'),
            'numero_devis' => $this->request->post('numero_devis'),
            'date_devis' => $this->request->post('date_devis'),
            'date_validite' => $this->request->post('date_validite'),
            'montant_ht' => $this->request->post('montant_ht', 0),
            'montant_tva' => $this->request->post('montant_tva', 0),
            'montant_ttc' => $this->request->post('montant_ttc', 0),
            'total_remise' => $this->request->post('total_remise', 0),
            'statut' => $this->request->post('statut', 'brouillon'),
            'notes' => $this->request->post('notes'),
            'conditions' => $this->request->post('conditions')
        ];

        // Récupérer les lignes
        $lignes = json_decode($this->request->post('lignes', '[]'), true);

        if (empty($lignes)) {
            $this->error('Le devis doit contenir au moins une ligne', '/devis/create');
            return;
        }

        try {
            $id = $this->devisModel->creerAvecLignes($dataDevis, $lignes);
            $this->historiqueModel->log('devis', $id, 'CREATE', null, $dataDevis);

            $this->success('Devis créé avec succès', '/devis/' . $id);
        } catch (Exception $e) {
            $this->error('Erreur lors de la création du devis: ' . $e->getMessage(), '/devis/create');
        }
    }

    /**
     * Afficher un devis
     */
    public function show($id)
    {
        $devis = $this->devisModel->getAvecDetails($id);

        if (!$devis) {
            $this->error('Devis introuvable', '/devis');
            return;
        }

        $lignes = $this->devisModel->getLignes($id);

        $this->view('devis.show', [
            'devis' => $devis,
            'lignes' => $lignes
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $devis = $this->devisModel->getAvecDetails($id);

        if (!$devis) {
            $this->error('Devis introuvable', '/devis');
            return;
        }

        if ($devis->statut === 'converti') {
            $this->error('Impossible de modifier un devis déjà converti en facture', '/devis/' . $id);
            return;
        }

        $lignes = $this->devisModel->getLignes($id);
        $clients = $this->clientModel->getActifs();
        $articles = $this->articleModel->getActifs();

        $this->view('devis.edit', [
            'devis' => $devis,
            'lignes' => $lignes,
            'clients' => $clients,
            'articles' => $articles
        ]);
    }

    /**
     * Mettre à jour un devis
     */
    public function update($id)
    {
        $devis = $this->devisModel->find($id);

        if (!$devis) {
            $this->error('Devis introuvable', '/devis');
            return;
        }

        if ($devis->statut === 'converti') {
            $this->error('Impossible de modifier un devis déjà converti', '/devis/' . $id);
            return;
        }

        $dataDevis = [
            'id_client' => $this->request->post('id_client'),
            'date_devis' => $this->request->post('date_devis'),
            'date_validite' => $this->request->post('date_validite'),
            'montant_ht' => $this->request->post('montant_ht', 0),
            'montant_tva' => $this->request->post('montant_tva', 0),
            'montant_ttc' => $this->request->post('montant_ttc', 0),
            'total_remise' => $this->request->post('total_remise', 0),
            'statut' => $this->request->post('statut'),
            'notes' => $this->request->post('notes'),
            'conditions' => $this->request->post('conditions')
        ];

        $lignes = json_decode($this->request->post('lignes', '[]'), true);

        try {
            $this->devisModel->updateAvecLignes($id, $dataDevis, $lignes);
            $this->historiqueModel->log('devis', $id, 'UPDATE', (array)$devis, $dataDevis);

            $this->success('Devis modifié avec succès', '/devis/' . $id);
        } catch (Exception $e) {
            $this->error('Erreur lors de la modification: ' . $e->getMessage(), '/devis/' . $id . '/edit');
        }
    }

    /**
     * Supprimer un devis
     */
    public function delete($id)
    {
        $devis = $this->devisModel->find($id);

        if (!$devis) {
            $this->error('Devis introuvable', '/devis');
            return;
        }

        if ($devis->statut === 'converti') {
            $this->error('Impossible de supprimer un devis converti en facture', '/devis');
            return;
        }

        $this->historiqueModel->log('devis', $id, 'DELETE', (array)$devis, null);
        $this->devisModel->delete($id);

        $this->success('Devis supprimé avec succès', '/devis');
    }

    /**
     * Générer le PDF d'un devis
     */
    public function generatePdf($id)
    {
        $devis = $this->devisModel->getAvecDetails($id);

        if (!$devis) {
            $this->error('Devis introuvable', '/devis');
            return;
        }

        $lignes = $this->devisModel->getLignes($id);
        $entreprise = (new Entreprise())->getInfo();

        // TODO: Implémenter la génération PDF
        $this->view('devis.pdf', [
            'devis' => $devis,
            'lignes' => $lignes,
            'entreprise' => $entreprise
        ]);
    }
}
