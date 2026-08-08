<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço — Belezza Studio</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
</head>
<body class="body-dashboard">
    <?php $paginaAdminAtiva = 'servicos-editar'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Editar Serviço</h1>
            <p class="admin-topbar-subtitulo">Atualize as informações do serviço selecionado</p>
        </div>
    </header>

    <div class="admin-container">
        <div class="superficie p-4 p-md-5">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="nome" class="form-label">Nome do serviço</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nome"
                            name="nome"
                            value="Corte Feminino"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="col-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea
                            class="form-control"
                            id="descricao"
                            name="descricao"
                            rows="4"
                            required
                        >Corte de cabelo feminino com lavagem e finalização.</textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="preco" class="form-label">Preço (R$)</label>
                        <input
                            type="number"
                            class="form-control"
                            id="preco"
                            name="preco"
                            value="80.00"
                            min="0"
                            step="0.01"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="duracao_em_minutos" class="form-label">Duração (minutos)</label>
                        <input
                            type="number"
                            class="form-control"
                            id="duracao_em_minutos"
                            name="duracao_em_minutos"
                            value="60"
                            min="1"
                            required
                        >
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

    <?php include __DIR__ . '/../layouts/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
