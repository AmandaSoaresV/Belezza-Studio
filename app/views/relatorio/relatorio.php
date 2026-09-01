<?php
    require_once __DIR__ . '/../../../api/conexao.php';
    require_once __DIR__ . '/../../../includes/analytics.php';

    $receitaTotal = 0;
    $totalHoje = 0;
    $totalClientes = 0;
    $receitaHoje = 0;
    $erroConsulta = false;

    $totalRankingExibido = 4;
    $ranking = [];
    $maiorTotalRanking = 0;
    $totalAgendamentos = 0;
    $totalServicos = 0;
    $totalUsuarios = 0;

    try {
      $resumo = obterResumoRelatorio($pdo);

      $receitaTotal = $resumo['receita_total'];
      $totalHoje = $resumo['total_hoje'];
      $totalClientes = $resumo['total_clientes'];
      $receitaHoje = $resumo['receita_hoje'];

      $ranking = array_slice(obterRankingServicos($pdo), 0, $totalRankingExibido);
      $maiorTotalRanking = $ranking[0]['total_agendamentos'] ?? 0;

      $totalAgendamentos = contarAgendamentosDashboard($pdo);
      $totalServicos = contarServicos($pdo);
      $totalUsuarios = contarUsuarios($pdo);
    } catch (PDOException $e) {
      $erroConsulta = true;
    }
    ?>

<?php
$tituloPagina = 'Relatório';
$classeBody = 'pagina-relatorio';
$cssPagina = ['dashboard.css', 'relatorio.css'];
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'relatorio'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
      <div>
        <h1 class="admin-topbar-titulo">
          <i class="ph ph-chart-line-up"></i>
          Painel Administrativo
        </h1>
        <p class="admin-topbar-subtitulo">
          Visão geral do desempenho do salão e estratégias para crescimento
        </p>
      </div>

      <div class="d-flex align-items-center gap-3">
        <button class="btn-outline-warning btn-exportar">
          Exportar Relatório
          <i class="ph ph-download"></i>
        </button>

        <div class="text-end">
          <strong class="d-block">Amanda Soares</strong>
          <small class="text-secondary">Administrador</small>
        </div>

        <div
          class="rounded-circle d-flex justify-content-center align-items-center"
          style="width: 44px; height: 44px; background: var(--primary-800);"
        >
          <i class="ph ph-user text-white fs-5"></i>
        </div>
      </div>
    </header>

    <main class="admin-container">

      <?php if ($erroConsulta): ?>
      <div class="alert alert-warning text-center mb-4" role="alert">
        Não foi possível carregar os dados analíticos. Verifique se a view <code>vw_agendamentos_completos</code> foi importada no banco.
      </div>
      <?php endif; ?>

      <div class="row g-4 mb-4">
        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--marca">
                <i class="ph ph-currency-dollar"></i>
              </div>

              <div>
                <h2 class="quantidade">
                  R$<?php echo number_format($receitaTotal, 2, ',', '.'); ?>
                </h2>
                <p class="text-card mb-0">Receita total</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--sucesso">
                <i class="ph ph-calendar"></i>
              </div>

              <div>
                <h2 class="quantidade">
                  <?php echo $totalHoje; ?>
                </h2>
                <p class="text-card mb-0">Agendamentos</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--aviso">
                <i class="ph ph-users-four"></i>
              </div>

              <div>
                <h2 class="quantidade">
                  <?php echo $totalClientes; ?>
                </h2>
                <p class="text-card mb-0">Clientes Ativos</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="superficie dashboard-card">
            <div class="card-body d-flex align-items-center gap-4">
              <div class="icone-tile icone-tile--info">
                <i class="ph ph-currency-dollar"></i>
              </div>

              <div>
                <h2 class="quantidade">
                  R$<?php echo number_format($receitaHoje, 2, ',', '.'); ?>
                </h2>
                <p class="text-card mb-0">Receita hoje</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-12 col-lg-8">
          <div class="card shadow-sm border-0 rounded-4 grafico-card h-100">
            <div class="card-body p-3 p-lg-4">
              <h2 class="h5 mb-1">Agendamentos por mês</h2>

              <p class="text-body-secondary mb-3">
                Quantidade de agendamentos registrados em cada mês
              </p>

              <div class="grafico-container rounded-3 p-2 p-lg-3">
                <canvas id="graficoAgendamentos"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-lg-4">
          <div class="card shadow-sm border-0 rounded-4 h-100">
            <div class="card-body p-4">
              <div class="mb-4">
                <h2 class="h5 mb-1">
                  <i class="ph ph-trophy" style="color: pink"></i>
                  Serviços Mais Populares
                </h2>

                <p class="text-body-secondary small mb-0">
                  Ranking por número de agendamentos
                </p>
              </div>

              <?php if (empty($ranking)): ?>
              <p class="text-center text-body-secondary">Nenhum serviço agendado ainda.</p>
              <?php else: ?>
              <?php foreach ($ranking as $posicao => $servico): ?>
              <?php $largura = $maiorTotalRanking > 0
                  ? round(($servico['total_agendamentos'] / $maiorTotalRanking) * 100)
                  : 0; ?>
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <div class="d-flex align-items-center gap-2">
                    <span class="ranking ranking-<?php echo $posicao + 1; ?>"><?php echo $posicao + 1; ?></span>
                    <strong><?php echo htmlspecialchars($servico['nome_servico']); ?></strong>
                  </div>

                  <span class="text-secondary"><?php echo $servico['total_agendamentos']; ?> agend.</span>
                </div>

                <div class="progress progress-custom">
                  <div
                    class="progress-bar barra-<?php echo $posicao + 1; ?>"
                    style="width: <?php echo $largura; ?>%"
                  ></div>
                </div>
              </div>
              <?php endforeach; ?>
              <?php endif; ?>

              <div class="grafico-donut">
                <canvas id="graficoServicos"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card shadow-sm border-0 rounded-4 mt-4">
        <div class="card-header fw-bold">Resumo do período</div>

        <div class="card-body">
          <div class="row g-3">
            <div class="col-6 col-md-3">
              <div class="card h-100 border-0">
                <div class="card-body text-center p-2">
                  <h5 class="mb-0 fw-bold">
                    <i class="ph ph-list-checks"></i>
                    <span id="resumo-agendamentos"><?php echo $totalAgendamentos; ?></span>
                  </h5>

                  <small class="fw-light">Agendamentos no total</small>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="card h-100 border-0">
                <div class="card-body text-center p-2">
                  <h5 class="mb-0 fw-bold">
                    <i class="ph ph-scissors"></i>
                    <span id="resumo-servicos"><?php echo $totalServicos; ?></span>
                  </h5>

                  <small class="fw-light">Serviços cadastrados</small>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="card h-100 border-0">
                <div class="card-body text-center p-2">
                  <h5 class="mb-0 fw-bold">
                    <i class="ph ph-users"></i>
                    <span id="resumo-usuarios"><?php echo $totalUsuarios; ?></span>
                  </h5>

                  <small class="fw-light">Usuários cadastrados</small>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="card h-100 border-0">
                <div class="card-body text-center p-2">
                  <h5 class="mb-0 fw-bold">
                    <i class="ph ph-currency-dollar"></i>
                    <span id="resumo-receita">R$ <?php echo number_format($receitaTotal, 2, ',', '.'); ?></span>
                  </h5>

                  <small class="fw-light">Receita total</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="/assets/js/relatorio.js"></script>
  </body>
</html>