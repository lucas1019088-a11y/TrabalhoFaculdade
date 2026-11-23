<?php
namespace App\Controllers;

use App\Models\User;
use PDOException;
use function App\Core\{checkCsrf, flash, loggedIn, redirect, render};

final class AuthController
{
    public function loginPage(): void { if (loggedIn()) redirect('dashboard'); render('auth/login', ['title'=>'Entrar']); }
    public function registerPage(): void { if (loggedIn()) redirect('dashboard'); render('auth/register', ['title'=>'Criar conta']); }
    public function login(): void {
        checkCsrf();
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $user = User::byEmail($email);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            flash('error', 'E-mail ou senha inválidos.'); redirect('login');
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = $user['name'];
        redirect('dashboard');
    }
    public function register(): void {
        checkCsrf();
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        if (mb_strlen($name) < 2 || mb_strlen($name) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            flash('error', 'Informe nome, e-mail válido e senha com ao menos 8 caracteres.'); redirect('register');
        }
        if (User::byEmail($email)) { flash('error', 'Este e-mail já está cadastrado.'); redirect('register'); }
        try { $id = User::create($name, $email, $password); }
        catch (PDOException $e) {
            if ($e->getCode() === '23000') { flash('error', 'Este e-mail já está cadastrado.'); redirect('register'); }
            throw $e;
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id; $_SESSION['user_name'] = $name;
        flash('success', 'Conta criada! Comece adicionando sua primeira tarefa.');
        redirect('dashboard');
    }
    public function logout(): void {
        checkCsrf();
        $_SESSION = [];
        session_regenerate_id(true);
        redirect('login');
    }
}
