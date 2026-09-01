<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';

$statusPermitidos = ['pendente', 'confirmado', 'cancelado', 'concluido'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idParaExcluir = isset($_POST['id_agendamento']) ? (int) $_POST['id_agendamento'] : 0;
    $paginaDeOrigem = isset($_POST['pagina']) ? max(1, (int) $_POST['pagina']) : 1;
    $statusDeOrigem = isset($_POST['status']) && in_array($_POST['status'], $statusPermitidos, true)
        ? $_POST['status']
        : null;

    $voltarPara = '/dashboard?pagina=' . $paginaDeOrigem
        . ($statusDeOrigem ? '&status=' . urlencode($statusDeOrigem) : '');

    if ($idParaExcluir < 1) {
        header('Location: ' . $voltarPara . '&naoencontrado=1');
        exit;
    }

    try {
        $agendamentoParaExcluir = obterAgendamento($pdo, $idParaExcluir);

        if ($agendamentoParaExcluir === null) {
            header('Location: ' . $voltarPara . '&naoencontrado=1');
            exit;
        }

        excluirAgendamento($pdo, $idParaExcluir);
        header('Location: ' . $voltarPara . '&excluido=1');
        exit;
    } catch (PDOException $e) {
        header('Location: ' . $voltarPara . '&erroexclusao=1');
        exit;
    }
}

$porPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

$statusFiltro = isset($_GET['status']) && in_array($_GET['status'], $statusPermitidos, true)
    ? $_GET['status']
    : null;

$mensagens = mensagensDeRetorno($_GET, [
    'excluido' => ['tipo' => 'success', 'texto' => 'Agendamento excluído com sucesso.'],
    'naoencontrado' => ['tipo' => 'warning', 'texto' => 'Agendamento não encontrado.'],
    'erroexclusao' => ['tipo' => 'danger', 'texto' => 'Não foi possível excluir o agendamento, tente novamente.'],
]);

$totalHoje = 0;
$totalConfirmados = 0;
$totalPendentes = 0;
$receitaHoje = 0;
$agendamentos = [];
$totalPaginas = 1;
$erroConsulta = false;

try {
    $indicadores = obterIndicadoresDashboard($pdo);

    $totalHoje = $indicadores['total_hoje'];
    $totalConfirmados = $indicadores['total_confirmados'];
    $totalPendentes = $indicadores['total_pendentes'];
    $receitaHoje = $indicadores['receita_hoje'];

    $totalRegistros = contarAgendamentosDashboard($pdo, $statusFiltro);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

    if ($paginaAtual > $totalPaginas) {
        $paginaAtual = $totalPaginas;
        $offset = ($paginaAtual - 1) * $porPagina;
    }

    $agendamentos = listarAgendamentosDashboard($pdo, $porPagina, $offset, $statusFiltro);
} catch (PDOException $e) {
    $erroConsulta = true;
}

$queryPaginacao = $statusFiltro ? '&status=' . urlencode($statusFiltro) : '';
?>

