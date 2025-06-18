<?php 
namespace App\Database;

use PDO;

class Mariadb {
    private string $host = "localhost"; // endereco
    private string $dbname = "my_tarefas"; // nome para o banco
    private string $username = "root"; // user do banco
    private string $password = "123456"; // senha do banco
    private ?\PDO $connection = null; // conexao com o banco


    public function __construct() {
        try {
            $this->connection = new \PDO(
                "mysql:host={$this->host};dbname={$this->dbname};
                charset=utf8",
            $this->username,
            $this->password,
            [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
        } catch (\PDOException $e) {
            die("Erro de conexão: ". $e->getMessage());
        }
    }

    public function getConnection(): ?\PDO {
        return $this->connection;
    }


}



?>