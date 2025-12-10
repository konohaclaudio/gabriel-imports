<?php
namespace App\Controllers;

use App\Models\Venda;
use App\Models\Produto;

class VendaController {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function index() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->listar(null, 1, false); 
        
        require \ROOT_PATH . '/app/Views/admin/vendas_pdv.php';
    }
    
    public function historico() {
        $vendaModel = new Venda();
        $vendas = $vendaModel->listarVendas();
        
        require \ROOT_PATH . '/app/Views/admin/vendas_historico.php';
    }

    /**
     * Visualiza os detalhes de uma venda específica.
     * Rota: /vendas/detalhe/{id}
     * @param int $id ID da venda
     */
    public function detalhe($id) {
        $vendaModel = new Venda();
        $venda = $vendaModel->getVendaDetalhada($id);

        if (!$venda) {
            header('Location: ' . BASE_URL . 'vendas/historico?error=venda_nao_encontrada');
            exit;
        }
        
        require \ROOT_PATH . '/app/Views/admin/vendas_detalhe.php';
    }

    /**
     * Exclui (cancela) a venda e reverte o estoque.
     * Rota: /vendas/excluir/{id}
     * @param int $id ID da venda a ser excluída
     */
    public function excluir($id) {
        $vendaModel = new Venda();
        
        if ($vendaModel->excluirVenda($id)) {
            header('Location: ' . BASE_URL . 'vendas/historico?status=cancelado');
            exit;
        } else {
            header('Location: ' . BASE_URL . 'vendas/historico?error=falha_cancelamento');
            exit;
        }
    }


    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $vendaData = json_decode($_POST['venda_data'] ?? '{}', true);
            
            if (empty($vendaData['itens']) || $vendaData['total'] <= 0) {
                header('Location: ' . BASE_URL . 'vendas?error=1');
                exit;
            }

            $vendaModel = new Venda();
            
            try {
                $dadosVenda = [
                    'cliente_nome' => $vendaData['cliente_nome'] ?? 'Balcão',
                    'total' => $vendaData['total'],
                    'forma_pagamento' => $vendaData['forma_pagamento'] ?? 'dinheiro'
                ];
                
                $vendaModel->registrarVenda($dadosVenda, $vendaData['itens']);

                header('Location: ' . BASE_URL . 'vendas?success=1');
                exit;

            } catch (\Exception $e) {
                header('Location: ' . BASE_URL . 'vendas?error=2&msg=' . urlencode('Falha ao registrar venda. Verifique o estoque.'));
                exit;
            }
        }
    }
}