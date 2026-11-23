<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) return;
    $path = dirname(__DIR__) . '/app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($path)) require $path;
});
require dirname(__DIR__) . '/app/Core/Helpers.php';

session_set_cookie_params(['httponly'=>true, 'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'samesite'=>'Lax']);
session_start();

use App\Controllers\AuthController;
use App\Controllers\TaskController;
use function App\Core\redirect;

$route = (string)($_GET['route'] ?? '');
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$routes = [
    'GET' => [
        'login'=>[AuthController::class,'loginPage'],
        'register'=>[AuthController::class,'registerPage'],
        'dashboard'=>[TaskController::class,'dashboard'],
        'tasks/create'=>[TaskController::class,'createPage'],
        'tasks/edit'=>[TaskController::class,'editPage'],
    ],
    'POST' => [
        'login'=>[AuthController::class,'login'],
        'register'=>[AuthController::class,'register'],
        'logout'=>[AuthController::class,'logout'],
        'tasks/store'=>[TaskController::class,'store'],
        'tasks/update'=>[TaskController::class,'update'],
        'tasks/delete'=>[TaskController::class,'delete'],
    ],
];
if ($route === '') redirect('dashboard');
if (!isset($routes[$method][$route])) { http_response_code(404); exit('Página não encontrada.'); }
[$class,$action] = $routes[$method][$route];
try { (new $class())->$action(); }
catch (PDOException $e) { error_log((string)$e); http_response_code(500); exit('Erro na conexão ou operação do banco. Confira a configuração e o MySQL.'); }
