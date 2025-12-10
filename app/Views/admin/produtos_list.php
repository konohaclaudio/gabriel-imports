<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">
    
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8">
        
        <div class="flex justify-between items-center mb-6 sticky top-0 bg-[#111] z-10">
            <h1 class="text-3xl font-bold text-white">Produtos <span class="text-gray-500">/ Catálogo (<?= count($produtos) ?>)</span></h1>
            
            <a href="<?= BASE_URL ?>produtos/novo" class="bg-[#C8A165] text-black px-5 py-3 rounded-lg font-bold hover:bg-[#b08d55] transition shadow-md transform hover:scale-[1.02]">
                + Novo Produto
            </a>
        </div>

        <form method="GET" action="<?= BASE_URL ?>produtos" class="mb-8 p-4 bg-[#1A1A1A] rounded-xl border border-[#333] shadow-lg">
            <div class="flex items-center gap-4">
                <i data-lucide="search" class="w-5 h-5 text-gray-400"></i>
                <input type="text" name="q" placeholder="Buscar por nome ou descrição..." 
                       value="<?= htmlspecialchars($search ?? '') ?>"
                       class="flex-1 bg-transparent text-white p-2 focus:ring-0 focus:outline-none placeholder-gray-500 border-b border-[#333] focus:border-[#C8A165] transition-all">
                <button type="submit" class="bg-[#C8A165] text-black px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#b08d55]">
                    Buscar
                </button>
            </div>
        </form>

        <div class="bg-[#1A1A1A] border border-[#333] rounded-xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-[#0A0A0A] text-xs uppercase text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3 w-16">Foto</th>
                            <th scope="col" class="px-6 py-3">Produto & Categoria</th>
                            <th scope="col" class="px-6 py-3">Preço</th>
                            <th scope="col" class="px-6 py-3">Estoque</th>
                            <th scope="col" class="px-6 py-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($produtos)): ?>
                            <tr class="border-b border-[#333]">
                                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                    <i data-lucide="info" class="w-8 h-8 mx-auto mb-2"></i>
                                    <p>Nenhum produto encontrado. Limpe a busca ou cadastre um item.</p>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach($produtos as $prod): ?>
                            <tr class="border-b border-[#333] hover:bg-[#333]/50 transition duration-150">
                                
                                <td class="px-6 py-4">
                                    <div class="w-10 h-10 rounded overflow-hidden bg-black flex items-center justify-center">
                                        <?php if($prod['imagem_path']): ?>
                                            <img src="<?= BASE_URL . $prod['imagem_path'] ?>" alt="<?= $prod['nome'] ?>" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <i data-lucide="image" class="w-5 h-5 text-gray-600"></i>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="px-6 py-4 max-w-xs truncate">
                                    <p class="font-bold text-white"><?= $prod['nome'] ?></p>
                                    <p class="text-xs text-gray-500 mt-1"><?= $prod['categoria_nome'] ?? 'Sem Categoria' ?></p>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($prod['preco_promo']): ?>
                                        <span class="text-xs text-gray-500 line-through mr-2">R$ <?= number_format($prod['preco'], 2, ',', '.') ?></span><br>
                                        <span class="font-bold text-[#C8A165]">R$ <?= number_format($prod['preco_promo'], 2, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="font-bold text-white">R$ <?= number_format($prod['preco'], 2, ',', '.') ?></span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 font-bold whitespace-nowrap">
                                    <?php if($prod['estoque'] <= 5): ?>
                                        <span class="text-red-400 flex items-center"><i data-lucide="zap" class="w-4 h-4 mr-1"></i> Baixo (<?= $prod['estoque'] ?>)</span>
                                    <?php else: ?>
                                        <span class="text-green-400"><?= $prod['estoque'] ?> UN.</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex justify-center gap-2">
                                        <a href="<?= BASE_URL ?>produtos/editar/<?= $prod['id'] ?>" title="Editar Item" class="text-gray-400 hover:text-[#C8A165] p-3 rounded-full hover:bg-[#C8A165]/10 transition-colors">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>

                                        <a href="<?= BASE_URL ?>produtos/deletar/<?= $prod['id'] ?>" onclick="return confirm('Deseja realmente excluir este produto?')" title="Excluir Item" class="text-red-500 hover:text-red-400 p-3 rounded-full hover:bg-red-900/10 transition-colors">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
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