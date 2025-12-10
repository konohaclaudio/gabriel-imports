<?php
namespace App\Controllers;
use App\Models\Categoria;

class CategoriaController {

    public function __construct() {
        if (!isset($_SESSION['admin_id'])) { header('Location: ' . BASE_URL . 'login'); exit; }
    }

    public function index() {
        $model = new Categoria();
        $categorias = $model->listar();
        require '../app/Views/admin/categorias_list.php';
    }

    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Categoria();
            $model->salvar($_POST['nome'], $_POST['slug']);
            header('Location: ' . BASE_URL . 'categorias');
        }
    }

    public function deletar($id) {
        $model = new Categoria();
        $model->deletar($id);
        header('Location: ' . BASE_URL . 'categorias');
    }
}