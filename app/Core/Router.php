<?php
namespace App\Core;

class Router {
    public function dispatch() {
        $url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'home';
        $urlParts = explode('/', $url);

        // 1. Mapa de Controllers
        $rota = strtolower($urlParts[0]);
   $mapa = [
            'home'      => 'SiteController',
            'login'     => 'AuthController',
            'admin'     => 'AdminController',
            'produtos'  => 'ProdutoController',
            'barbeiros' => 'BarbeiroController',
            'vendas'    => 'VendaController',
            'categorias'=> 'CategoriaController',
            'servicos'  => 'ServicoController' // <-- NOVA ROTA
        ];

        // 2. Define o Controlador
        $controllerName = isset($mapa[$rota]) ? $mapa[$rota] : 'SiteController';
        $controllerClass = "App\\Controllers\\" . $controllerName;

        // 3. Define o Método
        $method = isset($urlParts[1]) ? $urlParts[1] : 'index';

        // 4. Verifica e Executa
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $method)) {
                // Passa os parâmetros restantes (ex: ID)
                call_user_func_array([$controller, $method], array_slice($urlParts, 2));
            } else {
                if(method_exists($controller, 'index')) {
                    $controller->index();
                } else {
                    echo "Erro 404: Método '$method' não encontrado.";
                }
            }
        } else {
            // Se o controller não existe (como o VendaController ainda não criado)
            echo "<div style='padding:50px; text-align:center; font-family:sans-serif;'>";
            echo "<h1>🚧 Módulo $controllerName em Construção</h1>";
            echo "<p>Crie este arquivo na pasta app/Controllers para continuar.</p>";
            echo "</div>";
        }
    }
}