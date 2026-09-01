<?php
    require_once __DIR__ . '/../../../api/conexao.php';
    require_once __DIR__ . '/../../../includes/app.php';
    require_once __DIR__ . '/../../../includes/analytics.php';
    require_once __DIR__ . '/../../../includes/sessao.php';

    $idCliente = usuarioLogado()['id_usuario'];

    $servicos = [];
    $profissionais = [];
    $cliente = null;
    $erros = [];

    try {
        $servicos = listarServicosParaSelecao($pdo);
        $profissionais = listarProfissionais($pdo);
        $cliente = obterUsuario($pdo, $idCliente);
    } catch (PDOException $e) {
        $erros[] = 'Não foi possível carregar os serviços e profissionais, tente novamente.';
    }

    $valores = [
        'id_servico' => '',
        'id_profissional' => '',
        'data' => '',
        'horario' => '',
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erros)) {
        $valores['id_servico'] = trim($_POST['id_servico'] ?? '');
        $valores['id_profissional'] = trim($_POST['id_profissional'] ?? '');
        $valores['data'] = trim($_POST['data'] ?? '');
        $valores['horario'] = trim($_POST['horario'] ?? '');

        $idServico = (int) $valores['id_servico'];
        $idProfissional = (int) $valores['id_profissional'];
        $dataHora = converterDataHoraParaBanco($valores['data'] . 'T' . $valores['horario']);

        if (!existeIdNaLista($servicos, 'id_servico', $idServico)) {
            $erros[] = 'Selecione um serviço da lista.';
        }

        if (!existeIdNaLista($profissionais, 'id_profissional', $idProfissional)) {
            $erros[] = 'Selecione um profissional da lista.';
        }

        if ($dataHora === null) {
            $erros[] = 'Escolha uma data e um horário válidos.';
        }

        if (empty($erros)) {
            try {
                criarAgendamento($pdo, [
                    'id_cliente' => $idCliente,
                    'id_profissional' => $idProfissional,
                    'id_servico' => $idServico,
                    'data_hora_servico' => $dataHora,
                    'status' => 'pendente',
                    'observacao' => '',
                ]);

                header('Location: /seushorarios?criado=1');
                exit;
            } catch (PDOException $e) {
                $erros[] = 'Não foi possível confirmar o agendamento, tente novamente.';
            }
        }
    }
?>

<?php
    $tituloPagina = 'Agendamento';
    $classeBody = 'pagina-agendamento';
    $cssPagina = ['agendamento.css'];
    $usarFormularios = true;
?>
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

          <?php if (!empty($erros)): ?>
          <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
              <?php foreach ($erros as $erro): ?>
              <li><?php echo htmlspecialchars($erro); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

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
            <form class="form-agendamento" action="/agendamento" method="POST" id="formAgendamento" data-parsley-validate="">
              <section class="etapa-agendamento" data-etapa="1">
                <h2 class="h5 fw-semibold mb-4">Qual serviço você deseja?</h2>

                <div class="mb-4">
                  <label for="id_servico" class="form-label">Serviço</label>
                  <select
                    class="form-select form-select-lg"
                    id="id_servico"
                    name="id_servico"
                    required
                    data-parsley-group="passo1"
                    data-parsley-required-message="Preencha este campo"
                  >
                    <option value="" disabled <?php echo $valores['id_servico'] === '' ? 'selected' : ''; ?>>
                      Selecione um serviço
                    </option>

                    <?php foreach ($servicos as $servico): ?>
                    <option
                      value="<?php echo $servico['id_servico']; ?>"
                      <?php echo (string) $servico['id_servico'] === $valores['id_servico'] ? 'selected' : ''; ?>
                    >
                      <?php echo htmlspecialchars($servico['nome']); ?> &mdash; R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="mb-4">
                  <label for="id_profissional" class="form-label">Profissional</label>
                  <select
                    class="form-select form-select-lg"
                    id="id_profissional"
                    name="id_profissional"
                    required
                    data-parsley-group="passo1"
                    data-parsley-required-message="Preencha este campo"
                  >
                    <option value="" disabled <?php echo $valores['id_profissional'] === '' ? 'selected' : ''; ?>>
                      Selecione um profissional
                    </option>

                    <?php foreach ($profissionais as $profissional): ?>
                    <option
                      value="<?php echo $profissional['id_profissional']; ?>"
                      <?php echo (string) $profissional['id_profissional'] === $valores['id_profissional'] ? 'selected' : ''; ?>
                    >
                      <?php echo htmlspecialchars($profissional['nome']); ?> &mdash; <?php echo htmlspecialchars($profissional['especialidade']); ?>
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
                    value="<?php echo htmlspecialchars($valores['data']); ?>"
                    required
                    data-parsley-group="passo2"
                    data-parsley-required-message="Preencha este campo"
                  />
                </div>

                <div class="mb-4">
                  <label class="form-label">
                    Horários Disponíveis
                  </label>

                  <div class="d-flex flex-wrap gap-2" id="horarios">
                    <button type="button" class="botao-horario botao-horario--livre">09:00</button>
                    <button type="button" class="botao-horario botao-horario--livre">09:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">10:00</button>
                    <button type="button" class="botao-horario botao-horario--livre">10:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">11:00</button>
                    <button type="button" class="botao-horario botao-horario--livre">11:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">14:00</button>
                    <button type="button" class="botao-horario botao-horario--livre">14:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">15:00</button>
                    <button type="button" class="botao-horario botao-horario--livre">15:30</button>
                    <button type="button" class="botao-horario botao-horario--livre">16:00</button>
                    <button type="button" class="botao-horario botao-horario--livre">16:30</button>
                  </div>
                  <input
                    type="hidden"
                    name="horario"
                    id="horarioEscolhido"
                    value="<?php echo htmlspecialchars($valores['horario']); ?>"
                    data-parsley-group="passo2"
                    required
                    data-parsley-required-message="Escolha um horário"
                  />
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

                <p class="texto-lead mb-4">
                  O agendamento será feito em nome da conta que está logada. Para alterar esses dados,
                  fale com a recepção do salão.
                </p>

                <div class="mb-3">
                  <label for="nomeCliente" class="form-label">Nome Completo</label>
                  <input
                    type="text"
                    class="form-control form-control-lg"
                    id="nomeCliente"
                    value="<?php echo htmlspecialchars($cliente['nome'] ?? ''); ?>"
                    readonly
                  />
                </div>

                <div class="mb-4">
                  <label for="telefoneCliente" class="form-label">Telefone</label>
                  <input
                    type="text"
                    class="form-control form-control-lg"
                    id="telefoneCliente"
                    value="<?php echo htmlspecialchars($cliente['telefone'] ?? ''); ?>"
                    readonly
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

    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
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
