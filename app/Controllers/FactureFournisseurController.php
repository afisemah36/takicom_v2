<?php

class FactureFournisseurController extends Controller
{
    private $factureFournisseurModel;
    private $fournisseurModel;
    private $articleModel;

    public function __construct()
    {
        $this->factureFournisseurModel = new FactureFournisseur();
        $this->fournisseurModel = new Fournisseur();
        $this->articleModel = new Article();
    }

    /**
     * Liste des factures fournisseur
     */
    public function index()
    {
        $factures = $this->factureFournisseurModel->getAllAvecFournisseur();

        $this->view('factures-fournisseur.index', [
            'factures' => $factures
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $fournisseurs = $this->fournisseurModel->getAll();
        $articles = $this->articleModel->getAll();

        $this->view('factures-fournisseur.create', [
            'fournisseurs' => $fournisseurs,
            'articles' => $articles
        ]);
    }

    /**
     * Enregistrer une nouvelle facture
     */
    public function store()
    {
        try {
            // Validation
            $required = ['id_fournisseur', 'numero_facture', 'date_facture'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Le champ $field est requis");
                }
            }

            // Vérifier qu'il y a des lignes
            if (empty($_POST['lignes']) || !is_array($_POST['lignes'])) {
                throw new Exception("Aucune ligne de facture");
            }

            // Préparer les données de la facture
            $dataFacture = [
                'id_fournisseur' => $_POST['id_fournisseur'],
                'id_utilisateur' => auth()->id_utilisateur,
                'numero_facture' => $_POST['numero_facture'],
                'date_facture' => $_POST['date_facture'],
                'date_echeance' => $_POST['date_echeance'] ?? null,
                'montant_ht' => $_POST['montant_ht'],
                'montant_tva' => $_POST['montant_tva'],
                'montant_ttc' => $_POST['montant_ttc'],
                'total_remise' => $_POST['total_remise'],
                'statut' => 'brouillon',
                'mode_reglement' => $_POST['mode_reglement'] ?? 'virement',
                'notes' => $_POST['notes'] ?? null
            ];

            // Préparer les lignes
            $lignes = [];
            foreach ($_POST['lignes'] as $ligne) {
                // Ignorer les lignes vides
                if (empty($ligne['quantite']) || empty($ligne['prix_unitaire'])) {
                    continue;
                }

                $lignes[] = [
                    'id_article' => $ligne['id_article'] ?? null,
                    'designation' => $ligne['designation'] ?? null,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'taux_tva' => $ligne['taux_tva'] ?? 0,
                    'taux_remise' => $ligne['taux_remise'] ?? 0,
                    'montant_ht' => $ligne['montant_ht'],
                    'montant_tva' => $ligne['montant_tva'],
                    'montant_ttc' => $ligne['montant_ttc'],
                    'montant_remise' => $ligne['montant_remise']
                ];
            }

            if (empty($lignes)) {
                throw new Exception("Aucune ligne valide dans la facture");
            }

            // Créer la facture
            $id = $this->factureFournisseurModel->creerAvecLignes($dataFacture, $lignes);

            setSuccessMessage("Facture fournisseur créée avec succès");
            redirect('/factures-fournisseur/' . $id);
        } catch (Exception $e) {
            setErrorMessage("Erreur : " . $e->getMessage());
            redirect('/factures-fournisseur/create');
        }
    }

    /**
     * Afficher une facture
     */
    public function show($id)
    {
        $facture = $this->factureFournisseurModel->getAvecDetails($id);

        if (!$facture) {
            setErrorMessage("Facture introuvable");
            redirect('/factures-fournisseur');
            return;
        }

        $lignes = $this->factureFournisseurModel->getLignes($id);

        $this->view('factures-fournisseur.show', [
            'facture' => $facture,
            'lignes' => $lignes
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $facture = $this->factureFournisseurModel->getAvecDetails($id);

        if (!$facture) {
            setErrorMessage("Facture introuvable");
            redirect('/factures-fournisseur');
            return;
        }

        // On ne peut modifier que les factures en brouillon
        if ($facture->statut !== 'brouillon') {
            setErrorMessage("Seules les factures en brouillon peuvent être modifiées");
            redirect('/factures-fournisseur/' . $id);
            return;
        }

        $fournisseurs = $this->fournisseurModel->getAll();
        $articles = $this->articleModel->getAll();
        $lignes = $this->factureFournisseurModel->getLignes($id);

        $this->view('factures-fournisseur.edit', [
            'facture' => $facture,
            'lignes' => $lignes,
            'fournisseurs' => $fournisseurs,
            'articles' => $articles
        ]);
    }

    /**
     * Mettre à jour une facture
     */
    public function update($id)
    {
        try {
            $facture = $this->factureFournisseurModel->find($id);

            if (!$facture) {
                throw new Exception("Facture introuvable");
            }

            if ($facture->statut !== 'brouillon') {
                throw new Exception("Seules les factures en brouillon peuvent être modifiées");
            }

            // Validation
            $required = ['id_fournisseur', 'numero_facture', 'date_facture'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Le champ $field est requis");
                }
            }

            // Préparer les données
            $dataFacture = [
                'id_fournisseur' => $_POST['id_fournisseur'],
                'numero_facture' => $_POST['numero_facture'],
                'date_facture' => $_POST['date_facture'],
                'date_echeance' => $_POST['date_echeance'] ?? null,
                'montant_ht' => $_POST['montant_ht'],
                'montant_tva' => $_POST['montant_tva'],
                'montant_ttc' => $_POST['montant_ttc'],
                'total_remise' => $_POST['total_remise'],
                'mode_reglement' => $_POST['mode_reglement'] ?? 'virement',
                'notes' => $_POST['notes'] ?? null
            ];

            // Préparer les lignes
            $lignes = [];
            foreach ($_POST['lignes'] as $ligne) {
                if (empty($ligne['quantite']) || empty($ligne['prix_unitaire'])) {
                    continue;
                }

                $lignes[] = [
                    'id_article' => $ligne['id_article'] ?? null,
                    'designation' => $ligne['designation'] ?? null,
                    'quantite' => $ligne['quantite'],
                    'prix_unitaire' => $ligne['prix_unitaire'],
                    'taux_tva' => $ligne['taux_tva'] ?? 0,
                    'taux_remise' => $ligne['taux_remise'] ?? 0,
                    'montant_ht' => $ligne['montant_ht'],
                    'montant_tva' => $ligne['montant_tva'],
                    'montant_ttc' => $ligne['montant_ttc'],
                    'montant_remise' => $ligne['montant_remise']
                ];
            }

            // Mettre à jour
            $this->factureFournisseurModel->updateAvecLignes($id, $dataFacture, $lignes);

            setSuccessMessage("Facture modifiée avec succès");
            redirect('/factures-fournisseur/' . $id);
        } catch (Exception $e) {
            setErrorMessage("Erreur : " . $e->getMessage());
            redirect('/factures-fournisseur/' . $id . '/edit');
        }
    }

    /**
     * Changer le statut d'une facture
     */
    public function changerStatut($id)
    {
        try {
            if (!isset($_POST['statut'])) {
                throw new Exception("Statut non spécifié");
            }

            $this->factureFournisseurModel->changerStatut($id, $_POST['statut']);

            if (isAjax()) {
                jsonResponse(['success' => true, 'message' => 'Statut modifié']);
            } else {
                setSuccessMessage("Statut modifié avec succès");
                redirect('/factures-fournisseur/' . $id);
            }
        } catch (Exception $e) {
            if (isAjax()) {
                jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            } else {
                setErrorMessage("Erreur : " . $e->getMessage());
                redirect('/factures-fournisseur/' . $id);
            }
        }
    }

    /**
     * Supprimer une facture
     */
    public function delete($id)
    {
        try {
            $facture = $this->factureFournisseurModel->find($id);

            if (!$facture) {
                throw new Exception("Facture introuvable");
            }

            // Seules les factures en brouillon peuvent être supprimées
            if ($facture->statut !== 'brouillon') {
                throw new Exception("Seules les factures en brouillon peuvent être supprimées");
            }

            $this->factureFournisseurModel->supprimerAvecStock($id);

            if (isAjax()) {
                jsonResponse(['success' => true, 'message' => 'Facture supprimée']);
            } else {
                setSuccessMessage("Facture supprimée avec succès");
                redirect('/factures-fournisseur');
            }
        } catch (Exception $e) {
            if (isAjax()) {
                jsonResponse(['success' => false, 'message' => $e->getMessage()]);
            } else {
                setErrorMessage("Erreur : " . $e->getMessage());
                redirect('/factures-fournisseur');
            }
        }
    }

    /**
     * Imprimer une facture (PDF)
     */
    public function imprimer($id)
    {
        $facture = $this->factureFournisseurModel->getAvecDetails($id);

        if (!$facture) {
            setErrorMessage("Facture introuvable");
            redirect('/factures-fournisseur');
            return;
        }

        $lignes = $this->factureFournisseurModel->getLignes($id);

        $this->view('factures-fournisseur.pdf', [
            'facture' => $facture,
            'lignes' => $lignes
        ]);
    }
}