<?php
$tituloPagina = 'Dashboard';
$cssPagina = ['dashboard.css', 'relatorio.css'];
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'dashboard'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
      <div>
        <h1 class="admin-topbar-titulo">Dashboard</h1>
        <p class="admin-topbar-subtitulo">Visão geral dos agendamentos do salão</p>
      </div>

      <a href="/agendamento" class="btn-marca btn-marca--pequeno">
        <i class="ph ph-plus"></i> Novo Agendamento
      </a>
    </header>

    <div class="admin-container">
      <?php if ($erroConsulta): ?>
      <div class="alert alert-warning text-center" role="alert">
        Não foi possível carregar os dados verifique se a view e as procedures foram importadas no banco.
      </div>
      <?php endif; ?>

      <?php include __DIR__ . '/../../../includes/alertas.php'; ?>

      <div id="mensagem-dashboard" class="alert alert-light border text-center d-none" role="status">
        Nenhum dado registrado.
      </div>

      <div class="row g-4">
        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--info">
                <i class="ph ph-calendar"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade"><?php echo $totalHoje; ?></h2>
                <p class="text-card mb-0">Hoje</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--sucesso">
                <i class="ph ph-check-circle"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade"><?php echo $totalConfirmados; ?></h2>
                <p class="text-card mb-0">Confirmados</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--aviso">
                <i class="ph ph-clock"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade"><?php echo $totalPendentes; ?></h2>
                <p class="text-card mb-0">Pendentes</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--marca">
                <i class="ph ph-currency-dollar"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade quantidade--valor">R$
                   <?php echo number_format($receitaHoje, 2, ',', '.'); ?></h2>
                <p class="text-card mb-0">Receita hoje</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mt-1">
        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--info">
                <i class="ph ph-scissors"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade" id="total-servicos">—</h2>
                <p class="text-card mb-0">Serviços cadastrados</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--sucesso">
                <i class="ph ph-tag"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade quantidade--valor" id="media-precos">—</h2>
                <p class="text-card mb-0">Preço médio</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--aviso">
                <i class="ph ph-star"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade quantidade--texto" id="servico-mais-caro">—</h2>
                <p class="text-card mb-0">Serviço mais caro</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--marca">
                <i class="ph ph-crown"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade" id="total-premium">—</h2>
                <p class="text-card mb-0">Serviços premium</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mt-1">
        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--marca">
                <i class="ph ph-trend-up"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade quantidade--valor" id="faturamento-previsto">&mdash;</h2>
                <p class="text-card mb-0">Faturamento previsto</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--sucesso">
                <i class="ph ph-wallet"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade quantidade--valor" id="faturamento-realizado">&mdash;</h2>
                <p class="text-card mb-0">Faturamento realizado</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--aviso">
                <i class="ph ph-trophy"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade quantidade--texto" id="servico-mais-agendado">&mdash;</h2>
                <p class="text-card mb-0">Serviço mais agendado</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--info">
                <i class="ph ph-list-checks"></i>
              </div>
              <div class="dashboard-card-texto">
                <h2 class="quantidade" id="total-agendamentos">&mdash;</h2>
                <p class="text-card mb-0">Total de agendamentos</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="superficie mt-5">
        <div class="card-body">
          <div class="mb-4">
            <h4 class="mb-1">
              <i class="ph ph-trophy"></i>
              Serviços mais populares
            </h4>

            <p class="text-body-secondary small mb-0">
              Ranking por número de agendamentos
            </p>
          </div>

          <div id="lista-ranking">
            <p class="text-center mb-0">Carregando o ranking&hellip;</p>
          </div>
        </div>
      </div>

      <div class="superficie mt-5">
        <div class="card-body">
          <div
            class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"
          >
            <h4 class="mb-0">Agendamentos</h4>

            <form method="get" class="d-flex align-items-center gap-2">
              <label for="filtro-status" class="form-label mb-0 small text-secondary">Status</label>
              <select id="filtro-status" name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="" <?php echo $statusFiltro === null ? 'selected' : ''; ?>>Todos</option>
                <option value="pendente" <?php echo $statusFiltro === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                <option value="confirmado" <?php echo $statusFiltro === 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                <option value="concluido" <?php echo $statusFiltro === 'concluido' ? 'selected' : ''; ?>>Concluído</option>
                <option value="cancelado" <?php echo $statusFiltro === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
              </select>
            </form>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle tabela-marca">
              <thead>
                <tr>
                  <th>Cliente</th>
                  <th>Serviço</th>
                  <th>Data</th>
                  <th>Horário</th>
                  <th>Status</th>
                  <th class="text-center">Ações</th>
                </tr>
              </thead>

              <tbody>
                <?php if (empty($agendamentos)): ?>
                <tr>
                  <td colspan="6" class="text-center py-4">Nenhum dado registrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($agendamentos as $agendamento): ?>
                <tr>
                  <td><?php echo $agendamento['nome_cliente']; ?></td>
                  <td><?php echo $agendamento['nome_servico']; ?></td>
                  <td><?php echo date('d/m/Y', strtotime($agendamento['data_hora_servico'])); ?></td>
                  <td><?php echo date('H:i', strtotime($agendamento['data_hora_servico'])); ?></td>

                  <td>
                    <span class="status-chip status-chip--<?php echo $agendamento['status']; ?>">
                      <?php echo ucfirst($agendamento['status']); ?>
                    </span>
                  </td>
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                      <button
                        type="button"
                        class="btn btn-outline-primary btn-sm"
                        disabled
                        aria-label="Editar agendamento"
                        title="A edição de agendamento ainda não está disponível"
                      >
                        <i class="ph ph-pencil"></i>
                      </button>

                      <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-excluir-<?php echo $agendamento['id_agendamento']; ?>"
                        aria-label="Excluir agendamento"
                      >
                        <i class="ph ph-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
            <nav aria-label="Paginação de agendamentos">
              <ul class="pagination justify-content-center mt-3">
              <li class="page-item <?php echo $paginaAtual <= 1 ? 'disabled' : ''; ?>">
              <a class="page-link" href="?pagina=<?php echo $paginaAtual - 1 . $queryPaginacao; ?>">
              <span aria-hidden="true">&laquo;</span>
              </a>
              </li>

              <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
              <li class="page-item <?php echo $i === $paginaAtual ? 'active' : ''; ?>">
              <a class="page-link" href="?pagina=<?php echo $i . $queryPaginacao; ?>"><?php echo $i; ?></a>
              </li>
              <?php endfor; ?>

             <li class="page-item <?php echo $paginaAtual >= $totalPaginas ? 'disabled' : ''; ?>">
             <a class="page-link" href="?pagina=<?php echo $paginaAtual + 1 . $queryPaginacao; ?>">
            <span aria-hidden="true">&raquo;</span>
             </a>
             </li>
  </ul>
</nav>
          </div>
        </div>
      </div>
    </div>

    <?php foreach ($agendamentos as $agendamento): ?>
    <div class="modal fade" id="modal-excluir-<?php echo $agendamento['id_agendamento']; ?>" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Excluir agendamento</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>

          <div class="modal-body">
            <p>
              Tem certeza que deseja excluir o agendamento de
              <strong><?php echo htmlspecialchars($agendamento['nome_cliente']); ?></strong>
              para <strong><?php echo htmlspecialchars($agendamento['nome_servico']); ?></strong>
              em <?php echo date('d/m/Y', strtotime($agendamento['data_hora_servico'])); ?> às <?php echo date('H:i', strtotime($agendamento['data_hora_servico'])); ?>?
              Essa ação não pode ser desfeita.
            </p>

            <?php if ($agendamento['status'] === 'concluido'): ?>
            <p class="text-danger mb-0">
              Atenção: esse agendamento está concluído.
            </p>
            <?php endif; ?>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

            <form method="POST" action="/dashboard">
              <input type="hidden" name="id_agendamento" value="<?php echo $agendamento['id_agendamento']; ?>">
              <input type="hidden" name="pagina" value="<?php echo $paginaAtual; ?>">
              <input type="hidden" name="status" value="<?php echo htmlspecialchars((string) $statusFiltro); ?>">
              <button type="submit" class="btn btn-danger">Excluir</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
  </body>
</html>
