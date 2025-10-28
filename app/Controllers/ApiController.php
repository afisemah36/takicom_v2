<?php

/**
 * Contrôleur API
 * Pour les appels AJAX
 */

class ApiController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Rechercher des articles
     */
    public function searchArticles()
    {
        $term = $this->request->get('term', '');

        if (strlen($term) < 2) {
            $this->json(['results' => []]);
            return;
        }

        $articleModel = new Article();
        $articles = $articleModel->rechercher($term);

        $results = [];
        foreach ($articles as $article) {
            $results[] = [
                'id' => $article->id_article,
                'reference' => $article->reference,
                'designation' => $article->designation,
                'prix_vente_ht' => $article->prix_vente_ht,
                'taux_tva' => $article->taux_tva,
                'unite' => $article->unite,
                'quantite_disponible' => $article->quantite_disponible ?? 0
            ];
        }

        $this->json(['results' => $results]);
    }

    /**
     * Rechercher des clients
     */
    public function searchClients()
    {
        $term = $this->request->get('term', '');

        if (strlen($term) < 2) {
            $this->json(['results' => []]);
            return;
        }

        $clientModel = new Client();
        $clients = $clientModel->rechercher($term);

        $results = [];
        foreach ($clients as $client) {
            $nom = $client->raison_sociale ?: ($client->nom . ' ' . $client->prenom);
            $results[] = [
                'id' => $client->id_client,
                'code_client' => $client->code_client,
                'nom' => $nom,
                'adresse' => $client->adresse,
                'ville' => $client->ville,
                'telephone' => $client->telephone,
                'email' => $client->email
            ];
        }

        $this->json(['results' => $results]);
    }

    /**
     * Rechercher des fournisseurs
     */
    public function searchFournisseurs()
    {
        $term = $this->request->get('term', '');

        if (strlen($term) < 2) {
            $this->json(['results' => []]);
            return;
        }

        $fournisseurModel = new Fournisseur();
        $fournisseurs = $fournisseurModel->rechercher($term);

        $results = [];
        foreach ($fournisseurs as $fournisseur) {
            $results[] = [
                'id' => $fournisseur->id_fournisseur,
                'code_fournisseur' => $fournisseur->code_fournisseur,
                'raison_sociale' => $fournisseur->raison_sociale,
                'nom_contact' => $fournisseur->nom_contact,
                'adresse' => $fournisseur->adresse,
                'ville' => $fournisseur->ville,
                'telephone' => $fournisseur->telephone,
                'email' => $fournisseur->email
            ];
        }

        $this->json(['results' => $results]);
    }

    /**
     * Obtenir les détails d'un article
     */
    public function getArticle()
    {
        $id = $this->request->get('id');

        if (!$id) {
            $this->json(['success' => false, 'message' => 'ID requis'], 400);
            return;
        }

        $articleModel = new Article();
        $article = $articleModel->getAvecDetails($id);

        if (!$article) {
            $this->json(['success' => false, 'message' => 'Article introuvable'], 404);
            return;
        }

        $this->json([
            'success' => true,
            'article' => [
                'id' => $article->id_article,
                'reference' => $article->reference,
                'designation' => $article->designation,
                'prix_vente_ht' => $article->prix_vente_ht,
                'taux_tva' => $article->taux_tva,
                'unite' => $article->unite,
                'quantite_disponible' => $article->quantite_disponible ?? 0
            ]
        ]);
    }

    /**
     * Calculer les montants d'une ligne
     */
    public function calculerLigne()
    {
        $quantite = floatval($this->request->post('quantite', 0));
        $prix_unitaire = floatval($this->request->post('prix_unitaire', 0));
        $taux_tva = floatval($this->request->post('taux_tva', 19));
        $taux_remise = floatval($this->request->post('taux_remise', 0));

        $montants = LigneFactureClient::calculerMontants($quantite, $prix_unitaire, $taux_tva, $taux_remise);

        $this->json([
            'success' => true,
            'montants' => $montants
        ]);
    }

    /**
     * Vérifier le stock disponible
     */
    public function verifierStock()
    {
        $id_article = $this->request->get('id_article');
        $quantite = floatval($this->request->get('quantite', 0));

        if (!$id_article) {
            $this->json(['success' => false, 'message' => 'ID article requis'], 400);
            return;
        }

        $stockModel = new Stock();
        $suffisant = $stockModel->isSuffisant($id_article, $quantite);

        if ($suffisant) {
            $this->json(['success' => true, 'suffisant' => true]);
        } else {
            $stock = $stockModel->getByArticle($id_article);
            $this->json([
                'success' => true,
                'suffisant' => false,
                'quantite_disponible' => $stock->quantite_disponible ?? 0
            ]);
        }
    }

    /**
     * Convertir un devis en facture
     */
    public function convertirDevisEnFacture()
    {
        $id_devis = $this->request->post('id_devis');

        if (!$id_devis) {
            $this->json(['success' => false, 'message' => 'ID devis requis'], 400);
            return;
        }

        try {
            $devisModel = new Devis();
            $id_facture = $devisModel->convertirEnFacture($id_devis);

            if ($id_facture) {
                $this->json([
                    'success' => true,
                    'message' => 'Devis converti en facture',
                    'id_facture' => $id_facture
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Erreur lors de la conversion'], 500);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtenir les statistiques du dashboard
     */
    public function getStatsDashboard()
    {
        $periode = $this->request->get('periode', 'mois'); // mois, trimestre, annee

        $dateDebut = '';
        $dateFin = date('Y-m-d');

        switch ($periode) {
            case 'mois':
                $dateDebut = date('Y-m-01');
                break;
            case 'trimestre':
                $dateDebut = date('Y-m-d', strtotime('-3 months'));
                break;
            case 'annee':
                $dateDebut = date('Y-01-01');
                break;
        }

        $factureModel = new FactureClient();
        $devisModel = new Devis();

        $ca = $factureModel->getChiffreAffaires($dateDebut, $dateFin);
        $totalDevis = $devisModel->getTotalParPeriode($dateDebut, $dateFin);

        $this->json([
            'success' => true,
            'stats' => [
                'ca' => $ca,
                'total_devis' => $totalDevis,
                'periode' => $periode
            ]
        ]);
    }
}
