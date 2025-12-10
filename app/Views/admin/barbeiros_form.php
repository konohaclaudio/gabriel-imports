<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title><?= isset($barbeiro) ? 'Editar' : 'Novo' ?> Barbeiro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">
    
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8 flex justify-center">
        
        <div class="w-full max-w-xl">
            <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4">
                <h1 class="text-3xl font-bold text-white">Barbeiros <span class="text-gray-500">/ <?= isset($barbeiro) ? 'Editar Perfil' : 'Novo Cadastro' ?></span></h1>
                <a href="<?= BASE_URL ?>barbeiros" class="text-gray-400 hover:text-white flex items-center gap-1">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Voltar
                </a>
            </div>

            <form action="<?= BASE_URL ?>barbeiros/salvar" method="POST" enctype="multipart/form-data" class="bg-[#1A1A1A] p-8 rounded-xl border border-[#333] grid grid-cols-2 gap-6 shadow-2xl">
                
                <input type="hidden" name="id" value="<?= $barbeiro['id'] ?? '' ?>">
                <input type="hidden" name="foto_path_atual" value="<?= $barbeiro['foto_path'] ?? '' ?>">
                
                <div class="col-span-2">
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Nome Completo *</label>
                    <input type="text" name="nome" value="<?= $barbeiro['nome'] ?? '' ?>" required class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none transition-colors">
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Especialidade (Ex: Degradê, Barba) *</label>
                    <input type="text" name="especialidade" value="<?= $barbeiro['especialidade'] ?? '' ?>" required class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none transition-colors">
                </div>
                
                <div class="col-span-2">
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Telefone (WhatsApp) *</label>
                    <input type="text" name="telefone" placeholder="5514999999999" value="<?= $barbeiro['telefone'] ?? '' ?>" required class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Apenas números com DDD e código do país (Ex: 5514...)</p>
                </div>

                <div class="col-span-2">
                    <label class="block text-gray-400 text-sm mb-1 uppercase tracking-wide">Biografia</label>
                    <textarea name="bio" rows="3" class="w-full bg-[#0A0A0A] border border-[#333] p-4 rounded-lg text-white focus:border-[#C8A165] outline-none transition-colors"><?= $barbeiro['bio'] ?? '' ?></textarea>
                </div>

                <input type="hidden" name="apelido" value="<?= $barbeiro['apelido'] ?? '' ?>">

                <div class="col-span-2 border-2 border-dashed border-[#333] hover:border-[#C8A165] p-6 rounded-lg text-center transition-colors cursor-pointer relative group">
                    <input type="file" name="foto" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    
                    <div class="group-hover:scale-105 transition-transform">
                        <?php if (!empty($barbeiro['foto_path'])): ?>
                            <p class="text-sm font-bold text-white mb-2">Foto Atual:</p>
                            <img src="<?= BASE_URL . ltrim($barbeiro['foto_path'], '/') ?>" alt="Foto do Barbeiro" class="w-24 h-24 object-cover rounded-full mx-auto mb-2 border border-[#C8A165]">
                            <p class="text-[#C8A165] font-bold">Clique para Trocar</p>
                        <?php else: ?>
                            <p class="text-2xl mb-2">📸</p>
                            <p class="text-[#C8A165] font-bold">Clique para adicionar foto</p>
                            <p class="text-xs text-gray-500">(Opcional)</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-span-2 mt-4">
                    <button type="submit" class="w-full bg-[#C8A165] text-black font-bold py-4 rounded-lg hover:bg-[#b08d55] text-lg shadow-lg transform active:scale-95 transition-all">
                        <?= isset($barbeiro) ? 'ATUALIZAR PERFIL' : 'SALVAR PERFIL' ?>
                    </button>
                </div>

            </form>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>