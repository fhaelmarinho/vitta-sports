<?php

$erros = [];
$sucesso = '';
$nome = '';
$email = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $nome = trim($_POST['nome'] ?? '');    
    $email = trim($_POST['email'] ?? '');    
    $senha = trim($_POST['senha'] ?? '');    
    $confirmarSenha = trim($_POST['confirmarSenha'] ?? '');
    //validações básicas dos dados
    if($nome === ''){
        $erro[] = 'Informe um nome!';
    }    
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $erros[] = 'Informe um email válido!';
    }
    //strlen - verifica o tamanho da variável/caracteres digitados que ela capturou
    if(strlen($senha) <8){
        $erros[] = 'A senha deve ter pelo menos 9 caracteres!';
    }

    if($senha !== $confirmarSenha){
        $erros[] = "As senhas não são iguais";
    }

    if(empty($erros)){
        try{
            require_once __DIR__ . '/config/conexao.php';
            if(!isset($pdo) OR !($pdo instanceof PDO)){
                throw new RuntimeException('A conexão não foi encontada. Verifique se config/conexao.php cria a cariável $pdo.');
            }
            $consulta = $pdo -> prepare(
                'SELECT id FROM usuarios WHERE email = :email LIMIT 1'
            );
            $consulta -> execute([':email' => $email]);

            if($consulta -> fetch()){
                $erros[] = 'Já existe conta cadastrada com esse email'; 
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = 'INSERT INTO usuarios (nome, email, senha, tipo, criado_em) VALUES (:nome, :email, :tipo, NOW())';
                $inserir = $pdo -> prepare($sql);
                $inserir -> execute([
                        ':nome' => $nome,
                        ':email' => $email,
                        ':senha' => $senhaHash,
                        ':tipo' => 'cliente'
                ]);
                $sucesso = 'Cadastro realizado com sucesso! Agora você já pode entrar na sua conta.';
                $nome = '';
                $email = '';    
            }

        }catch(PDOException){
            exit;
        }
    }
}




?>