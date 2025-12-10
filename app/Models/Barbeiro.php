<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Barbeiro {

    // --- CRUD BÁSICO ---

    public function listar() {
        $conn = Database::getConnection();
        return $conn->query("SELECT * FROM barbeiros ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $conn = Database::getConnection();
        $stmt = $conn->prepare("SELECT * FROM barbeiros WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function salvar($dados) {
        $conn = Database::getConnection();

        // Se tem ID, é UPDATE
        if (!empty($dados['id'])) {
            $sql = "UPDATE barbeiros SET nome = :nome, especialidade = :esp, bio = :bio, telefone = :tel, foto_path = :foto WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $dados['id'] = (int) $dados['id'];
        } 
        // Se não tem ID, é INSERT
        else {
            $sql = "INSERT INTO barbeiros (nome, especialidade, bio, telefone, foto_path) VALUES (:nome, :esp, :bio, :tel, :foto)";
            $stmt = $conn->prepare($sql);
            unset($dados['id']); // Remove ID para o insert
        }

        return $stmt->execute([
            'nome' => $dados['nome'],
            'esp' => $dados['especialidade'],
            'bio' => $dados['bio'],
            'tel' => $dados['telefone'],
            'foto' => $dados['foto_path'],
            ...(!empty($dados['id']) ? ['id' => $dados['id']] : [])
        ]);
    }

    public function excluir($id) {
        $conn = Database::getConnection();
        
        // Primeiro deleta os preços associados para não dar erro de chave estrangeira (se houver)
        $stmtPrecos = $conn->prepare("DELETE FROM precos_barbeiros WHERE barbeiro_id = ?");
        $stmtPrecos->execute([$id]);

        // Deleta o barbeiro
        $stmt = $conn->prepare("DELETE FROM barbeiros WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // --- LÓGICA DE PREÇOS (MANTIDA DO SEU SISTEMA ORIGINAL) ---
    
    public function listarPrecosPorBarbeiro($barbeiroId) {
        $conn = Database::getConnection();
        $sql = "
            SELECT s.id as servico_id, s.nome as servico_nome,
                   pb.valor as preco_barbeiro, pb.id as preco_id
            FROM servicos s
            LEFT JOIN precos_barbeiros pb 
            ON s.id = pb.servico_id AND pb.barbeiro_id = :barbeiro_id
            ORDER BY s.nome ASC
        ";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['barbeiro_id' => $barbeiroId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvarPreco($barbeiroId, $servicoId, $valor) {
        $conn = Database::getConnection();
        
        $stmt = $conn->prepare("SELECT id FROM precos_barbeiros WHERE barbeiro_id = ? AND servico_id = ?");
        $stmt->execute([$barbeiroId, $servicoId]);
        $precoExistente = $stmt->fetch();

        if ($precoExistente) {
            $sql = "UPDATE precos_barbeiros SET valor = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$valor, $precoExistente['id']]);
        } else {
            $sql = "INSERT INTO precos_barbeiros (barbeiro_id, servico_id, valor) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            return $stmt->execute([$barbeiroId, $servicoId, $valor]);
        }
    }
}