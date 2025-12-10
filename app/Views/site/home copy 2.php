<?php
// =========================================================================
// === HOME: INSTITUCIONAL + NOVIDADES + CONTATO RESTAURADO ===
// =========================================================================
use App\Core\Database;

if (!defined('BASE_URL')) define('BASE_URL', '/'); 

try {
    $conn = Database::getConnection();

    // 1. Barbeiros
    $barbeiros = $conn->query("SELECT * FROM barbeiros ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

    // 2. Novidades (Apenas os 4 últimos produtos)
    $sqlNovidades = "SELECT * FROM produtos WHERE estoque > 0 ORDER BY id DESC LIMIT 4";
    $novidades = $conn->query($sqlNovidades)->fetchAll(PDO::FETCH_ASSOC);

} catch (\Exception $e) {
    $barbeiros = []; $novidades = [];
}

// 3. Depoimentos (Dados Estáticos Restaurados)
$depoimentos = [
    ['nome' => 'Marcos F.', 'cidade' => 'Piraju, SP', 'texto' => 'O melhor corte que já fiz. O ambiente é top e os profissionais entendem muito de visagismo. Virei cliente fiel!', 'estrelas' => 5],
    ['nome' => 'Ana C.', 'cidade' => 'Avaré, SP', 'texto' => 'Adorei a nova coleção streetwear! As peças têm uma qualidade incrível e o caimento é perfeito.', 'estrelas' => 5],
    ['nome' => 'João P.', 'cidade' => 'Cerqueira César, SP', 'texto' => 'Ambiente muito resenha, cerveja gelada e o degradê na régua. A Gabriel Imports elevou o nível da região.', 'estrelas' => 5],
    ['nome' => 'Beatriz S.', 'cidade' => 'Ourinhos, SP', 'texto' => 'Comprei um presente para meu namorado na loja e ele amou. A entrega foi rápida e o produto veio muito bem embalado.', 'estrelas' => 5],
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
        :root { --color-gold: #C8A165; --color-bg: #111111; --color-card: #1A1A1A; --color-border: #333333; }
        body { background-color: var(--color-bg); color: white; }
        .text-gold { color: var(--color-gold); } .bg-gold { background-color: var(--color-gold); }
        .border-gold { border-color: var(--color-gold); }
        .bg-card-custom { background-color: var(--color-card); }
        .header-glass { background-color: rgba(17, 17, 17, 0.95); backdrop-filter: blur(10px); border-bottom: 1px solid #333; }
        .hero-bg { background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('<?= BASE_URL ?>assets/img/gabrielimportsheader2.png'); background-size: cover; background-position: center; min-height: 100vh; }
        .map-filter { filter: grayscale(100%) invert(92%) contrast(83%); }
    </style>
</head>
<body class="font-sans antialiased">

    <header class="header-glass fixed top-0 w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-20">
            <a href="<?= BASE_URL ?>home" class="text-2xl font-black text-gold tracking-tighter">G. IMPORTS</a>
            
            <nav class="hidden md:flex space-x-8">
                <a href="#home" class="font-bold hover:text-gold uppercase text-sm">Início</a>
                <a href="#valores" class="font-bold hover:text-gold uppercase text-sm">Sobre</a>
                <a href="#barbershop" class="font-bold hover:text-gold uppercase text-sm">Equipe</a>
                <a href="<?= BASE_URL ?>loja" class="font-bold text-gold uppercase text-sm border-b border-gold">Store (Loja)</a>
                <a href="#contact" class="font-bold hover:text-gold uppercase text-sm">Contato</a>
            </nav>

            <div class="flex items-center gap-4">
                <button onclick="toggleCart()" class="relative hover:text-gold group">
                    <i data-lucide="shopping-cart" class="w-6 h-6 group-hover:scale-110 transition"></i>
                    <span id="cart-count" class="absolute -top-2 -right-2 bg-gold text-black text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center opacity-0 transition-opacity">0</span>
                </button>
                
                <button id="mobile-menu-btn" class="md:hidden text-white hover:text-gold"><i data-lucide="menu"></i></button>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden bg-[#1A1A1A] border-t border-[#333]">
            <div class="px-4 py-4 space-y-2">
                <a href="#home" class="block py-2 hover:text-gold">Início</a>
                <a href="#barbershop" class="block py-2 hover:text-gold">Barbearia</a>
                <a href="<?= BASE_URL ?>loja" class="block py-2 text-gold font-bold">Store</a>
                <a href="#contact" class="block py-2 hover:text-gold">Contato</a>
            </div>
        </div>
    </header>

<main>
        <section id="home" class="relative flex items-center justify-center min-h-screen overflow-hidden">
            
            <div class="absolute inset-0 z-0">
                <img src="<?= BASE_URL ?>assets/img/gabrielimportshome.png" alt="Background Gabriel Imports" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-black/80 via-black/50 to-[#111]"></div>
            </div>

            <div class="relative z-10 container mx-auto px-4 text-center mt-10">
                
                <div class="mb-8 animate-fade-in-down flex justify-center">
                    <img src="<?= BASE_URL ?>assets/img/logosombra.png" 
                         alt="Gabriel Imports Logo" 
                         class="w-4/5 max-w-[320px] md:max-w-[500px] drop-shadow-[0_0_15px_rgba(200,161,101,0.3)] hover:drop-shadow-[0_0_25px_rgba(200,161,101,0.6)] transition-all duration-500 transform hover:scale-105">
                </div>

                <p class="text-gray-300 text-lg md:text-2xl mb-12 font-light max-w-2xl mx-auto tracking-wide animate-fade-in-up delay-200 drop-shadow-md">
                    A fusão definitiva entre a barbearia clássica e o streetwear moderno.
                </p>

                <div class="flex flex-col md:flex-row justify-center gap-5 animate-fade-in-up delay-400">
                    
                    <a href="https://wa.me/5514998676477" target="_blank"
                       class="group bg-[#C8A165] text-black px-10 py-4 rounded-xl font-black text-lg hover:bg-[#b08d55] transition-all duration-300 shadow-[0_0_20px_rgba(200,161,101,0.2)] hover:shadow-[0_0_30px_rgba(200,161,101,0.4)] transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i data-lucide="calendar" class="w-5 h-5 group-hover:rotate-12 transition-transform"></i>
                        AGENDAR CORTE
                    </a>

                    <a href="<?= BASE_URL ?>loja"
                       class="group bg-white/5 border border-white/30 text-white px-10 py-4 rounded-xl font-bold text-lg hover:bg-white hover:text-black hover:border-white transition-all duration-300 backdrop-blur-sm transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <i data-lucide="shopping-bag" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        VER PRODUTOS
                    </a>

                </div>
            </div>
            
            <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce text-gray-500">
                <i data-lucide="chevron-down" class="w-8 h-8"></i>
            </div>
        </section>

        <section class="py-24 bg-[#111] border-b border-[#333]">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12">
                    <div>
                        <span class="text-gold font-bold tracking-widest uppercase text-sm mb-2 block">Acabou de Chegar</span>
                        <h2 class="text-4xl font-bold text-white">Novidades</h2>
                    </div>
                    <a href="<?= BASE_URL ?>loja" class="text-white hover:text-gold font-bold flex items-center gap-2 mt-4 md:mt-0 transition">
                        Ver Catálogo Completo <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php if(empty($novidades)): ?>
                        <div class="col-span-4 text-center text-gray-500 py-10">Em breve novos lançamentos.</div>
                    <?php else: ?>
                        <?php foreach($novidades as $prod): 
                            $preco = $prod['preco_promo'] ?? $prod['preco'];
                            $img = !empty($prod['imagem_path']) ? BASE_URL . ltrim($prod['imagem_path'], '/') : null;
                        ?>
                        <div class="bg-[#1A1A1A] rounded-xl overflow-hidden border border-[#333] hover:border-gold transition group flex flex-col h-full">
                            <div class="h-64 bg-[#222] relative overflow-hidden">
                                <?php if($img): ?>
                                    <img src="<?= $img ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-105">
                                <?php endif; ?>
                                <span class="absolute top-2 left-2 bg-gold text-black text-[10px] font-black px-2 py-1 rounded uppercase">Novo</span>
                            </div>
                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="font-bold text-white truncate mb-2"><?= htmlspecialchars($prod['nome']) ?></h3>
                                <div class="mt-auto flex justify-between items-center">
                                    <span class="text-xl font-black text-gold">R$ <?= number_format($preco, 2, ',', '.') ?></span>
                                    <button onclick='addToCart(<?= json_encode(["id" => $prod["id"], "nome" => $prod["nome"], "preco" => $preco, "imagem" => $prod["imagem_path"]]) ?>)' 
                                        class="bg-[#333] hover:bg-gold hover:text-black text-white p-2 rounded-lg transition">
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

        <section id="barbershop" class="py-24 bg-[#1A1A1A]">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-white mb-4">Nossos Profissionais</h2>
                    <p class="text-gray-400">Os mestres por trás do corte perfeito.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <?php foreach($barbeiros as $b): 
                        $foto = !empty($b['foto_path']) ? BASE_URL . ltrim($b['foto_path'], '/') : null;
                    ?>
                    <div class="bg-[#111] rounded-xl border border-[#333] p-6 text-center hover:border-gold transition group">
                        <div class="w-24 h-24 mx-auto rounded-full overflow-hidden mb-4 border-2 border-gold relative">
                            <?php if($foto): ?>
                                <img src="<?= $foto ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="bg-gray-700 w-full h-full flex items-center justify-center"><i data-lucide="user"></i></div>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-xl font-bold text-white"><?= htmlspecialchars($b['nome']) ?></h3>
                        <p class="text-gold text-sm font-bold uppercase mb-4"><?= htmlspecialchars($b['especialidade']) ?></p>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/','',$b['telefone']) ?>" target="_blank" class="text-sm bg-[#333] hover:bg-gold hover:text-black px-4 py-2 rounded text-white transition block w-full">AGENDAR</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section id="testimonials" class="py-24 bg-[#111]">
            <div class="max-w-4xl mx-auto px-4 text-center">
                <i data-lucide="quote" class="w-12 h-12 text-gold mx-auto mb-6 opacity-50"></i>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-12">Experiência dos Clientes</h2>
                
                <div class="relative bg-card-custom p-8 md:p-12 rounded-2xl border border-custom shadow-xl">
                    <button onclick="prevTestimonial()" class="absolute left-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/50 text-gold hover:bg-gold hover:text-black transition focus:outline-none z-10">
                        <i data-lucide="chevron-left" class="w-6 h-6"></i>
                    </button>

                    <div id="testimonials-wrapper">
                        <?php foreach($depoimentos as $index => $dep): ?>
                            <div class="testimonial-slide <?= $index === 0 ? '' : 'hidden' ?>" data-index="<?= $index ?>">
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

                    <button onclick="nextTestimonial()" class="absolute right-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-black/50 text-gold hover:bg-gold hover:text-black transition focus:outline-none z-10">
                        <i data-lucide="chevron-right" class="w-6 h-6"></i>
                    </button>
                </div>
                
                <div class="mt-8">
                    <a href="https://g.page/r/SeuLinkGoogle" target="_blank" class="text-gray-400 hover:text-white text-sm border-b border-gray-600 hover:border-white pb-1 transition">
                        Ver mais avaliações no Google
                    </a>
                </div>
            </div>
        </section>

        <section id="contact" class="py-24 bg-card-custom relative">
            <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-start">
                
                <div>
                    <span class="text-gold font-bold uppercase tracking-widest text-sm mb-2 block">Localização</span>
                    <h2 class="text-4xl md:text-5xl font-bold text-white mb-8">Visite Nossa Base</h2>
                    <p class="text-gray-400 mb-10 text-lg">
                        Estamos localizados no coração de Piraju. O espaço ideal para cuidar do visual e encontrar seu estilo.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-[#111] border border-custom">
                            <i data-lucide="map-pin" class="w-6 h-6 text-gold mt-1"></i>
                            <div>
                                <h4 class="text-white font-bold">Endereço</h4>
                                <p class="text-gray-400">Av. Dr. Domingos Teodoro Gallo, 600<br>Vila Piratininga, Piraju - SP<br>CEP 18805-270</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4 p-4 rounded-xl bg-[#111] border border-custom">
                            <i data-lucide="clock" class="w-6 h-6 text-gold mt-1"></i>
                            <div>
                                <h4 class="text-white font-bold flex items-center gap-3">
                                    Horário 
                                    <span id="store-status" class="text-xs px-2 py-0.5 rounded font-bold bg-gray-700 text-gray-300">Verificando...</span>
                                </h4>
                                <p class="text-gray-400 text-sm mt-1">Seg - Sex: 09h às 19h<br>Sáb: 09h às 17h</p>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <a href="https://wa.me/5514998676477" target="_blank" class="flex-1 bg-green-600 hover:bg-green-700 text-white p-4 rounded-xl font-bold flex items-center justify-center gap-2 transition">
                                <i data-lucide="message-circle" class="w-5 h-5"></i> WhatsApp
                            </a>
                            <a href="tel:14998676477" class="flex-1 bg-[#222] hover:bg-[#333] border border-custom text-white p-4 rounded-xl font-bold flex items-center justify-center gap-2 transition">
                                <i data-lucide="phone" class="w-5 h-5"></i> Ligar
                            </a>
                        </div>
                    </div>
                </div>

                <div class="h-full min-h-[400px] rounded-2xl overflow-hidden shadow-2xl border border-custom map-container relative bg-[#222]">
                    <div class="absolute inset-0 flex items-center justify-center text-gray-500 z-0">
                        <span class="animate-pulse">Carregando Mapa...</span>
                    </div>
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3674.394142756853!2d-49.38714342395376!3d-23.20038847901851!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94c026544d26f253%3A0xc62cc2193b225301!2sAv.%20Dr.%20Domingos%20Teodoro%20Gallo%2C%20600%20-%20Vila%20Piratininga%2C%20Piraju%20-%20SP%2C%2018800-000!5e0!3m2!1spt-BR!2sbr!4v1700000000000!5m2!1spt-BR!2sbr" 
                        width="100%" 
                        height="100%" 
                        style="border:0; position: relative; z-index: 10;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        class="map-filter"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>

            </div>
        </section>

    </main>

    <?php include __DIR__ . '/includes/footer_scripts.php'; ?>

    <script>
        // === LÓGICA DO CARROSSEL DE DEPOIMENTOS ===
        const slides = document.querySelectorAll('.testimonial-slide');
        let currentSlide = 0;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.classList.toggle('hidden', i !== index);
            });
            currentSlide = index;
        }

        function nextTestimonial() {
            showSlide((currentSlide + 1) % slides.length);
        }

        function prevTestimonial() {
            showSlide((currentSlide - 1 + slides.length) % slides.length);
        }

        // === LÓGICA DE STATUS DA LOJA (Aberto/Fechado) ===
        function updateStoreStatus() {
            const now = new Date();
            const day = now.getDay(); // 0 = Domingo, 1 = Segunda, ...
            const hour = now.getHours();
            
            const statusEl = document.getElementById('store-status');
            let isOpen = false;

            // Seg-Sex: 9h às 19h
            if (day >= 1 && day <= 5) {
                if (hour >= 9 && hour < 19) isOpen = true;
            } 
            // Sábado: 9h às 17h
            else if (day === 6) {
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