<?php
namespace App\Controllers;

use App\Core\Database;

class SiteController {

    public function index() {
        // Carrega a Home Institucional
        require ROOT_PATH . '/app/Views/site/home.php';
    }

    public function loja() {
        // Carrega a Loja (Catálogo)
        require ROOT_PATH . '/app/Views/site/loja.php';
    }
}