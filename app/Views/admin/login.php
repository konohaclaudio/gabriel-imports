<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#111] h-screen flex items-center justify-center font-sans text-white">
    
    <div class="w-full max-w-sm bg-[#1A1A1A] p-8 rounded-xl border border-[#333] shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-[#C8A165]">GABRIEL IMPORTS</h1>
            <p class="text-gray-500 text-sm mt-2">Área Administrativa</p>
        </div>

        <?php if(isset($erro)): ?>
            <div class="bg-red-900/30 text-red-400 border border-red-800 p-3 rounded mb-4 text-center text-sm">
                <?= $erro ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>login/login" method="POST" class="space-y-6">
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Email</label>
                <input type="email" name="email" required placeholder="admin@gabrielimports.com"
                    class="w-full bg-[#0A0A0A] border border-[#333] text-white p-3 rounded focus:border-[#C8A165] outline-none transition-colors">
            </div>
            <div>
                <label class="block text-xs uppercase text-gray-500 font-bold mb-2">Senha</label>
                <input type="password" name="senha" required placeholder="••••••"
                    class="w-full bg-[#0A0A0A] border border-[#333] text-white p-3 rounded focus:border-[#C8A165] outline-none transition-colors">
            </div>
            <button type="submit" class="w-full bg-[#C8A165] text-black font-bold py-3 rounded hover:bg-[#b08d55] transition-transform active:scale-95">
                ENTRAR NO SISTEMA
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="<?= BASE_URL ?>" class="text-xs text-gray-600 hover:text-[#C8A165]">Voltar ao Site</a>
        </div>
    </div>

</body>
</html>