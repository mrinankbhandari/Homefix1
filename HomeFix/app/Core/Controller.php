<?php

namespace App\Core;

abstract class Controller {
    protected function render(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);
        $user = Auth::user();

        // Flash messages
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        $flashInfo = $_SESSION['flash_info'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error'], $_SESSION['flash_info']);

        $viewFile = dirname(__DIR__) . "/Views/{$view}.php";
        if (!file_exists($viewFile)) {
            die("View not found: {$view}");
        }

        if ($layout === 'none') {
            require $viewFile;
            return;
        }

        // Layout wrapper
        $headerFile = dirname(__DIR__) . "/Views/layouts/header.php";
        $navbarFile = dirname(__DIR__) . "/Views/layouts/navbar.php";
        $footerFile = dirname(__DIR__) . "/Views/layouts/footer.php";
        $alertFile  = dirname(__DIR__) . "/Views/layouts/alert.php";

        require $headerFile;
        require $navbarFile;
        require $alertFile;
        require $viewFile;
        require $footerFile;
    }

    protected function json(array $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $page, array $params = []): void {
        $query = http_build_query(array_merge(['page' => $page], $params));
        header("Location: index.php?{$query}");
        exit;
    }

    protected function flash(string $type, string $message): void {
        $_SESSION["flash_{$type}"] = $message;
    }

    protected function getPost(): array {
        return $_POST;
    }

    protected function getGet(): array {
        return $_GET;
    }

    protected function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf(): bool {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        return !empty($token) && hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }
}
