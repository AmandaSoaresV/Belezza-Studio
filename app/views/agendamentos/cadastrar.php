<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Agendamento — Belezza Studio</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
</head>
<body class="body-dashboard">
    <?php $paginaAdminAtiva = 'agendamentos-cadastrar'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Cadastrar Agendamento</h1>
            <p class="admin-topbar-subtitulo">Registre um novo agendamento no sistema</p>
        </div>
    </header>

    <div class="admin-container">
        <div class="superficie p-4 p-md-5">
            <form method="POST" action="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="id_cliente" class="form-label">Cliente</label>
                        <select class="form-select" id="id_cliente" name="id_cliente" required>
                            <option value="" selected disabled>Selecione o cliente</option>
                            <option value="2">Maria Eduarda Santos</option>
                            <option value="3">Ana Carolina Ribeiro</option>
                            <option value="4">Fernanda Costa Lima</option>
                            <option value="5">Beatriz Fernandes Rocha</option>
                            <option value="6">Juliana Pereira Martins</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="id_profissional" class="form-label">Profissional</label>
                        <select class="form-select" id="id_profissional" name="id_profissional" required>
                            <option value="" selected disabled>Selecione o profissional</option>
                            <option value="1">Juliana Alves — Cabelo</option>
                            <option value="2">Renata Souza — Estética</option>
                            <option value="3">Camila Ferreira — Unhas</option>
                            <option value="4">Patrícia Lima — Maquiagem</option>
                            <option value="5">Bianca Martins — Cílios e Sobrancelhas</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="id_servico" class="form-label">Serviço</label>
                        <select class="form-select" id="id_servico" name="id_servico" required>
                            <option value="" selected disabled>Selecione o serviço</option>
                            <option value="1">Corte Feminino — R$ 80,00</option>
                            <option value="2">Coloração Completa — R$ 350,00</option>
                            <option value="3">Manicure e Pedicure — R$ 120,00</option>
                            <option value="4">Escova Progressiva — R$ 450,00</option>
                            <option value="5">Design de Sobrancelhas — R$ 90,00</option>
                            <option value="6">Maquiagem Profissional — R$ 200,00</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="data_hora_servico" class="form-label">Data e hora</label>
                        <input
                            type="datetime-local"
                            class="form-control"
                            id="data_hora_servico"
                            name="data_hora_servico"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="" selected disabled>Selecione o status</option>
                            <option value="pendente">Pendente</option>
                            <option value="confirmado">Confirmado</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="concluido">Concluído</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="observacao" class="form-label">Observação</label>
                        <textarea
                            class="form-control"
                            id="observacao"
                            name="observacao"
                            rows="3"
                            placeholder="Informações adicionais sobre o agendamento (opcional)"
                        ></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn-marca btn-marca--contorno btn-marca--pequeno">
                        <i class="ph ph-arrow-left"></i> Cancelar
                    </button>
                    <button type="submit" class="btn-marca btn-marca--pequeno">
                        Cadastrar agendamento <i class="ph ph-calendar-plus"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
