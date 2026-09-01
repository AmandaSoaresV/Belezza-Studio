<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';

$statusPermitidos = ['pendente', 'confirmado', 'cancelado', 'concluido'];

$clientes = [];
$profissionais = [];
$servicos = [];
$erros = [];

try {
    $clientes = listarUsuariosParaSelecao($pdo);
    $profissionais = listarProfissionais($pdo);
    $servicos = listarServicosParaSelecao($pdo);
} catch (PDOException $e) {
    $erros[] = 'Não foi possível carregar as listas de clientes, profissionais e serviços.';
}

$valores = [
    'id_cliente' => '',
    'id_profissional' => '',
    'id_servico' => '',
    'data_hora_servico' => '',
    'status' => 'pendente',
    'observacao' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erros)) {
    $valores['id_cliente'] = trim($_POST['id_cliente'] ?? '');
    $valores['id_profissional'] = trim($_POST['id_profissional'] ?? '');
    $valores['id_servico'] = trim($_POST['id_servico'] ?? '');
    $valores['data_hora_servico'] = trim($_POST['data_hora_servico'] ?? '');
    $valores['status'] = trim($_POST['status'] ?? '');
    $valores['observacao'] = trim($_POST['observacao'] ?? '');

    $idCliente = (int) $valores['id_cliente'];
    $idProfissional = (int) $valores['id_profissional'];
    $idServico = (int) $valores['id_servico'];
    $dataHora = converterDataHoraParaBanco($valores['data_hora_servico']);

    if (!existeIdNaLista($clientes, 'id_usuario', $idCliente)) {
        $erros[] = 'Selecione um cliente da lista.';
    }

    if (!existeIdNaLista($profissionais, 'id_profissional', $idProfissional)) {
        $erros[] = 'Selecione um profissional da lista.';
    }

    if (!existeIdNaLista($servicos, 'id_servico', $idServico)) {
        $erros[] = 'Selecione um serviço da lista.';
    }

    if ($dataHora === null) {
        $erros[] = 'Informe uma data e hora válidas.';
    }

    if (!in_array($valores['status'], $statusPermitidos, true)) {
        $erros[] = 'Selecione um status válido.';
    }

    if (mb_strlen($valores['observacao']) > 500) {
        $erros[] = 'A observação deve ter no máximo 500 caracteres.';
    }

    if (empty($erros)) {
        try {
            criarAgendamento($pdo, [
                'id_cliente' => $idCliente,
                'id_profissional' => $idProfissional,
                'id_servico' => $idServico,
                'data_hora_servico' => $dataHora,
                'status' => $valores['status'],
                'observacao' => $valores['observacao'],
            ]);

            header('Location: /dashboard?criado=1');
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível cadastrar o agendamento, tente novamente.';
        }
    }
}
?>

<?php
$tituloPagina = 'Cadastrar Agendamento';
$usarFormularios = true;
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'agendamentos-cadastrar'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Cadastrar Agendamento</h1>
            <p class="admin-topbar-subtitulo">Novo agendamento para um cliente cadastrado</p>
        </div>
    </header>

    <div class="admin-container">
        <?php if (!empty($erros)): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($erros as $erro): ?>
                <li><?php echo htmlspecialchars($erro); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="superficie p-4 p-md-5">
            <form method="POST" action="/agendamentos/cadastrar" data-parsley-validate="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="id_cliente" class="form-label">Cliente</label>
                        <select class="form-select" id="id_cliente" name="id_cliente" required data-parsley-required-message="Preencha este campo">
                            <option value="" disabled <?php echo $valores['id_cliente'] === '' ? 'selected' : ''; ?>>Selecione o cliente</option>
                            <?php foreach ($clientes as $cliente): ?>
                            <option
                                value="<?php echo $cliente['id_usuario']; ?>"
                                <?php echo (string) $cliente['id_usuario'] === $valores['id_cliente'] ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($cliente['nome']); ?><?php echo $cliente['tipo_perfil'] === 'admin' ? ' (admin)' : ''; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="id_profissional" class="form-label">Profissional</label>
                        <select class="form-select" id="id_profissional" name="id_profissional" required data-parsley-required-message="Preencha este campo">
                            <option value="" disabled <?php echo $valores['id_profissional'] === '' ? 'selected' : ''; ?>>Selecione o profissional</option>
                            <?php foreach ($profissionais as $profissional): ?>
                            <option
                                value="<?php echo $profissional['id_profissional']; ?>"
                                <?php echo (string) $profissional['id_profissional'] === $valores['id_profissional'] ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($profissional['nome']); ?> &mdash; <?php echo htmlspecialchars($profissional['especialidade']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="id_servico" class="form-label">Serviço</label>
                        <select class="form-select" id="id_servico" name="id_servico" required data-parsley-required-message="Preencha este campo">
                            <option value="" disabled <?php echo $valores['id_servico'] === '' ? 'selected' : ''; ?>>Selecione o serviço</option>
                            <?php foreach ($servicos as $servico): ?>
                            <option
                                value="<?php echo $servico['id_servico']; ?>"
                                <?php echo (string) $servico['id_servico'] === $valores['id_servico'] ? 'selected' : ''; ?>
                            >
                                <?php echo htmlspecialchars($servico['nome']); ?> &mdash; R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="data_hora_servico" class="form-label">Data e hora</label>
                        <input
                            type="datetime-local"
                            class="form-control"
                            id="data_hora_servico"
                            name="data_hora_servico"
                            value="<?php echo htmlspecialchars($valores['data_hora_servico']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required data-parsley-required-message="Preencha este campo">
                            <option value="" disabled <?php echo $valores['status'] === '' ? 'selected' : ''; ?>>Selecione o status</option>
                            <option value="pendente" <?php echo $valores['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                            <option value="confirmado" <?php echo $valores['status'] === 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                            <option value="cancelado" <?php echo $valores['status'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                            <option value="concluido" <?php echo $valores['status'] === 'concluido' ? 'selected' : ''; ?>>Concluído</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label for="observacao" class="form-label">Observação</label>
                        <textarea
                            class="form-control"
                            id="observacao"
                            name="observacao"
                            rows="3"
                            maxlength="500"
                            placeholder="Opcional"
                        ><?php echo htmlspecialchars($valores['observacao']); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/dashboard" class="btn-marca btn-marca--contorno btn-marca--pequeno"><i class="ph ph-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn-marca btn-marca--pequeno">
                        Cadastrar agendamento <i class="ph ph-calendar-plus"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
</body>
</html>
