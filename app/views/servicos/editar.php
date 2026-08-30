<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';

$idServico = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idServico < 1) {
    header('Location: /servicos?naoencontrado=1');
    exit;
}

try {
    $servico = obterServico($pdo, $idServico);
} catch (PDOException $e) {
    $servico = null;
}

if ($servico === null) {
    header('Location: /servicos?naoencontrado=1');
    exit;
}

$erros = [];
$valores = [
    'nome' => $servico['nome'],
    'preco' => number_format($servico['preco'], 2, ',', '.'),
    'duracao_em_minutos' => (string) $servico['duracao_em_minutos'],
    'descricao' => $servico['descricao'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores['nome'] = trim($_POST['nome'] ?? '');
    $valores['preco'] = trim($_POST['preco'] ?? '');
    $valores['duracao_em_minutos'] = trim($_POST['duracao_em_minutos'] ?? '');
    $valores['descricao'] = trim($_POST['descricao'] ?? '');

    $preco = converterPrecoParaDecimal($valores['preco']);
    $duracao = (int) $valores['duracao_em_minutos'];

    if ($valores['nome'] === '') {
        $erros[] = 'Informe o nome do serviço.';
    } elseif (mb_strlen($valores['nome']) > 100) {
        $erros[] = 'O nome do serviço deve ter no máximo 100 caracteres.';
    }

    if ($preco === null || $preco <= 0) {
        $erros[] = 'Informe um preço maior que zero.';
    }

    if ($duracao < 1) {
        $erros[] = 'Informe a duração em minutos.';
    }

    if ($valores['descricao'] === '') {
        $erros[] = 'Informe a descrição do serviço.';
    }

    if (empty($erros)) {
        try {
            $sql = <<<SQL
            UPDATE servicos
            SET nome = :nome,
                descricao = :descricao,
                preco = :preco,
                duracao_em_minutos = :duracao,
                updated_at = NOW()
            WHERE id_servico = :id
            SQL;

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $valores['nome']);
            $stmt->bindValue(':descricao', $valores['descricao']);
            $stmt->bindValue(':preco', $preco);
            $stmt->bindValue(':duracao', $duracao, PDO::PARAM_INT);
            $stmt->bindValue(':id', $idServico, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: /servicos?atualizado=1');
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível salvar as alterações, tente novamente.';
        }
    }
}
?>

<?php
$tituloPagina = 'Editar Serviço';
$usarFormularios = true;
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'servicos'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Editar Serviço</h1>
            <p class="admin-topbar-subtitulo">Alterando <?php echo htmlspecialchars($servico['nome']); ?></p>
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
            <form method="POST" action="/servicos/editar?id=<?php echo $idServico; ?>" data-parsley-validate="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nome"
                            name="nome"
                            maxlength="100"
                            placeholder="Nome do serviço"
                            value="<?php echo htmlspecialchars($valores['nome']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="preco" class="form-label">Preço (R$)</label>
                        <input
                            type="text"
                            class="form-control"
                            id="preco"
                            name="preco"
                            placeholder="0,00"
                            value="<?php echo htmlspecialchars($valores['preco']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="duracao_em_minutos" class="form-label">Duração (minutos)</label>
                        <input
                            type="number"
                            class="form-control"
                            id="duracao_em_minutos"
                            name="duracao_em_minutos"
                            min="1"
                            placeholder="Ex.: 60"
                            value="<?php echo htmlspecialchars($valores['duracao_em_minutos']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                            data-parsley-min="1"
                            data-parsley-min-message="Informe uma duração válida"
                        >
                    </div>

                    <div class="col-12">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea
                            class="form-control"
                            id="descricao"
                            name="descricao"
                            rows="4"
                            placeholder="Descreva o serviço"
                            required
                            data-parsley-required-message="Preencha este campo"
                        ><?php echo htmlspecialchars($valores['descricao']); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/servicos" class="btn-marca btn-marca--contorno btn-marca--pequeno"><i class="ph ph-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn-marca btn-marca--pequeno">
                        Salvar <i class="ph ph-floppy-disk"></i>
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
