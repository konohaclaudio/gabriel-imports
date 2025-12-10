<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Vendas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">
    
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8">
        
        <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4 sticky top-0 bg-[#111] z-10">
            <h1 class="text-3xl font-bold text-white">Vendas <span class="text-gray-500">/ Histórico Completo</span></h1>
            <div class="flex gap-4">
                <a href="<?= BASE_URL ?>vendas" class="bg-[#C8A165] text-black px-5 py-3 rounded-lg font-bold hover:bg-[#b08d55] transition shadow-md transform hover:scale-[1.02]">
                    💰 Abrir PDV
                </a>
            </div>
        </div>
        
        <?php if(isset($_GET['status']) && $_GET['status'] === 'cancelado'): ?>
            <div class="bg-red-900/30 text-red-400 p-4 rounded-lg mb-4 border border-red-800/50 flex items-center gap-3">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                Venda CANCELADA com sucesso. O estoque foi revertido.
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['error']) && $_GET['error'] === 'falha_cancelamento'): ?>
            <div class="bg-red-900/30 text-red-400 p-4 rounded-lg mb-4 border border-red-800/50 flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                Falha ao cancelar a venda. Tente novamente ou verifique os logs.
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['error']) && $_GET['error'] === 'venda_nao_encontrada'): ?>
            <div class="bg-red-900/30 text-red-400 p-4 rounded-lg mb-4 border border-red-800/50 flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                Venda não encontrada.
            </div>
        <?php endif; ?>
        
        <div class="bg-[#1A1A1A] border border-[#333] rounded-xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-[#0A0A0A] text-xs uppercase text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID Venda</th>
                            <th scope="col" class="px-6 py-3">Cliente</th>
                            <th scope="col" class="px-6 py-3">Forma Pgto</th>
                            <th scope="col" class="px-6 py-3">Data</th>
                            <th scope="col" class="px-6 py-3 text-right">Total</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($vendas)): ?>
                            <tr class="border-b border-[#333]">
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    Nenhuma venda registrada ainda. Faça a primeira transação no PDV!
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach($vendas as $venda): ?>
                            <tr class="border-b border-[#333] hover:bg-[#333]/50 transition duration-150">
                                <td class="px-6 py-4 font-bold text-white"><?= $venda['id'] ?></td>
                                <td class="px-6 py-4"><?= htmlspecialchars($venda['cliente_nome']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded-full text-xs <?= $venda['forma_pagamento'] == 'pix' ? 'bg-green-600/30 text-green-300' : ($venda['forma_pagamento'] == 'cartao' ? 'bg-indigo-600/30 text-indigo-300' : 'bg-yellow-600/30 text-yellow-300') ?>">
                                        <?= ucfirst($venda['forma_pagamento']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?= date('d/m/Y H:i', strtotime($venda['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right font-extrabold text-[#C8A165]">
                                    R$ <?= number_format($venda['total'], 2, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <a href="<?= BASE_URL ?>vendas/detalhe/<?= $venda['id'] ?>" title="Ver Detalhes" class="text-blue-500 hover:text-blue-400 p-2">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $venda['id'] ?>)" title="Cancelar Venda" class="text-red-500 hover:text-red-400 p-2">
                                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
    <script>
        function confirmDelete(id) {
            if (confirm("ATENÇÃO: Deseja realmente CANCELAR esta venda? O estoque será revertido.")) {
                window.location.href = '<?= BASE_URL ?>vendas/excluir/' + id;
            }
        }
        lucide.createIcons();
    </script>
</body>
</html>