<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';

$erros = [];

$valores = [
    'nome' => '',
    'especialidade' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores['nome'] = trim($_POST['nome'] ?? '');
    $valores['especialidade'] = trim($_POST['especialidade'] ?? '');

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

    if (empty($erros)) {
        try {
            criarProfissional($pdo, $valores['nome'], $valores['especialidade']);

            header('Location: /profissionais/cadastrar?criado=1');
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível cadastrar o profissional, tente novamente.';
        }
    }
}

$mensagens = mensagensDeRetorno($_GET, [
    'criado' => ['tipo' => 'success', 'texto' => 'Profissional cadastrado com sucesso.'],
]);
?>

<?php
$tituloPagina = 'Cadastrar Profissional';
$usarFormularios = true;
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'profissionais-cadastrar'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Cadastrar Profissional</h1>
            <p class="admin-topbar-subtitulo">Formulário com validação de campos obrigatórios</p>
        </div>
    </header>

    <div class="admin-container">
        <?php include __DIR__ . '/../../../includes/alertas.php'; ?>

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
            <form method="POST" action="/profissionais/cadastrar" data-parsley-validate="">
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
