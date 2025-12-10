<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>PDV - Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Garantir que o scroll apareça apenas onde deve (listas) */
        .scrolling-area::-webkit-scrollbar {
            width: 8px;
        }
        .scrolling-area::-webkit-scrollbar-thumb {
            background-color: #333;
            border-radius: 4px;
        }
        .scrolling-area::-webkit-scrollbar-track {
            background: #1A1A1A;
        }
    </style>
</head>
<body class="bg-[#111] text-white font-sans flex h-screen overflow-hidden">
    
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="flex-1 overflow-hidden p-6 flex gap-6"> 
        
        <section class="w-[30%] flex flex-col bg-[#1A1A1A] rounded-xl border border-[#333] p-4 shadow-2xl flex-shrink-0">
            <h2 class="text-xl font-bold text-[#C8A165] mb-4 border-b border-[#333] pb-2">Buscar Produtos</h2>
            
            <input type="text" id="search-produto" placeholder="Digite o nome ou código..." 
                   class="w-full bg-[#0A0A0A] border border-[#333] p-3 rounded-lg text-white focus:border-[#C8A165] outline-none mb-4" onkeyup="filterProducts()">

            <div id="product-list" class="flex-1 scrolling-area space-y-3 pr-2">
                <?php if (empty($produtos)): ?>
                    <p class="text-gray-500 text-center mt-10">Nenhum produto em estoque.</p>
                <?php endif; ?>

                <?php foreach($produtos as $prod): ?>
                    <div class="product-item bg-[#333] p-3 rounded-lg flex justify-between items-center cursor-pointer hover:bg-[#C8A165]/20 transition"
                         data-id="<?= $prod['id'] ?>"
                         data-nome="<?= htmlspecialchars($prod['nome']) ?>"
                         data-preco="<?= $prod['preco_promo'] ?? $prod['preco'] ?>"
                         data-estoque="<?= $prod['estoque'] ?>"
                         onclick="addItemToCart(this)">
                        
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-md overflow-hidden bg-black flex items-center justify-center flex-shrink-0">
                                <?php if($prod['imagem_path'] ?? null): ?>
                                    <img src="<?= BASE_URL . $prod['imagem_path'] ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i data-lucide="package" class="w-5 h-5 text-gray-600"></i>
                                <?php endif; ?>
                            </div>
                            
                            <div>
                                <p class="font-bold truncate text-white"><?= $prod['nome'] ?></p>
                                <p class="text-xs text-gray-400">Estoque: <?= $prod['estoque'] ?></p>
                            </div>
                        </div>
                        <p class="font-extrabold text-[#C8A165]">R$ <?= number_format($prod['preco_promo'] ?? $prod['preco'], 2, ',', '.') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="flex-1 flex gap-6">
            
            <div class="flex flex-col w-3/5"> 
                
                <?php if(isset($_GET['success'])): ?>
                    <div class="bg-green-900/30 text-green-400 p-4 rounded-lg mb-4 border border-green-800/50 flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-6 h-6"></i>
                        Venda registrada com sucesso!
                    </div>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <div class="bg-red-900/30 text-red-400 p-4 rounded-lg mb-4 border border-red-800/50 flex items-center gap-3">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                        Erro ao finalizar a venda. <?= htmlspecialchars($_GET['msg'] ?? 'Verifique o console.') ?>
                    </div>
                <?php endif; ?>

                <div class="flex-1 bg-[#1A1A1A] rounded-xl border border-[#333] p-4 shadow-2xl overflow-hidden flex flex-col">
                    <h2 class="text-xl font-bold text-[#C8A165] mb-4 border-b border-[#333] pb-2">Itens (Carrinho)</h2>
                    
                    <div id="cart-items" class="flex-1 scrolling-area space-y-3 pr-2">
                        <p id="empty-cart-message" class="text-gray-500 text-center mt-10">Nenhum item adicionado.</p>
                    </div>
                </div>
            </div>

            <div class="w-2/5 flex flex-col gap-6"> 
                
                <div class="bg-[#1A1A1A] rounded-xl border border-[#333] p-4 shadow-2xl flex-shrink-0">
                    <h3 class="font-bold text-lg mb-4 border-b border-[#333] pb-2 text-white">Resumo e Descontos</h3>
                    
                    <div class="pb-4 border-b border-[#333] mb-4">
                        <h4 class="font-bold mb-2 flex items-center gap-2 text-gray-300">
                            <i data-lucide="tag" class="w-4 h-4"></i> Aplicar Cupom
                        </h4>
                        <div class="flex gap-2">
                            <input type="text" id="coupon-code" placeholder="CÓDIGO DO CUPOM" 
                                class="flex-1 bg-[#0A0A0A] border border-[#333] p-2 rounded-lg text-white focus:border-[#C8A165] outline-none uppercase text-sm">
                            <button onclick="applyCoupon()" id="apply-coupon-btn" class="bg-[#C8A165] text-black px-4 py-2 rounded-lg text-sm font-bold hover:bg-[#b08d55] transition transform active:scale-95">
                                APLICAR
                            </button>
                        </div>
                        <p id="coupon-feedback" class="text-xs mt-1 h-4"></p>
                    </div>
                    
                    <h4 class="font-bold text-base mb-2">Resumo da Venda</h4>
                    <div class="space-y-1 text-base mb-4">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Subtotal:</span>
                            <span id="subtotal-display" class="font-medium text-white">R$ 0,00</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Desconto:</span>
                            <span id="discount-display" class="text-red-400 font-medium">- R$ 0,00</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-[#C8A165]/50 pt-3">
                        <div class="flex justify-between font-extrabold text-4xl items-center">
                            <span class="text-white">TOTAL:</span>
                            <span id="total-display" class="text-[#C8A165]">R$ 0,00</span>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1A1A1A] rounded-xl border border-[#333] p-4 shadow-2xl flex-shrink-0">
                    <h3 class="font-bold mb-4 border-b border-[#333] pb-2 text-white">Finalizar Transação</h3>
                    
                    <div class="flex gap-4 mb-4">
                        <select id="payment-method" class="flex-1 bg-[#0A0A0A] border border-[#333] p-3 rounded-lg text-white focus:border-[#C8A165] outline-none">
                            <option value="dinheiro">Dinheiro</option>
                            <option value="pix">PIX</option>
                            <option value="cartao">Cartão</option>
                        </select>
                        <input type="text" id="cliente-nome" placeholder="Nome do Cliente (Balcão)" 
                               class="flex-1 bg-[#0A0A0A] border border-[#333] p-3 rounded-lg text-white focus:border-[#C8A165] outline-none">
                    </div>
                    
                    <div class="flex gap-4">
                        <button onclick="clearCart()" class="w-1/3 bg-gray-700 text-white px-6 py-4 rounded-lg font-bold hover:bg-gray-600 transition shadow-md transform active:scale-95 flex items-center justify-center gap-2">
                            <i data-lucide="trash-2" class="w-5 h-5"></i> Limpar
                        </button>
                        <button onclick="finalizeSale()" class="w-2/3 bg-[#C8A165] text-black px-6 py-4 rounded-lg font-extrabold hover:bg-[#b08d55] transition shadow-lg transform active:scale-95">
                            <i data-lucide="zap" class="w-5 h-5 inline mr-2"></i> REGISTRAR VENDA
                        </button>
                    </div>
                </div>

            </div>
        </section>
        
    </main>
    
    <script>
        // Lógica JS (inclusa)
        let cart = {}; 
        let currentCoupon = null; 

        const AVAILABLE_COUPONS = {
            'PROMO10': { type: 'percent', value: 0.10, min_value: 0.00, expiration: Infinity }, 
            'VALE20': { type: 'fixed', value: 20.00, min_value: 50.00, expiration: Infinity },
        };

        const cartItemsContainer = document.getElementById('cart-items');
        const emptyCartMessage = document.getElementById('empty-cart-message');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const discountDisplay = document.getElementById('discount-display');
        const totalDisplay = document.getElementById('total-display');
        const couponFeedback = document.getElementById('coupon-feedback');
        
        function formatCurrency(value) {
            return `R$ ${parseFloat(value).toFixed(2).replace('.', ',')}`;
        }

        function updateTotals() {
            let subtotal = 0;
            for (const id in cart) {
                subtotal += cart[id].quantidade * cart[id].preco_unitario;
            }
            
            let discount = 0;
            let finalTotal = subtotal;

            if (currentCoupon) {
                const coupon = AVAILABLE_COUPONS[currentCoupon.code];
                
                if (coupon.min_value > 0 && subtotal < coupon.min_value) {
                    discount = 0;
                    couponFeedback.textContent = `❌ Mínimo de ${formatCurrency(coupon.min_value)} para aplicar o cupom.`;
                    couponFeedback.className = 'text-red-400 text-xs mt-1';
                    currentCoupon = null; 
                } 
                else if (coupon.type === 'percent') {
                    discount = subtotal * coupon.value;
                } else if (coupon.type === 'fixed') {
                    discount = coupon.value;
                }
                
                finalTotal = subtotal - discount;
                
                if (finalTotal < 0) finalTotal = 0;
            } else {
                 couponFeedback.textContent = '';
            }

            subtotalDisplay.textContent = formatCurrency(subtotal);
            discountDisplay.textContent = `- ${formatCurrency(discount)}`;
            totalDisplay.textContent = formatCurrency(finalTotal);
            
            emptyCartMessage.style.display = subtotal > 0 ? 'none' : 'block';
        }

        function renderCart() {
            cartItemsContainer.innerHTML = ''; 
            for (const id in cart) {
                 const item = cart[id];
                 const itemTotal = item.quantidade * item.preco_unitario;

                 const itemHtml = document.createElement('div');
                 // Classe ajustada para melhor usabilidade e contraste
                 itemHtml.className = 'bg-[#333] p-3 rounded-lg flex justify-between items-center hover:bg-[#333]/70 transition'; 
                 itemHtml.innerHTML = `
                     <div class="flex items-center gap-4 flex-1">
                        <div class="flex items-center border border-[#555] rounded-lg overflow-hidden flex-shrink-0">
                            <button onclick="removeItemFromCart(${id}, true, false)" 
                                    class="text-red-400 hover:bg-red-900/30 w-8 h-8 flex items-center justify-center transition">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                             </button>
                             <span class="font-extrabold text-white text-base w-8 text-center bg-[#1A1A1A]">${item.quantidade}</span>
                             <button onclick="addItemToCart(document.querySelector('.product-item[data-id=\"${id}\"]'), false)" 
                                     class="text-green-400 hover:bg-green-900/30 w-8 h-8 flex items-center justify-center transition">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                             </button>
                        </div>
                         
                        <div class="flex-1 min-w-0">
                           <p class="font-bold text-white truncate">${item.nome}</p>
                           <p class="text-xs text-gray-400">@ ${formatCurrency(item.preco_unitario)}</p>
                        </div>
                     </div>
                     
                     <div class="flex items-center gap-3 flex-shrink-0 ml-4">
                         <span class="font-extrabold text-[#C8A165] text-xl w-24 text-right">${formatCurrency(itemTotal)}</span>
                         
                         <button onclick="removeItemFromCart(${id}, false, true)" title="Remover Todos" 
                                 class="text-red-500 hover:text-red-400 p-1 transition">
                             <i data-lucide="x-circle" class="w-6 h-6"></i>
                         </button>
                     </div>
                 `;
                 cartItemsContainer.appendChild(itemHtml);
            }
            lucide.createIcons();
            updateTotals(); 
        }

        function addItemToCart(element, isInitialClick = true) {
            const id = element.getAttribute('data-id');
            const nome = element.getAttribute('data-nome');
            const preco = parseFloat(element.getAttribute('data-preco'));
            const estoque = parseInt(element.getAttribute('data-estoque'));
            
            if (estoque <= 0) {
                // UX SUGGESTION: Trocar por um toast ou modal
                alert('Estoque esgotado para este item!'); 
                return;
            }

            if (cart[id]) {
                if (cart[id].quantidade + 1 > estoque) {
                    // UX SUGGESTION: Trocar por um toast ou modal
                    alert('Quantidade máxima em estoque atingida.'); 
                    return;
                }
                cart[id].quantidade += 1;
            } else {
                cart[id] = {
                    produto_id: id,
                    nome: nome,
                    preco_unitario: preco,
                    quantidade: 1
                };
            }
            // Microinteração: Flash visual no item adicionado (opcional, mas recomendado)
            if (isInitialClick) {
                element.classList.add('bg-green-600/50');
                setTimeout(() => {
                     element.classList.remove('bg-green-600/50');
                }, 150);
            }
            renderCart();
        }

        function removeItemFromCart(id, decrementOnly = false, removeAll = false) {
            if (!cart[id]) return;

            if (removeAll) {
                 delete cart[id];
            } else if (decrementOnly) {
                if (cart[id].quantidade > 1) {
                    cart[id].quantidade -= 1;
                } else {
                    delete cart[id];
                }
            } else {
                delete cart[id];
            }
            
            if (Object.keys(cart).length === 0) currentCoupon = null;
            
            renderCart();
        }

        function clearCart() {
             if (Object.keys(cart).length === 0) return;

             if (confirm("Tem certeza que deseja limpar o carrinho?")) {
                 cart = {};
                 currentCoupon = null;
                 renderCart();
             }
        }

        function applyCoupon() {
            const code = document.getElementById('coupon-code').value.toUpperCase();
            const coupon = AVAILABLE_COUPONS[code];

            if (coupon) {
                currentCoupon = { code: code, type: coupon.type, value: coupon.value };
                couponFeedback.textContent = `✅ Cupom ${code} aplicado!`;
                couponFeedback.className = 'text-green-400 text-xs mt-1';
            } else {
                couponFeedback.textContent = `❌ Cupom inválido ou não encontrado.`;
                couponFeedback.className = 'text-red-400 text-xs mt-1';
                currentCoupon = null;
            }
            updateTotals(); 
        }
        
        function finalizeSale() {
            if (Object.keys(cart).length === 0) {
                alert('Adicione itens ao carrinho antes de finalizar a venda.');
                return;
            }
            
            const totalText = totalDisplay.textContent.replace('R$ ', '').replace(',', '.');
            const finalTotal = parseFloat(totalText);

            if (isNaN(finalTotal) || finalTotal < 0) {
                alert('Erro no cálculo do total. Tente limpar o carrinho.');
                return;
            }

            const confirmSale = confirm(`Confirma o registro da venda no valor final de ${formatCurrency(finalTotal)}?`);
            if (!confirmSale) {
                return;
            }
            
            // Microinteração: Mostrar loading e desabilitar botão
            const finalizeBtn = document.querySelector('button[onclick="finalizeSale()"]');
            finalizeBtn.innerHTML = '<i data-lucide="loader-circle" class="w-5 h-5 inline mr-2 animate-spin"></i> REGISTRANDO...';
            finalizeBtn.disabled = true;
            lucide.createIcons(); // Recriar o ícone de spinner

            const vendaData = {
                cliente_nome: document.getElementById('cliente-nome').value || 'Balcão',
                forma_pagamento: document.getElementById('payment-method').value,
                total: finalTotal, 
                itens: Object.values(cart),
                cupom: currentCoupon ? currentCoupon.code : null 
            };
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?= BASE_URL ?>vendas/registrar';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'venda_data';
            input.value = JSON.stringify(vendaData);
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }

        function filterProducts() {
             const search = document.getElementById('search-produto').value.toLowerCase();
             const items = document.querySelectorAll('.product-item');
            
             items.forEach(item => {
                 const nome = item.getAttribute('data-nome').toLowerCase();
                 if (nome.includes(search)) {
                     item.style.display = 'flex';
                 } else {
                     item.style.display = 'none';
                 }
             });
        }

        document.addEventListener('DOMContentLoaded', () => {
             lucide.createIcons();
             renderCart(); 
        });
    </script>
    </body>
</html>