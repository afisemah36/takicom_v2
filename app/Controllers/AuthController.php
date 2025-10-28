<?php

/**
 * Contrôleur Auth
 */

class AuthController extends Controller
{
    private $utilisateurModel;

    public function __construct()
    {
        parent::__construct();
        $this->utilisateurModel = new Utilisateur();
    }

    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login()
    {
        // Validation
        $valid = $this->request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);

        if (!$valid) {
            $this->redirect('/login');
            return;
        }

        $login = $this->request->post('login');
        $password = $this->request->post('password');

        // Authentifier
        $user = $this->utilisateurModel->authentifier($login, $password);

        if ($user) {
            // Enregistrer l'utilisateur en session
            Session::setUser($user);

            // Logger l'action
            $historique = new Historique();
            $historique->log('utilisateur', $user->id_utilisateur, 'LOGIN');

            $this->success('Connexion réussie', '/dashboard');
        } else {
            $this->error('Login ou mot de passe incorrect', '/login');
        }
    }

    /**
     * Déconnexion
     */
    public function logout()
    {
        // Logger l'action avant de détruire la session
        if (Session::isLoggedIn()) {
            $historique = new Historique();
            $historique->log('utilisateur', Session::get('user_id'), 'LOGOUT');
        }

        Session::logout();
        $this->redirect('/login');
    }
    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegisterForm()
    {
        // Si déjà connecté, rediriger vers le dashboard
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register()
    {
        // Validation des données
        $valid = $this->request->validate([
            'nom' => 'required|max:100',
            'prenom' => 'required|max:100',
            'email' => 'required|email|max:150',
            'login' => 'required|min:3|max:50',
            'password' => 'required|min:6'
        ]);

        if (!$valid) {
            $this->redirect('/register');
            return;
        }

        $nom = $this->request->post('nom');
        $prenom = $this->request->post('prenom');
        $email = $this->request->post('email');
        $login = $this->request->post('login');
        $password = $this->request->post('password');

        // Vérifier si le login ou email existe déjà
        if ($this->utilisateurModel->findByLogin($login)) {
            $this->error("Ce nom d'utilisateur est déjà utilisé", '/register');
            return;
        }

        if ($this->utilisateurModel->findByEmail($email)) {
            $this->error("Cet email est déjà utilisé", '/register');
            return;
        }

        // Hacher le mot de passe
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Créer l'utilisateur (par défaut, rôle utilisateur = 2, actif = 1)
        $data = [
            'id_role' => 2, // ← adapte selon ta logique (1 = admin, 2 = user)
            'login' => $login,
            'password_hash' => $passwordHash,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'telephone' => $this->request->post('telephone') ?? '',
            'actif' => 1,
            'date_creation' => date('Y-m-d H:i:s'),
            'date_modification' => date('Y-m-d H:i:s')
        ];

        if ($this->utilisateurModel->create($data)) {
            $this->success("Compte créé avec succès ! Vous pouvez vous connecter.", '/login');
        } else {
            $this->error("Une erreur est survenue lors de la création du compte.", '/register');
        }
    }
}
