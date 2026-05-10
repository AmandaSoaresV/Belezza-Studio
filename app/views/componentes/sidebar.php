<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Agendamento</title>
    <link rel="stylesheet" href="/public/assets/css/global.css" />
    <link rel="stylesheet" href="/public/assets/css/sidebar.css" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
  </head>

  <body>
    <div
      class="d-flex flex-column flex-shrink-0 p-3 "
      style="width: 280px; height: 100vh; background-color: var(--primary-700, #72558e)"
    >
      <a
        href="/"
        class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"
      >
        <img
          src="/public/assets/img/logo-site.png"
          alt="Logo"
          style="width: 250px; height: auto; padding: 20px"
        />
      </a>

      <hr />

      <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
          <a href="#" class="nav-link active" aria-current="page">
            <i class="ph ph-house me-2"></i>
            Dashboard
          </a>
        </li>

        <li>
          <a href="#" class="nav-link text-white">
            <i class="ph ph-calendar-check me-2"></i>
            Agendamentos
          </a>
        </li>

        <li>
          <a href="#" class="nav-link text-white">
            <i class="ph ph-scissors me-2"></i>
            Serviços
          </a>
        </li>
      </ul>

      <hr />

      <div class="dropdown">
        <a
          href="#"
          class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <img
            src="https://api.dicebear.com/9.x/adventurer/svg?seed=Vivian"
            alt=""
            width="32"
            height="32"
            class="rounded-circle me-2"
          />

          <strong>Amanda</strong>
        </a>

        <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
          <li><a class="dropdown-item" href="#">Sair</a></li>
        </ul>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>