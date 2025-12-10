<?php
namespace App\Controllers;

use App\Core\Database;
use PDO;

class AuthController {

    // Exibe o formulário de login (/login)
    public function index() {
        if (isset($_SESSION['admin_id'])) {
            header('Location: ' . BASE_URL . 'admin');
            exit;
        }
        // Caminho corrigido para usar ROOT_PATH
        require ROOT_PATH . '/app/Views/login/index.php'; // Assumindo login.php está em app/Views/login/
    }

    // Processa os dados do formulário (/login/login)
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $senha = $_POST['senha'] ?? '';

            $conn = Database::getConnection();
            
            // Busca o usuário pelo email
            $stmt = $conn->prepare("SELECT * FROM admin_users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            // Verifica a senha (Hash)
            if ($user && password_verify($senha, $user['password_hash'])) {
                // SUCESSO: Cria a sessão
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_nome'] = $user['nome'];
                
                header('Location: ' . BASE_URL . 'admin');
                exit;
            } else {
                // ERRO
                $erro = "Email ou senha incorretos.";
                // Caminho corrigido para usar ROOT_PATH
                require ROOT_PATH . '/app/Views/login/index.php';
            }
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'login');
        exit;
    }
}