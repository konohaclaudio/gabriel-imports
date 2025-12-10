<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= isset($produto) ? 'Editar' : 'Novo' ?> Produto - Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans h-screen flex overflow-hidden">

    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8 flex justify-center">
        
        <div class="w-full max-w-3xl">
            <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4">
                <h1 class="text-3xl font-bold text-white">Produtos <span class="text-gray-500">/ <?= isset($produto) ? 'Editar' : 'Novo Cadastro' ?></span></h1>
                <a href="<?= BASE_URL ?>produtos" class="text-gray-400 hover:text-white flex items-center gap-1">
                     <i data-lucide="arrow-left" class="w-4 h-4"></i> Voltar
                </a>
            </div>

            <form action="<?= BASE_URL ?>produtos/salvar" method="POST" enctype="multipart/form-data" class="bg-[#1A1A1A] p-8 rounded-xl border border-[#333] grid grid-cols-2 gap-6 shadow-2xl">
                
                <input type="hidden" name="id" value="<?= $produto['id'] ?? '' ?>">
                <input type="hidden" name="imagem_path_atual" value="<?= $produto['imagem_path'] ?? '' ?>"> 
                
                <div class="col-span-2">
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Nome do Produto *</label>
                    <input type="text" name="nome" value="<?= $produto['nome'] ?? '' ?>" required class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none">
                </div>

                <div class="col-span-2 grid grid-cols-2 gap-6 p-4 border border-[#333] rounded-lg">
                    <div class="col-span-2"><span class="text-gray-400 text-sm uppercase">Precificação</span></div>

                    <div>
                        <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Preço Original *</label>
                        <input type="number" step="0.01" name="preco" value="<?= $produto['preco'] ?? '' ?>" required class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none">
                    </div>

                    <div>
                        <label class="block text-[#C8A165] text-sm mb-1 uppercase tracking-wide font-bold">Preço Promo (Opcional)</label>
                        <input type="number" step="0.01" name="preco_promo" value="<?= $produto['preco_promo'] ?? '' ?>" class="w-full bg-[#0A0A0A] border border-[#C8A165]/50 p-4 rounded-lg text-white focus:border-[#C8A165] outline-none placeholder-gray-700" placeholder="0.00">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Estoque</label>
                    <input type="number" name="estoque" value="<?= $produto['estoque'] ?? 1 ?>" class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none">
                </div>

                <div>
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Categoria</label>
                    <select name="categoria_id" class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none cursor-pointer">
                        <?php $selectedCat = $produto['categoria_id'] ?? ''; ?>
                        <option value="1" <?= $selectedCat == 1 ? 'selected' : '' ?>>Roupas</option>
                        <option value="2" <?= $selectedCat == 2 ? 'selected' : '' ?>>Tênis</option>
                        <option value="3" <?= $selectedCat == 3 ? 'selected' : '' ?>>Acessórios</option>
                    </select>
                </div>

                <div class="col-span-2 border-2 border-dashed border-[#333] hover:border-[#C8A165] p-6 rounded-lg text-center transition-colors cursor-pointer relative group">
                    <input type="file" name="foto" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div class="group-hover:scale-105 transition-transform">
                        <?php if (isset($produto['imagem_path']) && $produto['imagem_path']): ?>
                            <p class="text-sm font-bold text-white mb-2">Foto Atual:</p>
                            <img src="<?= BASE_URL . $produto['imagem_path'] ?>" alt="Foto do Produto" class="w-24 h-24 object-cover rounded-md mx-auto mb-2 border border-[#C8A165]">
                            <p class="text-[#C8A165] font-bold">Clique para Trocar</p>
                            <p class="text-xs text-gray-500">(Ou mantenha a atual)</p>
                        <?php else: ?>
                            <p class="text-2xl mb-2">📸</p>
                            <p class="text-[#C8A165] font-bold">Clique para adicionar foto</p>
                            <p class="text-xs text-gray-500">(Opcional)</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-span-2 mt-4">
                    <button type="submit" class="w-full bg-[#C8A165] text-black font-bold py-4 rounded-lg hover:bg-[#b08d55] text-lg shadow-lg transform active:scale-95 transition-all">
                        <?= isset($produto) ? 'ATUALIZAR PRODUTO' : 'SALVAR PRODUTO' ?>
                    </button>
                </div>

            </form>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>