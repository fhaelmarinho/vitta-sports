<?php
session_start();
require_once __DIR__ . '/../includes/funcoes.php';
// once = uma vez
exigirAdmin();
require_once __DIR__ . '/../config/conexao.php';

if($_SERVER['REQUEST_METHOD']!=='POST'){
    header('Location: produtos.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$preco = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
$estoque = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);

if(
    $nome === '' || $preco===null || 
    $preco===false || $preco<0 || 
    $estoque===false || $estoque===null || $estoque<0){
        exit("Dados inválidos!");
}
$produto = [
    ':nome' => $nome,
    ':descricao' => trim($_POST['descricao'] ?? ''),
    ':preco' => $preco,
    ':imagem' => trim($_POST['imagem']??''),
    ':tamanho' => trim($_POST['tamanho']??''),
    ':estoque' => $estoque,
    ':categoria_id'=>($_POST['categoria_id']??'')!==''?(int)$_POST['categoria_id']: null
    ];

if($id){
    $produto[':id']=$id;
    $sql = 'UPDATE produtos SET nome = :nome,
    descricao=:descricao, preco=:preco, imagem=:imagem, tamanho=:tamanho,
    estoque=:estoque, categoria_id=:categoria_id WHERE id=:id';
} else {
    $sql = 'INSERT INTO produtos(nome,descricao,preco,imagem,tamanho,estoque,categoria_id) VALUES (:nome, :descricao, :preco, :imagem, :tamanho, :estoque, :categoria_id)';
}

$banco = $pdo->prepare($sql);
$banco->execute($produto);
header('Location: produtos.php');
exit;