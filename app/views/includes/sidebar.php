<?php $paginaAdminAtiva = $paginaAdminAtiva ?? ''; ?>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a href="/" class="admin-sidebar-logo">
      <img src="/assets/img/logo-site.png" alt="Belezza Studio">
    </a>

    <nav class="admin-sidebar-nav">
      <a href="/dashboard" class="admin-nav-link <?php echo $paginaAdminAtiva === 'dashboard' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-squares-four"></i> Dashboard
      </a>
      <a href="/relatorio" class="admin-nav-link <?php echo $paginaAdminAtiva === 'relatorio' ? 'admin-nav-link--ativo' : ''; ?>">
        <i class="ph ph-chart-line-up"></i> Relatório
      </a>
      <a href="/agendamento" class="admin-nav-link">
        <i class="ph ph-calendar-plus"></i> Novo Agendamento
      </a>
    </nav>

    <a href="/" class="admin-sidebar-voltar">
      <i class="ph ph-arrow-left"></i> Voltar ao site
    </a>
  </aside>

  <div class="admin-conteudo">
