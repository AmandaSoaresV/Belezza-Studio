<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salão</title>
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
</head>

<?php
    require_once __DIR__ . '/../../../config/conexao.php';

   $sqlServicos =<<<CONSULTA
     SELECT * FROM servicos
   CONSULTA;
    $resultado = $conn->query($sqlServicos);
?>

  <body>
    <?php
    $header = __DIR__ . '/../includes/header.php'; 
     if (file_exists($header))
         { include $header; } 
     else { include __DIR__ . '/../erro/erro.php'; } ?>

    <section class="banner-principal">
   <div class="filtro-banner"></div>
        <div class="conteudo-banner">
            <p class="subtitulo">
                ✦ BEM-VINDO AO BELEZZA STUDIO ✦
            </p>

         <h1>
                Agende seu horário de forma rápida e fácil
            </h1>

       <p class="descricao">
                Cuidado premium com agendamento simples.
                Sem filas, sem espera.
            </p>
            <a href="/agendamento" class="botao-agendar-agora">
                Agendar Agora
            </a>
        </div>
    </section>

    <main class="conteudo-principal-index">
        <section id="servicos" class="servicos-destaque">
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

        <?php while ($servico = $resultado->fetch_assoc()): ?>

        <div class="col-12 col-md-6 col-lg-3 coluna-card-servico">
            <div class="card-servico">
                    <i class="ph ph-flower-lotus icone-servico"></i>
                <h3 class="nome-servico">
                    <?php echo $servico['nome']; ?>
                </h3>
                <p class="descricao-servico">
                    <?php echo $servico['descricao']; ?>
                </p>
                <p class="preco-servico">
                    <?php echo 'R$ ' . number_format($servico['preco'], 2, ',', '.'); ?>
                </p>
                <a href="/agendamento" class="botao-agendar-servico">
                    Agendar
                </a>
            </div>
        </div>

        <?php endwhile; ?>

    </div>
</div>
        </section>
        <section class="cuidados">
            <div class="container-cuidados">
                <h3 class="titulo-cuidados">Pronto para se cuidar?</h3>
                <p class="paragrafo-cuidados">Reserve seu horário agora mesmo rápido, fácil e sem complicação.</p>
             <a href="/agendamento" class="botao-agendar-agora " style="background-color: var(--primary-500); color: var(--white);">
                Agendar Agora
            </a>
            </div>
        </section>
    </main>
    
   <?php
        $footer = __DIR__ . '/../includes/footer.php';

        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>