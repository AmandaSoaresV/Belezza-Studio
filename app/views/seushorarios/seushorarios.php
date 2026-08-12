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

    try {
        $resultado = $pdo->query($sqlSeusHorarios);
        $seusHorarios = $resultado->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("erro na consulta: " . $e->getMessage());
    }
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

  <body class="pagina-horarios">

     <?php
        $header = __DIR__ . '/../layouts/header.php';

        if (file_exists($header)) {
            include $header;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <div class="container-marca pt-5">
      <div class="banner-agenda text-white">
        <p class="eyebrow" style="color: var(--pink-300);">Agenda do Cliente</p>
        <h1 class="display-marca text-white" style="font-size: clamp(1.8rem, 4vw, 2.6rem);">Seus Horários Agendados</h1>
        <p class="mb-0" style="color: var(--primary-100);">
          Acompanhe, confirme ou cancele seus agendamentos.
        </p>
      </div>
    </div>

    <div class="container-marca secao">

      <?php if (empty($seusHorarios)): ?>
        <div class="superficie text-center p-5">
          <i class="ph ph-calendar-blank" style="font-size: 2.5rem; color: var(--primary-400);"></i>
          <p class="texto-lead mt-3 mb-0">Você ainda não tem agendamentos.</p>
        </div>
      <?php endif; ?>

      <?php foreach ($seusHorarios as $agendamentos): ?>

        <?php
          $status = $agendamentos['status'];
          $classeStatus = 'status-chip--' . $status;
        ?>

        <div class="superficie cartao-horario mb-4">
          <div class="cartao-horario-faixa cartao-horario-faixa--<?php echo $status; ?>"></div>

          <div class="cartao-horario-corpo">
            <div>
              <h3 class="cartao-horario-servico"><?php echo $agendamentos['nome_servico']; ?></h3>
              <p class="cartao-horario-data">
                <i class="ph ph-clock"></i>
                <?php echo $agendamentos['data_hora_servico']; ?>
              </p>
              <p class="cartao-horario-cliente">
                <i class="ph ph-user"></i>
                <?php echo $agendamentos['nome_cliente']; ?>
              </p>
            </div>

            <div class="cartao-horario-acoes">
              <span class="status-chip <?php echo $classeStatus; ?>">
                <?php echo ucfirst($status); ?>
              </span>

              <div class="d-flex flex-column flex-sm-row gap-2">

                <?php if ($status == 'confirmado'): ?>
                  <button
                    type="button"
                    class="btn-marca btn-marca--contorno btn-marca--pequeno">
                    <i class="ph ph-x-circle"></i>
                     Cancelar
                  </button>

                <?php elseif ($status == 'pendente'): ?>
                  <a
                    href="https://wa.me/5544998870670?text=Olá,%20quero%20confirmar%20meu%20agendamento"
                    target="_blank"
                    class="btn-marca btn-marca--pequeno"
                    style="background-color: var(--cor-sucesso);"
                  >
                    <i class="ph ph-whatsapp-logo"></i> Confirmar no WhatsApp
                  </a>

                  <button
                   type="button"
                   class="btn-marca btn-marca--contorno btn-marca--pequeno">
                    <i class="ph ph-x-circle"></i>
                     Cancelar
                  </button>

                <?php elseif ($status == 'concluido'): ?>
                  <button
                   type="button" 
                   class="btn-marca btn-marca--contorno btn-marca--pequeno" disabled>
                    <i class="ph ph-check-circle"></i> 
                    Finalizado
                  </button>

                <?php elseif ($status == 'cancelado'): ?>
                  <button
                   type="button" 
                   class="btn-marca btn-marca--contorno btn-marca--pequeno" disabled>
                    <i class="ph ph-prohibit"></i> 
                    Agendamento Cancelado
                  </button>
                <?php endif; ?>

              </div>
            </div>
          </div>
        </div>

      <?php endforeach; ?>
    </div>

   <?php
        $footer = __DIR__ . '/../layouts/footer.php';

        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }

      $pdo = null;
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>