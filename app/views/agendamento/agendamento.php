<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agendamento</title>
    <link rel="stylesheet" href="/public/assets/css/global.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
  </head>

  <body class="bg-light">
     <?php
     $header = __DIR__ . '/../componentes/header.php'; 
     if (file_exists($header))
         { include $header; } 
     else { include __DIR__ . '/../erro/erro.php'; } ?>

    <main class="container py-5">
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
          <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
              <div class="text-center mb-4">
                <span class="badge mb-3 px-3 py-2" style="background-color:var(--primary-800) ">
                  Agendamento Online
                </span>

                <h1 class="fw-bold">Reserve seu horário</h1>

                <p class="text-secondary mb-0">
                  Preencha os dados abaixo para agendar seu serviço.
                </p>
              </div>

              <form
                class="form-agendamento"
                action=""
                method="POST"
              >
                <div class="mb-3">
                  <label for="inputNome" class="form-label fw-semibold">
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

                <div class="mb-3">
                  <label for="inputTelefone" class="form-label fw-semibold">
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

                <div class="mb-3">
                  <label for="servico" class="form-label fw-semibold">
                    Serviço
                  </label>

                  <select
                    class="form-select form-select-lg"
                    id="servico"
                    name="servico"
                    required
                  >
                    <option value="" selected disabled>
                      Selecione um serviço
                    </option>

                    <option value="corte">Corte de Cabelo - R$ 30,00</option>

                    <option value="barba">Barba - R$ 20,00</option>

                    <option value="sobrancelha">Sobrancelha - R$ 15,00</option>

                    <option value="unhas">Unhas - R$ 45,00</option>
                  </select>
                </div>

                <div class="mb-4">
                  <label for="data" class="form-label fw-semibold">
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
                  <label class="form-label fw-semibold">
                    Horários Disponíveis
                  </label>

                  <div class="d-flex flex-wrap gap-2" id="horarios">
                    <button type="button" class="btn btn-outline-success">
                      09:00
                    </button>

                    <button
                      type="button"
                      class="btn btn-outline-secondary"
                      disabled
                    >
                      09:30
                    </button>

                    <button type="button" class="btn btn-outline-success">
                      10:00
                    </button>

                    <button
                      type="button"
                      class="btn btn-outline-secondary"
                      disabled
                    >
                      10:30
                    </button>

                    <button type="button" class="btn btn-outline-success">
                      11:00
                    </button>

                    <button
                      type="button"
                      class="btn btn-outline-secondary"
                      disabled
                    >
                      11:30
                    </button>
                    <button
                    type="button"
                    class="btn btn-outline-success"
                    >
                      12:00
                    </button>
                    <button
                      type="button"
                      class="btn btn-outline-secondary"
                      disabled
                    >
                      12:30
                    </button>
                    <button
                      type="button"                      
                      class="btn btn-outline-success"
                    >
                      13:00
                    </button>
                  </div>
                </div>

                <div class="d-grid">
                  <button type="submit" class="btn btn-agendar" style="background-color:var(--primary-800); color:var(--white)">
                    Agendar Horário
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </main>
    
   <?php
        $footer = __DIR__ . '/../componentes/footer.php';

        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
