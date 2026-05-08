<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão</title>
    <link rel="stylesheet" href="/public/assets/css/global.css">
    <link rel="stylesheet" href="/app/views/index/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
</head>

<body>

    <section class="banner-principal">
        <div class="filtro-banner"></div>
        <div class="conteudo-banner">
            <p class="subtitulo">
                ✦ BEM-VINDO AO BELLEZZA STUDIO ✦
            </p>

            <h1>
                Agende seu horário de forma rápida e fácil
            </h1>

            <p class="descricao">
                Cuidado premium com agendamento simples.
                Sem filas, sem espera.
            </p>
            <a href="#" class="botao-agendar-agora">
                Agendar Agora
            </a>
        </div>
    </section>

    <main class="conteudo-principal-index">
        <section class="servicos-destaque">
            <div class="cabecalho-servicos">
                <h2 class="titulo-servicos">
                    Nossos Serviços
                </h2>
                <p class="descricao-servicos">
                    Experiências pensadas para cada detalhe do seu bem-estar.
                </p>
            </div>

            <div class="container">
                <div class="row g-4 linha-servicos">
                    <div class="col-12 col-md-6 col-lg-3 coluna-card-servico">
                        <div class="card-servico-famoso">
                            <i class="ph-fill ph-star"></i>
                             <span class="badge-famoso">
                             MAIS PEDIDO
                            </span>
                            <i class="ph ph-scissors icone-servico-famoso"></i>
                            <h3 class="nome-servico-famoso">
                                Corte de Cabelo
                            </h3>
                            <p class="descricao-servico-famoso">
                                Corte moderno com finalização profissional e lavagem inclusa.
                            </p>
                            <p class="preco-servico-famoso">
                                R$ 65,00
                            </p>
                            <a href="#" class="botao-agendar-servico-famoso">
                                Agendar
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 coluna-card-servico">
                        <div class="card-servico">
                            <i class="ph ph-paint-brush icone-servico"></i>
                            <h3 class="nome-servico">
                                Manicure
                            </h3>
                            <p class="descricao-servico">
                                Tratamento completo para suas unhas.
                            </p>
                            <p class="preco-servico">
                                R$ 45,00
                            </p>
                            <a href="#" class="botao-agendar-servico">
                                Agendar
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 coluna-card-servico">
                        <div class="card-servico">
                            <i class="ph ph-footprints icone-servico"></i>
                            <h3 class="nome-servico">
                                Pedicure
                            </h3>
                            <p class="descricao-servico">
                                Cuidado especial para seus pés.
                            </p>
                            <p class="preco-servico">
                                R$ 50,00
                            </p>
                            <a href="#" class="botao-agendar-servico">
                                Agendar
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3 coluna-card-servico">
                        <div class="card-servico">
                            <i class="ph ph-hair-dryer icone-servico"></i>
                            <h3 class="nome-servico">
                                Lavagem de Cabelo
                            </h3>
                            <p class="descricao-servico">
                                Lavagem profunda com produtos de alta qualidade.
                            </p>
                            <p class="preco-servico">
                                R$ 80,00
                            </p>
                            <a href="#" class="botao-agendar-servico">
                                Agendar
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section class="cuidados">
            <div class="container-cuidados">
                <h3 class="titulo-cuidados">Pronto para se cuidar?</h3>
                <p class="paragrafo-cuidados">Reserve seu horário agora mesmo rápido, fácil e sem complicação.</p>
             <a href="#" class="botao-agendar-agora " style="background-color: var(--primary-500); color: var(--white);">
                Agendar Agora
            </a>
            </div>
        </section>

    </main>
<?php include '../componentes/footer.php'; ?>

</body>
</html>