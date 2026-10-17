<?php

namespace App\Core;

class Router {
    private array $routes = [];

    public function get(string $page, string $controllerAction): void {
        $this->routes['GET'][$page] = $controllerAction;
    }

    public function post(string $page, string $controllerAction): void {
        $this->routes['POST'][$page] = $controllerAction;
    }

    public function any(string $page, string $controllerAction): void {
        $this->routes['GET'][$page] = $controllerAction;
        $this->routes['POST'][$page] = $controllerAction;
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'];
        $page = $_GET['page'] ?? 'home';

        // Normalize trailing slashes
        $page = trim($page, '/');
        if ($page === '') {
            $page = 'home';
        }

        $handler = $this->routes[$method][$page] ?? null;

        // If not found in current method, check other method or 404
        if (!$handler) {
            http_response_code(404);
            echo "<div style='font-family:sans-serif; text-align:center; padding:50px;'><h1>404 - Page Not Found</h1><p>The page <b>" . htmlspecialchars($page) . "</b> could not be found.</p><a href='index.php?page=home' style='display:inline-block; padding:10px 20px; background:#6c5ce7; color:#fff; text-decoration:none; border-radius:8px; font-weight:bold;'>Back to Home</a></div>";
            return;
        }

        [$controllerClass, $action] = explode('@', $handler);
        $fullControllerClass = "App\\Controllers\\{$controllerClass}";

        if (!class_exists($fullControllerClass)) {
            die("Controller class {$fullControllerClass} does not exist.");
        }

        $controller = new $fullControllerClass();
        if (!method_exists($controller, $action)) {
            die("Action {$action} not found on controller {$fullControllerClass}.");
        }

        $controller->$action();
    }
}
