<?php

/**
 * Contrôleur Stock
 */

class StockController extends Controller
{
    private $stockModel;
    private $articleModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->stockModel = new Stock();
        $this->articleModel = new Article();
        $this->historiqueModel = new Historique();
    }

    /**
     * Liste du stock
     */
    public function index()
    {
        $stocks = $this->stockModel->getAllAvecArticles();
        $alertes = $this->stockModel->getAlertes();
        $ruptures = $this->stockModel->getRuptures();
        $valeurTotale = $this->stockModel->getValeurTotale();

        $this->view('stock.index', [
            'stocks' => $stocks,
            'alertes' => $alertes,
            'ruptures' => $ruptures,
            'valeur_totale' => $valeurTotale
        ]);
    }

    /**
     * Afficher le détail du stock d'un article
     */
    public function show($id)
    {
        $stock = $this->stockModel->getByArticle($id);

        if (!$stock) {
            $this->error('Stock introuvable', '/stock');
            return;
        }

        $article = $this->articleModel->find($id);

        $this->view('stock.show', [
            'stock' => $stock,
            'article' => $article
        ]);
    }

    /**
     * Mettre à jour le stock
     */
    public function update($id)
    {
        $stock = $this->stockModel->getByArticle($id);

        if (!$stock) {
            $this->error('Stock introuvable', '/stock');
            return;
        }

        $action = $this->request->post('action'); // 'ajuster', 'augmenter', 'diminuer'
        $quantite = $this->request->post('quantite', 0);
        $motif = $this->request->post('motif', '');

        $ancienneQuantite = $stock->quantite_disponible;

        Database::beginTransaction();

        try {
            switch ($action) {
                case 'ajuster':
                    // Ajustement direct
                    $this->stockModel->update($stock->id_stock, [
                        'quantite_disponible' => $quantite
                    ]);
                    break;

                case 'augmenter':
                    $this->stockModel->augmenter($id, $quantite);
                    break;

                case 'diminuer':
                    if (!$this->stockModel->isSuffisant($id, $quantite)) {
                        throw new Exception('Stock insuffisant');
                    }
                    $this->stockModel->diminuer($id, $quantite);
                    break;

                default:
                    throw new Exception('Action invalide');
            }

            // Logger l'action
            $this->historiqueModel->log(
                'stock',
                $stock->id_stock,
                'UPDATE',
                ['quantite' => $ancienneQuantite, 'motif' => $motif],
                ['quantite' => $quantite, 'action' => $action]
            );

            Database::commit();
            $this->success('Stock mis à jour avec succès', '/stock');
        } catch (Exception $e) {
            Database::rollback();
            $this->error($e->getMessage(), '/stock/' . $id);
        }
    }
}
