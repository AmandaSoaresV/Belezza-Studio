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

                <div class="superficie superficie--elevada p-4 p-md-5">
                    <form method="POST" action="" data-parsley-validate="" data-form-demo="1">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nome" class="form-label">Nome</label>
                                <input
                                    type="text"
                                    class="form-control form-control-lg"
                                    id="nome"
                                    name="nome"
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
                                    maxlength="40"
                                    placeholder="seu@email.com"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                    data-parsley-type-message="Preencha com um e-mail válido"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="tipo_perfil" class="form-label">Perfil</label>
                                <select
                                    class="form-select form-select-lg"
                                    id="tipo_perfil"
                                    name="tipo_perfil"
                                    required
                                    data-parsley-required-message="Preencha este campo"
                                >
                                    <option value="" selected disabled>Selecione</option>
                                    <option value="cliente">Cliente</option>
                                    <option value="admin">Administrador</option>
                                </select>
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
