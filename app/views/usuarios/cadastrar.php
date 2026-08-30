<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/app.php';
require_once __DIR__ . '/../../../includes/analytics.php';

$erros = [];
$valores = [
    'nome' => '',
    'cpf' => '',
    'datanascimento' => '',
    'telefone' => '',
    'email' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valores['nome'] = trim($_POST['nome'] ?? '');
    $valores['cpf'] = trim($_POST['cpf'] ?? '');
    $valores['datanascimento'] = trim($_POST['datanascimento'] ?? '');
    $valores['telefone'] = trim($_POST['telefone'] ?? '');
    $valores['email'] = trim($_POST['email'] ?? '');
    $senha = (string) ($_POST['senha'] ?? '');
    $senha2 = (string) ($_POST['senha2'] ?? '');

    $dataNascimento = converterDataParaBanco($valores['datanascimento']);

    if ($valores['nome'] === '') {
        $erros[] = 'Informe o nome.';
    } elseif (mb_strlen($valores['nome']) > 40) {
        $erros[] = 'O nome deve ter no máximo 40 caracteres.';
    }

    if ($valores['cpf'] === '') {
        $erros[] = 'Informe o CPF.';
    } elseif (existeUsuarioComCpf($pdo, $valores['cpf'])) {
        $erros[] = 'Já existe uma conta com esse CPF.';
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
    } elseif (existeUsuarioComEmail($pdo, $valores['email'])) {
        $erros[] = 'Já existe uma conta com esse e-mail.';
    }

    if (mb_strlen($senha) < 6) {
        $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $senha2) {
        $erros[] = 'As senhas digitadas não são iguais.';
    }

    if (empty($erros)) {
        try {
            $sql = <<<SQL
            INSERT INTO usuarios (nome, cpf, email, hash_senha, telefone, tipo_perfil, data_nasc, created_at, updated_at)
            VALUES (:nome, :cpf, :email, :hash_senha, :telefone, 'cliente', :data_nasc, NOW(), NOW())
            SQL;

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':nome', $valores['nome']);
            $stmt->bindValue(':cpf', $valores['cpf']);
            $stmt->bindValue(':email', $valores['email']);
            $stmt->bindValue(':hash_senha', password_hash($senha, PASSWORD_DEFAULT));
            $stmt->bindValue(':telefone', $valores['telefone']);
            $stmt->bindValue(':data_nasc', $dataNascimento);
            $stmt->execute();

            header('Location: /login?cadastrado=1');
            exit;
        } catch (PDOException $e) {
            $erros[] = 'Não foi possível criar a conta, tente novamente.';
        }
    }
}
?>

<?php
    $tituloPagina = 'Cadastrar Usuário';
    $usarFormularios = true;
?>
    <?php
        $header = __DIR__ . '/../../../includes/header.php';
        if (file_exists($header)) {
            include $header;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <main class="container-marca secao">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="text-center mb-4">
                    <p class="eyebrow cor-marca justify-content-center">Nova conta</p>
                    <h1 class="display-marca" style="font-size: clamp(1.9rem, 4vw, 2.4rem);">Cadastrar usuário</h1>
                    <p class="texto-lead">Preencha os dados abaixo. A validação ocorre antes do envio.</p>
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

                <div class="superficie superficie--elevada p-4 p-md-5">
                    <form method="POST" action="/usuarios/cadastrar" data-parsley-validate="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="nome"
                                    name="nome"
                                    value="<?php echo htmlspecialchars($valores['nome']); ?>"
                                    maxlength="40"
                                    placeholder="Digite o nome completo"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="cpf" class="form-label">CPF</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="cpf"
                                    name="cpf"
                                    value="<?php echo htmlspecialchars($valores['cpf']); ?>"
                                    placeholder="000.000.000-00"
                                    inputmode="numeric"
                                    data-inputmask="'mask': '999.999.999-99'"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="datanascimento" class="form-label">Data de nascimento</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="datanascimento"
                                    name="datanascimento"
                                    value="<?php echo htmlspecialchars($valores['datanascimento']); ?>"
                                    placeholder="dd/mm/aaaa"
                                    inputmode="numeric"
                                    data-inputmask="'mask': '99/99/9999'"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="telefone"
                                    name="telefone"
                                    value="<?php echo htmlspecialchars($valores['telefone']); ?>"
                                    placeholder="(44) 99999-9999"
                                    inputmode="tel"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input
                                    type="email"
                                    class="form-control form-control-lg"
                                    id="email"
                                    name="email"
                                    value="<?php echo htmlspecialchars($valores['email']); ?>"
                                    maxlength="40"
                                    placeholder="seu@email.com"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                    data-parsley-type-message="Preencha com um e-mail válido"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="senha" class="form-label">Senha</label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="senha"
                                    name="senha"
                                    placeholder="Crie uma senha"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                    data-parsley-minlength="6"
                                    data-parsley-minlength-message="Digite pelo menos 6 caracteres"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="senha2" class="form-label">Confirmar senha</label>
                                <input
                                    type="password"
                                    class="form-control form-control-lg"
                                    id="senha2"
                                    name="senha2"
                                    placeholder="Confirme a senha"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                    data-parsley-equalto="#senha"
                                    data-parsley-equalto-message="As senhas digitadas não são iguais"
                                >
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-4">
                            <p class="mb-0" style="color: var(--texto-secundario);">
                                Já tem conta?
                                <a href="/login" class="cor-marca fw-semibold text-decoration-none">Entrar</a>
                            </p>
                            <button type="submit" class="btn-marca">
                                Cadastrar <i class="ph ph-user-plus"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php
        $footer = __DIR__ . '/../../../includes/footer.php';
        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <?php include __DIR__ . '/../../../includes/form-validacao-foot.php'; ?>
    <script>
        $(document).ready(function () {
            $('#cpf').inputmask('999.999.999-99');
            $('#datanascimento').inputmask('99/99/9999');
            $('#telefone').mask('(00) 00000-0000');
        });
    </script>
</body>
</html>
