<div id="cart-sidebar" class="fixed inset-0 z-[60] hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm transition-opacity" onclick="toggleCart()"></div>

    <div class="absolute right-0 top-0 h-full w-full max-w-md bg-[#1A1A1A] border-l border-[#333] shadow-2xl transform transition-transform duration-300 translate-x-full flex flex-col" id="cart-panel">
        
        <div class="p-6 border-b border-[#333] flex justify-between items-center bg-[#111]">
            <h2 class="text-xl font-black text-white flex items-center gap-2">
                <i data-lucide="shopping-bag" class="text-gold"></i> SEU CARRINHO
            </h2>
            <button onclick="toggleCart()" class="text-gray-400 hover:text-white transition">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <div id="cart-items" class="flex-1 overflow-y-auto p-6 space-y-4">
            <div class="text-center text-gray-500 mt-10">
                <i data-lucide="shopping-cart" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                <p>Seu carrinho está vazio.</p>
                <button onclick="toggleCart()" class="mt-4 text-gold font-bold hover:underline">Continuar Comprando</button>
            </div>
        </div>

        <div class="p-6 bg-[#111] border-t border-[#333]">
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-400">Subtotal</span>
                <span class="text-2xl font-black text-gold" id="cart-total">R$ 0,00</span>
            </div>
            <button onclick="checkoutWhatsApp()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl flex items-center justify-center gap-2 transition shadow-lg hover:shadow-green-900/50">
                <i data-lucide="message-circle" class="w-5 h-5"></i> FINALIZAR NO WHATSAPP
            </button>
        </div>
    </div>
</div>


<footer class="bg-[#0f0f0f] border-t border-[#333] py-10 text-center">
    <div class="max-w-7xl mx-auto px-4 space-y-4">

        <!-- Marca -->
        <p class="text-xl font-black text-gold tracking-tighter">
            GABRIEL IMPORTS
        </p>

        <!-- Navegação rápida -->
        <nav class="space-x-4 text-sm text-gray-400">
            <a href="<?= BASE_URL ?>loja" class="hover:text-gold transition">
                Loja
            </a>
            <a href="<?= BASE_URL ?>barbearia" class="hover:text-gold transition">
                Barbearia
            </a>
            <a href="<?= BASE_URL ?>admin" class="hover:text-gold transition inline-flex items-center gap-1">
                <i data-lucide="lock" class="w-3 h-3"></i>
                Acesso Administrativo
            </a>
        </nav>

        <!-- Direitos autorais -->
        <p class="text-xs text-gray-500">
            © 2025 Gabriel Imports. Todos os direitos reservados.
        </p>

        <!-- Créditos -->
        <p class="text-[10px] text-gray-700 pt-4 border-t border-[#1a1a1a] mt-4">
            Desenvolvido por 
            <a 
                href="https://konohaclaudio.github.io/claudiosantana/#slide01"
                target="_blank"
                class="hover:text-gold transition underline underline-offset-2"
            >
                Claudio Santana
            </a>
        </p>

    </div>
</footer>


    

<div id="toast-cart" class="fixed bottom-5 right-5 bg-[#C8A165] text-black px-6 py-4 rounded-lg shadow-2xl font-bold transform translate-y-24 opacity-0 transition-all duration-300 z-[100] flex items-center gap-3">
    <i data-lucide="check-circle" class="w-6 h-6"></i>
    <div>
        <p>Produto adicionado!</p>
    </div>
</div>

