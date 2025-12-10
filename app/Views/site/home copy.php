<?php
// =========================================================================
// === LÓGICA DE CARREGAMENTO (Backend na View para One-Page) ===
// =========================================================================

use App\Core\Database;
use App\Models\Produto;
use PDO;

// 1. Definição de Constantes
if (!defined('BASE_URL')) {
    define('BASE_URL', '/'); 
}

// 2. Carregar PRODUTOS (Via Model)
$produtosDestaque = [];
try {
    if (class_exists('App\Models\Produto')) {
        $produtoModel = new Produto();
        $todosProdutos = $produtoModel->listar(null, 1); 
        if (!empty($todosProdutos)) {
            $produtosDestaque = array_slice($todosProdutos, 0, 4);
        }
    }
} catch (\Exception $e) {
    // Falha silenciosa
}

// 3. Carregar BARBEIROS (Via Database Direto)
$barbeiros = [];
try {
    $conn = Database::getConnection();
    $stmt = $conn->query("SELECT * FROM barbeiros");
    $barbeiros = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $barbeiros = []; 
}

// 4. DEPOIMENTOS (Lista com 4 itens para o Carrossel)
$depoimentos = [
    [
        'nome' => 'Marcos F.', 
        'cidade' => 'Piraju, SP', 
        'texto' => 'O melhor corte que já fiz. O ambiente é top e os profissionais entendem muito de visagismo. Virei cliente fiel!', 
        'estrelas' => 5
    ],
    [
        'nome' => 'Ana C.', 
        'cidade' => 'Avaré, SP', 
        'texto' => 'Adorei a nova coleção streetwear! As peças têm uma qualidade incrível e o caimento é perfeito. Atendimento nota 10.', 
        'estrelas' => 5
    ],
    [
        'nome' => 'João P.', 
        'cidade' => 'Cerqueira César, SP', 
        'texto' => 'Ambiente muito resenha, cerveja gelada e o degradê na régua. A Gabriel Imports elevou o nível da região.', 
        'estrelas' => 5
    ],
    [
        'nome' => 'Beatriz S.', 
        'cidade' => 'Ourinhos, SP', 
        'texto' => 'Comprei um presente para meu namorado na loja e ele amou. A entrega foi rápida e o produto veio muito bem embalado.', 
        'estrelas' => 5
    ],
];
?>

