<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Agendamento</title>

    <link rel="stylesheet" href="/assets/css/global.css" />
    <link rel="stylesheet" href="/assets/css/seushorarios.css" />

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
  </head>

  <body>
      <?php
        $header = __DIR__ . '/../includes/header.php';

        if (file_exists($header)) {
            include $header;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <div class="container py-4">
      <div class="card container-agenda text-white border-0">
        <div class="card-body d-flex flex-column justify-content-center p-4 p-md-5">
          <span class="badge text-bg-light text-dark mb-3 align-self-start px-3 py-2">
            Agenda do Cliente
          </span>

          <h1 class="display-6 fw-bold">Seus Horários Agendados</h1>

          <p class="mb-0 fs-6" style="color: var(--pink-100)">
            Gerencie seus horários agendados
          </p>
        </div>
      </div>
    </div>

    <div class="container py-4">
      <div
        class="container-horarios rounded p-3 p-md-4 mb-4"
        style="background-color: var(--neutro-300)"
      >
        <p class="text-black fw-bold mb-4">Hoje, 22 de maio</p>

        <div
          class="card border-0 w-100 mb-3"
          style="background-color: var(--neutro-100)"
        >
          <div class="card-body p-3 p-md-4">
            <h5 class="card-title fw-bold" style="color: var(--neutro-500)">
              Corte de Cabelo
            </h5>

            <p class="card-text fw-bold mb-1" style="color: var(--neutro-900)">
              Agendado para 14:00
            </p>

            <p class="card-text mb-3" style="color: var(--neutro-500)">
              Amanda Soares Vieira
            </p>

            <div
              class="d-flex flex-column flex-xl-row justify-content-between align-items-start align-items-xl-center gap-3"
            >
              <button type="button" class="btn btn-danger">
                <i class="ph ph-x-circle"></i>
                Cancelar Agendamento
              </button>

              <span class="badge text-bg-success px-3 py-2">
                Confirmado
              </span>
            </div>
          </div>
        </div>

        <div
          class="card border-0 w-100"
          style="background-color: var(--neutro-100)"
        >
          <div class="card-body p-3 p-md-4">
            <h5 class="card-title fw-bold" style="color: var(--neutro-500)">
              Escova Progressiva
            </h5>

            <p class="card-text fw-bold mb-1" style="color: var(--neutro-900)">
              Agendado para 16:30
            </p>

            <p class="card-text mb-3" style="color: var(--neutro-500)">
              Juliana Martins
            </p>

            <div
              class="d-flex flex-column flex-xl-row justify-content-between align-items-start align-items-xl-center gap-3"
            >
              <div class="d-flex flex-column flex-sm-row gap-2">
                <button type="button" class="btn btn-success">
                  <i class="ph ph-whatsapp-logo"></i>
                  Confirmar no WhatsApp
                </button>

                <button type="button" class="btn btn-danger">
                  <i class="ph ph-x-circle"></i>
                  Cancelar Agendamento
                </button>
              </div>

              <span class="badge text-bg-warning px-3 py-2">
                Pendente
              </span>
            </div>
          </div>
        </div>
      </div>

      <div
        class="container-horarios rounded p-3 p-md-4 mb-4"
        style="background-color: var(--neutro-300)"
      >
        <p class="text-black fw-bold mb-4">Sexta-feira, 23 de maio</p>

        <div
          class="card border-0 w-100"
          style="background-color: var(--neutro-100)"
        >
          <div class="card-body p-3 p-md-4">
            <h5 class="card-title fw-bold" style="color: var(--neutro-500)">
              Design de Sobrancelha
            </h5>

            <p class="card-text fw-bold mb-1" style="color: var(--neutro-900)">
              Agendado para 11:45
            </p>

            <p class="card-text mb-3" style="color: var(--neutro-500)">
              Camila Rodrigues
            </p>

            <div
              class="d-flex flex-column flex-xl-row justify-content-between align-items-start align-items-xl-center gap-3"
            >
              <div class="d-flex flex-column flex-sm-row gap-2">
                <button type="button" class="btn btn-success disabled" disabled>
                  <i class="ph ph-whatsapp-logo"></i>
                  Confirmar no WhatsApp
                </button>

                <button type="button" class="btn btn-danger disabled" disabled>
                  <i class="ph ph-x-circle"></i>
                  Cancelar Agendamento
                </button>
              </div>

              <span class="badge text-bg-secondary px-3 py-2">
                Finalizado
              </span>
            </div>
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