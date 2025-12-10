<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Preços - <?= $barbeiro['nome'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">
    
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8">
        
        <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4 sticky top-0 bg-[#111] z-10">
            <h1 class="text-3xl font-bold text-white">
                Tabela de Preços <span class="text-[#C8A165]">/ <?= $barbeiro['nome'] ?></span>
            </h1>
            <a href="<?= BASE_URL ?>barbeiros" class="text-gray-400 hover:text-white flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Voltar à Equipe
            </a>
        </div>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="bg-green-900/30 text-green-400 p-4 rounded-lg mb-6 border border-green-800/50 flex items-center gap-3">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
                ✅ Tabela de preços atualizada com sucesso!
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>barbeiros/salvarPrecos" method="POST" class="bg-[#1A1A1A] border border-[#333] rounded-xl shadow-2xl">
            <input type="hidden" name="barbeiro_id" value="<?= $barbeiro['id'] ?>">

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-[#0A0A0A] text-xs uppercase text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-1/2">Serviço</th>
                            <th scope="col" class="px-6 py-3 w-1/4">Preço Sugerido (Padrão)</th>
                            <th scope="col" class="px-6 py-3 w-1/4">Preço Cobrado por <?= $barbeiro['apelido'] ?> (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($servicosPrecos as $servico): ?>
                            <tr class="border-b border-[#333] hover:bg-[#333]/50 transition">
                                <td class="px-6 py-4 font-bold text-white"><?= $servico['servico_nome'] ?></td>
                                <td class="px-6 py-4 text-gray-500">R$ 40,00</td> <td class="px-6 py-4">
                                    <input type="text" 
                                           name="precos[<?= $servico['servico_id'] ?>]" 
                                           value="<?= number_format($servico['preco_barbeiro'] ?? 0, 2, ',', '') ?>"
                                           placeholder="Ex: 45,00"
                                           class="w-full bg-[#0A0A0A] border border-[#C8A165]/50 p-2 rounded-lg text-white focus:border-[#C8A165] outline-none text-right">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="p-6 border-t border-[#333] text-right">
                <button type="submit" class="bg-[#C8A165] text-black px-8 py-3 rounded-lg font-bold hover:bg-[#b08d55] shadow-lg transform active:scale-95">
                    SALVAR TABELA DE PREÇOS
                </button>
            </div>
        </form>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>