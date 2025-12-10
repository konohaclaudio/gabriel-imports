<?php
namespace App\Controllers;
use App\Models\Servico; // <-- A linha que busca a classe

class ServicoController {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) { header('Location: ' . BASE_URL . 'login'); exit; }
    }

    public function index() {
        // Linha 12 (onde o erro ocorreu)
        $model = new Servico(); 
        $servicos = $model->listar();
        require \ROOT_PATH . '/app/Views/admin/servicos_list.php';
    }

    public function novo() {
        $servico = null;
        require \ROOT_PATH . '/app/Views/admin/servicos_form.php';
    }
    
    public function editar($id) {
        $model = new Servico();
        $servico = $model->getById($id);
        if (!$servico) { header('Location: ' . BASE_URL . 'servicos'); exit; }
        require \ROOT_PATH . '/app/Views/admin/servicos_form.php';
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Servico();
            $model->salvar([
                'id' => $_POST['id'] ?? null,
                'nome' => $_POST['nome'],
                'duracao_minutos' => $_POST['duracao_minutos']
            ]);
            header('Location: ' . BASE_URL . 'servicos');
        }
    }

    public function deletar($id) {
        $model = new Servico();
        $model->deletar($id);
        header('Location: ' . BASE_URL . 'servicos');
    }
}