<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gabriel Imports | Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        /* --- TEMA GABRIEL 2.0 (Dark & Gold) --- */
        :root {
            --color-gold: #C8A165;
            --color-gold-hover: #b08d55;
            --color-bg: #111111;
            --color-card: #1A1A1A;
            --color-border: #333333;
        }
        
        body { background-color: var(--color-bg); color: white; }
        
        .text-gold { color: var(--color-gold); }
        .bg-gold { background-color: var(--color-gold); }
        .border-gold { border-color: var(--color-gold); }
        
        .bg-card-custom { background-color: var(--color-card); }
        .border-custom { border-color: var(--color-border); }

        .header-glass {
            background-color: rgba(17, 17, 17, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--color-border);
        }
        
        .hero-section {
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?= BASE_URL ?>assets/img/gabrielimportsheader2.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
        }

        .map-filter {
            filter: grayscale(100%) invert(92%) contrast(83%);
        }

        /* Animação suave para o carrossel */
        .fade-enter {
            opacity: 0;
            transform: scale(0.95);
        }
        .fade-enter-active {
            opacity: 1;
            transform: scale(1);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
    </style>
</head>
<body class="font-sans antialiased">

    <header class="header-glass fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex-shrink-0 flex items-center">
                    <a href="#home" class="text-2xl font-black tracking-tighter text-gold hover:opacity-80 transition">
                        G. IMPORTS
                    </a>
                </div>

                <nav class="hidden md:flex space-x-8">
                    <a href="#home" class="text-sm font-bold text-white hover:text-gold transition uppercase tracking-wide">Início</a>
                    <a href="#valores" class="text-sm font-bold text-white hover:text-gold transition uppercase tracking-wide">Sobre</a>
                    <a href="#barbershop" class="text-sm font-bold text-white hover:text-gold transition uppercase tracking-wide">Barbearia</a>
                    <a href="#loja" class="text-sm font-bold text-white hover:text-gold transition uppercase tracking-wide">Loja</a>
                    <a href="#contact" class="text-sm font-bold text-white hover:text-gold transition uppercase tracking-wide">Contato</a>
                </nav>

                <div class="flex items-center gap-4">
                    <a href="<?= BASE_URL ?>carrinho" class="relative text-white hover:text-gold transition">
                        <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                        <span class="absolute -top-2 -right-2 bg-gold text-black text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </a>
                    
                    <?php if(isset($_SESSION['admin_id'])): ?>
                        <a href="<?= BASE_URL ?>admin" class="hidden md:inline-flex items-center px-4 py-2 border border-transparent text-sm font-bold rounded-md text-black bg-gold hover:bg-[var(--color-gold-hover)] transition">
                            Admin
                        </a>
                    <?php endif; ?>

                    <button id="mobile-menu-btn" class="md:hidden text-white hover:text-gold focus:outline-none">
                        <i data-lucide="menu" class="w-8 h-8"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div id="mobile-menu" class="hidden md:hidden bg-card-custom border-t border-custom">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="#home" class="block px-3 py-2 text-base font-bold text-white hover:text-gold border-l-4 border-transparent hover:border-gold bg-[#111] rounded-r-md">Início</a>
                <a href="#barbershop" class="block px-3 py-2 text-base font-bold text-white hover:text-gold border-l-4 border-transparent hover:border-gold bg-[#111] rounded-r-md">Barbearia</a>
                <a href="#loja" class="block px-3 py-2 text-base font-bold text-white hover:text-gold border-l-4 border-transparent hover:border-gold bg-[#111] rounded-r-md">Loja</a>
                <a href="#contact" class="block px-3 py-2 text-base font-bold text-white hover:text-gold border-l-4 border-transparent hover:border-gold bg-[#111] rounded-r-md">Contato</a>
            </div>
        </div>
    </header>

    <main>
        <section id="home" class="hero-section flex items-center justify-center text-center px-4 relative">
            <div class="max-w-5xl w-full z-10">
                <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter drop-shadow-2xl mb-6">
                    ESTILO <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#C8A165] to-[#E5C58E]">GABRIEL</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 mb-10 font-light max-w-2xl mx-auto drop-shadow-md">
                    A fusão definitiva entre a barbearia clássica e o streetwear moderno.
                </p>
                <div class="flex flex-col md:flex-row justify-center gap-6">
                    <a href="#barbershop" class="bg-gold text-black px-8 py-4 rounded-lg text-lg font-black hover:bg-[var(--color-gold-hover)] transition transform hover:scale-105 shadow-[0_0_20px_rgba(200,161,101,0.3)] flex items-center justify-center gap-2">
                        <i data-lucide="scissors"></i> AGENDAR CORTE
                    </a>
                    <a href="#loja" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-bold hover:bg-white hover:text-black transition transform hover:scale-105 flex items-center justify-center gap-2">
                        <i data-lucide="shopping-bag"></i> VER PRODUTOS
                    </a>
                </div>
            </div>
        </section>

        <section id="valores" class="py-24 bg-card-custom border-b border-custom">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <span class="text-gold font-bold tracking-widest uppercase text-sm mb-2 block">Nossa Essência</span>
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-8">Missão & Valores</h2>
                <div class="grid md:grid-cols-3 gap-12 mt-12">
                    <div class="p-8 rounded-2xl bg-[#111] border border-custom hover:border-gold transition group">
                        <div class="w-16 h-16 bg-gold/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-gold transition">
                            <i data-lucide="shield" class="w-8 h-8 text-gold group-hover:text-black transition"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Respeito</h3>
                        <p class="text-gray-500">Valorizamos cada cliente e sua história.</p>
                    </div>
                    <div class="p-8 rounded-2xl bg-[#111] border border-custom hover:border-gold transition group">
                        <div class="w-16 h-16 bg-gold/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-gold transition">
                            <i data-lucide="award" class="w-8 h-8 text-gold group-hover:text-black transition"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Lealdade</h3>
                        <p class="text-gray-500">Compromisso com a qualidade e transparência.</p>
                    </div>
                    <div class="p-8 rounded-2xl bg-[#111] border border-custom hover:border-gold transition group">
                        <div class="w-16 h-16 bg-gold/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-gold transition">
                            <i data-lucide="heart" class="w-8 h-8 text-gold group-hover:text-black transition"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-4">Fidelidade</h3>
                        <p class="text-gray-500">Construímos relacionamentos duradouros.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="barbershop" class="py-24 bg-[#111]">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Nossos Profissionais</h2>
                    <p class="text-gray-400">Os mestres por trás do corte perfeito.</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <?php if(empty($barbeiros)): ?>
                        <div class="col-span-3 text-center py-12 bg-card-custom rounded-xl border border-dashed border-custom">
                            <p class="text-gray-500 text-lg">Nenhum barbeiro disponível no momento.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($barbeiros as $barbeiro): ?>
                            <?php 
                                $nome = $barbeiro['nome'] ?? 'Barbeiro';
                                $especialidade = $barbeiro['especialidade'] ?? 'Corte Masculino';
                                $bio = $barbeiro['bio'] ?? 'Especialista em estilo.';
                                $foto = !empty($barbeiro['foto_path']) ? BASE_URL . $barbeiro['foto_path'] : null;
                                $telefoneBruto = $barbeiro['telefone'] ?? '5514998676477';
                                $telefoneLimpo = preg_replace('/[^0-9]/', '', $telefoneBruto);
                            ?>
                            <div class="group relative overflow-hidden rounded-xl bg-card-custom border border-custom hover:border-gold transition duration-300 flex flex-col h-full">
                                <div class="aspect-[4/5] bg-[#222] overflow-hidden relative">
                                    <?php if($foto): ?>
                                        <img src="<?= $foto ?>" alt="<?= htmlspecialchars($nome) ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                                    <?php else: ?>
                                        <div class="flex items-center justify-center h-full text-gray-700">
                                            <i data-lucide="user" class="w-24 h-24 opacity-50"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-90"></div>
                                </div>
                                <div class="absolute bottom-0 left-0 w-full p-6">
                                    <h3 class="text-2xl font-bold text-white mb-1"><?= htmlspecialchars($nome) ?></h3>
                                    <p class="text-gold font-medium text-sm uppercase tracking-wider mb-2"><?= htmlspecialchars($especialidade) ?></p>
                                    <p class="text-gray-400 text-sm line-clamp-2 mb-6"><?= htmlspecialchars($bio) ?></p>
                                    <a href="https://api.whatsapp.com/send?phone=<?= $telefoneLimpo ?>&text=Olá, gostaria de agendar um horário com <?= urlencode($nome) ?>." target="_blank" class="flex items-center justify-center w-full bg-gold/90 hover:bg-gold text-black font-bold py-3 rounded-lg text-sm transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                        <i data-lucide="calendar" class="w-4 h-4 mr-2"></i> AGENDAR AGORA
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section id="loja" class="py-24 bg-card-custom border-y border-custom">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-custom pb-6">
                    <div>
                        <h2 class="text-4xl font-bold text-white mb-2">Destaques da Loja</h2>
                        <p class="text-gray-400">Streetwear exclusivo e limitado.</p>
                    </div>
                    <a href="<?= BASE_URL ?>loja" class="text-gold hover:text-white font-bold transition flex items-center mt-4 md:mt-0">
                        VER TODOS <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php if(empty($produtosDestaque)): ?>
                        <div class="col-span-4 py-12 text-center text-gray-500 border border-dashed border-custom rounded-lg">
                            <i data-lucide="package-open" class="w-12 h-12 mx-auto mb-3 opacity-50"></i>
                            <p>Nenhum produto em destaque no momento.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($produtosDestaque as $prod): 
                             $pNome = $prod['nome'] ?? 'Produto sem nome';
                             $pEstoque = $prod['estoque'] ?? 0;
                             $pPreco = $prod['preco'] ?? 0;
                             $pPromo = $prod['preco_promo'] ?? null;
                             $pImg = !empty($prod['imagem_path']) ? BASE_URL . $prod['imagem_path'] : null;
                        ?>
                        <div class="group bg-[#111] rounded-xl overflow-hidden border border-custom hover:border-gold transition shadow-lg flex flex-col">
                            <div class="h-64 bg-[#1f1f1f] relative overflow-hidden">
                                <?php if($pImg): ?>
                                    <img src="<?= $pImg ?>" alt="<?= htmlspecialchars($pNome) ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                <?php else: ?>
                                    <div class="flex items-center justify-center h-full text-gray-700">
                                        <i data-lucide="image-off" class="w-10 h-10"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if($pPromo): ?>
                                    <div class="absolute top-2 right-2 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded">PROMO</div>
                                <?php endif; ?>
                            </div>
                            <div class="p-5 flex flex-col flex-1">
                                <h3 class="text-white font-bold text-lg mb-1 truncate"><?= htmlspecialchars($pNome) ?></h3>
                                <p class="text-gray-500 text-sm mb-4">Estoque: <?= $pEstoque ?></p>
                                <div class="mt-auto flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <?php if($pPromo): ?>
                                            <span class="text-xs text-gray-500 line-through">R$ <?= number_format($pPreco, 2, ',', '.') ?></span>
                                            <span class="text-xl font-black text-gold">R$ <?= number_format($pPromo, 2, ',', '.') ?></span>
                                        <?php else: ?>
                                            <span class="text-xl font-black text-gold">R$ <?= number_format($pPreco, 2, ',', '.') ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <button class="bg-white/10 hover:bg-gold hover:text-black text-white p-2 rounded-lg transition" title="Adicionar ao Carrinho">
                                        <i data-lucide="plus" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <section id="testimonials" class="py-24 bg-[#111]">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <i data-lucide="quote" class="w-12 h-12 text-gold mx-auto mb-6 opacity-50"></i>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-12">O que dizem sobre nós</h2>
                
                <div class="relative bg-card-custom p-8 md:p-12 rounded-2xl border border-custom shadow-xl overflow-hidden min-h-[300px] flex items-center justify-center">
                    
                    <button onclick="prevTestimonial()" class="absolute left-4 z-10 p-2 rounded-full bg-black/50 text-gold hover:bg-gold hover:text-black transition focus:outline-none">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>

                    <div id="testimonials-wrapper" class="w-full">
                        <?php foreach($depoimentos as $index => $dep): ?>
                            <div class="testimonial-slide transition-opacity duration-500 <?= $index === 0 ? '' : 'hidden' ?>" data-index="<?= $index ?>">
                                <div class="flex justify-center mb-4 text-gold space-x-1">
                                    <?php for($i=0; $i<$dep['estrelas']; $i++): ?>
                                        <i data-lucide="star" class="w-5 h-5 fill-current"></i>
                                    <?php endfor; ?>
                                </div>
                                <p class="text-xl text-gray-300 italic mb-8 leading-relaxed">"<?= htmlspecialchars($dep['texto']) ?>"</p>
                                <h4 class="font-bold text-white text-lg"><?= htmlspecialchars($dep['nome']) ?></h4>
                                <span class="text-sm text-gray-500"><?= htmlspecialchars($dep['cidade']) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button onclick="nextTestimonial()" class="absolute right-4 z-10 p-2 rounded-full bg-black/50 text-gold hover:bg-gold hover:text-black transition focus:outline-none">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>

                <div class="flex justify-center gap-2 mt-8">
                    <?php foreach($depoimentos as $index => $dep): ?>
                        <button onclick="showTestimonial(<?= $index ?>)" 
                                class="testimonial-dot w-3 h-3 rounded-full transition-all duration-300 <?= $index === 0 ? 'bg-gold w-8' : 'bg-gray-700 hover:bg-gray-500' ?>" 
                                id="dot-<?= $index ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
                
                <div class="mt-10">
                    <a href="https://g.page/r/SeuLinkGoogle" target="_blank" class="text-gray-400 hover:text-white text-sm border-b border-gray-600 hover:border-white pb-1 transition">
                        Ver todas as avaliações no Google
                    </a>
                </div>
            </div>
        </section>

        <section id="contact" class="py-24 bg-card-custom relative">
            <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-stretch">
                <div class="flex flex-col justify-center">
                    <span class="text-gold font-bold uppercase tracking-widest text-sm mb-2 block">Localização</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Visite Nossa Base</h2>
                    <p class="text-gray-400 mb-10 text-lg">
                        Estamos localizados no coração de Piraju. O espaço ideal para cuidar do visual e encontrar seu estilo.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4 p-5 rounded-xl bg-[#111] border border-custom hover:border-gold transition group">
                            <i data-lucide="map-pin" class="w-6 h-6 text-gold mt-1 group-hover:scale-110 transition"></i>
                            <div>
                                <h4 class="text-white font-bold">Endereço</h4>
                                <p class="text-gray-400 mt-1">Av. Dr. Domingos Teodoro Gallo, 600<br>Vila Piratininga, Piraju - SP<br>CEP 18805-270</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-5 rounded-xl bg-[#111] border border-custom hover:border-gold transition group">
                            <i data-lucide="clock" class="w-6 h-6 text-gold mt-1 group-hover:scale-110 transition"></i>
                            <div>
                                <h4 class="text-white font-bold flex items-center gap-3">
                                    Horário 
                                    <span id="store-status" class="text-xs px-2 py-0.5 rounded font-bold bg-gray-700 text-gray-300">Verificando...</span>
                                </h4>
                                <p class="text-gray-400 mt-1 text-sm">Seg - Sex: 09h às 19h<br>Sáb: 09h às 17h</p>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-4">
                            <a href="https://wa.me/5514998676477" target="_blank" class="flex-1 bg-green-600 hover:bg-green-700 text-white p-4 rounded-xl font-bold flex items-center justify-center gap-2 transition shadow-lg">
                                <i data-lucide="message-circle" class="w-5 h-5"></i> WhatsApp
                            </a>
                            <a href="tel:14998676477" class="flex-1 bg-[#222] hover:bg-[#333] border border-custom text-white p-4 rounded-xl font-bold flex items-center justify-center gap-2 transition">
                                <i data-lucide="phone" class="w-5 h-5"></i> Ligar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="h-full min-h-[400px] rounded-2xl overflow-hidden shadow-2xl border border-custom relative bg-[#222]">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3674.394142756853!2d-49.38714342395376!3d-23.20038847901851!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c026544d26f253%3A0xc62cc2193b225301!2sAv.%20Dr.%20Domingos%20Teodoro%20Gallo%2C%20600%20-%20Vila%20Piratininga%2C%20Piraju%20-%20SP%2C%2018800-000!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="map-filter">
                    </iframe>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-[#0f0f0f] py-10 border-t border-custom">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-center md:text-left">
                <span class="text-xl font-black text-gold">G. IMPORTS</span>
                <p class="text-gray-500 text-sm mt-1">© <?= date('Y') ?>. Todos os direitos reservados.</p>
            </div>
            <div class="flex gap-6">
                <a href="#" class="text-gray-400 hover:text-gold transition"><i data-lucide="instagram"></i></a>
                <a href="#" class="text-gray-400 hover:text-gold transition"><i data-lucide="facebook"></i></a>
                <a href="#" class="text-gray-400 hover:text-gold transition"><i data-lucide="mail"></i></a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Menu Mobile
        const btnMobile = document.getElementById('mobile-menu-btn');
        const menuMobile = document.getElementById('mobile-menu');
        btnMobile.addEventListener('click', () => {
            menuMobile.classList.toggle('hidden');
        });

        // ============================================
        // LÓGICA DO CARROSSEL DE DEPOIMENTOS
        // ============================================
        const slides = document.querySelectorAll('.testimonial-slide');
        const dots = document.querySelectorAll('.testimonial-dot');
        let currentSlide = 0;
        let slideInterval;

        function showTestimonial(index) {
            // Garante loop infinito
            if (index >= slides.length) currentSlide = 0;
            else if (index < 0) currentSlide = slides.length - 1;
            else currentSlide = index;

            // Atualiza Slides (Esconde todos, mostra atual)
            slides.forEach((slide, i) => {
                if (i === currentSlide) {
                    slide.classList.remove('hidden');
                    // Pequeno delay para animação de fade
                    setTimeout(() => slide.classList.add('fade-enter-active'), 10);
                } else {
                    slide.classList.add('hidden');
                    slide.classList.remove('fade-enter-active');
                }
            });

            // Atualiza Dots
            dots.forEach((dot, i) => {
                if (i === currentSlide) {
                    dot.classList.remove('bg-gray-700', 'w-3');
                    dot.classList.add('bg-gold', 'w-8');
                } else {
                    dot.classList.add('bg-gray-700', 'w-3');
                    dot.classList.remove('bg-gold', 'w-8');
                }
            });
        }

        function nextTestimonial() {
            showTestimonial(currentSlide + 1);
            resetInterval();
        }

        function prevTestimonial() {
            showTestimonial(currentSlide - 1);
            resetInterval();
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(() => nextTestimonial(), 5000);
        }

        // Inicia o carrossel automático
        resetInterval();


        // ============================================
        // STATUS DA LOJA
        // ============================================
        function updateStoreStatus() {
            const now = new Date();
            const day = now.getDay();
            const hour = now.getHours();
            
            const statusEl = document.getElementById('store-status');
            let isOpen = false;

            if (day >= 1 && day <= 5) {
                if (hour >= 9 && hour < 19) isOpen = true;
            } else if (day === 6) {
                if (hour >= 9 && hour < 17) isOpen = true;
            }

            if (isOpen) {
                statusEl.textContent = "ABERTO AGORA";
                statusEl.className = "text-xs px-2 py-0.5 rounded font-bold bg-green-900 text-green-400 border border-green-700 ml-2 animate-pulse";
            } else {
                statusEl.textContent = "FECHADO";
                statusEl.className = "text-xs px-2 py-0.5 rounded font-bold bg-red-900 text-red-400 border border-red-700 ml-2";
            }
        }
        updateStoreStatus();
        setInterval(updateStoreStatus, 60000); 
    </script>
</body>
</html>