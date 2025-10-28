<?php

/**
 * Classe Controller
 * Contrôleur de base dont tous les contrôleurs héritent
 */

class Controller
{
    protected $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    /**
     * Rendre une vue
     */
    protected function view($view, $data = [])
    {
        View::render($view, $data);
    }

    /**
     * Retourner du JSON
     */
    protected function json($data, $status = 200)
    {
        Response::json($data, $status);
    }

    /**
     * Rediriger
     */
    protected function redirect($path)
    {
        Response::redirect($path);
    }

    /**
     * Retourner avec succès
     */
    protected function success($message, $redirectTo = null)
    {
        Response::success($message, $redirectTo);
    }

    /**
     * Retourner avec erreur
     */
    protected function error($message, $redirectTo = null)
    {
        Response::error($message, $redirectTo);
    }

    /**
     * Vérifier si l'utilisateur est connecté
     */
    protected function requireAuth()
    {
        if (!Session::isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    /**
     * Obtenir l'utilisateur connecté
     */
    protected function user()
    {
        return Session::getUser();
    }
}
