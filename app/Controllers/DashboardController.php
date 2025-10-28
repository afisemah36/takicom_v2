<?php

/**
 * Contrôleur Dashboard
 */

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Tableau de bord
     */
    public function index()
    {
        // Statistiques générales
        $stats = [];

        // Clients
        $clientModel = new Client();
        $stats['total_clients'] = $clientModel->count();

        // Articles
        $articleModel = new Article();
        $stats['total_articles'] = $articleModel->count();

        // Stock en alerte
        $stockModel = new Stock();
        $alertesStock = $stockModel->getAlertes();
        $stats['alertes_stock'] = count($alertesStock);

        // Factures du mois
        $factureModel = new FactureClient();
        $dateDebut = date('Y-m-01');
        $dateFin = date('Y-m-t');
        $stats['ca_mois'] = $factureModel->getChiffreAffaires($dateDebut, $dateFin);

        // Factures impayées
        $facturesImpayees = $factureModel->getImpayees();
        $stats['factures_impayees'] = count($facturesImpayees);

        // Devis en attente
        $devisModel = new Devis();
        $devisEnAttente = $devisModel->getByStatut('en_attente');
        $stats['devis_en_attente'] = count($devisEnAttente);

        // Dernières factures
        $dernieresFactures = $factureModel->getAllAvecClient();
        $dernieresFactures = array_slice($dernieresFactures, 0, 10);

        // Derniers devis
        $derniersDevis = $devisModel->getAllAvecClient();
        $derniersDevis = array_slice($derniersDevis, 0, 10);

        // Historique récent
        $historiqueModel = new Historique();
        $historique = $historiqueModel->getRecent(15);

        $this->view('dashboard.index', [
            'stats' => $stats,
            'alertes_stock' => $alertesStock,
            'factures_impayees' => $facturesImpayees,
            'dernieres_factures' => $dernieresFactures,
            'derniers_devis' => $derniersDevis,
            'historique' => $historique
        ]);
    }
}
