<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Produto {

    // --- Métodos de Buscas e KPIs ---

    public function getById($id, $incluirInativos = false) {
        $conn = Database::getConnection();
        $sql = "SELECT p.*, c.nome as categoria_nome, p.preco, p.preco_promo, p.imagem_path, p.estoque, p.ativo 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id 
                WHERE p.id = ?";
        
        if (!$incluirInativos) {
            $sql .= " AND p.ativo = 1";
        }
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Método principal de listagem (agora filtra por ativo por padrão, a menos que especificado)
    public function listar($search = null, $minStock = 0, $incluirInativos = false) {
        $conn = Database::getConnection();
        
        $sql = "SELECT p.*, c.nome as categoria_nome, p.preco, p.preco_promo, p.imagem_path, p.ativo 
                FROM produtos p 
                LEFT JOIN categorias c ON p.categoria_id = c.id";
        
        $where = " WHERE p.estoque >= :minStock";
        $params = ['minStock' => $minStock];

        // Filtro de Ativo (EXCLUI inativos por padrão, a menos que a flag seja TRUE)
        if (!$incluirInativos) {
            $where .= " AND p.ativo = 1";
        }
        
        if ($search) {
            $where .= " AND (p.nome LIKE :search OR p.descricao LIKE :search)";
            $params['search'] = '%' . $search . '%';
        }
        
        $sql .= $where . " ORDER BY p.id DESC";
        
        $stmt = $conn->prepare($sql);

        foreach ($params as $key => &$val) {
            if ($key === 'search') {
                $stmt->bindParam(":$key", $val);
            } else {
                $stmt->bindParam(":$key", $val, PDO::PARAM_INT);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalEstoque() {
        $conn = Database::getConnection();
        // Contagem apenas de produtos ativos (ativos=1)
        return (int) $conn->query("SELECT SUM(estoque) FROM produtos WHERE ativo = 1")->fetchColumn();
    }

    public function countProdutos() {
        $conn = Database::getConnection();
        // Contagem apenas de produtos ativos (ativos=1)
        return (int) $conn->query("SELECT COUNT(id) FROM produtos WHERE ativo = 1")->fetchColumn();
    }

    // --- Métodos de CRUD (Salvar, Ativar/Desativar) ---
    public function salvar($dados) {
        $conn = Database::getConnection();
        
        // Mantém a lógica de INSERT/UPDATE
        if (!empty($dados['id'])) {
            $sql = "UPDATE produtos SET nome = :nome, categoria_id = :cat, preco = :preco, preco_promo = :promo, estoque = :estoque, imagem_path = :img, ativo = :ativo WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $dados['id'] = (int) $dados['id']; 
        } else {
            // Adiciona 'ativo' ao INSERT (novo produto é sempre ativo = 1)
            $sql = "INSERT INTO produtos (nome, categoria_id, preco, preco_promo, estoque, imagem_path, ativo) VALUES (:nome, :cat, :preco, :promo, :estoque, :img, 1)";
            $stmt = $conn->prepare($sql);
        }
        
        $params = [
            'nome' => $dados['nome'], 'cat' => $dados['categoria_id'], 'preco' => $dados['preco'], 
            'promo' => $dados['preco_promo'], 'estoque' => $dados['estoque'], 'img' => $dados['imagem_path'],
        ];

        if (!empty($dados['id'])) {
            $params['id'] = $dados['id'];
            // Inclui o status 'ativo' no UPDATE (necessário se o formulário permitir reativar)
            $params['ativo'] = $dados['ativo'] ?? 1; 
        }

        return $stmt->execute($params);
    }
    
    // MÉTODO 'DELETAR' AGORA É 'SOFT DELETE' (DESATIVAR)
    public function deletar($id) {
        $conn = Database::getConnection();
        // Ação: Define ativo = 0
        $stmt = $conn->prepare("UPDATE produtos SET ativo = 0 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    // NOVO MÉTODO PARA REATIVAR O PRODUTO
    public function reativar($id) {
        $conn = Database::getConnection();
        // Ação: Define ativo = 1
        $stmt = $conn->prepare("UPDATE produtos SET ativo = 1 WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}