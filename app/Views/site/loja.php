<?php
// Lógica de Filtros e Busca (Smart View Pattern)
use App\Core\Database;

if (!defined('BASE_URL')) define('BASE_URL', '/gabriel-imports/public/'); 

$busca     = $_GET['q'] ?? '';
$catId     = $_GET['cat'] ?? null;
$ordem     = $_GET['sort'] ?? 'newest';
$minPrice  = $_GET['min_price'] ?? null;
$maxPrice  = $_GET['max_price'] ?? null;
$page      = max(1, intval($_GET['page'] ?? 1));
$perPage   = 12; 
$offset    = ($page - 1) * $perPage;

try {
    $conn = Database::getConnection();
    // Categorias
    $categorias = $conn->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Query Base de Produtos
    $sqlBase = "FROM produtos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.estoque > 0";
    $params = [];

    // Filtros
    if ($busca) { $sqlBase .= " AND (p.nome LIKE :q OR p.descricao LIKE :q)"; $params[':q'] = "%$busca%"; }
    if ($catId) { $sqlBase .= " AND p.categoria_id = :cat"; $params[':cat'] = $catId; }
    if ($minPrice) { $sqlBase .= " AND p.preco >= :min"; $params[':min'] = $minPrice; }
    if ($maxPrice) { $sqlBase .= " AND p.preco <= :max"; $params[':max'] = $maxPrice; }

    // Ordenação
    $sqlOrder = " ORDER BY p.id DESC"; 
    if ($ordem === 'price_asc')  $sqlOrder = " ORDER BY COALESCE(p.preco_promo, p.preco) ASC";
    if ($ordem === 'price_desc') $sqlOrder = " ORDER BY COALESCE(p.preco_promo, p.preco) DESC";
    if ($ordem === 'alpha')      $sqlOrder = " ORDER BY p.nome ASC";

    // Paginação
    $stmtCount = $conn->prepare("SELECT COUNT(*) $sqlBase");
    $stmtCount->execute($params);
    $totalProd = $stmtCount->fetchColumn();
    $totalPages = ceil($totalProd / $perPage);

    // Busca Final
    $sqlFinal = "SELECT p.*, c.nome as cat_nome $sqlBase $sqlOrder LIMIT $perPage OFFSET $offset";
    $stmt = $conn->prepare($sqlFinal);
    $stmt->execute($params);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (\Exception $e) { $produtos = []; $totalProd = 0; }

function buildQuery($exclude = '') {
    $p = $_GET; unset($p[$exclude]); return http_build_query($p);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Loja Oficial | Gabriel Imports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#111] text-white font-sans flex flex-col min-h-screen">

    <header class="fixed top-0 w-full z-50 bg-[#111] border-b border-[#333] shadow-lg">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-20">
            <a href="<?= BASE_URL ?>home" class="flex items-center gap-2 group">
                <span class="text-2xl font-black text-white tracking-tighter group-hover:text-[#C8A165] transition-colors">GABRIEL IMPORTS</span>
            </a>
            
            <nav class="hidden md:flex items-center space-x-4">
                <a href="<?= BASE_URL ?>home" class="text-sm font-bold text-gray-400 hover:text-white transition uppercase">Home</a>
                <span class="text-[#333]">|</span>
                <span class="text-sm font-bold text-[#C8A165] uppercase tracking-wide">Catálogo Oficial</span>
            </nav>

            <div class="flex items-center gap-4">
                <button onclick="toggleFilters()" class="md:hidden text-gray-300 hover:text-white p-2">
                    <i data-lucide="filter" class="w-6 h-6"></i>
                </button>
                <button class="relative text-[#C8A165] hover:text-white transition p-2 group">
                    <i data-lucide="shopping-cart" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
                    <span class="absolute top-0 right-0 bg-[#C8A165] text-black text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">0</span>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-grow pt-28 pb-12 px-4 max-w-7xl mx-auto w-full flex flex-col md:flex-row gap-8">
        
        <aside id="filter-sidebar" class="hidden md:block w-full md:w-64 flex-shrink-0 space-y-8 p-4 md:p-0 bg-[#1A1A1A] md:bg-transparent rounded-xl md:rounded-none absolute md:relative top-24 md:top-0 left-0 z-40 shadow-2xl md:shadow-none border border-[#333] md:border-none">
            
            <div class="flex justify-between md:hidden mb-4 border-b border-[#333] pb-2">
                <h3 class="font-bold text-white">Filtros</h3>
                <button onclick="toggleFilters()" class="text-gray-400"><i data-lucide="x"></i></button>
            </div>

            <form class="relative">
                <input type="text" name="q" value="<?= htmlspecialchars($busca) ?>" placeholder="Buscar produto..." class="w-full bg-[#1A1A1A] border border-[#333] p-3 rounded-lg text-white outline-none focus:border-[#C8A165] placeholder-gray-600 transition-colors">
                <button class="absolute right-3 top-3 text-[#C8A165]"><i data-lucide="search" class="w-5 h-5"></i></button>
            </form>

            <div>
                <h3 class="font-bold text-white mb-4 text-lg border-b border-[#333] pb-2 inline-block">Categorias</h3>
                <div class="space-y-2">
                    <a href="?<?= buildQuery('cat') ?>" class="block px-3 py-2 rounded-lg text-sm font-medium transition <?= !$catId ? 'bg-[#C8A165] text-black font-bold' : 'text-gray-400 hover:bg-[#1A1A1A] hover:text-white border border-transparent hover:border-[#333]' ?>">
                        Todos os Produtos
                    </a>
                    <?php foreach($categorias as $c): ?>
                        <a href="?cat=<?= $c['id'] ?>&<?= buildQuery('cat') ?>" class="block px-3 py-2 rounded-lg text-sm font-medium transition <?= $catId == $c['id'] ? 'bg-[#C8A165] text-black font-bold' : 'text-gray-400 hover:bg-[#1A1A1A] hover:text-white border border-transparent hover:border-[#333]' ?>">
                            <?= htmlspecialchars($c['nome']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <form action="" method="GET">
                <h3 class="font-bold text-white mb-4 text-lg border-b border-[#333] pb-2 inline-block">Faixa de Preço</h3>
                <?php if($busca): ?><input type="hidden" name="q" value="<?= $busca ?>"><?php endif; ?>
                <?php if($catId): ?><input type="hidden" name="cat" value="<?= $catId ?>"><?php endif; ?>
                
                <div class="flex items-center gap-2 mb-3">
                    <input type="number" name="min_price" placeholder="Mín" value="<?= $minPrice ?>" class="w-full bg-[#1A1A1A] border border-[#333] rounded p-2 text-sm text-white outline-none focus:border-[#C8A165]">
                    <span class="text-gray-500">-</span>
                    <input type="number" name="max_price" placeholder="Máx" value="<?= $maxPrice ?>" class="w-full bg-[#1A1A1A] border border-[#333] rounded p-2 text-sm text-white outline-none focus:border-[#C8A165]">
                </div>
                <button type="submit" class="w-full bg-[#333] hover:bg-[#C8A165] hover:text-black text-white text-xs font-bold py-3 rounded transition uppercase tracking-wide">Aplicar Filtro</button>
            </form>
        </aside>

        <div class="flex-1">
            
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4 pb-4 border-b border-[#333]">
                <div class="text-sm text-gray-400">
                    Mostrando <span class="text-white font-bold"><?= count($produtos) ?></span> de <?= $totalProd ?>
                </div>
                
                <form action="" method="GET" class="w-full sm:w-auto">
                    <?php foreach($_GET as $k=>$v): if(!in_array($k, ['sort','page'])) echo "<input type='hidden' name='$k' value='$v'>"; endforeach; ?>
                    <select name="sort" onchange="this.form.submit()" class="w-full sm:w-48 bg-[#1A1A1A] border border-[#333] text-white text-sm px-4 py-2 rounded-lg focus:border-[#C8A165] outline-none cursor-pointer">
                        <option value="newest" <?= $ordem == 'newest' ? 'selected' : '' ?>>Mais Recentes</option>
                        <option value="price_asc" <?= $ordem == 'price_asc' ? 'selected' : '' ?>>Menor Preço</option>
                        <option value="price_desc" <?= $ordem == 'price_desc' ? 'selected' : '' ?>>Maior Preço</option>
                        <option value="alpha" <?= $ordem == 'alpha' ? 'selected' : '' ?>>A - Z</option>
                    </select>
                </form>
            </div>

            <?php if(empty($produtos)): ?>
                <div class="text-center py-24 bg-[#1A1A1A] rounded-xl border border-dashed border-[#333]">
                    <i data-lucide="package-search" class="w-16 h-16 mx-auto mb-4 text-gray-600"></i>
                    <h3 class="text-xl font-bold text-gray-400">Nenhum produto encontrado.</h3>
                    <a href="<?= BASE_URL ?>loja" class="inline-block mt-4 text-[#C8A165] text-sm font-bold hover:underline">Limpar Filtros</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach($produtos as $prod): 
                        $preco = $prod['preco_promo'] ?? $prod['preco'];
                        $img = !empty($prod['imagem_path']) ? BASE_URL . ltrim($prod['imagem_path'], '/') : null;
                    ?>
                    <div class="group bg-[#1A1A1A] rounded-xl overflow-hidden border border-[#333] hover:border-[#C8A165]/50 transition duration-300 flex flex-col relative shadow-lg">
                        
                        <div class="aspect-[4/5] bg-[#0f0f0f] relative overflow-hidden">
                            <?php if($img): ?>
                                <img src="<?= $img ?>" class="w-full h-full object-cover transition duration-700 group-hover:scale-110 opacity-90 group-hover:opacity-100">
                            <?php else: ?>
                                <div class="flex items-center justify-center h-full text-gray-700"><i data-lucide="image-off"></i></div>
                            <?php endif; ?>
                            
                            <?php if(!empty($prod['preco_promo'])): ?>
                                <span class="absolute top-2 left-2 bg-red-600 text-white text-[10px] font-black px-2 py-1 rounded shadow">PROMO</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-4 flex flex-col flex-grow">
                            <span class="text-[10px] text-gray-500 uppercase tracking-widest font-bold mb-1"><?= htmlspecialchars($prod['cat_nome'] ?? 'Geral') ?></span>
                            <h3 class="font-bold text-white text-sm md:text-base leading-tight mb-3 line-clamp-2 group-hover:text-[#C8A165] transition-colors"><?= htmlspecialchars($prod['nome']) ?></h3>
                            
                            <div class="mt-auto pt-3 border-t border-[#333] flex items-center justify-between">
                                <div class="flex flex-col">
                                    <?php if(!empty($prod['preco_promo'])): ?>
                                        <span class="text-[10px] text-gray-500 line-through">R$ <?= number_format($prod['preco'], 2, ',', '.') ?></span>
                                    <?php endif; ?>
                                    <span class="text-lg font-black text-[#C8A165]">R$ <?= number_format($preco, 2, ',', '.') ?></span>
                                </div>
                                <button class="bg-[#222] hover:bg-[#C8A165] hover:text-black text-white w-10 h-10 rounded-lg flex items-center justify-center transition shadow-md active:scale-95 border border-[#333] hover:border-[#C8A165]">
                                    <i data-lucide="plus" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if($totalPages > 1): ?>
                <div class="mt-12 flex justify-center gap-2">
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>&<?= buildQuery('page') ?>" 
                           class="px-4 py-2 border rounded font-bold text-sm transition <?= $i == $page ? 'bg-[#C8A165] text-black border-[#C8A165]' : 'bg-[#1A1A1A] border-[#333] text-gray-400 hover:text-white hover:border-white' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

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

    <script>
        lucide.createIcons();
        function toggleFilters() {
            document.getElementById('filter-sidebar').classList.toggle('hidden');
        }
    </script>
</body>
</html>