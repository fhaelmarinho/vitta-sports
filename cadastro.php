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
                // Nunca armazenamos uma senha original no banco, então o password_hash vai fazer uma criprografia da senha para hash. PASSWORD_HASH é a forma padrão de transformação para hash
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

        } catch(PDOException){
            $erros = "Não foi possível concluir o cadastro";
        } catch(RuntimeException $e){
            $erros = $e -> getMessage();
        }
    }
}

require __DIR__ . '/includes/cabecalho.php';
?>

<main class="login">
    <section>
        <small>FAÇA PARTE DA VITTA</small>
        <h1>SEU PRÓXIMO <br>
        PASSO COMEÇA <br>
        <em>AQUI.</em>
    </h1>
    <p>Crie sua conta e acompanhe sua experiência na Vitta Sports.</p>
    </section>
    <form action="cadastro.php" method="POST">
        <small>NOVA CONTA</small>
        <h2>Cadastre-se</h2>
        <?php if(!empty($erros)): ?>
            <div class="auth-message auth-error">
                <?php foreach($erros as $erro): ?>
                    <p> <?php e($erro) ?></p>
                <?php endforeach; ?>
            </div>    
        <?php endif; ?>
        
        <?php if($sucesso !== ''): ?>
            <div class="auth-message auth-error">
                <?= e($sucesso) ?>
                <p>
                    <a href="login.php">Ir para o Login.</a>
                </p>
            </div>
        <?php endif; ?>
        
        <label for="nome">Nome completo:</label>
        <input type="text" id="nome" name="nome" maxlength="100" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" maxlength="150" required>

        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" minlength="8" required>

        <label for="confirmar_senha">Confirmar senha:</label>
        <input type="password" id="confirmar_senha" name="confirmar_senha" minlength="8" required>

        <button type="submit" class="btn">Criar minha conta!</button>
        <p class="auth-extra">Já possui conta?
        <a href="login.php">Entrar</a>
        </p>
    </form>

</main>