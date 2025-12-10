<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class Venda {

    public function getVendasKPIs() {
        $conn = Database::getConnection();
        $mesAtual = date('m'); $anoAtual = date('Y');

        // Note: Se você implementar o Soft Delete para vendas, adicione o filtro: WHERE status != 'CANCELADA'
        $totalGeral = $conn->query("SELECT SUM(total) FROM vendas")->fetchColumn() ?: 0;
        
        $stmtMes = $conn->prepare("SELECT SUM(total) FROM vendas WHERE MONTH(created_at) = :mes AND YEAR(created_at) = :ano");
        $stmtMes->execute(['mes' => $mesAtual, 'ano' => $anoAtual]);
        $totalMes = $stmtMes->fetchColumn() ?: 0;

        $countVendas = $conn->query("SELECT COUNT(id) FROM vendas")->fetchColumn() ?: 0;

        return [
            'total_geral' => (float) $totalGeral, 'total_mes' => (float) $totalMes, 'count_vendas' => (int) $countVendas
        ];
    }
    
    public function listarVendas() {
        $conn = Database::getConnection();
        // Note: Se você implementar o Soft Delete para vendas, adicione o filtro: WHERE status != 'CANCELADA'
        $sql = "SELECT id, cliente_nome, total, forma_pagamento, created_at FROM vendas ORDER BY created_at DESC";
        return $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrarVenda($dadosVenda, $itens) {
        $conn = Database::getConnection();
        
        try {
            $conn->beginTransaction();

            // 1. Inserir a Venda Principal
            $sqlVenda = "INSERT INTO vendas (cliente_nome, total, forma_pagamento) VALUES (?, ?, ?)";
            $stmtVenda = $conn->prepare($sqlVenda);
            $stmtVenda->execute([
                $dadosVenda['cliente_nome'], $dadosVenda['total'], $dadosVenda['forma_pagamento']
            ]);
            
            $vendaId = $conn->lastInsertId();

            // 2. Inserir Itens e Dar Baixa no Estoque
            $sqlItem = "INSERT INTO itens_venda (venda_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)";
            $sqlBaixa = "UPDATE produtos SET estoque = estoque - ? WHERE id = ?";

            foreach ($itens as $item) {
                // Insere o item na tabela de itens_venda
                $stmtItem = $conn->prepare($sqlItem);
                $stmtItem->execute([$vendaId, $item['produto_id'], $item['quantidade'], $item['preco_unitario']]);

                // Dá baixa no estoque do produto
                $stmtBaixa = $conn->prepare($sqlBaixa);
                $stmtBaixa->execute([$item['quantidade'], $item['produto_id']]);
            }
            
            $conn->commit();
            return true;

        } catch (PDOException $e) {
            $conn->rollBack();
            // Em ambiente de produção, registre $e->getMessage() em um arquivo de log
            throw new \Exception("Erro ao registrar a venda: " . $e->getMessage());
        }
    }

    /**
     * Busca os detalhes de uma venda e seus itens.
     * @param int $vendaId ID da venda.
     * @return array|null Venda detalhada ou null se não encontrada.
     */
    public function getVendaDetalhada($vendaId) {
        $conn = Database::getConnection();

        // 1. Busca a Venda Principal
        $sqlVenda = "SELECT * FROM vendas WHERE id = ?";
        $stmtVenda = $conn->prepare($sqlVenda);
        $stmtVenda->execute([$vendaId]);
        $venda = $stmtVenda->fetch(PDO::FETCH_ASSOC);

        if (!$venda) {
            return null;
        }

        // 2. Busca os Itens da Venda, fazendo JOIN com produtos para obter o nome
        $sqlItens = "SELECT iv.*, p.nome as produto_nome 
                     FROM itens_venda iv
                     JOIN produtos p ON iv.produto_id = p.id
                     WHERE iv.venda_id = ?";
        $stmtItens = $conn->prepare($sqlItens);
        $stmtItens->execute([$vendaId]);
        $itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);

        $venda['itens'] = $itens;
        return $venda;
    }

    /**
     * Exclui (cancela) a venda e reverte o estoque dos produtos.
     * @param int $vendaId ID da venda a ser excluída/cancelada.
     * @return bool
     */
    public function excluirVenda($vendaId) {
        $conn = Database::getConnection();
        $conn->beginTransaction();
        
        try {
            // 1. Obter itens da venda antes de deletar
            $venda = $this->getVendaDetalhada($vendaId);
            if (!$venda || empty($venda['itens'])) {
                $conn->rollBack();
                return false;
            }

            // 2. Reverter o estoque (CRÍTICO!)
            foreach ($venda['itens'] as $item) {
                // Adiciona a quantidade de volta ao estoque
                $sqlEstoque = "UPDATE produtos SET estoque = estoque + ? WHERE id = ?";
                $stmtEstoque = $conn->prepare($sqlEstoque);
                $stmtEstoque->execute([$item['quantidade'], $item['produto_id']]);
            }

            // 3. Excluir a Venda e seus Itens (HARD DELETE)
            // Exclui os itens da venda (deve vir primeiro devido à FK)
            $sqlDelItens = "DELETE FROM itens_venda WHERE venda_id = ?";
            $stmtDelItens = $conn->prepare($sqlDelItens);
            $stmtDelItens->execute([$vendaId]);

            // Exclui a venda principal
            $sqlDelVenda = "DELETE FROM vendas WHERE id = ?";
            $stmtDelVenda = $conn->prepare($sqlDelVenda);
            $stmtDelVenda->execute([$vendaId]);
            
            $conn->commit();
            return true;

        } catch (PDOException $e) {
            $conn->rollBack();
            // Em ambiente de produção, logar o $e->getMessage() aqui.
            return false;
        } catch (\Exception $e) {
            $conn->rollBack();
            // Outros erros
            return false;
        }
    }
}