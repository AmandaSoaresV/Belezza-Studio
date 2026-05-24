<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Dashboard</title>

    <link rel="stylesheet" href="/assets/css/global.css" />
    <link rel="stylesheet" href="/assets/css/dashboard.css" />

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
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
                <h2 class="quantidade">12</h2>
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
                <h2 class="quantidade">8</h2>
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
                <h2 class="quantidade">3</h2>
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
                <h2 class="quantidade">R$640</h2>
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
            <h4 class="mb-0">Agendamentos do dia</h4>

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
                <tr>
                  <td>Amanda</td>
                  <td>Corte de Cabelo</td>
                  <td>22/05/2026</td>
                  <td>14:00</td>

                  <td>
                    <span class="badge bg-success">
                      Confirmado
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

                <tr>
                  <td>Ana</td>
                  <td>Manicure</td>
                  <td>23/05/2026</td>
                  <td>15:30</td>

                  <td>
                    <span class="badge bg-warning text-dark">
                      Pendente
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

                <tr>
                  <td>John</td>
                  <td>Corte de Cabelo</td>
                  <td>24/05/2026</td>
                  <td>16:00</td>

                  <td>
                    <span class="badge bg-success">
                      Confirmado
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
              </tbody>
            </table>
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