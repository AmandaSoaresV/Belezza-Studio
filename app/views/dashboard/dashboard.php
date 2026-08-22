<?php require_once __DIR__ . '/../../../api/conexao.php';

$porPagina = 10; 
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

try {
    $sqlTotal = "SELECT COUNT(*) AS total FROM agendamentos";
    $totalRegistros = $pdo->query($sqlTotal)->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPaginas = ceil($totalRegistros / $porPagina);

    $sqlHoje = <<<CONSULTA
    SELECT COUNT(*) AS total_hoje
    FROM agendamentos
    WHERE DATE(agendamentos.data_hora_servico) = CURDATE()
    AND agendamentos.status IN ('confirmado', 'concluido');
    CONSULTA;

    $totalHoje = $pdo->query($sqlHoje)->fetch(PDO::FETCH_ASSOC)['total_hoje'];

    $sqlTotalConfirmados = <<<CONSULTA
    SELECT COUNT(*) AS total_confirmados
    FROM agendamentos
    WHERE agendamentos.status = 'confirmado';
    CONSULTA;

    $totalConfirmados = $pdo->query($sqlTotalConfirmados)->fetch(PDO::FETCH_ASSOC)['total_confirmados'];

    $sqlTotalPendentes = <<<CONSULTA
    SELECT COUNT(*) AS total_pendentes
    FROM agendamentos
    WHERE agendamentos.status = 'pendente';
    CONSULTA;

    $totalPendentes = $pdo->query($sqlTotalPendentes)->fetch(PDO::FETCH_ASSOC)['total_pendentes'];

    $sqlReceitaHoje = <<<CONSULTA
    SELECT COALESCE(SUM(servicos.preco), 0) AS receita_hoje
    FROM agendamentos
    INNER JOIN servicos ON agendamentos.id_servico = servicos.id_servico
    WHERE agendamentos.status = 'concluido'
    AND DATE(agendamentos.data_hora_servico) = CURDATE();
    CONSULTA;

    $receitaHoje = $pdo->query($sqlReceitaHoje)->fetch(PDO::FETCH_ASSOC)['receita_hoje'];

    $sqlAgendamentos = <<<CONSULTA
    SELECT
        usuarios.nome AS nome_cliente,
        agendamentos.data_hora_servico,
        servicos.nome AS nome_servico,
        agendamentos.status
    FROM agendamentos
    INNER JOIN usuarios ON agendamentos.id_cliente = usuarios.id_usuario
    INNER JOIN servicos ON agendamentos.id_servico = servicos.id_servico
    ORDER BY agendamentos.data_hora_servico ASC
    LIMIT $porPagina OFFSET $offset;
    CONSULTA;

    $agendamentos = $pdo->query($sqlAgendamentos)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("erro na consulta: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
        <link rel="stylesheet" href="/assets/css/global.css" />
        <link rel="stylesheet" href="/assets/css/dashboard.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css" />
    <link rel="stylesheet" href="/assets/css/global.css" />
    <link rel="stylesheet" href="/assets/css/admin.css" />
  </head>

  <body class="body-dashboard">
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
      <div class="row g-4">
        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--info">
                <i class="ph ph-calendar"></i>
              </div>
              <div>
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
              <div>
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
              <div>
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
              <div>
                <h2 class="quantidade">R$
                   <?php echo number_format($receitaHoje, 2, ',', '.'); ?></h2>
                <p class="text-card mb-0">Receita hoje</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4 mt-1">
        <div class="col-md-4">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--info">
                <i class="ph ph-scissors"></i>
              </div>
              <div>
                <h2 class="quantidade" id="total-servicos">—</h2>
                <p class="text-card mb-0">Serviços cadastrados</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--sucesso">
                <i class="ph ph-tag"></i>
              </div>
              <div>
                <h2 class="quantidade" id="media-precos">—</h2>
                <p class="text-card mb-0">Preço médio</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--aviso">
                <i class="ph ph-star"></i>
              </div>
              <div>
                <h2 class="quantidade" id="servico-mais-caro">—</h2>
                <p class="text-card mb-0">Serviço mais caro</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="superficie mt-5">
        <div class="card-body">
          <div
            class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"
          >
            <h4 class="mb-0">Agendamentos</h4>
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
                      <button class="btn btn-outline-primary btn-sm">
                        <i class="ph ph-pencil"></i>
                      </button>

                      <button class="btn btn-outline-danger btn-sm">
                        <i class="ph ph-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
            <nav aria-label="Paginação de agendamentos">
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

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/app.js"></script>
  </body>
</html>
