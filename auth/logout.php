<?php
session_start();
$_SESSION=[];

// Verifica se a sessão possui cookies e está ativa - ini_get faz a leitura da configuração da sessão
if(ini_get('session.use_cookies')){
    // Vai recuperar as informações (session_get_cookie_params) da sessão para dentro da variável -> $p
    $p=session_get_cookie_params();
    //Tuo que esta dentro de $p é informação da sessão
    setcookie(
        session_name(), // recupera o nome do cookie da sessão
        '', // define o valor do cookie como vazio
        time()-42000, // define um tempo de expiração
        $p['path'], // caminho do site em que o cookie fuinciona
        $p['domain'], // define se o cookie deve ser enviado somente por HTTPS
        $p['secure'], // impede que o JS acesse diretamente o cookie
        $p['httponly'],);
}
session_destroy();
header('Location: ../index.php');
exit;