<?php

/**
 * Classe Response
 * Gestion des réponses HTTP
 */

class Response
{
    /**
     * Retourner une réponse JSON
     */
    public static function json($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redirection
     */
    public static function redirect($path)
    {
        // S'assurer que le chemin commence par /
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }

        // Calculer le chemin de base de l'application (ex: /takicom_v2)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = rtrim(dirname(dirname($scriptName)), '/');

        // Construire l'URL complète
        $location = $basePath . $path;

        header("Location: " . $location);
        exit;
    }

    /**
     * Retourner avec succès
     */
    public static function success($message, $redirectTo = null)
    {
        Session::set('success', $message);

        if ($redirectTo) {
            self::redirect($redirectTo);
        }
    }

    /**
     * Retourner avec erreur
     */
    public static function error($message, $redirectTo = null)
    {
        Session::set('error', $message);

        if ($redirectTo) {
            self::redirect($redirectTo);
        }
    }

    /**
     * Page 404
     */
    public static function notFound()
    {
        http_response_code(404);
        View::render('errors/404');
        exit;
    }

    /**
     * Erreur serveur 500
     */
    public static function serverError($message = "Erreur serveur")
    {
        http_response_code(500);
        View::render('errors/500', ['message' => $message]);
        exit;
    }

    /**
     * Non autorisé 401
     */
    public static function unauthorized()
    {
        http_response_code(401);
        View::render('errors/401');
        exit;
    }

    /**
     * Interdit 403
     */
    public static function forbidden()
    {
        http_response_code(403);
        View::render('errors/403');
        exit;
    }

    /**
     * Définir le code de statut HTTP
     */
    public static function setStatusCode($code)
    {
        http_response_code($code);
    }
}
