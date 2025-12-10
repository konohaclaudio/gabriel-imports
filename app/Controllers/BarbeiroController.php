<?php
namespace App\Controllers;

use App\Models\Barbeiro;

class BarbeiroController {

    public function __construct() {
        // Verifica sessão de admin
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    // LISTAGEM
    public function index() {
        $barbeiroModel = new Barbeiro();
        $barbeiros = $barbeiroModel->listar();
        require \ROOT_PATH . '/app/Views/admin/barbeiros_list.php';
    }

    // FORMULÁRIO (NOVO/EDITAR)
    public function novo($id = null) {
        $barbeiro = null;
        if ($id) {
            $model = new Barbeiro();
            $barbeiro = $model->getById($id);
            if (!$barbeiro) {
                header('Location: ' . BASE_URL . 'barbeiros');
                exit;
            }
        }
        require \ROOT_PATH . '/app/Views/admin/barbeiros_form.php';
    }

    // SALVAR
    public function salvar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $id = $_POST['id'] ?? null;
            $fotoPath = $_POST['foto_path_atual'] ?? null; 

            // Upload de Imagem
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0 && !empty($_FILES['foto']['name'])) {
                $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $novoNome = "barber_" . uniqid() . "." . $ext;
                $pastaDestino = \ROOT_PATH . '/public/assets/img/'; // Ajustado para a pasta que a Home usa
                
                if (!is_dir($pastaDestino)) {
                    mkdir($pastaDestino, 0777, true);
                }
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $pastaDestino . $novoNome)) {
                    $fotoPath = 'assets/img/' . $novoNome;
                }
            }

            $dados = [
                'id' => $id,
                'nome' => $_POST['nome'],
                'especialidade' => $_POST['especialidade'],
                'bio' => $_POST['bio'],
                'telefone' => $_POST['telefone'], // Novo campo
                'foto_path' => $fotoPath
            ];

            $barbeiroModel = new Barbeiro();
            $barbeiroModel->salvar($dados);

            header('Location: ' . BASE_URL . 'barbeiros?success=1');
            exit;
        }
    }

    // EXCLUIR
    public function excluir($id) {
        $model = new Barbeiro();
        $model->excluir($id);
        header('Location: ' . BASE_URL . 'barbeiros?deleted=1');
        exit;
    }

    // --- MÉTODOS DE PREÇOS (MANTIDOS) ---

    public function precos($barbeiroId) {
        $barbeiroModel = new Barbeiro();
        $barbeiro = $barbeiroModel->getById($barbeiroId);
        
        if (!$barbeiro) die("Barbeiro não encontrado.");
        
        $servicosPrecos = $barbeiroModel->listarPrecosPorBarbeiro($barbeiroId);
        require \ROOT_PATH . '/app/Views/admin/barbeiros_precos.php';
    }

    public function salvarPrecos() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $barbeiroId = $_POST['barbeiro_id'];
            $precos = $_POST['precos'];

            $barbeiroModel = new Barbeiro();
            foreach ($precos as $servicoId => $valor) {
                if ($valor !== '') {
                    $valorLimpo = str_replace(',', '.', $valor);
                    $barbeiroModel->salvarPreco($barbeiroId, $servicoId, $valorLimpo);
                }
            }
            header('Location: ' . BASE_URL . 'barbeiros/precos/' . $barbeiroId . '?success=1');
            exit;
        }
    }
}