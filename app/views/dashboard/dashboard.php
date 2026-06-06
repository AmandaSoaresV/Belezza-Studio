<?php require_once __DIR__ . '/../../../config/conexao.php';

$porPagina = 10; 
$paginaAtual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

$sqlTotal = "SELECT COUNT(*) AS total FROM agendamentos";
$totalRegistros = $conn->query($sqlTotal)->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $porPagina);

    $sqlHoje = <<<CONSULTA
    SELECT COUNT(*) AS total_hoje
    FROM agendamentos
    WHERE DATE(agendamentos.data_hora_servico) = CURDATE()
    AND agendamentos.status IN ('confirmado', 'concluido');
    CONSULTA;

    $resultadoHoje = $conn->query($sqlHoje);
    if (!$resultadoHoje) { 
      die("erro na consulta" . $conn->error); }

    $totalHoje = $resultadoHoje->fetch_assoc()['total_hoje'];

    $sqlTotalConfirmados = <<<CONSULTA
    SELECT COUNT(*) AS total_confirmados
    FROM agendamentos
    WHERE agendamentos.status = 'confirmado';
    CONSULTA;

    $resultadoTotalConfirmados = $conn->query($sqlTotalConfirmados);
    if (!$resultadoTotalConfirmados) { 
      die("erro na consulta" . $conn->error); }

    $totalConfirmados = $resultadoTotalConfirmados->fetch_assoc()['total_confirmados'];

    $sqlTotalPendentes = <<<CONSULTA
    SELECT COUNT(*) AS total_pendentes
    FROM agendamentos
    WHERE agendamentos.status = 'pendente';
    CONSULTA;

    $resultadoTotalPendentes = $conn->query($sqlTotalPendentes);
    if (!$resultadoTotalPendentes) { 
      die("erro na consulta" . $conn->error); }

    $totalPendentes = $resultadoTotalPendentes->fetch_assoc()['total_pendentes'];

    $sqlReceitaHoje = <<<CONSULTA
    SELECT COALESCE(SUM(servicos.preco), 0) AS receita_hoje
    FROM agendamentos
    INNER JOIN servicos ON agendamentos.id_servico = servicos.id_servico
    WHERE agendamentos.status = 'concluido'
    AND DATE(agendamentos.data_hora_servico) = CURDATE();
    CONSULTA;

    $resultadoReceitaHoje = $conn->query($sqlReceitaHoje);
    if (!$resultadoReceitaHoje) { 
      die("erro na consulta" . $conn->error); }
      
    $receitaHoje = $resultadoReceitaHoje->fetch_assoc()['receita_hoje'];

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

    $resultadoAgendamentos = $conn->query($sqlAgendamentos);
    if (!$resultadoAgendamentos) { 
      die("erro na consulta" . $conn->error); }

    $agendamentos = $resultadoAgendamentos->fetch_all(MYSQLI_ASSOC);
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
  </head>

  <body class="body-dashboard">
    <?php
    $header = __DIR__ . '/../includes/header.php'; 
     if (file_exists($header))
         { include $header; } 
     else { include __DIR__ . '/../erro/erro.php'; } ?>
     
    <div class="container py-4">
      <div class="row g-4">
        <div class="col-md-3">
          <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icon-box icone-azul">
                <i class="ph ph-calendar"></i>
              </div>
              <div>
                <h2 class="quantidade">
                  <?php echo $totalHoje; ?></h2>
                <p class="text-card mb-0">Hoje</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icon-box icone-verde">
                <i class="ph ph-check-circle"></i>
              </div>
              <div>
                <h2 class="quantidade">
                  <?php echo $totalConfirmados; ?></h2>
                <p class="text-card mb-0">Confirmados</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icon-box icone-amarelo">
                <i class="ph ph-clock"></i>
              </div>
              <div>
                <h2 class="quantidade">
                  <?php echo $totalPendentes; ?></h2>
                <p class="text-card mb-0">Pendentes</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="card dashboard-card shadow-sm border-0">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icon-box icone-roxo">
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

      <div class="card shadow-sm border-0 mt-5">
        <div class="card-body">
          <div
            class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4"
          >
            <h4 class="mb-0">Agendamentos</h4>

            <button
              class="btn btn-sm"
              style="background-color: var(--primary-700); color: #fff"
            >
              Novo Agendamento
            </button>
          </div>

          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
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
                    <?php if ($agendamento['status'] === 'confirmado'): ?>
                      <span class="badge bg-success">Confirmado</span>

                    <?php elseif ($agendamento['status'] === 'pendente'): ?>
                      <span class="badge bg-warning text-dark">Pendente</span>

                    <?php elseif ($agendamento['status'] === 'concluido'): ?>
                      <span class="badge bg-secondary">Concluído</span>
                      
                    <?php elseif ($agendamento['status'] === 'cancelado'): ?>
                      <span class="badge bg-danger">Cancelado</span>
                    <?php endif; ?>
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

    <?php
        $footer = __DIR__ . '/../includes/footer.php';

        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>