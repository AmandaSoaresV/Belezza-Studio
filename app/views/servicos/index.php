<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idParaExcluir = isset($_POST['id_servico']) ? (int) $_POST['id_servico'] : 0;

    if ($idParaExcluir < 1) {
        header('Location: /servicos?naoencontrado=1');
        exit;
    }

    try {
        $servicoParaExcluir = obterServico($pdo, $idParaExcluir);

        if ($servicoParaExcluir === null) {
            header('Location: /servicos?naoencontrado=1');
            exit;
        }

        $agendamentosVinculados = contarAgendamentosDoServico($pdo, $idParaExcluir);

        if ($agendamentosVinculados > 0) {
            header('Location: /servicos?vinculado=' . $agendamentosVinculados);
            exit;
        }

        excluirServico($pdo, $idParaExcluir);
        header('Location: /servicos?excluido=1');
        exit;
    } catch (PDOException $e) {
        header('Location: /servicos?erroexclusao=1');
        exit;
    }
}

$porPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

$mensagens = mensagensDeRetorno($_GET, [
    'salvo' => ['tipo' => 'success', 'texto' => 'Serviço cadastrado com sucesso.'],
    'atualizado' => ['tipo' => 'success', 'texto' => 'Serviço atualizado com sucesso.'],
    'excluido' => ['tipo' => 'success', 'texto' => 'Serviço excluído com sucesso.'],
    'naoencontrado' => ['tipo' => 'warning', 'texto' => 'Serviço não encontrado.'],
    'vinculado' => ['tipo' => 'warning', 'texto' => 'Não é possível excluir: o serviço tem {valor} agendamento{plural} vinculado{plural}.'],
    'erroexclusao' => ['tipo' => 'danger', 'texto' => 'Não foi possível excluir o serviço, tente novamente.'],
]);

$servicos = [];
$totalRegistros = 0;
$totalPaginas = 1;

try {
    $totalRegistros = contarServicos($pdo);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

    if ($paginaAtual > $totalPaginas) {
        $paginaAtual = $totalPaginas;
        $offset = ($paginaAtual - 1) * $porPagina;
    }

    $servicos = listarServicos($pdo, $porPagina, $offset);
} catch (PDOException $e) {
    $mensagens[] = [
        'tipo' => 'warning',
        'texto' => 'Não foi possível carregar os serviços, verifique se o banco foi importado.',
    ];
}
?>

<?php
$tituloPagina = 'Serviços';
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'servicos'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
      <div>
        <h1 class="admin-topbar-titulo">Serviços</h1>
        <p class="admin-topbar-subtitulo">
          <?php echo $totalRegistros; ?> serviço<?php echo $totalRegistros === 1 ? '' : 's'; ?> cadastrado<?php echo $totalRegistros === 1 ? '' : 's'; ?>
        </p>
      </div>

      <a href="/servicos/cadastrar" class="btn-marca btn-marca--pequeno">
        <i class="ph ph-plus"></i> Novo Serviço
      </a>
    </header>

    <div class="admin-container">
      <?php include __DIR__ . '/../../../includes/alertas.php'; ?>

      <div class="superficie">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle tabela-marca">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Descrição</th>
                  <th>Preço</th>
                  <th>Duração</th>
                  <th class="text-center">Ações</th>
                </tr>
              </thead>

              <tbody>
                <?php if (empty($servicos)): ?>
                <tr>
                  <td colspan="5" class="text-center py-4">Nenhum serviço cadastrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($servicos as $servico): ?>
                <tr>
                  <td><?php echo htmlspecialchars($servico['nome']); ?></td>
                  <td class="text-body-secondary"><?php echo htmlspecialchars($servico['descricao']); ?></td>
                  <td>R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></td>
                  <td><?php echo $servico['duracao_em_minutos']; ?> min</td>
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                      <a href="/servicos/editar?id=<?php echo $servico['id_servico']; ?>" class="btn btn-outline-primary btn-sm" aria-label="Editar serviço">
                        <i class="ph ph-pencil"></i>
                      </a>

                      <?php if ($servico['total_agendamentos'] > 0): ?>
                      <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        disabled
                        aria-label="Excluir serviço"
                        title="Não é possível excluir: <?php echo $servico['total_agendamentos']; ?> agendamento<?php echo $servico['total_agendamentos'] === 1 ? '' : 's'; ?> vinculado<?php echo $servico['total_agendamentos'] === 1 ? '' : 's'; ?>"
                      >
                        <i class="ph ph-trash"></i>
                      </button>
                      <?php else: ?>
                      <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-excluir-<?php echo $servico['id_servico']; ?>"
                        aria-label="Excluir serviço"
                      >
                        <i class="ph ph-trash"></i>
                      </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>

            <nav aria-label="Paginação de serviços">
              <ul class="pagination justify-content-center mt-3">
                <li class="page-item <?php echo $paginaAtual <= 1 ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php echo $paginaAtual - 1; ?>">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>

                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?php echo $i === $paginaAtual ? 'active' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?php echo $paginaAtual >= $totalPaginas ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php echo $paginaAtual + 1; ?>">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <?php foreach ($servicos as $servico): ?>
    <?php if ($servico['total_agendamentos'] > 0) { continue; } ?>
    <div class="modal fade" id="modal-excluir-<?php echo $servico['id_servico']; ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Excluir serviço</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>

          <div class="modal-body">
            Tem certeza que deseja excluir <strong><?php echo htmlspecialchars($servico['nome']); ?></strong>? Essa ação não pode ser desfeita.
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

            <form method="POST" action="/servicos">
              <input type="hidden" name="id_servico" value="<?php echo $servico['id_servico']; ?>">
              <button type="submit" class="btn btn-danger">Excluir</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