<script>
    lucide.createIcons();

    // === LÓGICA DO CARRINHO ===

    // 1. Abrir/Fechar Gaveta
    function toggleCart() {
        const sidebar = document.getElementById('cart-sidebar');
        const panel = document.getElementById('cart-panel');
        
        if (sidebar.classList.contains('hidden')) {
            // Abrir
            sidebar.classList.remove('hidden');
            // Pequeno delay para animação CSS funcionar
            setTimeout(() => {
                panel.classList.remove('translate-x-full');
            }, 10);
            renderCartItems(); // Atualiza a lista visualmente
        } else {
            // Fechar
            panel.classList.add('translate-x-full');
            setTimeout(() => {
                sidebar.classList.add('hidden');
            }, 300);
        }
    }

    // 2. Atualizar UI (Contador do Header)
    function updateCartCount() {
        const cart = JSON.parse(localStorage.getItem('gabriel_cart')) || [];
        const totalItems = cart.reduce((acc, item) => acc + item.quantidade, 0);
        const badges = document.querySelectorAll('#cart-count'); // Pega todos os badges (mobile/desktop)
        
        badges.forEach(badge => {
            badge.textContent = totalItems;
            badge.style.opacity = totalItems > 0 ? '1' : '0';
        });
    }

    // 3. Renderizar Itens na Gaveta
    function renderCartItems() {
        const cart = JSON.parse(localStorage.getItem('gabriel_cart')) || [];
        const container = document.getElementById('cart-items');
        const totalEl = document.getElementById('cart-total');
        
        container.innerHTML = '';
        let total = 0;

        if (cart.length === 0) {
            container.innerHTML = `
                <div class="text-center text-gray-500 mt-10">
                    <i data-lucide="shopping-bag" class="w-12 h-12 mx-auto mb-3 opacity-30"></i>
                    <p>Seu carrinho está vazio.</p>
                </div>`;
        } else {
            cart.forEach((item, index) => {
                const subtotal = item.preco * item.quantidade;
                total += subtotal;
                
                // Formatação da Imagem
                let imgHtml = '<div class="w-16 h-16 bg-gray-800 rounded flex items-center justify-center text-xs text-gray-500">Sem Foto</div>';
                if(item.imagem) {
                    // Ajuste de caminho se necessário
                    const imgSrc = item.imagem.startsWith('http') ? item.imagem : '<?= BASE_URL ?>' + item.imagem.replace(/^\//, '');
                    imgHtml = `<img src="${imgSrc}" class="w-16 h-16 object-cover rounded border border-[#333]">`;
                }

                container.innerHTML += `
                    <div class="flex gap-4 bg-[#111] p-3 rounded-lg border border-[#333] relative group">
                        ${imgHtml}
                        <div class="flex-1">
                            <h4 class="font-bold text-white text-sm line-clamp-1">${item.nome}</h4>
                            <p class="text-gold font-bold text-sm mt-1">R$ ${item.preco.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</p>
                            
                            <div class="flex items-center gap-3 mt-2">
                                <button onclick="changeQty(${index}, -1)" class="w-6 h-6 rounded bg-[#333] hover:bg-white hover:text-black flex items-center justify-center transition">-</button>
                                <span class="text-sm font-bold w-4 text-center">${item.quantidade}</span>
                                <button onclick="changeQty(${index}, 1)" class="w-6 h-6 rounded bg-[#333] hover:bg-white hover:text-black flex items-center justify-center transition">+</button>
                            </div>
                        </div>
                        <button onclick="removeItem(${index})" class="absolute top-2 right-2 text-gray-600 hover:text-red-500 transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                `;
            });
        }

        totalEl.textContent = total.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        lucide.createIcons();
    }

    // 4. Adicionar ao Carrinho
    function addToCart(prod) {
        let cart = JSON.parse(localStorage.getItem('gabriel_cart')) || [];
        const existing = cart.find(item => item.id === prod.id);

        if (existing) {
            existing.quantidade++;
        } else {
            cart.push({
                id: prod.id,
                nome: prod.nome,
                preco: parseFloat(prod.preco),
                imagem: prod.imagem,
                quantidade: 1
            });
        }

        localStorage.setItem('gabriel_cart', JSON.stringify(cart));
        updateCartCount();
        
        // Feedback Visual
        const toast = document.getElementById('toast-cart');
        toast.classList.remove('translate-y-24', 'opacity-0');
        setTimeout(() => toast.classList.add('translate-y-24', 'opacity-0'), 2000);
        
        // Opcional: Abrir o carrinho automaticamente
        // toggleCart(); 
    }

    // 5. Alterar Quantidade
    function changeQty(index, delta) {
        let cart = JSON.parse(localStorage.getItem('gabriel_cart'));
        cart[index].quantidade += delta;
        
        if (cart[index].quantidade <= 0) {
            cart.splice(index, 1);
        }
        
        localStorage.setItem('gabriel_cart', JSON.stringify(cart));
        renderCartItems();
        updateCartCount();
    }

    // 6. Remover Item
    function removeItem(index) {
        let cart = JSON.parse(localStorage.getItem('gabriel_cart'));
        cart.splice(index, 1);
        localStorage.setItem('gabriel_cart', JSON.stringify(cart));
        renderCartItems();
        updateCartCount();
    }

    // 7. Finalizar no WhatsApp
    function checkoutWhatsApp() {
        const cart = JSON.parse(localStorage.getItem('gabriel_cart')) || [];
        if (cart.length === 0) {
            alert("Seu carrinho está vazio!");
            return;
        }

        let msg = "*PEDIDO VIA SITE - G. IMPORTS*\n\n";
        let total = 0;

        cart.forEach(item => {
            const sub = item.preco * item.quantidade;
            total += sub;
            msg += `▪ ${item.quantidade}x ${item.nome}\n`;
        });

        msg += `\n*TOTAL: R$ ${total.toLocaleString('pt-BR', {minimumFractionDigits: 2})}*\n`;
        msg += `\nOlá, gostaria de finalizar esse pedido.`;

        const phone = "5514998676477"; // Seu número
        const url = `https://api.whatsapp.com/send?phone=${phone}&text=${encodeURIComponent(msg)}`;
        
        window.open(url, '_blank');
    }

    // Inicialização
    document.addEventListener('DOMContentLoaded', updateCartCount);
    
    // Configura Botão Menu Mobile (se existir na página)
    const btnMobile = document.getElementById('mobile-menu-btn');
    if(btnMobile) {
        btnMobile.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
    }
</script>   