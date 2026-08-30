<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';
require_once __DIR__ . '/../../../includes/sessao.php';

$destino = destinoInterno($_GET['destino'] ?? '');
$erro = '';
$email = '';

$mensagens = mensagensDeRetorno($_GET, [
    'cadastrado' => ['tipo' => 'success', 'texto' => 'Conta criada com sucesso. Faça login para continuar.'],
    'saiu' => ['tipo' => 'success', 'texto' => 'Você saiu da sua conta.'],
    'precisalogin' => ['tipo' => 'warning', 'texto' => 'Faça login para continuar.'],
]);

if (estaLogado()) {
    header('Location: ' . ($destino ?? paginaInicialDoPerfil(usuarioLogado()['tipo_perfil'])));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = (string) ($_POST['senha'] ?? '');

    try {
        $usuario = obterUsuarioPorEmail($pdo, $email);

        if ($usuario === null || !password_verify($senha, $usuario['hash_senha'])) {
            $erro = 'E-mail ou senha incorretos.';
        } else {
            entrarNaSessao($usuario);
            header('Location: ' . ($destino ?? paginaInicialDoPerfil($usuario['tipo_perfil'])));
            exit;
        }
    } catch (PDOException $e) {
        $erro = 'Não foi possível entrar agora, tente novamente.';
    }
}
?>
<?php
    $tituloPagina = 'Login';
    $usarFormularios = true;
?>
    <?php
        $header = __DIR__ . '/../../../includes/header.php';
        if (file_exists($header)) {
            include $header;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <main class="container-marca secao">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="text-center mb-4">
                    <p class="eyebrow cor-marca justify-content-center">Acesso</p>
                    <h1 class="display-marca" style="font-size: clamp(1.9rem, 4vw, 2.4rem);">Entrar na conta</h1>
                    <p class="texto-lead">Acesse sua conta para gerenciar agendamentos.</p>
                </div>

                <?php include __DIR__ . '/../../../includes/alertas.php'; ?>

                <?php if ($erro !== ''): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?php echo htmlspecialchars($erro); ?>
                </div>
                <?php endif; ?>

                <div class="superficie superficie--elevada p-4 p-md-5">
                    <form method="POST" action="/login<?php echo $destino !== null ? '?destino=' . urlencode($destino) : ''; ?>" name="formLogin" data-parsley-validate="">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                                type="email"
                                class="form-control form-control-lg"
                                id="email"
                                name="email"
                                value="<?php echo htmlspecialchars($email); ?>"
                                placeholder="seu@email.com"
                                required
                                data-parsley-required-message="Digite seu email"
                                data-parsley-type-message="Insira um email válido"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="senha" class="form-label">Senha</label>
                            <input
                                type="password"
                                class="form-control form-control-lg"
                                id="senha"
                                name="senha"
                                placeholder="Sua senha"
                                required
                                data-parsley-required-message="Digite a senha"
                            >
                        </div>

                        <button type="submit" class="btn-marca w-100">
                            Entrar <i class="ph ph-sign-in"></i>
                        </button>

                        <p class="text-center mt-4 mb-0" style="color: var(--texto-secundario);">
                            Não tem conta?
                            <a href="/usuarios/cadastrar" class="cor-marca fw-semibold text-decoration-none">Cadastre-se</a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
        $footer = __DIR__ . '/../../../includes/footer.php';
        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
</body>
</html>
