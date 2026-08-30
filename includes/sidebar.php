<?php $paginaAdminAtiva = $paginaAdminAtiva ?? ''; ?>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a href="/" class="admin-sidebar-logo">
      <img class="logo-escura" src="/assets/img/logo-tema-escuro.png" alt="Belezza Studio">
      <img class="logo-clara" src="/assets/img/logo-tema-claro.png" alt="Belezza Studio">
    </a>

    <nav class="admin-sidebar-nav">
      <a href="/dashboard" class="admin-nav-link <?php echo $paginaAdminAtiva === 'dashboard' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-squares-four"></i> Dashboard
      </a>
      <a href="/relatorio" class="admin-nav-link <?php echo $paginaAdminAtiva === 'relatorio' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-chart-line-up"></i> Relatório
      </a>
      <a href="/servicos" class="admin-nav-link <?php echo $paginaAdminAtiva === 'servicos' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-scissors"></i> Serviços
      </a>
      <a href="/usuarios" class="admin-nav-link <?php echo $paginaAdminAtiva === 'usuarios' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-users"></i> Usuários
      </a>
      <a href="/agendamento" class="admin-nav-link">
        <i class="ph ph-calendar-plus"></i> Agendar (cliente)
      </a>

      <span class="admin-sidebar-secao">Formulários</span>

      <a href="/usuarios/cadastrar" class="admin-nav-link <?php echo $paginaAdminAtiva === 'usuarios-cadastrar' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-users"></i> Cadastrar Usuário
      </a>
      <a href="/servicos/cadastrar" class="admin-nav-link <?php echo $paginaAdminAtiva === 'servicos-cadastrar' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-scissors"></i> Cadastrar Serviço
      </a>
      <a href="/agendamentos/cadastrar" class="admin-nav-link <?php echo $paginaAdminAtiva === 'agendamentos-cadastrar' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-calendar-plus"></i> Cadastrar Agendamento
      </a>
      <a href="/profissionais/cadastrar" class="admin-nav-link <?php echo $paginaAdminAtiva === 'profissionais-cadastrar' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-identification-badge"></i> Cadastrar Profissional
      </a>
    </nav>

    <button type="button" class="admin-nav-link btn-tema-toggle" data-tema-toggle aria-label="Alternar tema">
      <i class="ph ph-sun icon-tema-claro"></i>
      <i class="ph ph-moon icon-tema-escuro"></i> Alternar tema
    </button>

    <a href="/" class="admin-sidebar-voltar">
      <i class="ph ph-arrow-left"></i> Voltar ao site
    </a>
  </aside>

  <div class="admin-conteudo">
