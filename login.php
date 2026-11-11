<?php include __DIR__ . "/includes/cabecalho.php" ?>

<main class="login">
    <section>
        <small>BEM-VINDO À VITTA</small>
        <h1>
            SEU PRÓXIMO <br>
            PASSO COMEÇA <br>
            <em>AQUI.</em>
        </h1>
    </section>

    <form action="./auth/autenticar.php" method="POST">
        <small>ÁREA RESTRITA</small>
        <h2>Acesse sua conta</h2>

        <?php if(isset($_GET['erro'])): ?>
            <p class="notice">Email ou senha inválidos</p>
        <?php endif; ?>
        
        <label for="email">EMAIL</label>
        <input type="email" name="email" id="email" required>

        <label for="senha">SENHA</label>
        <input type="password" name="senha" id="senha" required>

        <button type="submit" class="btn">Entrar</button>
    </form>
</main>


<?php include __DIR__ . "/includes/rodape.php" ?>