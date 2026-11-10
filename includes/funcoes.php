<?php

// função 'e' imprime o texto, removendo os caracteres especiais que o html não reconhece
function e(?string $valor): string{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
    // <"Pietro"> -> Pietro
}

// verifica se o usuário é administrador ou não, se não for, vai para o login.
function exigirAdmin(): void{
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!isset(
        $_SESSION['usuario_id'])
        OR 
        ($_SESSION['tipo'] ?? '')
        !== 'admin'){
        header('Location: ../login.php');
        exit;
    }
    }