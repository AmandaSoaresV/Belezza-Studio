<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/servicos.php';
require_once __DIR__ . '/../../../includes/profissionais.php';

$idProfissional = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idProfissional < 1) {
    header('Location: /profissionais?naoencontrado=1');
    exit;
}

try {
    $profissional = obterProfissional($pdo, $idProfissional);
} catch (PDOException $e) {
    $profissional = null;
}

if ($profissional === null) {
    header('Location: /profissionais?naoencontrado=1');
    exit;
}

$erros = [];
$servicos = [];
$servicosAtendidos = [];

try {
    $servicos = listarServicosParaSelecao($pdo);
    $servicosAtendidos = servicosDoProfissional($pdo, $idProfissional);
} catch (PDOException $e) {
    $erros[] = 'Não foi possível carregar a lista de serviços.';
}

$valores = [
    'nome' => $profissional['nome'],
    'especialidade' => $profissional['especialidade'],
    'servicos' => $servicosAtendidos,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erros)) {
    $valores['nome'] = trim($_POST['nome'] ?? '');
    $valores['especialidade'] = trim($_POST['especialidade'] ?? '');
    $valores['servicos'] = array_map('intval', (array) ($_POST['servicos'] ?? []));

    if ($valores['nome'] === '') {
        $erros[] = 'Informe o nome do profissional.';
    }

    if (mb_strlen($valores['nome']) > 80) {
        $erros[] = 'O nome deve ter no máximo 80 caracteres.';
    }

    if ($valores['especialidade'] === '') {
        $erros[] = 'Informe a especialidade do profissional.';
    }

    if (mb_strlen($valores['especialidade']) > 80) {
        $erros[] = 'A especialidade deve ter no máximo 80 caracteres.';
    }

    $servicosValidos = array_values(array_filter(
        $valores['servicos'],
        fn (int $idServico): bool => existeIdNaLista($servicos, 'id_servico', $idServico)
    ));

    if (empty($servicosValidos)) {
        $erros[] = 'Selecione ao menos um serviço que o profissional atende.';
    }

    if (empty($erros)) {
        try {
            atualizarProfissional($pdo, $idProfissional, $valores['nome'], $valores['especialidade'], $servicosValidos);

            header('Location: /profissionais?atualizado=1');
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível salvar as alterações, tente novamente.';
        }
    }
}
?>

<?php
$tituloPagina = 'Editar Profissional';
$usarFormularios = true;
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'profissionais'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Editar Profissional</h1>
            <p class="admin-topbar-subtitulo">
                Alterando o cadastro de <?php echo htmlspecialchars($profissional['nome']); ?>
            </p>
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
            <form method="POST" action="/profissionais/editar?id=<?php echo $idProfissional; ?>" data-parsley-validate="">
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
                            value="<?php echo htmlspecialchars($valores['nome']); ?>"
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
                            value="<?php echo htmlspecialchars($valores['especialidade']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-12">
                        <label class="form-label">Serviços que atende</label>
                        <p class="text-body-secondary small mb-2">
                            Só os serviços marcados aqui vão oferecer esse profissional no agendamento.
                        </p>

                        <?php if (empty($servicos)): ?>
                        <p class="mb-0">Nenhum serviço cadastrado.</p>
                        <?php else: ?>
                        <div class="row g-2">
                            <?php foreach ($servicos as $servico): ?>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="servicos[]"
                                        id="servico_<?php echo $servico['id_servico']; ?>"
                                        value="<?php echo $servico['id_servico']; ?>"
                                        <?php echo in_array($servico['id_servico'], $valores['servicos'], true) ? 'checked' : ''; ?>
                                    >
                                    <label class="form-check-label" for="servico_<?php echo $servico['id_servico']; ?>">
                                        <?php echo htmlspecialchars($servico['nome']); ?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/profissionais" class="btn-marca btn-marca--contorno btn-marca--pequeno"><i class="ph ph-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn-marca btn-marca--pequeno">Salvar <i class="ph ph-floppy-disk"></i></button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
</body>
</html>
