<?php
namespace App\Repositories;

use App\Core\Database;
use PDO;

/** Repository Pattern: centraliza consultas e persistência de tarefas. */
final class TaskRepository
{
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance()->connection(); }

    public function allForUser(int $userId): array {
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE user_id = ? ORDER BY (status = \'concluida\') ASC, created_at DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    public function findForUser(int $id, int $userId): ?array {
        $stmt = $this->db->prepare('SELECT * FROM tasks WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch() ?: null;
    }
    public function create(int $userId, string $title, string $description, string $priority, ?string $dueDate): void {
        $stmt = $this->db->prepare('INSERT INTO tasks (user_id, title, description, priority, due_date) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$userId, $title, $description, $priority, $dueDate]);
    }
    public function update(int $id, int $userId, string $title, string $description, string $priority, string $status, ?string $dueDate): void {
        $stmt = $this->db->prepare('UPDATE tasks SET title=?, description=?, priority=?, status=?, due_date=? WHERE id=? AND user_id=?');
        $stmt->execute([$title, $description, $priority, $status, $dueDate, $id, $userId]);
    }
    public function delete(int $id, int $userId): void {
        $stmt = $this->db->prepare('DELETE FROM tasks WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }
}
