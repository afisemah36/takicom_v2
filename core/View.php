<?php

/**
 * Classe View
 * Moteur de rendu des vues
 */

class View
{
    /**
     * Rendre une vue
     */
    public static function render($view, $data = [])
    {
        $viewPath = APP . '/Views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new Exception("Vue {$view} introuvable");
        }

        // Extraire les données pour les rendre accessibles dans la vue
        extract($data);

        // Capturer le contenu de la vue
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Si un layout est défini, l'inclure
        if (isset($layout)) {
            $layoutPath = APP . '/Views/layouts/' . $layout . '.php';

            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Rendre une vue partielle
     */
    public static function partial($partial, $data = [])
    {
        $partialPath = APP . '/Views/partials/' . $partial . '.php';

        if (!file_exists($partialPath)) {
            throw new Exception("Partiel {$partial} introuvable");
        }

        extract($data);
        require $partialPath;
    }

    /**
     * Inclure une section
     */
    public static function section($name)
    {
        if (isset($GLOBALS['sections'][$name])) {
            echo $GLOBALS['sections'][$name];
        }
    }

    /**
     * Démarrer une section
     */
    public static function startSection($name)
    {
        $GLOBALS['current_section'] = $name;
        ob_start();
    }

    /**
     * Terminer une section
     */
    public static function endSection()
    {
        $content = ob_get_clean();
        $name = $GLOBALS['current_section'];
        $GLOBALS['sections'][$name] = $content;
    }
}
