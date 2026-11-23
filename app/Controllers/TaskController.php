<?php
namespace App\Controllers;

use App\Repositories\TaskRepository;
use function App\Core\{checkCsrf, flash, redirect, render, requireAuth};

final class TaskController
{
    private TaskRepository $tasks;
    public function __construct() { $this->tasks = new TaskRepository(); }
    private function uid(): int { return (int) $_SESSION['user_id']; }

    public function dashboard(): void {
        requireAuth(); $tasks = $this->tasks->allForUser($this->uid());
        $total = count($tasks); $done = count(array_filter($tasks, fn($t) => $t['status'] === 'concluida'));
        render('tasks/dashboard', ['title'=>'Meu painel', 'tasks'=>$tasks, 'total'=>$total, 'done'=>$done]);
    }
    public function createPage(): void { requireAuth(); render('tasks/form', ['title'=>'Nova tarefa', 'task'=>null]); }
    public function editPage(): void {
        requireAuth(); $task = $this->tasks->findForUser((int)($_GET['id'] ?? 0), $this->uid());
        if (!$task) { http_response_code(404); exit('Tarefa não encontrada.'); }
        render('tasks/form', ['title'=>'Editar tarefa', 'task'=>$task]);
    }
    private function fields(): array {
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $priority = (string)($_POST['priority'] ?? 'media');
        $status = (string)($_POST['status'] ?? 'pendente');
        $dueDate = trim((string)($_POST['due_date'] ?? ''));
        if ($title === '' || mb_strlen($title) > 140 || mb_strlen($description) > 2000 || !in_array($priority, ['baixa', 'media', 'alta'], true) || !in_array($status, ['pendente', 'concluida'], true) || ($dueDate !== '' && (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate) || !self::validDate($dueDate)))) {
            flash('error', 'Confira os dados: título obrigatório, campos e data válidos.'); redirect('dashboard');
        }
        return [$title, $description, $priority, $status, $dueDate !== '' ? $dueDate : null];
    }
    private static function validDate(string $date): bool {
        [$y,$m,$d] = array_map('intval', explode('-', $date));
        return checkdate($m, $d, $y);
    }
    public function store(): void {
        requireAuth(); checkCsrf(); [$title, $desc, $priority, , $due] = $this->fields();
        $this->tasks->create($this->uid(), $title, $desc, $priority, $due);
        flash('success', 'Tarefa adicionada com sucesso.'); redirect('dashboard');
    }
    public function update(): void {
        requireAuth(); checkCsrf(); $id=(int)($_POST['id'] ?? 0);
        if (!$this->tasks->findForUser($id, $this->uid())) { http_response_code(404); exit('Tarefa não encontrada.'); }
        [$title, $desc, $priority, $status, $due] = $this->fields();
        $this->tasks->update($id, $this->uid(), $title, $desc, $priority, $status, $due);
        flash('success', 'Tarefa atualizada.'); redirect('dashboard');
    }
    public function delete(): void {
        requireAuth(); checkCsrf(); $this->tasks->delete((int)($_POST['id'] ?? 0), $this->uid());
        flash('success', 'Tarefa excluída.'); redirect('dashboard');
    }
}
