<?php
namespace App\Models;

use App\Core\Database;
use PDO;

final class User
{
    public static function byEmail(string $email): ?array {
        $stmt = Database::getInstance()->connection()->prepare('SELECT id, name, email, password_hash FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }
    public static function create(string $name, string $email, string $password): int {
        $stmt = Database::getInstance()->connection()->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
        $stmt->execute([$name, $email, password_hash($password, PASSWORD_BCRYPT)]);
        return (int) Database::getInstance()->connection()->lastInsertId();
    }
}
