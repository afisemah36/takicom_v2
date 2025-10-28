<?php

/**
 * Contrôleur Home
 */

class HomeController extends Controller
{
    /**
     * Page d'accueil
     */
    public function index()
    {
        // Si l'utilisateur est connecté, rediriger vers le dashboard
        if (Session::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        // Sinon, rediriger vers la page de connexion
        $this->redirect('/login');
    }
}
