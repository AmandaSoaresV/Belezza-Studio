<?php require_once __DIR__ . '/sessao.php'; ?>
<?php

$tituloPagina    = $tituloPagina ?? 'Belezza Studio';
$classeBody      = $classeBody ?? '';
$cssPagina       = $cssPagina ?? [];
$usarFormularios = $usarFormularios ?? false;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $tituloPagina; ?> — Belezza Studio</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="/assets/css/global.css">
  <link rel="stylesheet" href="/assets/css/style.css">
<?php foreach ($cssPagina as $arquivoCss): ?>
  <link rel="stylesheet" href="/assets/css/<?php echo $arquivoCss; ?>">
<?php endforeach; ?>
<?php if ($usarFormularios): ?>
  <?php include __DIR__ . '/form-validacao-head.php'; ?>
<?php endif; ?>

  <style>
    .topo-site {
      background-color: rgba(11, 11, 15, 0.85);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--borda-sutil);
      padding: 14px 0;
      position: sticky;
      top: 0;
      z-index: 40;
    }
    .topo-site .logo-site { width: 150px; height: auto; padding: 0; }
    .topo-site .nav-link {
      color: var(--texto-secundario) !important;
      font-weight: 500;
      font-size: 0.92rem;
      padding: 10px 16px !important;
      border-radius: var(--radius-pill);
      transition: background-color .15s ease, color .15s ease;
    }
    .topo-site .nav-link:hover {
      background-color: var(--bg-surface-2);
      color: var(--texto-primario) !important;
    }
    .topo-site .nav-link--cta {
      background-color: var(--primary-600);
      color: var(--white) !important;
      font-weight: 600;
      margin-left: 8px;
    }
    .topo-site .nav-link--cta:hover {
      background-color: var(--primary-700);
    }
  </style>
</head>

<body class="<?php echo $classeBody; ?>">
  <header class="topo-site text-white">
    <div class="container-marca">
      <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-between gap-3">
        <a href="/" class="d-inline-flex align-items-center text-decoration-none">
          <img class="logo-site logo-escura" src="/assets/img/logo-tema-escuro.png" alt="Logo do Site">
          <img class="logo-site logo-clara" src="/assets/img/logo-tema-claro.png" alt="Logo do Site">
        </a>

        <ul class="nav col-12 col-lg-auto justify-content-center align-items-center mb-0 gap-1">
          <li>
            <a href="/" class="nav-link">Início</a>
          </li>
          <li>
            <a href="/#servicos" class="nav-link">Serviços</a>
          </li>
          <?php if (estaLogado() && !ehAdmin()): ?>
          <li>
            <a href="/seushorarios" class="nav-link">Meus Horários</a>
          </li>
          <?php endif; ?>

          <?php if (ehAdmin()): ?>
          <li>
            <a href="/dashboard" class="nav-link">Dashboard</a>
          </li>
          <li>
            <a href="/relatorio" class="nav-link">Relatório</a>
          </li>
          <?php endif; ?>

          <?php if (!estaLogado()): ?>
          <li>
            <a href="/login" class="nav-link">Entrar</a>
          </li>
          <li>
            <a href="/usuarios/cadastrar" class="nav-link">Cadastrar</a>
          </li>
          <?php else: ?>
          <li>
            <a href="/logout" class="nav-link">Sair (<?php echo htmlspecialchars(usuarioLogado()['nome']); ?>)</a>
          </li>
          <?php endif; ?>
          <li>
            <a href="/agendamento" class="nav-link nav-link--cta">Agendar</a>
          </li>
          <li>
            <button type="button" class="nav-link btn-tema-toggle" data-tema-toggle aria-label="Alternar tema">
              <i class="fas fa-sun icon-tema-claro"></i>
              <i class="fas fa-moon icon-tema-escuro"></i>
            </button>
          </li>
        </ul>
      </div>
    </div>
  </header>
