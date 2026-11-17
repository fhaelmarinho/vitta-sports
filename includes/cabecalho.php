<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vitta Sports</title>
    <link rel="stylesheet" href="./asset/css/style.css">
</head>
<body>

    <?php 
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start(); 
}
$prefixo = (str_contains($_SERVER['PHP_SELF'] ?? '', '/admin/') || str_contains($_SERVER['PHP_SELF'] ?? '', '/auth/')) ? '../' : '';
?>
<header class="site-header">
    <a class="logo" href="<?= $prefixo ?>index.php">VITTA<span>SPORTS</span></a>
    <nav>
        <a href="<?= $prefixo ?>index.php">Início</a>
        <a href="<?= $prefixo ?>loja.php">Loja</a>
    <?php if (isset($_SESSION['usuario_id']) && ($_SESSION['tipo'] ?? '') === 'admin'): ?>
        <a href="<?= $prefixo ?>admin/produtos.php">Admin</a>
    <?php endif; ?>
    <?php if (isset($_SESSION['usuario_id'])): ?>
        <a href="<?= $prefixo ?>auth/logout.php">Sair</a>
    <?php else: ?>
        <a href="<?= $prefixo ?>login.php">Entrar</a>
        <a href="<?= $prefixo ?>cadastro.php">Cadastrar-se</a>
    <?php endif; ?>
    </nav>
</header>

    