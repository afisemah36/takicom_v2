<?php

/**
 * Fonctions utilitaires globales
 */

/**
 * Générer une URL
 */
/**
 * Génère une URL absolue vers une route de l'application
 * Fonctionne automatiquement avec ou sans sous-dossier (ex: /takicom_v2)
 */
function url($path = '')
{
    // Nettoyer le chemin
    $path = trim($path, '/');
    if ($path !== '') {
        $path = '/' . $path;
    }

    // Obtenir le chemin racine du projet (ex: /takicom_v2)
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $basePath = dirname(dirname($scriptName));

    // Normaliser : éviter / à la fin, et gérer la racine du serveur
    if ($basePath === '/' || $basePath === '\\') {
        $basePath = '';
    } else {
        $basePath = rtrim($basePath, '/');
    }

    return $basePath . $path;
}

/**
 * Générer une URL d'asset
 */
function asset($path)
{
    return url($path);
}

/**
 * Échapper les caractères HTML
 */
function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * Dump et die (pour le debug)
 */
function dd(...$vars)
{
    echo '<pre>';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Afficher les anciennes valeurs (après validation)
 */
function old($key, $default = '')
{
    $old = Session::get('old', []);
    return $old[$key] ?? $default;
}

/**
 * Afficher les erreurs de validation
 */
function error($key)
{
    $errors = Session::get('errors', []);
    if (isset($errors[$key])) {
        return '<span class="error">' . implode('<br>', $errors[$key]) . '</span>';
    }
    return '';
}

/**
 * Afficher le message de succès
 */
function successMessage()
{
    if ($message = Session::flash('success')) {
        return '<div class="alert alert-success">' . e($message) . '</div>';
    }
    return '';
}

/**
 * Afficher le message d'erreur
 */
function errorMessage()
{
    if ($message = Session::flash('error')) {
        return '<div class="alert alert-danger">' . e($message) . '</div>';
    }
    return '';
}

/**
 * Formater un montant en DT
 */
function formatMoney($amount, $decimals = 3)
{
    return number_format($amount, $decimals, ',', ' ') . ' DT';
}

/**
 * Formater une date
 */
function formatDate($date, $format = 'd/m/Y')
{
    if (empty($date)) {
        return '';
    }
    return date($format, strtotime($date));
}
/**
 * Définir un message de succès
 */
function setSuccessMessage($message)
{
    Session::flash('success', $message);
}

/**
 * Définir un message d'erreur
 */
function setErrorMessage($message)
{
    Session::flash('error', $message);
}

/**
 * Définir un message d'avertissement
 */
function setWarningMessage($message)
{
    Session::flash('warning', $message);
}
/**
 * Vérifier si la requête est AJAX
 */
function isAjax()
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
        && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Envoyer une réponse JSON
 */
function jsonResponse($data, $statusCode = 200)
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
/**
 * Définir un message d'information
 */
function setInfoMessage($message)
{
    Session::flash('info', $message);
}

/**
 * Formater une date et heure
 */
function formatDateTime($datetime, $format = 'd/m/Y H:i')
{
    if (empty($datetime)) {
        return '';
    }
    return date($format, strtotime($datetime));
}

/**
 * Générer un token CSRF
 */
function csrf_token()
{
    if (!Session::has('csrf_token')) {
        Session::set('csrf_token', bin2hex(random_bytes(32)));
    }
    return Session::get('csrf_token');
}

/**
 * Champ CSRF caché
 */
function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Vérifier le token CSRF
 */
function verify_csrf($token)
{
    return hash_equals(Session::get('csrf_token'), $token);
}

/**
 * Rediriger vers une URL
 */
function redirect($path)
{
    Response::redirect($path);
}

/**
 * Vérifier si l'utilisateur est connecté
 */
function auth()
{
    return Session::getUser();
}

/**
 * Générer un numéro de document
 */
function generateDocNumber($prefix, $year, $number, $length = 5)
{
    return $prefix . '-' . $year . '-' . str_pad($number, $length, '0', STR_PAD_LEFT);
}

/**
 * Calculer le montant TTC
 */
function calculateTTC($ht, $tva)
{
    return $ht + ($ht * $tva / 100);
}

/**
 * Calculer le montant HT depuis TTC
 */
function calculateHT($ttc, $tva)
{
    return $ttc / (1 + $tva / 100);
}

/**
 * Calculer le montant de la TVA
 */
function calculateTVA($ht, $tva)
{
    return $ht * $tva / 100;
}

/**
 * Générer un code aléatoire
 */
function generateCode($prefix = '', $length = 8)
{
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $prefix . $code;
}

/**
 * Paginer un tableau
 */
function paginate($items, $perPage = 10, $currentPage = 1)
{
    $total = count($items);
    $totalPages = ceil($total / $perPage);
    $offset = ($currentPage - 1) * $perPage;

    return [
        'data' => array_slice($items, $offset, $perPage),
        'current_page' => $currentPage,
        'per_page' => $perPage,
        'total' => $total,
        'total_pages' => $totalPages,
        'has_more' => $currentPage < $totalPages
    ];
}
