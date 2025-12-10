<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">
    
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="mb-8 p-6 bg-[#1A1A1A] rounded-xl shadow-2xl border border-[#333] sticky top-0 z-10">
            <h1 class="text-3xl font-extrabold text-[#C8A165] tracking-wide">Bem-vindo, <?= $_SESSION['admin_nome'] ?? 'Admin' ?>!</h1>
            <p class="text-gray-400 mt-1">Visão geral e desempenho do Gabriel Imports.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            
            <div class="bg-[#1A1A1A] border border-[#C8A165] p-6 rounded-xl shadow-lg transform hover:scale-[1.01] transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-gray-400 uppercase text-xs font-bold">Total Mês (Vendas)</p>
                    <i data-lucide="trending-up" class="text-[#C8A165] w-6 h-6"></i>
                </div>
                <h3 class="text-4xl font-extrabold mt-3 text-[#C8A165]">R$ <?= number_format($kpis['total_mes'] ?? 0, 2, ',', '.') ?></h3>
                <p class="text-xs text-gray-400 mt-1">Total de <?= $kpis['count_vendas'] ?? 0 ?> transações.</p>
            </div>

            <div class="bg-[#1A1A1A] border border-[#333] p-6 rounded-xl hover:border-[#C8A165]/50 transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-gray-400 uppercase text-xs font-bold">Faturamento Geral</p>
                    <i data-lucide="database" class="text-gray-500 w-6 h-6"></i>
                </div>
                <h3 class="text-4xl font-extrabold mt-3">R$ <?= number_format($kpis['total_geral'] ?? 0, 2, ',', '.') ?></h3>
                <p class="text-xs text-gray-400 mt-1">Desde o início da operação.</p>
            </div>

            <div class="bg-[#1A1A1A] border border-[#333] p-6 rounded-xl hover:border-[#C8A165]/50 transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-gray-400 uppercase text-xs font-bold">Total em Estoque (Unidades)</p>
                    <i data-lucide="package-open" class="text-white w-6 h-6"></i>
                </div>
                <h3 class="text-4xl font-extrabold mt-3 text-white"><?= $kpis['total_estoque'] ?? 0 ?></h3> 
                <p class="text-xs text-gray-400 mt-1"><?= $kpis['count_produtos'] ?? 0 ?> produtos diferentes (SKUs).</p>
            </div>

            <a href="<?= BASE_URL ?>vendas" class="block bg-red-800/20 border border-red-500/50 p-6 rounded-xl shadow-lg hover:bg-red-800/30 transition duration-300">
                <div class="flex justify-between items-center">
                    <p class="text-red-400 uppercase text-xs font-bold">Venda Rápida</p>
                    <i data-lucide="scan-line" class="text-red-400 w-6 h-6"></i>
                </div>
                <h3 class="text-2xl font-extrabold mt-3 text-white">IR PARA O PDV</h3>
                <p class="text-xs text-gray-400 mt-1">Registrar nova transação.</p>
            </a>
        </div>
        
        <div class="mt-8 bg-[#1A1A1A] p-6 rounded-xl border border-[#333] shadow-inner">
            <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                <i data-lucide="history" class="w-5 h-5 text-[#C8A165]"></i> 
                Acessos Rápidos
            </h2>
            <p class="text-gray-500 mb-4">Ações mais importantes do dia a dia.</p>
            
            <div class="grid grid-cols-3 gap-4">
                <a href="<?= BASE_URL ?>vendas/historico" class="bg-[#333] p-4 rounded-lg hover:bg-[#555] transition text-center">
                    <i data-lucide="list-ordered" class="w-6 h-6 mx-auto mb-1 text-white"></i>
                    <p class="text-sm font-medium">Histórico de Vendas</p>
                </a>
                <a href="<?= BASE_URL ?>produtos" class="bg-[#333] p-4 rounded-lg hover:bg-[#555] transition text-center">
                    <i data-lucide="package" class="w-6 h-6 mx-auto mb-1 text-white"></i>
                    <p class="text-sm font-medium">Gerenciar Estoque</p>
                </a>
                <a href="<?= BASE_URL ?>barbeiros" class="bg-[#333] p-4 rounded-lg hover:bg-[#555] transition text-center">
                    <i data-lucide="scissors" class="w-6 h-6 mx-auto mb-1 text-white"></i>
                    <p class="text-sm font-medium">Tabela de Preços</p>
                </a>
            </div>
        </div>

    </main>
    <script>lucide.createIcons();</script>
</body>
</html>