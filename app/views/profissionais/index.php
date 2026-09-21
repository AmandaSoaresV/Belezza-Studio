<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/profissionais.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idParaExcluir = isset($_POST['id_profissional']) ? (int) $_POST['id_profissional'] : 0;

    if ($idParaExcluir < 1) {
        header('Location: /profissionais?naoencontrado=1');
        exit;
    }

    try {
        $profissionalParaExcluir = obterProfissional($pdo, $idParaExcluir);

        if ($profissionalParaExcluir === null) {
            header('Location: /profissionais?naoencontrado=1');
            exit;
        }

        $agendamentosVinculados = contarAgendamentosDoProfissional($pdo, $idParaExcluir);

        if ($agendamentosVinculados > 0) {
            header('Location: /profissionais?vinculado=' . $agendamentosVinculados);
            exit;
        }

        excluirProfissional($pdo, $idParaExcluir);
        header('Location: /profissionais?excluido=1');
        exit;
    } catch (PDOException $e) {
        header('Location: /profissionais?erroexclusao=1');
        exit;
    }
}

$porPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

$mensagens = mensagensDeRetorno($_GET, [
    'criado' => ['tipo' => 'success', 'texto' => 'Profissional cadastrado com sucesso.'],
    'atualizado' => ['tipo' => 'success', 'texto' => 'Profissional atualizado com sucesso.'],
    'excluido' => ['tipo' => 'success', 'texto' => 'Profissional excluído com sucesso.'],
    'naoencontrado' => ['tipo' => 'warning', 'texto' => 'Profissional não encontrado.'],
    'vinculado' => ['tipo' => 'warning', 'texto' => 'Não é possível excluir: o profissional tem {valor} agendamento{plural} vinculado{plural}.'],
    'erroexclusao' => ['tipo' => 'danger', 'texto' => 'Não foi possível excluir o profissional, tente novamente.'],
]);

$profissionais = [];
$totalRegistros = 0;
$totalPaginas = 1;

try {
    $totalRegistros = contarProfissionais($pdo);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

    if ($paginaAtual > $totalPaginas) {
        $paginaAtual = $totalPaginas;
        $offset = ($paginaAtual - 1) * $porPagina;
    }

    $profissionais = listarProfissionaisPaginado($pdo, $porPagina, $offset);
} catch (PDOException $e) {
    $mensagens[] = [
        'tipo' => 'warning',
        'texto' => 'Não foi possível carregar os profissionais, verifique se o banco foi importado.',
    ];
}
?>

<?php
$tituloPagina = 'Profissionais';
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'profissionais'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
      <div>
        <h1 class="admin-topbar-titulo">Profissionais</h1>
        <p class="admin-topbar-subtitulo">
          <?php echo $totalRegistros; ?> profissional<?php echo $totalRegistros === 1 ? '' : 'is'; ?> cadastrado<?php echo $totalRegistros === 1 ? '' : 's'; ?>
        </p>
      </div>

      <a href="/profissionais/cadastrar" class="btn-marca btn-marca--pequeno">
        <i class="ph ph-plus"></i> Novo Profissional
      </a>
    </header>

    <div class="admin-container">
      <?php include __DIR__ . '/../../../includes/alertas.php'; ?>

      <div class="superficie">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover align-middle tabela-marca">
              <thead>
                <tr>
                  <th>Nome</th>
                  <th>Especialidade</th>
                  <th>Serviços que atende</th>
                  <th>Agendamentos</th>
                  <th class="text-center">Ações</th>
                </tr>
              </thead>

              <tbody>
                <?php if (empty($profissionais)): ?>
                <tr>
                  <td colspan="5" class="text-center py-4">Nenhum profissional cadastrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($profissionais as $profissional): ?>
                <tr>
                  <td><?php echo htmlspecialchars($profissional['nome']); ?></td>
                  <td class="text-body-secondary"><?php echo htmlspecialchars($profissional['especialidade']); ?></td>
                  <td>
                    <?php if ($profissional['total_servicos'] === 0): ?>
                    <span class="text-body-secondary" title="Sem serviços vinculados, ele não aparece no agendamento">
                      <i class="ph ph-warning"></i> nenhum
                    </span>
                    <?php else: ?>
                    <?php echo $profissional['total_servicos']; ?> serviço<?php echo $profissional['total_servicos'] === 1 ? '' : 's'; ?>
                    <?php endif; ?>
                  </td>
                  <td><?php echo $profissional['total_agendamentos']; ?></td>
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                      <a href="/profissionais/editar?id=<?php echo $profissional['id_profissional']; ?>" class="btn btn-outline-primary btn-sm" aria-label="Editar profissional">
                        <i class="ph ph-pencil"></i>
                      </a>

                      <?php if ($profissional['total_agendamentos'] > 0): ?>
                      <button
                        type="button"
                        class="btn btn-outline-danger btn-sm btn-excluir-bloqueado"
                        disabled
                        aria-label="Excluir profissional"
                        title="Não é possível excluir: <?php echo $profissional['total_agendamentos']; ?> agendamento<?php echo $profissional['total_agendamentos'] === 1 ? '' : 's'; ?> vinculado<?php echo $profissional['total_agendamentos'] === 1 ? '' : 's'; ?>"
                      >
                        <i class="ph ph-trash"></i>
                      </button>
                      <?php else: ?>
                      <form
                        method="POST"
                        action="/profissionais"
                        class="d-inline"
                        data-confirmar-exclusao="Excluir <?php echo htmlspecialchars($profissional['nome']); ?>?"
                        data-confirmar-detalhe="Essa ação não pode ser desfeita."
                      >
                        <input type="hidden" name="id_profissional" value="<?php echo $profissional['id_profissional']; ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Excluir profissional">
                          <i class="ph ph-trash"></i>
                        </button>
                      </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>

            <nav aria-label="Paginação de profissionais">
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
