<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Barbeiros - Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">
    
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto p-8">
        <div class="flex justify-between items-center mb-8 border-b border-[#333] pb-4 sticky top-0 bg-[#111] z-10">
            <h1 class="text-3xl font-bold text-white">Barbeiros <span class="text-gray-500">/ Gerenciamento de Equipe</span></h1>
            <a href="<?= BASE_URL ?>barbeiros/novo" class="bg-[#C8A165] text-black px-5 py-3 rounded-lg font-bold hover:bg-[#b08d55] transition shadow-md">
                + Novo Barbeiro
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (empty($barbeiros)): ?>
                <div class="md:col-span-4 p-8 text-center bg-[#1A1A1A] rounded-xl border border-[#333] text-gray-500">
                    <i data-lucide="user-plus" class="w-8 h-8 mx-auto mb-2"></i>
                    <p>Nenhum barbeiro cadastrado. Adicione o primeiro membro da equipe!</p>
                </div>
            <?php endif; ?>

            <?php foreach($barbeiros as $barbeiro): ?>
                <div class="bg-[#1A1A1A] border border-[#333] rounded-xl overflow-hidden shadow-lg group hover:border-[#C8A165]/50 transition duration-300">
                    
                    <div class="h-48 overflow-hidden bg-black flex items-center justify-center">
                        <?php if(!empty($barbeiro['foto_path'])): ?>
                            <img src="<?= BASE_URL . ltrim($barbeiro['foto_path'], '/') ?>" alt="<?= htmlspecialchars($barbeiro['nome']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <?php else: ?>
                            <i data-lucide="scissors" class="w-12 h-12 text-gray-600"></i>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-bold text-xl text-white truncate"><?= htmlspecialchars($barbeiro['nome']) ?></h3>
                        <?php if(!empty($barbeiro['apelido'])): ?>
                            <p class="text-[#C8A165] text-sm mb-4">@<?= htmlspecialchars($barbeiro['apelido']) ?></p>
                        <?php else: ?>
                            <p class="text-gray-500 text-sm mb-4">Sem apelido</p>
                        <?php endif; ?>

                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-[#333]">
                            <a href="<?= BASE_URL ?>barbeiros/precos/<?= $barbeiro['id'] ?>" class="flex items-center gap-1 text-sm font-bold text-[#C8A165] hover:text-white transition-colors">
                                <i data-lucide="dollar-sign" class="w-4 h-4"></i> Preços
                            </a>

                            <div class="flex gap-2">
                                <a href="<?= BASE_URL ?>barbeiros/novo/<?= $barbeiro['id'] ?>" title="Editar Perfil" class="text-gray-400 hover:text-white p-2 rounded-full hover:bg-[#333]">
                                    <i data-lucide="edit-2" class="w-4 h-4"></i>
                                </a>
                                <a href="<?= BASE_URL ?>barbeiros/excluir/<?= $barbeiro['id'] ?>" onclick="return confirm('Tem certeza que deseja excluir?')" title="Excluir Barbeiro" class="text-red-500 hover:text-red-400 p-2 rounded-full hover:bg-red-900/10">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <script>lucide.createIcons();</script>
</body>
</html>