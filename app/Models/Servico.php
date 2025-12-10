<?php
namespace App\Models;
use App\Core\Database;
use PDO;

class Servico {
    
    public function listar() {
        $conn = Database::getConnection();
        return $conn->query("SELECT * FROM servicos ORDER BY nome ASC")->fetchAll();
    }

    public function getById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM servicos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function salvar($dados) {
        $conn = Database::getConnection();
        
        if (!empty($dados['id'])) {
            // UPDATE
            $sql = "UPDATE servicos SET nome = :nome, duracao_minutos = :duracao WHERE id = :id";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                'nome' => $dados['nome'],
                'duracao' => $dados['duracao_minutos'],
                'id' => $dados['id']
            ]);
        } else {
            // INSERT
            $sql = "INSERT INTO servicos (nome, duracao_minutos) VALUES (:nome, :duracao)";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([
                'nome' => $dados['nome'],
                'duracao' => $dados['duracao_minutos']
            ]);
        }
    }

    public function deletar($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("DELETE FROM servicos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}