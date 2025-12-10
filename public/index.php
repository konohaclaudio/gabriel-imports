<?php
session_start();

// 1. CARREGAR CONFIGURAÇÕES
// Usa __DIR__ para voltar uma pasta e acessar config/config.php
require_once __DIR__ . '/../config/config.php';

// 2. AUTOLOAD
require_once ROOT_PATH . '/app/Core/Database.php'; 

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT_PATH . '/app/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) require $file;
});

// 3. ROTEAMENTO
$basePath = parse_url(BASE_URL, PHP_URL_PATH); 
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

$parts = explode('/', trim($uri, '/'));
$controllerName = $parts[0] ?: 'home';
$methodName = $parts[1] ?? 'index';
$param = $parts[2] ?? null;

// 4. MAPA DE ROTAS (CORRIGIDO)
switch ($controllerName) {
    // --- ROTAS PÚBLICAS ---
    case 'loja':
        $controller = new \App\Controllers\SiteController();
        $controller->loja();
        break;

    case 'home':
        $controller = new \App\Controllers\SiteController();
        $controller->index();
        break;
    
    // --- ROTAS ADMINISTRATIVAS ---

    // Rota de Login (usa AuthController)
    case 'login':
        $controller = new \App\Controllers\AuthController();
        if ($methodName === 'login') {
            $controller->login(); // Processa a submissão do formulário
        } elseif ($methodName === 'logout') {
            $controller->logout();
        } else {
            $controller->index(); // Exibe o formulário (rota /login)
        }
        break;

    // Rota Admin/Dashboard (aliases)
    case 'admin':
    case 'dashboard':
        $controller = new \App\Controllers\AdminController();
        $controller->index();
        break;

    // Módulos Administrativos
    case 'barbeiros':
        $controller = new \App\Controllers\BarbeiroController();
        if ($methodName == 'novo') $controller->novo($param);
        elseif ($methodName == 'salvar') $controller->salvar();
        elseif ($methodName == 'precos') $controller->precos($param);
        elseif ($methodName == 'salvarPrecos') $controller->salvarPrecos();
        else $controller->index();
        break;

    case 'produtos':
        $controller = new \App\Controllers\ProdutoController();
        if ($methodName == 'novo') $controller->novo();
        elseif ($methodName == 'editar') $controller->editar($param);
        elseif ($methodName == 'salvar') $controller->salvar();
        elseif ($methodName == 'deletar') $controller->deletar($param);
        else $controller->index();
        break;

    // Rota Padrão (Fallback)
    default:
        $controller = new \App\Controllers\SiteController();
        $controller->index();
        break;
}