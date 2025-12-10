<?php
// Arquivo: public/fix_login.php
require_once '../config/config.php';
require_once '../app/Core/Database.php';

use App\Core\Database;

echo "<body style='font-family: sans-serif; background: #111; color: #fff; padding: 40px;'>";
echo "<h1>🔧 Ferramenta de Correção de Login</h1>";

try {
    $db = Database::getInstance();

    // 1. LIMPEZA TOTAL: Apaga todos os usuários admin para não ter duplicatas ou lixo
    $db->query("DELETE FROM admin_users");
    echo "<p style='color: yellow;'>🧹 Usuários antigos removidos...</p>";

    // 2. CRIAÇÃO: Cria o admin oficial
    $email = 'admin@gabrielimports.com';
    $senhaPura = '123'; // Senha simples para garantir
    $senhaHash = password_hash($senhaPura, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO admin_users (nome, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute(['Admin Supremo', $email, $senhaHash]);
    
    echo "<p style='color: green;'>✅ Novo Admin criado com sucesso!</p>";

    // 3. PROVA REAL: Testa o login agora mesmo via código
    echo "<h3>🕵️ Teste Automático de Verificação:</h3>";
    
    // Busca o usuário que acabamos de criar
    $check = $db->prepare("SELECT * FROM admin_users WHERE email = ?");
    $check->execute([$email]);
    $user = $check->fetch();

    if ($user && password_verify($senhaPura, $user['password_hash'])) {
        echo "<div style='border: 2px solid green; padding: 20px; border-radius: 10px; background: #1a4d1a;'>";
        echo "<h2>SUCESSO TOTAL! 🚀</h2>";
        echo "<p>O sistema confirmou que a senha funciona.</p>";
        echo "<hr style='border-color: #555;'>";
        echo "<p><strong>Email:</strong> admin@gabrielimports.com</p>";
        echo "<p><strong>Senha:</strong> 123</p>";
        echo "<br>";
        echo "<a href='" . BASE_URL . "login' style='background: #C8A165; color: black; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>CLIQUE AQUI PARA LOGAR AGORA</a>";
        echo "</div>";
    } else {
        echo "<h2 style='color: red;'>❌ Algo muito estranho aconteceu. O teste falhou.</h2>";
    }

} catch (Exception $e) {
    echo "<h2 style='color: red;'>Erro Crítico: " . $e->getMessage() . "</h2>";
}
echo "</body>";
?>