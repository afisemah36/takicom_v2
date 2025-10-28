<?php

/**
 * Classe Request
 * Gestion des requêtes HTTP
 */

class Request
{
    /**
     * Obtenir la méthode HTTP
     */
    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Obtenir l'URI
     */
    /**
     * Obtenir l'URI propre (sans le chemin du projet, sans query string)
     */
    /**
     * Obtenir l'URI propre (sans le chemin du projet, sans query string)
     */
    
    public function getUri()
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $uri = strtok($requestUri, '?'); // Supprime la query string

        // Obtenir le chemin racine du projet (ex: /takicom_v2)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $projectRoot = rtrim(dirname(dirname($scriptName)), '/');

        // Si on est à la racine du serveur (ex: http://monsite.com/), $projectRoot = ''
        if ($projectRoot === '/' || $projectRoot === '\\') {
            $projectRoot = '';
        }

        // Supprimer le chemin du projet de l'URI
        if ($projectRoot !== '' && strpos($uri, $projectRoot) === 0) {
            $uri = substr($uri, strlen($projectRoot));
        }

        // Normaliser l'URI
        if ($uri === '' || $uri === '/') {
            return '/';
        }

        // S'assurer qu'elle commence par /
        if ($uri[0] !== '/') {
            $uri = '/' . $uri;
        }

        return $uri;
    }

    /**
     * Obtenir toutes les données POST
     */
    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }

        return $_POST[$key] ?? $default;
    }

    /**
     * Obtenir toutes les données GET
     */
    public function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }

        return $_GET[$key] ?? $default;
    }

    /**
     * Obtenir toutes les données (GET + POST)
     */
    public function all()
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * Obtenir une donnée de la requête
     */
    public function input($key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    /**
     * Vérifier si une clé existe
     */
    public function has($key)
    {
        return isset($this->all()[$key]);
    }

    /**
     * Obtenir les données JSON du body
     */
    public function json()
    {
        $json = file_get_contents('php://input');
        return json_decode($json, true);
    }

    /**
     * Vérifier si c'est une requête AJAX
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Obtenir l'adresse IP du client
     */
    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Valider les données
     */
    public function validate($rules)
    {
        $errors = [];
        $data = $this->all();

        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);

            foreach ($rulesArray as $rule) {
                $ruleParts = explode(':', $rule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;

                // Required
                if ($ruleName === 'required' && empty($data[$field])) {
                    $errors[$field][] = "Le champ {$field} est requis";
                }

                // Email
                if ($ruleName === 'email' && !empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "Le champ {$field} doit être un email valide";
                }

                // Min length
                if ($ruleName === 'min' && !empty($data[$field]) && strlen($data[$field]) < $ruleValue) {
                    $errors[$field][] = "Le champ {$field} doit contenir au moins {$ruleValue} caractères";
                }

                // Max length
                if ($ruleName === 'max' && !empty($data[$field]) && strlen($data[$field]) > $ruleValue) {
                    $errors[$field][] = "Le champ {$field} ne doit pas dépasser {$ruleValue} caractères";
                }

                // Numeric
                if ($ruleName === 'numeric' && !empty($data[$field]) && !is_numeric($data[$field])) {
                    $errors[$field][] = "Le champ {$field} doit être numérique";
                }
            }
        }

        if (!empty($errors)) {
            Session::set('errors', $errors);
            Session::set('old', $data);
            return false;
        }

        return true;
    }
}
