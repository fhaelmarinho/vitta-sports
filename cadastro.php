<?php
// cadastro.php
// Cadastro de clientes da Vitta Sports.
// O formulário e o processamento ficam neste mesmo arquivo.

$erros = [];
$sucesso = '';
$nome = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmarSenha = $_POST['confirmar_senha'] ?? '';

    // Validações básicas dos dados recebidos.
    if ($nome === '') {
        $erros[] = 'Informe seu nome.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um endereço de e-mail válido.';
    }

    if (strlen($senha) < 8) {
        $erros[] = 'A senha deve ter pelo menos 8 caracteres.';
    }

    if ($senha !== $confirmarSenha) {
        $erros[] = 'As senhas não são iguais.';
    }

    if (empty($erros)) {
        try {
            // O arquivo de conexão deve criar uma variável PDO chamada $pdo.
            require_once __DIR__ . '/config/conexao.php';

            if (!isset($pdo) || !($pdo instanceof PDO)) {
                throw new RuntimeException(
                    'A conexão não foi encontrada. Verifique se config/conexao.php cria a variável $pdo.'
                );
            }

            // Verifica se já existe uma conta com o mesmo e-mail.
            $consulta = $pdo->prepare(
                'SELECT id FROM usuarios WHERE email = :email LIMIT 1'
            );
            $consulta->execute([':email' => $email]);

            if ($consulta->fetch()) {
                $erros[] = 'Já existe uma conta cadastrada com esse e-mail.';
            } else {
                // Nunca armazene a senha original no banco.
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                $sql = 'INSERT INTO usuarios
                            (nome, email, senha, e_admin, criado_em)
                        VALUES
                            (:nome, :email, :senha, :e_admin, NOW())';

                $inserir = $pdo->prepare($sql);
                $inserir->execute([
                    ':nome'  => $nome,
                    ':email' => $email,
                    ':senha' => $senhaHash,
                    ':e_admin'  => 'cliente'
                ]);

                $sucesso = 'Cadastro realizado com sucesso! Agora você já pode entrar na sua conta.';
                $nome = '';
                $email = '';
            }
        } catch (PDOException $e) {
            // Em produção, registre o erro em log em vez de exibir detalhes técnicos.
            $erros[] = 'Não foi possível concluir o cadastro. Tente novamente mais tarde.';
        } catch (RuntimeException $e) {
            $erros[] = $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar conta | Vitta Sports</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        
    </style>
</head>
<body>
    <?php require __DIR__ . '/includes/cabecalho.php'; ?>

    <main class="login">
        <section>
            <small>FAÇA PARTE DA VITTA</small>
            <h1>SEU PRÓXIMO<br>PASSO COMEÇA<br><em>AQUI.</em></h1>
            <p>Crie sua conta e acompanhe sua experiência na Vitta Sports.</p>
        </section>

        <form action="cadastro.php" method="post">
            <small>NOVA CONTA</small>
            <h2>Cadastre-se</h2>

            <?php if (!empty($erros)): ?>
                <div class="auth-message auth-error" role="alert">
                    <?php foreach ($erros as $erro): ?>
                        <p><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($sucesso !== ''): ?>
                <div class="auth-message auth-success" role="status">
                    <?= htmlspecialchars($sucesso, ENT_QUOTES, 'UTF-8') ?>
                    <p><a href="login.php">Ir para o login</a></p>
                </div>
            <?php endif; ?>

            <label for="nome">Nome completo</label>
            <input
                id="nome"
                name="nome"
                type="text"
                value="<?= htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') ?>"
                autocomplete="name"
                maxlength="100"
                required
            >

            <label for="email">E-mail</label>
            <input
                id="email"
                name="email"
                type="email"
                value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                autocomplete="email"
                maxlength="150"
                required
            >

            <label for="senha">Senha</label>
            <input
                id="senha"
                name="senha"
                type="password"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <label for="confirmar_senha">Confirmar senha</label>
            <input
                id="confirmar_senha"
                name="confirmar_senha"
                type="password"
                autocomplete="new-password"
                minlength="8"
                required
            >

            <button class="btn" type="submit">Criar minha conta</button>

            <p class="auth-extra">
                Já possui uma conta?
                <a href="login.php">Entrar</a>
            </p>
        </form>
    </main>

    <?php require __DIR__ . '/includes/rodape.php'; ?>
</body>
</html>
