<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário — Belezza Studio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        $header = __DIR__ . '/../layouts/header.php';
        if (file_exists($header)) {
            include $header;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <main class="container-marca secao">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="text-center mb-4">
                    <p class="eyebrow cor-marca justify-content-center">Nova conta</p>
                    <h1 class="display-marca" style="font-size: clamp(1.9rem, 4vw, 2.4rem);">Cadastrar usuário</h1>
                    <p class="texto-lead">Preencha os dados abaixo para criar sua conta.</p>
                </div>

                <div class="superficie superficie--elevada p-4 p-md-5">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="nome" class="form-label">Nome completo</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="nome"
                                    name="nome"
                                    placeholder="Digite o nome completo"
                                    maxlength="40"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="cpf" class="form-label">CPF</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="cpf"
                                    name="cpf"
                                    placeholder="000.000.000-00"
                                    maxlength="14"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="data_nasc" class="form-label">Data de nascimento</label>
                                <input
                                    type="date"
                                    class="form-control form-control-lg"
                                    id="data_nasc"
                                    name="data_nasc"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input
                                    type="email"
                                    class="form-control form-control-lg"
                                    id="email"
                                    name="email"
                                    placeholder="seu@email.com"
                                    maxlength="40"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input
                                    type="tel"
                                    class="form-control form-control-lg"
                                    id="telefone"
                                    name="telefone"
                                    placeholder="(44) 99999-9999"
                                    maxlength="15"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="senha" class="form-label">Senha</label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="senha"
                                    name="senha"
                                    placeholder="Crie uma senha"
                                    required
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="tipo_perfil" class="form-label">Tipo de perfil</label>
                                <select class="form-select form-select-lg" id="tipo_perfil" name="tipo_perfil" required>
                                    <option value="" selected disabled>Selecione o perfil</option>
                                    <option value="cliente">Cliente</option>
                                    <option value="admin">Administrador</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                            <p class="mb-0" style="color: var(--texto-secundario);">
                                Já tem conta?
                                <a href="/login" class="cor-marca fw-semibold text-decoration-none">Entrar</a>
                            </p>
                            <button type="submit" class="btn-marca">
                                Cadastrar <i class="ph ph-user-plus"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
        $footer = __DIR__ . '/../layouts/footer.php';
        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
