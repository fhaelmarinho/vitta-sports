<?php
require 'config/conexao.php';
require 'includes/funcoes.php';

$busca = trim($_GET['busca'] ?? '');

$sql = 'SELECT p.*, c.nome AS categoria FROM produtos p
        INNER JOIN categorias c ON p.categoria_id = c.id';

if($busca !=='')$sql.=' WHERE p.nome LIKE :busca';
$sql.=' ORDER BY p.nome';

$stmt = $pdo->prepare($sql);
if($busca !=='')$stmt->bindValue(':busca', '%' .$busca.'%');
$stmt ->execute();
$produtos = $stmt->fetchAll();

include __DIR__ . 'includes/rodape.php';
?>

<main class="section">
    <small>VITTA / COLEÇÃO</small>
    <h1>Encontre seu próximo movimento.</h1>
    <form action="get" class="search">
        <input type="text" name="busca" value="<?= e($busca) ?>" placeholder="Buscar produto...">
        <button class="btn">Buscar</button>
    </form>
    <div class="products">
        <?php foreach($produtos as $p): ?>
            <a class="product" href="produto.php?id=<?php (int)$p['id'] ?>">
                <div class="visual">
                    <?= e($p['categoria']??'VITTA') ?>
                </div>
                <h3>
                    <? e($p['nome']) ?>
                </h3>
                <b>R$ <?= number_format((float)$p['preco'],2,',','.' ) ?></b>
            </a>
        <?php endforeach; ?>
        <?php if(!$produtos): ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>    
    </div>
</main>

<?php require __DIR__ . '/../includes/rodape.php'; ?>