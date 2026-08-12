<!doctype html>
   <?php
    require_once __DIR__ . '/../../../config/conexao.php';

    $sqlReceitaTotal = <<<CONSULTA
    SELECT
      SUM(servico.preco) AS receita_total
    FROM agendamentos AS agendamento
    INNER JOIN servicos AS servico
    ON servico.id_servico = agendamento.id_servico
    WHERE agendamento.status = 'concluido';
    CONSULTA;

    try {
      $receitaTotal = $pdo->query($sqlReceitaTotal)->fetch(PDO::FETCH_ASSOC)['receita_total'];

      $sqlHoje = <<<CONSULTA
      SELECT COUNT(*) AS total_hoje
      FROM agendamentos
      WHERE DATE(agendamentos.data_hora_servico) = CURDATE()
      AND agendamentos.status IN ('confirmado', 'concluido');
      CONSULTA;

      $totalHoje = $pdo->query($sqlHoje)->fetch(PDO::FETCH_ASSOC)['total_hoje'];

      $sqlClientes = <<<CONSULTA
      SELECT
        COUNT(DISTINCT id_cliente) AS total_clientes
      FROM agendamentos
      CONSULTA;

      $totalClientes = $pdo->query($sqlClientes)->fetch(PDO::FETCH_ASSOC)['total_clientes'];

      $sqlReceitaHoje = <<<CONSULTA
      SELECT
        SUM(servico.preco) AS receita_hoje
      FROM agendamentos AS agendamento
      INNER JOIN servicos AS servico
      ON servico.id_servico = agendamento.id_servico
      WHERE agendamento.status = 'concluido'
      AND DATE(agendamento.data_hora_servico) = CURDATE();
      CONSULTA;

      $receitaHoje = $pdo->query($sqlReceitaHoje)->fetch(PDO::FETCH_ASSOC)['receita_hoje'];
    } catch (PDOException $e) {
      die("erro na consulta: " . $e->getMessage());
    }
    ?>

<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Relatório</title>
        <link rel="stylesheet" href="/assets/css/global.css" />
        <link rel="stylesheet" href="/assets/css/admin.css" />
        <link rel="stylesheet" href="/assets/css/dashboard.css" />
        <link rel="stylesheet" href="/assets/css/relatorio.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="/assets/js/relatorio.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>


  <body class="pagina-relatorio">
    <?php $paginaAdminAtiva = 'relatorio'; include __DIR__ . '/../layouts/sidebar.php'; ?>

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
              <h2 class="h5 mb-1">Receita por período</h2>

              <p class="text-body-secondary mb-3">
                Receita diária acumulada nos últimos 30 dias
              </p>

              <div class="grafico-container rounded-3 p-2 p-lg-3">
                <canvas id="graficoReceita"></canvas>
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

              <div class="mb-4">
                <div
                  class="d-flex justify-content-between align-items-center mb-2"
                >
                  <div class="d-flex align-items-center gap-2">
                    <span class="ranking ranking-1">1</span>
                    <strong>Escova Progressiva</strong>
                  </div>

                  <span class="text-secondary">58 agend.</span>
                </div>

                <div class="progress progress-custom">
                  <div
                    class="progress-bar barra-1"
                    style="width: 100%"
                  ></div>
                </div>
              </div>

              <div class="mb-4">
                <div
                  class="d-flex justify-content-between align-items-center mb-2"
                >
                  <div class="d-flex align-items-center gap-2">
                    <span class="ranking ranking-2">2</span>
                    <strong>Dia da Noiva</strong>
                  </div>

                  <span class="text-secondary">42 agend.</span>
                </div>

                <div class="progress progress-custom">
                  <div class="progress-bar barra-2" style="width: 75%"></div>
                </div>
              </div>

              <div class="mb-4">
                <div
                  class="d-flex justify-content-between align-items-center mb-2"
                >
                  <div class="d-flex align-items-center gap-2">
                    <span class="ranking ranking-3">3</span>
                    <strong>Tratamento Facial Gold Therapy</strong>
                  </div>

                  <span class="text-secondary">28 agend.</span>
                </div>

                <div class="progress progress-custom">
                  <div class="progress-bar barra-3" style="width: 48%"></div>
                </div>
              </div>

              <div class="mb-4">
                <div
                  class="d-flex justify-content-between align-items-center mb-2"
                >
                  <div class="d-flex align-items-center gap-2">
                    <span class="ranking ranking-4">4</span>
                    <strong>Tratamento Facial</strong>
                  </div>

                  <span class="text-secondary">15 agend.</span>
                </div>

                <div class="progress progress-custom">
                  <div class="progress-bar barra-4" style="width: 26%"></div>
                </div>
              </div>

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
                    <i class="ph ph-users-four"></i>
                    94%
                  </h5>

                  <small class="fw-light">
                    Taxa de comparecimento
                  </small>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="card h-100 border-0">
                <div class="card-body text-center p-2">
                  <h5 class="mb-0 fw-bold">
                    <i class="ph ph-star"></i>
                    4.8
                  </h5>

                  <small class="fw-light">Avaliação média</small>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="card h-100 border-0">
                <div class="card-body text-center p-2">
                  <h5 class="mb-0 fw-bold">
                    <i class="ph ph-users"></i>
                    67%
                  </h5>

                  <small class="fw-light">
                    Clientes recorrentes
                  </small>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-3">
              <div class="card h-100 border-0">
                <div class="card-body text-center p-2">
                  <h5 class="mb-0 fw-bold">
                    <i class="ph ph-clock"></i>
                    45 min
                  </h5>

                  <small class="fw-light">
                    Tempo médio de atendimento
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
  </body>
</html>