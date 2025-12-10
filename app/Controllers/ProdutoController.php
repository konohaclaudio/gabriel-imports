<?php
namespace App\Controllers;

use App\Models\Produto;

class ProdutoController {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function index() {
        $searchQuery = $_GET['q'] ?? null;
        $produtoModel = new Produto();
        
        // Listagem padrão: busca apenas produtos ATIVOS (ativo = 1)
        // Usamos listar($search, 0, false) ou criamos um método buscarTodosAtivos()
        $produtos = $produtoModel->listar($searchQuery, 0, false); 
        $search = $searchQuery;

        require \ROOT_PATH . '/app/Views/admin/produtos_list.php';
    }

    // NOVA FUNÇÃO: Listar produtos inativos/arquivados
    public function arquivados() {
        $searchQuery = $_GET['q'] ?? null;
        $produtoModel = new Produto();
        
        // Listagem de arquivados: busca TODOS (incluirInativos = true) e filtra na view se necessário
        $produtos = $produtoModel->listar($searchQuery, 0, true); 
        $search = $searchQuery;

        require \ROOT_PATH . '/app/Views/admin/produtos_arquivados_list.php'; // Uma nova view pode ser necessária aqui
    }


    public function novo() {
        $produto = null; 
        require \ROOT_PATH . '/app/Views/admin/produtos_form.php';
    }

    public function editar($id) {
        $produtoModel = new Produto();
        // Deve ser capaz de editar produtos inativos também (incluirInativos = true)
        $produto = $produtoModel->getById($id, true);
        
        if (!$produto) {
             header('Location: ' . BASE_URL . 'produtos');
             exit;
        }
        require \ROOT_PATH . '/app/Views/admin/produtos_form.php';
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'] ?? null;
            $imagemPath = $_POST['imagem_path_atual'] ?? null; 

            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0 && !empty($_FILES['foto']['name'])) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $novoNome = "prod_" . uniqid() . "." . $ext;
                $pastaDestino = \ROOT_PATH . '/public/uploads/produtos/'; 
                
                if (!is_dir($pastaDestino)) {
                    mkdir($pastaDestino, 0777, true);
                }
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $pastaDestino . $novoNome)) {
                    $imagemPath = 'uploads/produtos/' . $novoNome;
                }
            }

            $precoPromo = !empty($_POST['preco_promo']) ? $_POST['preco_promo'] : null;
            // Captura o status ativo/inativo, se estiver no formulário de edição
            $ativo = $_POST['ativo'] ?? 1; 

            $produto = new Produto();
            $produto->salvar([
                'id' => $id, 'nome' => $_POST['nome'], 'categoria_id' => $_POST['categoria_id'],
                'preco' => $_POST['preco'], 'preco_promo' => $precoPromo, 'estoque' => $_POST['estoque'],
                'imagem_path' => $imagemPath, 'ativo' => $ativo // Passa o status 'ativo'
            ]);

            header('Location: ' . BASE_URL . 'produtos');
        }
    }

    // MÉTODO 'DELETAR' AGORA É 'SOFT DELETE' (DESATIVAÇÃO)
    public function deletar($id) {
        $produto = new Produto();
        $produto->deletar($id);
        header('Location: ' . BASE_URL . 'produtos?status=desativado');
    }
    
    // NOVO MÉTODO PARA REATIVAR O PRODUTO
    public function reativar($id) {
        $produto = new Produto();
        $produto->reativar($id);
        header('Location: ' . BASE_URL . 'produtos?status=reativado');
    }
}