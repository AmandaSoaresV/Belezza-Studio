<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';
require_once __DIR__ . '/../../../includes/sessao.php';

$idLogado = usuarioLogado()['id_usuario'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idParaExcluir = isset($_POST['id_usuario']) ? (int) $_POST['id_usuario'] : 0;

    if ($idParaExcluir < 1) {
        header('Location: /usuarios?naoencontrado=1');
        exit;
    }

    if ($idParaExcluir === $idLogado) {
        header('Location: /usuarios?propriaconta=1');
        exit;
    }

    try {
        $usuarioParaExcluir = obterUsuario($pdo, $idParaExcluir);

        if ($usuarioParaExcluir === null) {
            header('Location: /usuarios?naoencontrado=1');
            exit;
        }

        $agendamentosVinculados = contarAgendamentosDoUsuario($pdo, $idParaExcluir);

        if ($agendamentosVinculados > 0) {
            header('Location: /usuarios?vinculado=' . $agendamentosVinculados);
            exit;
        }

        excluirUsuario($pdo, $idParaExcluir);
        header('Location: /usuarios?excluido=1');
        exit;
    } catch (PDOException $e) {
        header('Location: /usuarios?erroexclusao=1');
        exit;
    }
}

$porPagina = 10;
$paginaAtual = isset($_GET['pagina']) ? max(1, (int) $_GET['pagina']) : 1;
$offset = ($paginaAtual - 1) * $porPagina;

$mensagens = mensagensDeRetorno($_GET, [
    'salvo' => ['tipo' => 'success', 'texto' => 'Usuário cadastrado com sucesso.'],
    'atualizado' => ['tipo' => 'success', 'texto' => 'Usuário atualizado com sucesso.'],
    'excluido' => ['tipo' => 'success', 'texto' => 'Usuário excluído com sucesso.'],
    'naoencontrado' => ['tipo' => 'warning', 'texto' => 'Usuário não encontrado.'],
    'vinculado' => ['tipo' => 'warning', 'texto' => 'Não é possível excluir: o usuário tem {valor} agendamento{plural} vinculado{plural}.'],
    'propriaconta' => ['tipo' => 'warning', 'texto' => 'Você não pode excluir a própria conta.'],
    'erroexclusao' => ['tipo' => 'danger', 'texto' => 'Não foi possível excluir o usuário, tente novamente.'],
]);

$usuarios = [];
$totalRegistros = 0;
$totalPaginas = 1;

try {
    $totalRegistros = contarUsuarios($pdo);
    $totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

    if ($paginaAtual > $totalPaginas) {
        $paginaAtual = $totalPaginas;
        $offset = ($paginaAtual - 1) * $porPagina;
    }

    $usuarios = listarUsuarios($pdo, $porPagina, $offset);
} catch (PDOException $e) {
    $mensagens[] = [
        'tipo' => 'warning',
        'texto' => 'Não foi possível carregar os usuários, verifique se o banco foi importado.',
    ];
}
?>

<?php
$tituloPagina = 'Usuários';
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'usuarios'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
      <div>
        <h1 class="admin-topbar-titulo">Usuários</h1>
        <p class="admin-topbar-subtitulo">
          <?php echo $totalRegistros; ?> usuário<?php echo $totalRegistros === 1 ? '' : 's'; ?> cadastrado<?php echo $totalRegistros === 1 ? '' : 's'; ?>
        </p>
      </div>

      <a href="/usuarios/cadastrar" class="btn-marca btn-marca--pequeno">
        <i class="ph ph-plus"></i> Novo Usuário
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
                  <th>E-mail</th>
                  <th>Telefone</th>
                  <th>Perfil</th>
                  <th class="text-center">Agendamentos</th>
                  <th class="text-center">Ações</th>
                </tr>
              </thead>

              <tbody>
                <?php if (empty($usuarios)): ?>
                <tr>
                  <td colspan="6" class="text-center py-4">Nenhum usuário cadastrado.</td>
                </tr>
                <?php else: ?>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                  <td><?php echo htmlspecialchars($usuario['nome']); ?></td>
                  <td class="text-body-secondary"><?php echo htmlspecialchars($usuario['email']); ?></td>
                  <td><?php echo htmlspecialchars($usuario['telefone']); ?></td>
                  <td>
                    <span class="badge <?php echo $usuario['tipo_perfil'] === 'admin' ? 'text-bg-primary' : 'text-bg-secondary'; ?>">
                      <?php echo $usuario['tipo_perfil'] === 'admin' ? 'Admin' : 'Cliente'; ?>
                    </span>
                  </td>
                  <td class="text-center"><?php echo $usuario['total_agendamentos']; ?></td>
                  <td>
                    <div class="d-flex justify-content-center gap-2">
                      <a href="/usuarios/editar?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-outline-primary btn-sm" aria-label="Editar usuário">
                        <i class="ph ph-pencil"></i>
                      </a>

                      <?php if ($usuario['id_usuario'] === $idLogado): ?>
                      <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        disabled
                        aria-label="Excluir usuário"
                        title="Não é possível excluir a própria conta"
                      >
                        <i class="ph ph-trash"></i>
                      </button>
                      <?php elseif ($usuario['total_agendamentos'] > 0): ?>
                      <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        disabled
                        aria-label="Excluir usuário"
                        title="Não é possível excluir: <?php echo $usuario['total_agendamentos']; ?> agendamento<?php echo $usuario['total_agendamentos'] === 1 ? '' : 's'; ?> vinculado<?php echo $usuario['total_agendamentos'] === 1 ? '' : 's'; ?>"
                      >
                        <i class="ph ph-trash"></i>
                      </button>
                      <?php else: ?>
                      <form
                        method="POST"
                        action="/usuarios"
                        class="d-inline"
                        data-confirmar-exclusao="Excluir <?php echo htmlspecialchars($usuario['nome']); ?>?"
                        data-confirmar-detalhe="Essa ação não pode ser desfeita."
                      >
                        <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
                        <button type="submit" class="btn btn-outline-danger btn-sm" aria-label="Excluir usuário">
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

            <nav aria-label="Paginação de usuários">
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
