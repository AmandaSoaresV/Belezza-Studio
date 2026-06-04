
  <?php
    require_once __DIR__ . '/../../../config/conexao.php';

    $sqlSeusHorarios = <<<CONSULTA
      SELECT
        usuarios.nome AS nome_cliente, 
        agendamentos.data_hora_servico, 
        servicos.nome AS nome_servico, 
        agendamentos.status 
      FROM `agendamentos` 
      INNER JOIN usuarios ON agendamentos.id_cliente = usuarios.id_usuario 
      INNER JOIN servicos On agendamentos.id_servico = servicos.id_servico 
      WHERE usuarios.id_usuario = 3
    CONSULTA;

    $resultado = $conn->query($sqlSeusHorarios);

    if (!$resultado) {
        die("erro na consulta: " . $conn->error);
    }
    $seusHorarios = $resultado->fetch_all(MYSQLI_ASSOC);
  ?>
  
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

      <?php foreach ($seusHorarios as $agendamentos): ?>

        <?php
          $status = $agendamentos['status'];

          if ($status == 'confirmado') {
              $classeBadge = 'text-bg-success';
          } elseif ($status == 'pendente') {
              $classeBadge = 'text-bg-warning';
          } elseif ($status == 'cancelado') {
              $classeBadge = 'text-bg-danger';
          } elseif ($status == 'concluido') {
              $classeBadge = 'text-bg-primary';
          } else {
              $classeBadge = 'text-bg-secondary';
          }
        ?>

        <div
        class="container-horarios rounded p-3 p-md-4 mb-4"
        style="background-color: var(--neutro-300)"
      >
        <div
          class="card border-0 w-100"
          style="background-color: var(--neutro-100)"
        >
            <div class="card-body p-3 p-md-4">
            <h5 class="card-title fw-bold" style="color: var(--neutro-500)">
                <?php echo $agendamentos['nome_servico']; ?>
              </h5>

             <p class="card-text fw-bold mb-1" style="color: var(--neutro-900)">
                <?php echo $agendamentos['data_hora_servico']; ?>
              </p>

              <p class="card-text mb-3" style="color: var(--neutro-500)">
                <?php echo $agendamentos['nome_cliente']; ?>
              </p>

             <div
              class="d-flex flex-column flex-xl-row justify-content-between align-items-start align-items-xl-center gap-3"
            >

              <div class="d-flex flex-column flex-sm-row gap-2">

                  <?php if ($status == 'confirmado'): ?>
                    <button
                      type="button"
                      class="btn btn-outline-danger d-flex align-items-center gap-2"
                    >
                  <i class="ph ph-x-circle"></i>
                  Cancelar Agendamento
                </button>

                  <?php elseif ($status == 'pendente'): ?>
                    <a
                     href="https://wa.me/5544998870670?text=Olá,%20quero%20confirmar%20meu%20agendamento"
                     target="_blank"
                     class="btn btn-success d-flex align-items-center gap-2"
>
                      <i class="ph ph-whatsapp-logo"></i>
                      Confirmar no WhatsApp
                    </a>

                    <button
                      type="button"
                      class="btn btn-outline-danger d-flex align-items-center gap-2"
                    >
                      <i class="ph ph-x-circle"></i>
                      Cancelar Agendamento
                    </button>

                  <?php elseif ($status == 'concluido'): ?>
                    <button
                      type="button"
                      class="btn btn-outline-secondary d-flex align-items-center gap-2"
                      disabled
                    >
                      <i class="ph ph-check-circle"></i>
                      Finalizado
                    </button>

                  <?php elseif ($status == 'cancelado'): ?>
                    <button
                      type="button"
                      class="btn btn-outline-secondary d-flex align-items-center gap-2"
                      disabled
                    >
                      <i class="ph ph-prohibit"></i>
                      Agendamento Cancelado
                    </button>
                  <?php endif; ?>

                </div>

                <span class="badge <?php echo $classeBadge; ?> px-3 py-2">
                  <?php echo $status; ?>
                </span>

              </div>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    </div>

   <?php
        $footer = __DIR__ . '/../includes/footer.php';

        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }

      $conn->close();
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>