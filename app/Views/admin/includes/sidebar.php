<?php 
// Pega o URI atual para checar qual link está ativo
$currentUri = $_SERVER['REQUEST_URI'] ?? ''; 
$currentRoute = trim(str_replace(BASE_URL, '/', $currentUri), '/'); // Remove a base da URL

/**
 * Função aprimorada para checar se o link está ativo.
 * Retorna classes de estado ativo com borda lateral e background sutil.
 */
function isActive($check) {
    global $currentRoute;
    
    // Checagem principal: A rota atual começa com o termo de checagem?
    if (strpos($currentRoute, $check) === 0) {
        // Estado Ativo: Fundo sutil e borda lateral destacada
        return 'bg-[#C8A165]/20 text-[#C8A165] font-semibold border-l-4 border-[#C8A165]';
    }
    
    // Tratamento especial para o Dashboard
    if ($check === 'admin' && ($currentRoute === 'admin' || $currentRoute === 'home' || $currentRoute === '')) {
        return 'bg-[#C8A165]/20 text-[#C8A165] font-semibold border-l-4 border-[#C8A165]';
    }
    
    // Estado Padrão: Efeito hover suave
    return 'text-gray-300 hover:text-white hover:bg-[#333] transition-colors duration-200';
}
?>

<aside class="w-64 bg-[#1A1A1A] border-r border-[#333] flex flex-col h-full flex-shrink-0">
    
    <div class="h-16 flex items-center px-6 border-b border-[#333]">
        <div class="flex items-center gap-3">
            <img src="<?= BASE_URL ?>assets/img/gblogopretofinal.jpg" alt="Logo Gabriel Imports" 
                 class="h-8 w-8 object-contain rounded-full border border-[#C8A165]/50">
            <span class="text-xl font-extrabold text-[#C8A165] tracking-widest">G. IMPORTS</span>
        </div>
    </div>

    <nav class="flex-1 p-3 space-y-1.5 overflow-y-auto">
        
        <a href="<?= BASE_URL ?>admin" class="flex items-center gap-4 px-3 py-2.5 rounded-lg transition-all <?= isActive('admin') ?>">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <a href="<?= BASE_URL ?>produtos" class="flex items-center gap-4 px-3 py-2.5 rounded-lg transition-all <?= isActive('produtos') ?>">
            <i data-lucide="package" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">Produtos & Estoque</span>
        </a>

        <a href="<?= BASE_URL ?>categorias" class="flex items-center gap-4 px-3 py-2.5 rounded-lg transition-all <?= isActive('categorias') ?>">
            <i data-lucide="tags" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">Categorias</span>
        </a>

        <a href="<?= BASE_URL ?>barbeiros" class="flex items-center gap-4 px-3 py-2.5 rounded-lg transition-all <?= isActive('barbeiros') ?>">
            <i data-lucide="scissors" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">Barbeiros & Preços</span>
        </a>
        
        <a href="<?= BASE_URL ?>servicos" class="flex items-center gap-4 px-3 py-2.5 rounded-lg transition-all <?= isActive('servicos') ?>">
            <i data-lucide="hand-platter" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">Lista de Serviços</span>
        </a>

        <a href="<?= BASE_URL ?>vendas" class="flex items-center gap-4 px-3 py-2.5 rounded-lg transition-all <?= isActive('vendas') ?>">
            <i data-lucide="receipt-text" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">Vendas (PDV)</span>
        </a>
    </nav>

    <div class="p-4 border-t border-[#333] flex-shrink-0">
        <span class="block text-xs text-gray-500 mb-2">Logado como: <strong class="text-white"><?= $_SESSION['admin_nome'] ?? 'Admin' ?></strong></span>
        
        <a href="<?= BASE_URL ?>login/logout" class="flex items-center gap-4 px-3 py-2 text-red-400 hover:bg-red-900/30 rounded-lg transition-colors">
            <i data-lucide="log-out" class="w-5 h-5 flex-shrink-0"></i>
            <span class="font-medium">Sair</span>
        </a>
    </div>
</aside>