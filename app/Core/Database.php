<?php
namespace App\Core;

use PDO;

/** Singleton: uma instância da conexão por execução PHP. */
final class Database
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $config = require dirname(__DIR__, 2) . '/config/config.php';
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['db_host'], $config['db_port'], $config['db_name']);
        $this->pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function connection(): PDO { return $this->pdo; }
    private function __clone() {}
    public function __wakeup(): void { throw new \LogicException('Não é permitido desserializar o Singleton.'); }
}
