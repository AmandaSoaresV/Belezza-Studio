<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Profissional — Belezza Studio</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <?php include __DIR__ . '/../../../includes/form-validacao-head.php'; ?>
</head>
<body class="body-dashboard">
    <?php $paginaAdminAtiva = 'profissionais-cadastrar'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Cadastrar Profissional</h1>
            <p class="admin-topbar-subtitulo">Formulário com validação de campos obrigatórios</p>
        </div>
    </header>

    <div class="admin-container">
        <div class="superficie p-4 p-md-5">
            <form method="POST" action="" data-parsley-validate="" data-form-demo="1">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome completo</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nome"
                            name="nome"
                            maxlength="80"
                            placeholder="Digite o nome do profissional"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>
                    <div class="col-md-6">
                        <label for="especialidade" class="form-label">Especialidade</label>
                        <input
                            type="text"
                            class="form-control"
                            id="especialidade"
                            name="especialidade"
                            maxlength="80"
                            placeholder="Ex.: Cabeleireira, Manicure"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/dashboard" class="btn-marca btn-marca--contorno btn-marca--pequeno"><i class="ph ph-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn-marca btn-marca--pequeno">Cadastrar profissional <i class="ph ph-user-plus"></i></button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
</body>
</html>
