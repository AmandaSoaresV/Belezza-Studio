<?php
session_start(); 

if (isset($_POST['login'])){
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    if (($email == 'admin@gmail.com') && ($senha == 'admin')) {
        $_SESSION['usuario'] = $email; 
        echo "Login bem-sucedido!";
    } else {
        echo "Login ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Belezza Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
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

                <div class="superficie superficie--elevada p-4 p-md-5">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input
                                type="email"
                                class="form-control form-control-lg"
                                id="email"
                                name="email"
                                placeholder="seu@email.com"
                                required
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
</body>
</html>
