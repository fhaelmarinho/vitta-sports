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

$stmt -> execute([':id' => $id?:0]);

$produto = $stmt -> fetch();