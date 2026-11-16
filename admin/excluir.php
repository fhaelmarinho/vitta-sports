<?php

session_start();
require_once __DIR__ .'/../includes/funcoes.php';
exigirAdmin();
require_once __DIR__ . '/../config/conexao.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if(!$id) exit('ID Inválido!');

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $server = $pdo-> prepare('DELETE FROM produtos WHERE id=:id');
        $server->execute((['id'=>$id]));
        header('Location: produtos.php');
        exit();
    }
    $server=$pdo -> prepare('SELECT nome FROM produtos WHERE id=:id');
    $server -> execute([':id => $id']);
    $produto = $server-> fetch();
    if(!$produto) exit('Produto não encontrado!');

    include __DIR__ . "/../includes/cabecalho.php";
    ?>

    <main class="section narrow">
        <h1>Confirmar Exclusão</h1>
        <p>Deseja realmente excluir <?= e($produto['nome']) ?> ? </p>
        <form method="POST">
            <button class="btn danger">Confirmar Exclusão</button>
        </form>
        <p>
            <a href="produtos.php">Cancelar</a>
        </p>
    </main>

    <?php include __DIR__ . "/../includes/rodape.php";