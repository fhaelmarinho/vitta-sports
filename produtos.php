<?php 
require './config/conexao.php';
require './includes/funcoes.php';

// pega o id do front e verifica se é um inteiro
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// SELECT p.* c.name AS categoria - busca todos os campos da tabela produtos (p.*) e o nome da tabela categoria (c.name) onde apelidamos de categoria
// FROM produtos p - Tabela onde pesquisamos a maior quantidade de campos
// INNER JOIN categorias c - Junta as tabelas (porque o campo categoria não exite na tabela 'produtos')
// ON p.categoria_id = c.id - ON relaciona os campos de categoria_id (tabela produtos) com o id das tabela categoria
// WHERE p.id =:id' - WHERE é o que estamos presquisando produto;id que é igual ao id da variável de produto (cariável hipotética)

$stmt = $pdo -> prepare('SELECT p.*, c.nome AS categoria
                        FROM produtos p
                        INNER JOIN categorias c ON p.categoria_id = c.id
                        WHERE p.id = :id');

// Alteramos o valor da variável hipotética (:id) para o valor da variável $id real
// ?: Funciona como um if verificando se o valor não é zero
$stmt -> execute([':id' => $id?:0]);

// Captura tudo que vem do SELECT e armazena
$produto = $stmt -> fetch();

include __DIR__ . "/includes/cabecalho.php";
?>

<main class="section">
    <?php if(!$produto): ?>    
        <h1>Produto não encontrado!</h1>
    <?php else: ?>
        <div class="details">
            <div class="visual large">
                VITTA / <?= (int)$produto['id'] ?>
            </div>
            <div>
                <small>
                    <?= e($produto['categoria'] ?? 'VITTA') ?>
                </small>
                <h1>
                    <?= e($produto['nome']) ?>
                </h1>

                <h2>
                    <?= number_format((float) $produto['preco'],2,',','.'); ?>
                </h2>

                <p>
                    Tamanho:
                    <?=  e($produto['tamanho']) ?>
                </p>

                <p>
                    Estoque:
                    <?=  e($produto['estoque']) ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
</main>

<?php include __DIR__ . "/includes/rodape.php" ?>
