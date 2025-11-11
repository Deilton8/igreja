<?php
namespace App\Core;

class Router
{
    private $routes = [];

    /**
     * Adiciona uma rota
     */
    private function addRoute($method, $uri, $action)
    {
        $uri = rtrim($uri, "/");
        if ($uri === '') {
            $uri = '/';
        }
        $this->routes[$method][$uri] = $action;
    }

    /**
     * Obtém a URI atual, tratando subdiretórios
     */
    private function getCurrentUri()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);

        // Remove o prefixo do script (caso esteja em subdiretório)
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }

        $uri = '/' . trim($uri, '/');
        return $uri === '//' ? '/' : $uri;
    }

    /**
     * Registra rota GET
     */
    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    /**
     * Registra rota POST
     */
    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    /**
     * Dispara a rota correta
     */
    public function dispatch()
    {
        $uri = $this->getCurrentUri();
        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "405 - Método não permitido";
            exit;
        }

        foreach ($this->routes[$method] as $route => $action) {
            // Converte {param} em regex
            $pattern = preg_replace("/\{[a-zA-Z_]+\}/", "([0-9a-zA-Z_-]+)", $route);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // remove a string completa da URI

                [$controller, $methodAction] = explode('@', $action);
                $controller = "App\\Modules\\" . $controller; // namespace ajustado

                if (!class_exists($controller)) {
                    http_response_code(500);
                    echo "500 - Controlador não encontrado: {$controller}";
                    exit;
                }

                $instance = new $controller();

                if (!method_exists($instance, $methodAction)) {
                    http_response_code(500);
                    echo "500 - Método não encontrado: {$methodAction} em {$controller}";
                    exit;
                }

                return call_user_func_array([$instance, $methodAction], $matches);
            }
        }

        http_response_code(404);
        echo "404 - Página não encontrada ({$uri})";
    }
}