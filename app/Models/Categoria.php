<?php
namespace App\Models;
use App\Core\Database;
use PDO;

class Categoria {
    public function listar() {
        $conn = Database::getConnection();
        return $conn->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll();
    }

    public function salvar($nome, $slug) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("INSERT INTO categorias (nome, slug) VALUES (?, ?)");
        return $stmt->execute([$nome, $slug]);
    }

    public function deletar($id) {
        $conn = Database::getConnection();
        // Cuidado: Excluir uma categoria pode quebrar produtos vinculados (Foreign Key).
        // Em sistemas reais, faríamos um "Soft Delete" (mudar ativo=0).
        $stmt = $conn->prepare("DELETE FROM categorias WHERE id = ?");
        return $stmt->execute([$id]);
    }
}