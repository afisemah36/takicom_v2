<?php

/**
 * Classe Session
 * Gestion des sessions
 */

class Session
{
    /**
     * Définir une valeur en session
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Obtenir une valeur de session
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Vérifier si une clé existe
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Supprimer une clé
     */
    public static function delete($key)
    {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Obtenir et supprimer (flash)
     */
    public static function flash($key, $default = null)
    {
        $value = self::get($key, $default);
        self::delete($key);
        return $value;
    }

    /**
     * Détruire la session
     */
    public static function destroy()
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Régénérer l'ID de session
     */
    public static function regenerate()
    {
        session_regenerate_id(true);
    }

    /**
     * Définir l'utilisateur connecté
     */
    public static function setUser($user)
    {
        self::set('user', $user);
        self::set('user_id', $user->id_utilisateur);
    }

    /**
     * Obtenir l'utilisateur connecté
     */
    public static function getUser()
    {
        return self::get('user');
    }

    /**
     * Vérifier si un utilisateur est connecté
     */
    public static function isLoggedIn()
    {
        return self::has('user_id');
    }

    /**
     * Déconnecter l'utilisateur
     */
    public static function logout()
    {
        self::delete('user');
        self::delete('user_id');
    }
}
