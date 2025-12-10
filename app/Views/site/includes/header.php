<header class="bg-[#1A1A1A] border-b border-[#333] h-20 flex items-center">
    <div class="container mx-auto flex justify-between items-center px-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl font-black text-[#C8A165]">G. IMPORTS</span>
        </div>
        <nav class="space-x-6 text-sm font-medium">
            <a href="<?= BASE_URL ?>" class="hover:text-gray-400 transition">Home</a>
            <a href="<?= BASE_URL ?>loja" class="hover:text-[#C8A165] transition">Loja (Streetwear)</a>
            <a href="<?= BASE_URL ?>barbearia" class="hover:text-[#C8A165] transition">Barbearia & Serviços</a>
        </nav>
        <div class="flex items-center space-x-4">
            <button id="cart-button" class="relative text-white hover:text-[#C8A165] transition hidden">
                🛒 Carrinho (<span id="cart-count">0</span>)
            </button>
        </div>
    </div>
</header>