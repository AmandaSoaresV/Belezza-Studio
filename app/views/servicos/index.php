<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/analytics.php';

$porPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

$servicoSalvo = isset($_GET['salvo']);
$servicoAtualizado = isset($_GET['atualizado']);
$servicoNaoEncontrado = isset($_GET['naoencontrado']);

$servicos = [];
$totalRegistros = 0;
$totalPaginas = 1;
$erroConsulta = false;

try {
    $totalRegistros = contarServicos($pdo);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

    if ($paginaAtual > $totalPaginas) {
        $paginaAtual = $totalPaginas;
        $offset = ($paginaAtual - 1) * $porPagina;
    }

    $servicos = listarServicos($pdo, $porPagina, $offset);
} catch (PDOException $e) {
    $erroConsulta = true;
}
?>

<?php
$tituloPagina = 'Serviços';
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'servicos'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
      <div>
        <h1 class="admin-topbar-titulo">Serviços</h1>
        <p class="admin-topbar-subtitulo">
          <?php echo $totalRegistros; ?> serviço<?php echo $totalRegistros === 1 ? '' : 's'; ?> cadastrado<?php echo $totalRegistros === 1 ? '' : 's'; ?>
        </p>
      </div>

      <a href="/servicos/cadastrar" class="btn-marca btn-marca--pequeno">
        <i class="ph ph-plus"></i> Novo Serviço
      </a>
    </header>

    <div class="admin-container">
      <?php if ($servicoSalvo): ?>
      <div class="alert alert-success text-center" role="alert">
        Serviço cadastrado com sucesso.
      </div>
      <?php endif; ?>

      <?php if ($servicoAtualizado): ?>
      <div class="alert alert-success text-center" role="alert">
        Serviço atualizado com sucesso.
      </div>
      <?php endif; ?>

      <?php if ($servicoNaoEncontrado): ?>
      <div class="alert alert-warning text-center" role="alert">
        Serviço não encontrado.
      </div>
      <?php endif; ?>

      <?php if ($erroConsulta): ?>
      <div class="alert alert-warning text-center" role="alert">
        Não foi possível carregar os serviços, verifique se o banco foi importado.
      </div>
      <?php endif; ?>

      <div class="superficie">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle tabela-marca">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Descrição</th>
                  <th>Preço</th>
                  <th>Duração</th>
                  <th class="text-center">Ações</th>
                </tr>
              </thead>

              <tbody>
                <?php if (empty($servicos)): ?>
                <tr>
                  <td colspan="5" class="text-center py-4">Nenhum serviço cadastrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($servicos as $servico): ?>
                <tr>
                  <td><?php echo htmlspecialchars($servico['nome']); ?></td>
                  <td class="text-body-secondary"><?php echo htmlspecialchars($servico['descricao']); ?></td>
                  <td>R$ <?php echo number_format($servico['preco'], 2, ',', '.'); ?></td>
                  <td><?php echo $servico['duracao_em_minutos']; ?> min</td>
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                      <a href="/servicos/editar?id=<?php echo $servico['id_servico']; ?>" class="btn btn-outline-primary btn-sm" aria-label="Editar serviço">
                        <i class="ph ph-pencil"></i>
                      </a>

                      <button class="btn btn-outline-danger btn-sm">
                        <i class="ph ph-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>

            <nav aria-label="Paginação de serviços">
              <ul class="pagination justify-content-center mt-3">
                <li class="page-item <?php echo $paginaAtual <= 1 ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php echo $paginaAtual - 1; ?>">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>

                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?php echo $i === $paginaAtual ? 'active' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?php echo $paginaAtual >= $totalPaginas ? 'disabled' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php echo $paginaAtual + 1; ?>">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
