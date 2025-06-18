
<?php

namespace App\Models;

class Tarefa
{
    private \PDO $connection;

    public ?int $id = null;
    public string $titulo = "";
    public string $descricao = "";
    public bool $status = false;
    public int $user_id = 0;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(): bool
    {
        $sql = "INSERT INTO tarefa (titulo, descricao, status, user_id)
                VALUES (:titulo, :descricao, :status, :user_id)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':titulo' => $this->titulo,
            ':descricao' => $this->descricao,
            ':status' => $this->status,
            ':user_id' => $this->user_id
        ]);
    }

    public function getTarefaById(int $id): ?array
    {
        $sql = "SELECT * FROM tarefa WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function update(): bool
    {
        $sql = "UPDATE tarefa SET titulo = :titulo, descricao = :descricao,
                       status = :status, user_id = :user_id WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':id' => $this->id,
            ':titulo' => $this->titulo,
            ':descricao' => $this->descricao,
            ':status' => $this->status,
            ':user_id' => $this->user_id
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tarefa WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getAllByUser(int $user_id): array
    {
        $sql = "SELECT * FROM tarefa WHERE user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }
}


?>
