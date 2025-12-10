<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= isset($servico) ? 'Editar' : 'Novo' ?> Serviço</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans h-screen flex overflow-hidden">

    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8 flex justify-center">
        
        <div class="w-full max-w-lg">
            <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4">
                <h1 class="text-3xl font-bold text-white">Serviços <span class="text-gray-500">/ <?= isset($servico) ? 'Editar' : 'Novo Cadastro' ?></span></h1>
                <a href="<?= BASE_URL ?>servicos" class="text-gray-400 hover:text-white flex items-center gap-1">
                     <i data-lucide="arrow-left" class="w-4 h-4"></i> Voltar
                </a>
            </div>

            <form action="<?= BASE_URL ?>servicos/salvar" method="POST" class="bg-[#1A1A1A] p-8 rounded-xl border border-[#333] grid grid-cols-1 gap-6 shadow-2xl">
                
                <input type="hidden" name="id" value="<?= $servico['id'] ?? '' ?>">
                
                <div>
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Nome do Serviço *</label>
                    <input type="text" name="nome" value="<?= $servico['nome'] ?? '' ?>" required class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none">
                </div>

                <div>
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Duração Média (em minutos) *</label>
                    <input type="number" name="duracao_minutos" value="<?= $servico['duracao_minutos'] ?? 30 ?>" required class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none">
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="w-full bg-[#C8A165] text-black font-bold py-4 rounded-lg hover:bg-[#b08d55] text-lg shadow-lg transform active:scale-95 transition-all">
                        <?= isset($servico) ? 'ATUALIZAR SERVIÇO' : 'SALVAR NOVO SERVIÇO' ?>
                    </button>
                </div>

            </form>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>