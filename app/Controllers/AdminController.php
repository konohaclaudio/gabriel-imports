<?php
namespace App\Controllers;

use App\Models\Venda;
use App\Models\Produto; 

class AdminController {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function index() {
        // As classes agora serão carregadas sem erro
        // Acesso aos Models para buscar dados
        $vendaModel = new Venda(); 
        $produtoModel = new Produto();
        
        // Puxa os KPIs e variáveis necessários para a view
        // Se as classes Venda e Produto estiverem corretamente definidas, isso funciona.
        $kpis = $vendaModel->getVendasKPIs(); 
        
        $kpis['total_estoque'] = $produtoModel->getTotalEstoque(); 
        $kpis['count_produtos'] = $produtoModel->countProdutos(); 

        // Caminho corrigido para usar ROOT_PATH
        require ROOT_PATH . '/app/Views/admin/dashboard.php';
    }
}