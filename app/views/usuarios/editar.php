<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';
require_once __DIR__ . '/../../../includes/sessao.php';

$idUsuario = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($idUsuario < 1) {
    header('Location: /usuarios?naoencontrado=1');
    exit;
}

try {
    $usuario = obterUsuario($pdo, $idUsuario);
} catch (PDOException $e) {
    $usuario = null;
}

if ($usuario === null) {
    header('Location: /usuarios?naoencontrado=1');
    exit;
}

$ehPropriaConta = usuarioLogado()['id_usuario'] === $idUsuario;

$erros = [];
$valores = [
    'nome' => $usuario['nome'],
    'cpf' => $usuario['cpf'],
    'datanascimento' => date('d/m/Y', strtotime($usuario['data_nasc'])),
    'telefone' => $usuario['telefone'],
    'email' => $usuario['email'],
    'tipo_perfil' => $usuario['tipo_perfil'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores['nome'] = trim($_POST['nome'] ?? '');
    $valores['cpf'] = trim($_POST['cpf'] ?? '');
    $valores['datanascimento'] = trim($_POST['datanascimento'] ?? '');
    $valores['telefone'] = trim($_POST['telefone'] ?? '');
    $valores['email'] = trim($_POST['email'] ?? '');
    $valores['tipo_perfil'] = trim($_POST['tipo_perfil'] ?? '');
    $senha = (string) ($_POST['senha'] ?? '');

    $dataNascimento = converterDataParaBanco($valores['datanascimento']);

    if ($valores['nome'] === '') {
        $erros[] = 'Informe o nome.';
    } elseif (mb_strlen($valores['nome']) > 40) {
        $erros[] = 'O nome deve ter no máximo 40 caracteres.';
    }

    if ($valores['cpf'] === '') {
        $erros[] = 'Informe o CPF.';
    } elseif (existeUsuarioComCpf($pdo, $valores['cpf'], $idUsuario)) {
        $erros[] = 'Já existe outra conta com esse CPF.';
    }

    if ($dataNascimento === null) {
        $erros[] = 'Informe uma data de nascimento válida.';
    } elseif ($dataNascimento > date('Y-m-d')) {
        $erros[] = 'A data de nascimento não pode ser no futuro.';
    }

    if ($valores['telefone'] === '') {
        $erros[] = 'Informe o telefone.';
    }

    if ($valores['email'] === '') {
        $erros[] = 'Informe o e-mail.';
    } elseif (!filter_var($valores['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    } elseif (existeUsuarioComEmail($pdo, $valores['email'], $idUsuario)) {
        $erros[] = 'Já existe outra conta com esse e-mail.';
    }

    if (!in_array($valores['tipo_perfil'], ['admin', 'cliente'], true)) {
        $erros[] = 'Selecione um perfil válido.';
    } elseif ($ehPropriaConta && $valores['tipo_perfil'] !== 'admin') {
        $erros[] = 'Você não pode remover o seu próprio acesso de administrador.';
    }

    if ($senha !== '' && mb_strlen($senha) < 6) {
        $erros[] = 'A nova senha deve ter pelo menos 6 caracteres.';
    }

    if (empty($erros)) {
        try {
            $sql = <<<SQL
            UPDATE usuarios
            SET nome = :nome,
                cpf = :cpf,
                email = :email,
                telefone = :telefone,
                tipo_perfil = :tipo_perfil,
                data_nasc = :data_nasc,
                updated_at = NOW()
            WHERE id_usuario = :id
            SQL;

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $valores['nome']);
            $stmt->bindValue(':cpf', $valores['cpf']);
            $stmt->bindValue(':email', $valores['email']);
            $stmt->bindValue(':telefone', $valores['telefone']);
            $stmt->bindValue(':tipo_perfil', $valores['tipo_perfil']);
            $stmt->bindValue(':data_nasc', $dataNascimento);
            $stmt->bindValue(':id', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            if ($senha !== '') {
                $stmtSenha = $pdo->prepare('UPDATE usuarios SET hash_senha = ? WHERE id_usuario = ?');
                $stmtSenha->execute([password_hash($senha, PASSWORD_DEFAULT), $idUsuario]);
            }

            header('Location: /usuarios?atualizado=1');
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível salvar as alterações, tente novamente.';
        }
    }
}
?>

<?php
$tituloPagina = 'Editar Usuário';
$usarFormularios = true;
include __DIR__ . '/../../../includes/admin-head.php';
?>
    <?php $paginaAdminAtiva = 'usuarios'; include __DIR__ . '/../../../includes/sidebar.php'; ?>

    <header class="admin-topbar">
        <div>
            <h1 class="admin-topbar-titulo">Editar Usuário</h1>
            <p class="admin-topbar-subtitulo">Alterando <?php echo htmlspecialchars($usuario['nome']); ?></p>
        </div>
    </header>

    <div class="admin-container">
        <?php if (!empty($erros)): ?>
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                <?php foreach ($erros as $erro): ?>
                <li><?php echo htmlspecialchars($erro); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="superficie p-4 p-md-5">
            <form method="POST" action="/usuarios/editar?id=<?php echo $idUsuario; ?>" data-parsley-validate="">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nome"
                            name="nome"
                            maxlength="40"
                            value="<?php echo htmlspecialchars($valores['nome']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <input
                            type="text"
                            class="form-control"
                            id="cpf"
                            name="cpf"
                            value="<?php echo htmlspecialchars($valores['cpf']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="datanascimento" class="form-label">Data de nascimento</label>
                        <input
                            type="text"
                            class="form-control"
                            id="datanascimento"
                            name="datanascimento"
                            placeholder="dd/mm/aaaa"
                            value="<?php echo htmlspecialchars($valores['datanascimento']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input
                            type="text"
                            class="form-control"
                            id="telefone"
                            name="telefone"
                            value="<?php echo htmlspecialchars($valores['telefone']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            maxlength="40"
                            value="<?php echo htmlspecialchars($valores['email']); ?>"
                            required
                            data-parsley-required-message="Preencha este campo"
                            data-parsley-type-message="Preencha com um e-mail válido"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="tipo_perfil" class="form-label">Perfil</label>
                        <select
                            class="form-select"
                            id="tipo_perfil"
                            name="tipo_perfil"
                            <?php echo $ehPropriaConta ? 'disabled' : ''; ?>
                            required
                        >
                            <option value="cliente" <?php echo $valores['tipo_perfil'] === 'cliente' ? 'selected' : ''; ?>>Cliente</option>
                            <option value="admin" <?php echo $valores['tipo_perfil'] === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                        </select>
                        <?php if ($ehPropriaConta): ?>
                        <input type="hidden" name="tipo_perfil" value="admin">
                        <div class="form-text">Você não pode alterar o perfil da sua própria conta.</div>
                        <?php endif; ?>
                    </div>

                    <div class="col-12">
                        <label for="senha" class="form-label">Nova senha</label>
                        <input
                            type="password"
                            class="form-control"
                            id="senha"
                            name="senha"
                            placeholder="Deixe em branco para manter a senha atual"
                            data-parsley-minlength="6"
                            data-parsley-minlength-message="Digite pelo menos 6 caracteres"
                        >
                        <div class="form-text">Preencha apenas se quiser definir uma senha nova para esta pessoa.</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="/usuarios" class="btn-marca btn-marca--contorno btn-marca--pequeno"><i class="ph ph-arrow-left"></i> Voltar</a>
                    <button type="submit" class="btn-marca btn-marca--pequeno">
                        Salvar <i class="ph ph-floppy-disk"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include __DIR__ . '/../../../includes/admin-footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
</body>
</html>
