<?php 
namespace App\Models;

class Usuario
{
    private \PDO $connection;

    public ?int $id = null;
    public string $nome = '';
    public string $login = '';
    public string $senha = '';
    public string $email = '';
    public string $foto_path = '';

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function createUser(): bool
    {
        $sql = "INSERT INTO usuario (nome, login, senha, email, foto_path)
                VALUES (:nome, :login, :senha, :email, :foto_path)";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':nome' => $this->nome,
            ':login' => $this->login,
            ':senha' => password_hash($this->senha, PASSWORD_DEFAULT),
            ':email' => $this->email,
            ':foto_path' => $this->foto_path
        ]);
    }

    public function getByUserId(int $id): ?array 
    {
        $sql = "SELECT * FROM usuario WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);
        $resultado = $stmt->fetch();
        if($resultado) {
         unset($resultado['senha']);
         return $resultado;
        }


        return [];
    }

    public function update(): bool
    {
        $sql = "UPDATE usuario SET 
                    nome = :nome, 
                    login = :login,
                    senha = :senha, 
                    email = :email, 
                    foto_path = :foto_path 
                WHERE id = :id";
        
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            ':id' => $this->id,
            ':nome' => $this->nome,
            ':login' => $this->login,
            ':senha' => password_hash($this->senha, PASSWORD_DEFAULT),
            ':email' => $this->email,
            ':foto_path' => $this->foto_path
        ]);
    }

    public function deleteUser(int $id): bool
    {
        $sql = "DELETE FROM usuario WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}