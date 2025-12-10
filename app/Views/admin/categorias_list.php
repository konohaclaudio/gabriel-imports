<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Categorias - Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">

    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4 sticky top-0 bg-[#111] z-10">
            <h1 class="text-3xl font-bold text-white">Categorias <span class="text-gray-500">/ Gerenciamento</span></h1>
            
        </div>
        
        <form method="POST" action="<?= BASE_URL ?>categorias/salvar" class="mb-8 p-6 bg-[#1A1A1A] rounded-xl border border-[#333] shadow-lg">
            <h2 class="text-xl font-bold text-[#C8A165] mb-4">Adicionar Nova Categoria</h2>
            <div class="flex gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-gray-400 text-sm mb-1">Nome *</label>
                    <input type="text" name="nome" placeholder="Ex: Roupas" required class="w-full bg-[#0A0A0A] border border-[#333] p-3 rounded-lg text-white focus:border-[#C8A165] outline-none">
                </div>
                <div class="flex-1">
                    <label class="block text-gray-400 text-sm mb-1">Slug (URL) *</label>
                    <input type="text" name="slug" placeholder="Ex: roupas" required class="w-full bg-[#0A0A0A] border border-[#333] p-3 rounded-lg text-white focus:border-[#C8A165] outline-none">
                </div>
                <button type="submit" class="bg-[#C8A165] text-black px-6 py-3 rounded-lg font-bold hover:bg-[#b08d55] shadow-md transform active:scale-95">
                    Adicionar Categoria
                </button>
            </div>
        </form>

        <div class="bg-[#1A1A1A] border border-[#333] rounded-xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-[#0A0A0A] text-xs uppercase text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID</th>
                            <th scope="col" class="px-6 py-3">Nome</th>
                            <th scope="col" class="px-6 py-3">Slug (URL)</th>
                            <th scope="col" class="px-6 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categorias as $cat): ?>
                            <tr class="border-b border-[#333] hover:bg-[#333]/50 transition">
                                <td class="px-6 py-4"><?= $cat['id'] ?></td>
                                <td class="px-6 py-4 font-bold text-white"><?= $cat['nome'] ?></td>
                                <td class="px-6 py-4 text-gray-400"><?= $cat['slug'] ?></td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?= BASE_URL ?>categorias/deletar/<?= $cat['id'] ?>" onclick="return confirm('ATENÇÃO: Excluir esta categoria irá quebrar produtos vinculados. Tem certeza?')" class="text-red-500 hover:text-red-400 p-1">
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