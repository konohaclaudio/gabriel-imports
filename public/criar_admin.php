<?php
require_once '../config/config.php';
require_once '../app/Core/Database.php'; // Usa a conexão do nosso sistema

use App\Core\Database;

try {
    $conn = Database::getConnection();

    // Limpa usuários antigos
    $conn->query("DELETE FROM admin_users");

    // Cria novo usuário
    $senha = password_hash('123', PASSWORD_DEFAULT); // Senha segura
    
    $stmt = $conn->prepare("INSERT INTO admin_users (nome, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute(['Gabriel Admin', 'admin@gabrielimports.com', $senha]);

    echo "<h1 style='color:green; font-family:sans-serif;'>✅ Usuário Admin Criado!</h1>";
    echo "<p>Email: admin@gabrielimports.com</p>";
    echo "<p>Senha: 123</p>";
    echo "<br><a href='".BASE_URL."login'>IR PARA O LOGIN</a>";

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}