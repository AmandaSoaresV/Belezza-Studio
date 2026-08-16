<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário — Belezza Studio</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
</head>
<body class="body-dashboard">
    <?php $paginaAdminAtiva = 'usuarios-editar'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Editar Usuário</h1>
            <p class="admin-topbar-subtitulo">Atualize os dados do usuário selecionado</p>
        </div>
    </header>

    <div class="admin-container">
        <div class="superficie p-4 p-md-5">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nome"
                            name="nome"
                            value="Maria Eduarda Santos"
                            maxlength="40"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <input
                            type="text"
                            class="form-control"
                            id="cpf"
                            name="cpf"
                            value="111.222.333-44"
                            maxlength="14"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="data_nasc" class="form-label">Data de nascimento</label>
                        <input
                            type="date"
                            class="form-control"
                            id="data_nasc"
                            name="data_nasc"
                            value="1995-05-10"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="maria.eduarda@example.com"
                            maxlength="40"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input
                            type="tel"
                            class="form-control"
                            id="telefone"
                            name="telefone"
                            value="44988887777"
                            maxlength="15"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="senha" class="form-label">Nova senha</label>
                        <input
                            type="password"
                            class="form-control"
                            id="senha"
                            name="senha"
                            placeholder="Deixe em branco para manter a atual"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="tipo_perfil" class="form-label">Tipo de perfil</label>
                        <select class="form-select" id="tipo_perfil" name="tipo_perfil" required>
                            <option value="cliente" selected>Cliente</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn-marca btn-marca--contorno btn-marca--pequeno">
                        <i class="ph ph-arrow-left"></i> Cancelar
                    </button>
                    <button type="submit" class="btn-marca btn-marca--pequeno">
                        Salvar alterações <i class="ph ph-floppy-disk"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
