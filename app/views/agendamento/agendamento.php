  <?php
    require_once __DIR__ . '/../../../api/conexao.php';

    $sqlServicos = <<<CONSULTA
      SELECT
        nome 
      FROM `servicos` 
    CONSULTA;

    try {
        $resultado = $pdo->query($sqlServicos);
        $servicos = $resultado->fetchAll(PDO::FETCH_ASSOC);
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
    <link rel="stylesheet" href="/assets/css/agendamento.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
  </head>


  <body class="pagina-agendamento">
     <?php
    $header = __DIR__ . '/../../../includes/header.php';
     if (file_exists($header))
         { include $header; }
     else { include __DIR__ . '/../erro/erro.php'; } ?>

    <main class="container-marca secao">
      <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">

          <div class="text-center mb-5">
            <p class="eyebrow cor-marca justify-content-center">Agendamento Online</p>
            <h1 class="display-marca" style="font-size: clamp(1.9rem, 4vw, 2.6rem);">Reserve seu horário</h1>
            <p class="texto-lead">Três passos rápidos e seu horário está garantido.</p>
          </div>

          <div class="passos-indicador mb-4" id="passosIndicador">
            <div class="passo-item passo-item--ativo" data-passo="1">
              <span class="passo-numero">1</span>
              <span class="passo-nome">Serviço</span>
            </div>
            <div class="passo-linha"></div>
            <div class="passo-item" data-passo="2">
              <span class="passo-numero">2</span>
              <span class="passo-nome">Data &amp; horário</span>
            </div>
            <div class="passo-linha"></div>
            <div class="passo-item" data-passo="3">
              <span class="passo-numero">3</span>
              <span class="passo-nome">Seus dados</span>
            </div>
          </div>

          <div class="superficie superficie--elevada p-4 p-md-5">
            <form class="form-agendamento" action="" method="POST" id="formAgendamento">
              <section class="etapa-agendamento" data-etapa="1">
                <h2 class="h5 fw-semibold mb-4">Qual serviço você deseja?</h2>

                <div class="mb-4">
                  <select
                    class="form-select form-select-lg"
                    id="servico"
                    name="servico"
                    required
                  >
                    <option value="" selected disabled>
                      Selecione um serviço
                    </option>

                    <?php foreach ($servicos as $servico): ?>
                    <option value="<?php echo $servico['nome']; ?>">
                      <?php echo $servico['nome']; ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="d-flex justify-content-end">
                  <button type="button" class="btn-marca btn-etapa-avancar">
                    Continuar <i class="ph ph-arrow-right"></i>
                  </button>
                </div>
              </section>

              <section class="etapa-agendamento d-none" data-etapa="2">
                <h2 class="h5 fw-semibold mb-4">Escolha a data e o horário</h2>

                <div class="mb-4">
                  <label for="data" class="form-label">
                    Data do Agendamento
                  </label>

                  <input
                    type="date"
                    class="form-control form-control-lg"
                    id="data"
                    name="data"
                    required
                  />
                </div>

                <div class="mb-4">
                  <label class="form-label">
                    Horários Disponíveis
                  </label>

                  <div class="d-flex flex-wrap gap-2" id="horarios">
                    <button type="button" class="botao-horario botao-horario--livre">09:00</button>
                    <button type="button" class="botao-horario" disabled>09:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">10:00</button>
                    <button type="button" class="botao-horario" disabled>10:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">11:00</button>
                    <button type="button" class="botao-horario" disabled>11:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">12:00</button>
                    <button type="button" class="botao-horario" disabled>12:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">13:00</button>
                  </div>
                  <input type="hidden" name="horario" id="horarioEscolhido" />
                </div>

                <div class="d-flex justify-content-between">
                  <button type="button" class="btn-marca btn-marca--contorno btn-etapa-voltar">
                    <i class="ph ph-arrow-left"></i> Voltar
                  </button>
                  <button type="button" class="btn-marca btn-etapa-avancar">
                    Continuar <i class="ph ph-arrow-right"></i>
                  </button>
                </div>
              </section>

              <section class="etapa-agendamento d-none" data-etapa="3">
                <h2 class="h5 fw-semibold mb-4">Seus dados</h2>

                <div class="mb-3">
                  <label for="inputNome" class="form-label">
                    Nome Completo
                  </label>

                  <input
                    type="text"
                    class="form-control form-control-lg"
                    id="inputNome"
                    name="nome"
                    placeholder="Digite seu nome completo"
                    required
                  />
                </div>

                <div class="mb-4">
                  <label for="inputTelefone" class="form-label">
                    Telefone
                  </label>

                  <input
                    type="tel"
                    class="form-control form-control-lg"
                    id="inputTelefone"
                    name="telefone"
                    placeholder="(44) 99999-9999"
                    required
                  />
                </div>

                <div class="d-flex justify-content-between">
                  <button type="button" class="btn-marca btn-marca--contorno btn-etapa-voltar">
                    <i class="ph ph-arrow-left"></i> Voltar
                  </button>
                  <button type="submit" class="btn-marca">
                    Confirmar Agendamento <i class="ph ph-check"></i>
                  </button>
                </div>
              </section>

            </form>
          </div>
        </div>
      </div>
    </main>

    <script src="/assets/js/agendamento.js"></script>

   <?php
        $footer = __DIR__ . '/../../../includes/footer.php';

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