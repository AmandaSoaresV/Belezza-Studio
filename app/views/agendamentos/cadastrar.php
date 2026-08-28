<?php
$tituloPagina = 'Cadastrar Agendamento';
$usarFormularios = true;
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'agendamentos-cadastrar'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Cadastrar Agendamento</h1>
            <p class="admin-topbar-subtitulo">Formulário com validação de campos obrigatórios</p>
        </div>
    </header>

    <div class="admin-container">
        <div class="superficie p-4 p-md-5">
            <form method="POST" action="" data-parsley-validate="" data-form-demo="1">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="id_cliente" class="form-label">Cliente</label>
                        <select class="form-select" id="id_cliente" name="id_cliente" required data-parsley-required-message="Preencha este campo">
                            <option value="" selected disabled>Selecione o cliente</option>
                            <option value="1">Maria Silva</option>
                            <option value="2">João Souza</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_profissional" class="form-label">Profissional</label>
                        <select class="form-select" id="id_profissional" name="id_profissional" required data-parsley-required-message="Preencha este campo">
                            <option value="" selected disabled>Selecione o profissional</option>
                            <option value="1">Ana — Cabeleireira</option>
                            <option value="2">Carla — Manicure</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="id_servico" class="form-label">Serviço</label>
                        <select class="form-select" id="id_servico" name="id_servico" required data-parsley-required-message="Preencha este campo">
                            <option value="" selected disabled>Selecione o serviço</option>
                            <option value="1">Corte — R$ 80,00</option>
                            <option value="2">Manicure — R$ 45,00</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="data_hora_servico" class="form-label">Data e hora</label>
                        <input type="datetime-local" class="form-control" id="data_hora_servico" name="data_hora_servico" required data-parsley-required-message="Preencha este campo">
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required data-parsley-required-message="Preencha este campo">
                            <option value="" selected disabled>Selecione o status</option>
                            <option value="pendente">Pendente</option>
                            <option value="confirmado">Confirmado</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="concluido">Concluído</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="observacao" class="form-label">Observação</label>
                        <textarea class="form-control" id="observacao" name="observacao" rows="3" placeholder="Opcional"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/dashboard" class="btn-marca btn-marca--contorno btn-marca--pequeno"><i class="ph ph-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn-marca btn-marca--pequeno">Cadastrar agendamento <i class="ph ph-calendar-plus"></i></button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
</body>
</html>
