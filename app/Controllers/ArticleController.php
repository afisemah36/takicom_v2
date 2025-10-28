<?php

/**
 * Contrôleur Article
 */

class ArticleController extends Controller
{
    private $articleModel;
    private $categorieModel;
    private $stockModel;
    private $historiqueModel;

    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->articleModel = new Article();
        $this->categorieModel = new CategorieArticle();
        $this->stockModel = new Stock();
        $this->historiqueModel = new Historique();
    }

    /**
     * Liste des articles
     */
    public function index()
    {
        $search = $this->request->get('search', '');
        $categorie = $this->request->get('categorie', '');
        $page = max(1, (int)$this->request->get('page', 1));
        $perPage = 10; // Nombre d'articles par page

        // Récupération des articles avec filtre
        if ($search) {
            $allArticles = $this->articleModel->rechercher($search);
        } elseif ($categorie) {
            $allArticles = $this->articleModel->getByCategorie($categorie);
        } else {
            $allArticles = $this->articleModel->getActifs();
        }

        $total = count($allArticles);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;

        // Articles de la page actuelle
        $articles = array_slice($allArticles, $offset, $perPage);

        // Pagination info pour la vue
        $pagination = (object)[
            'currentPage' => $page,
            'perPage' => $perPage,
            'totalItems' => $total,
            'totalPages' => $totalPages
        ];

        // ✅ Correction : getAll() à la place de getActives()
        $categories = $this->categorieModel->getAll();

        $this->view('articles.index', [
            'articles' => $articles,
            'categories' => $categories,
            'search' => $search,
            'categorie_selected' => $categorie,
            'pagination' => $pagination
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $reference = $this->articleModel->genererReference();

        // ✅ Correction : getAll() à la place de getActives()
        $categories = $this->categorieModel->getAll();

        $this->view('articles.create', [
            'reference' => $reference,
            'categories' => $categories
        ]);
    }

    /**
     * Enregistrer un nouvel article
     */
    public function store()
    {
        $valid = $this->request->validate([
            'reference' => 'required',
            'designation' => 'required',
            'prix_achat_ht' => 'required|numeric',
            'gain_pourcentage' => 'required|numeric'
        ]);

        if (!$valid) {
            $this->redirect('/articles/create');
            return;
        }

        $data = $this->request->post();

        if ($this->articleModel->referenceExists($data['reference'])) {
            $this->error('Cette référence existe déjà', '/articles/create');
            return;
        }

        Database::beginTransaction();

        try {
            $id = $this->articleModel->create($data);
            $this->historiqueModel->log('article', $id, 'CREATE', null, $data);
            Database::commit();
            $this->success('Article créé avec succès', '/articles');
        } catch (Exception $e) {
            Database::rollback();
            $this->error('Erreur lors de la création de l\'article', '/articles/create');
        }
    }

    /**
     * Afficher un article
     */
    public function show($id)
    {
        $article = $this->articleModel->getAvecDetails($id);

        if (!$article) {
            $this->error('Article introuvable', '/articles');
            return;
        }

        $stock = $this->stockModel->getByArticle($id);

        $this->view('articles.show', [
            'article' => $article,
            'stock' => $stock
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $article = $this->articleModel->find($id);

        if (!$article) {
            $this->error('Article introuvable', '/articles');
            return;
        }

        // ✅ Correction : getAll() à la place de getActives()
        $categories = $this->categorieModel->getAll();

        $this->view('articles.edit', [
            'article' => $article,
            'categories' => $categories
        ]);
    }

    /**
     * Mettre à jour un article
     */
    public function update($id)
    {
        $article = $this->articleModel->find($id);

        if (!$article) {
            $this->error('Article introuvable', '/articles');
            return;
        }

        $valid = $this->request->validate([
            'reference' => 'required',
            'designation' => 'required',
            'prix_achat_ht' => 'required|numeric',
            'gain_pourcentage' => 'required|numeric'
        ]);

        if (!$valid) {
            $this->redirect('/articles/' . $id . '/edit');
            return;
        }

        $data = $this->request->post();

        if ($this->articleModel->referenceExists($data['reference'], $id)) {
            $this->error('Cette référence existe déjà', '/articles/' . $id . '/edit');
            return;
        }

        $this->historiqueModel->log('article', $id, 'UPDATE', (array)$article, $data);
        $this->articleModel->update($id, $data);

        $this->success('Article modifié avec succès', '/articles/' . $id);
    }

    /**
     * Supprimer un article
     */
    public function delete($id)
    {
        $article = $this->articleModel->find($id);

        if (!$article) {
            $this->error('Article introuvable', '/articles');
            return;
        }

        // Plutôt que de supprimer, on désactive l'article
        $this->historiqueModel->log('article', $id, 'DELETE', (array)$article, null);
        $this->articleModel->update($id, ['actif' => 0]);

        $this->success('Article désactivé avec succès', '/articles');
    }
}
