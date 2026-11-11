<?php 
// inicia a sessão no navegador
session_start();
// requer a conexão com o banco
require __DIR__ . '/../config/conexao.php';

// verifica se o método que está vindo do login é um POST, caso não seja é feito o direcionamento do login
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: ../login.php');
}

// Variavel de email -> recebe o email do front, tratado e verificado por um filtro que garante que é um email válido
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

// filter_input -> aplica um filtro para validar o inpur/dado de entrada e exige 3 informações primordiais - 1o. Exige um dado vindo de uma rquisição, 2o. Variável ou campo a ser verificado e 3o. o tipo de filtro a ser validado.

$senha = $_POST['senha'] ?? '';

$stmt = $pdo -> prepare('SELECT id, nome, senha, tipo FROM usuarios WHERE email = :email LIMIT 1');

$stmt -> execute([':email' => $email?:'']);

// fetch captura do banco o resultado executado anteriormente
$usuario = $stmt -> fetch(); // 1 linha captura 1 linha

// verifica se há um retorno dentro da virável usuário e depois verifica se a senha não está errada com o o password_verify (sempre com as duas informações que serão comparadas)
if (!$usuario OR !password_verify($senha, $usuario['senha'])){
    header('Location: ../login.php?erro=1');
    exit();
}

session_regenerate_id(true);
$_SESSION['usuario_id'] = (int)$usuario['id'];
$_SESSION['usuario_nome'] = $usuario['nome'];
$_SESSION['tipo'] = $usuario['tipo'];
header('Location: ../admin/produtos.php');
exit;

?>