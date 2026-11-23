<?php
namespace App\Core;

function e(?string $text): string { return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $route = ''): string { return 'index.php' . ($route !== '' ? '?route=' . urlencode($route) : ''); }
function redirect(string $route): never { header('Location: ' . url($route)); exit; }
function loggedIn(): bool { return isset($_SESSION['user_id']); }
function requireAuth(): void { if (!loggedIn()) redirect('login'); }
function csrf(): string {
    if (!isset($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function checkCsrf(): void {
    if (!isset($_POST['csrf']) || !hash_equals(csrf(), (string) $_POST['csrf'])) {
        http_response_code(419); exit('Formulário expirado. Volte e tente novamente.');
    }
}
function flash(string $type, string $message): void { $_SESSION['flash'] = ['type'=>$type, 'message'=>$message]; }
function takeFlash(): ?array { $value = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $value; }
function render(string $view, array $data = []): void {
    extract($data, EXTR_SKIP);
    ob_start();
    require dirname(__DIR__) . '/Views/' . $view . '.php';
    $content = ob_get_clean();
    require dirname(__DIR__) . '/Views/layouts/main.php';
}
