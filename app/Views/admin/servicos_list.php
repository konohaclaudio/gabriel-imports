<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Gerenciamento de Serviços</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">

    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4 sticky top-0 bg-[#111] z-10">
            <h1 class="text-3xl font-bold text-white">Serviços <span class="text-gray-500">/ Lista Mestra</span></h1>
            <a href="<?= BASE_URL ?>servicos/novo" class="bg-[#C8A165] text-black px-5 py-3 rounded-lg font-bold hover:bg-[#b08d55] transition shadow-md">
                + Novo Serviço
            </a>
        </div>
        
        <div class="bg-[#1A1A1A] border border-[#333] rounded-xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-[#0A0A0A] text-xs uppercase text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Nome</th>
                            <th scope="col" class="px-6 py-3">Duração (Minutos)</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($servicos)): ?>
                            <tr class="border-b border-[#333]">
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                    Nenhum serviço cadastrado.
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach($servicos as $servico): ?>
                            <tr class="border-b border-[#333] hover:bg-[#333]/50 transition">
                                <td class="px-6 py-4"><?= $servico['id'] ?></td>
                                <td class="px-6 py-4 font-bold text-white"><?= $servico['nome'] ?></td>
                                <td class="px-6 py-4 text-gray-400"><?= $servico['duracao_minutos'] ?> min</td>
                                <td class="px-6 py-4 text-right space-x-3">
                                    <a href="<?= BASE_URL ?>servicos/editar/<?= $servico['id'] ?>" class="text-[#C8A165] hover:text-white p-1">
                                        <i data-lucide="pencil" class="w-4 h-4 inline-block align-middle"></i> Editar
                                    </a>
                                    <a href="<?= BASE_URL ?>servicos/deletar/<?= $servico['id'] ?>" onclick="return confirm('ATENÇÃO: Deletar este serviço irá afetar a Matriz de Preços de TODOS os barbeiros. Confirmar exclusão?')" class="text-red-500 hover:text-red-400 p-1">
                                        <i data-lucide="trash-2" class="w-4 h-4 inline-block align-middle"></i> Excluir
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>