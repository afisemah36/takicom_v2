<?php

/**
 * Classe Router
 * Gestion du routage de l'application
 */

class Router
{
    private $routes = [];
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Ajouter une route GET
     */
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
        return $this;
    }

    /**
     * Ajouter une route POST
     */
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
        return $this;
    }

    /**
     * Ajouter une route PUT
     */
    public function put($path, $callback)
    {
        $this->addRoute('PUT', $path, $callback);
        return $this;
    }

    /**
     * Ajouter une route DELETE
     */
    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
        return $this;
    }

    /**
     * Ajouter une route
     */
    private function addRoute($method, $path, $callback)
    {
        $path = $this->normalizePath($path);
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
        ];
    }

    /**
     * Normaliser le chemin
     */
    private function normalizePath($path)
    {
        $path = trim($path, '/');
        return '/' . $path;
    }

    /**
     * Résoudre la route
     */
    public function resolve()
    {
        $method = $this->request->getMethod();
        $uri = $this->request->getUri();
        //var_dump($this->request->getUri());
        //die();
        foreach ($this->routes as $route) {
            // Vérifier la méthode
            if ($route['method'] !== $method) {
                continue;
            }

            // Convertir la route en regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            // Vérifier si la route correspond
            if (preg_match($pattern, $uri, $matches)) {
                // Extraire les paramètres
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return $this->executeCallback($route['callback'], $params);
            }
        }

        // Aucune route trouvée - 404
        Response::notFound();
    }

    /**
     * Exécuter le callback de la route
     */
    private function executeCallback($callback, $params = [])
    {
        // Si c'est une closure
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        // Si c'est Controller@method
        if (is_string($callback)) {
            $parts = explode('@', $callback);

            if (count($parts) !== 2) {
                throw new Exception("Format de callback invalide");
            }

            $controller = $parts[0];
            $method = $parts[1];

            if (!class_exists($controller)) {
                throw new Exception("Contrôleur {$controller} introuvable");
            }

            $controllerInstance = new $controller();

            if (!method_exists($controllerInstance, $method)) {
                throw new Exception("Méthode {$method} introuvable dans {$controller}");
            }

            return call_user_func_array([$controllerInstance, $method], $params);
        }

        throw new Exception("Type de callback non supporté");
    }
}
