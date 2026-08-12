<?php require_once __DIR__ . '/../../../config/conexao.php';

$sqlServicos = <<<CONSULTA
SELECT * FROM servicos
CONSULTA;

try {
    $resultado = $pdo->query($sqlServicos);
    $servicos = $resultado->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("erro na consulta: " . $e->getMessage());
}

function validarServicos(array $servicos)
{
    if (empty($servicos)) {
        return false;
    }

    foreach ($servicos as $servico) {
        if ($servico['preco'] <= 0) {
            return false;
        }
    }

    return true;
}

function calcularDesconto(float $preco, float $percentual): float
{
    return $preco - ($preco * ($percentual / 100));
}

function filtrarPremium(array $servicos): array
{
    $premium = [];

    foreach ($servicos as $servico) {
        if ($servico['preco'] > 300) {
            $premium[] = $servico;
        }
    }

    return $premium;
}

if (!validarServicos($servicos)) {
    die("Serviços inválidos.");
}

$servicosPremium = filtrarPremium($servicos);
?>

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

  <body>
    <?php
    $header = __DIR__ . '/../layouts/header.php'; 
     if (file_exists($header))
         { include $header; } 
     else { include __DIR__ . '/../erro/erro.php'; } ?>

    <section class="banner-principal">
        <div class="filtro-banner"></div>
        <div class="container-marca banner-grade">
            <div class="conteudo-banner">
                <p class="eyebrow banner-eyebrow">
                    <i class="ph ph-sparkle"></i> ✦ BEM-VINDO AO BELEZZA STUDIO ✦
                </p>

                <h1 class="display-marca text-white">
                    Cuidado premium, <span class="banner-destaque">sem espera.</span>
                </h1>

                <p class="texto-lead banner-descricao">
                     Cuidado premium com agendamento simples.
                     <br>
                Sem filas, sem espera.
                </p>

                <div class="d-flex flex-wrap gap-3">
                    <a href="/agendamento" class="btn-marca btn-marca--claro">
                        Agendar agora <i class="ph ph-arrow-right"></i>
                    </a>
                    <a href="#servicos" class="btn-marca" style="background-color: rgba(255,255,255,0.14); border-color: rgba(255,255,255,0.4);">
                        Ver serviços
                    </a>
                </div>
            </div>
        </div>
    </section>

    <main class="conteudo-principal-index">
        <section id="servicos" class="secao servicos-destaque">
            <div class="container-marca">
                <div class="cabecalho-servicos">
                    <p class="eyebrow cor-marca">✦ O que oferecemos</p>
                    <h2 class="titulo-servicos">Nossos Serviços</h2>
                    <p class="texto-lead">
                        Experiências pensadas para cada detalhe do seu bem-estar.</p>
                </div>

                <div class="premium-info">
                    <div class="icone-tile icone-tile--marca">
                        <i class="ph ph-crown"></i>
                    </div>

                    <div class="premium-info-conteudo">
                        <h4>Experiências Premium</h4>
                        <p>
                            Descubra nossos <?= count($servicosPremium) ?> serviços premium com desconto exclusivo para agendamentos online.
                        </p>
                    </div>
                </div>

                <div class="row g-4 linha-servicos">

                    <?php foreach ($servicos as $servico): ?>

                    <div class="col-12 col-md-6 col-lg-3 coluna-card-servico">
                        <div class="card-servico">

                            <?php if ($servico['preco'] > 300): ?>
                            <span class="badge-servico-premium">
                                <i class="ph ph-crown"></i> Premium
                            </span>
                            <?php endif; ?>

                            <i class="ph ph-flower-lotus icone-servico"></i>
                            <h3 class="nome-servico">
                                <?php echo $servico['nome']; ?>
                            </h3>
                            <p class="descricao-servico">
                                <?php echo $servico['descricao']; ?>
                            </p>

                            <?php if ($servico['preco'] > 300): ?>

                                <?php $precoPromocional = calcularDesconto($servico['preco'], 10); ?>

                                <p class="texto-promocao">
                                    <i class="ph ph-fire"></i> 10% OFF
                                </p>

                                <p class="preco-antigo">
                                    R$ <?= number_format($servico['preco'], 2, ',', '.'); ?>
                                </p>

                                <p class="preco-promocional">
                                    R$ <?= number_format($precoPromocional, 2, ',', '.'); ?>
                                </p>

                            <?php else: ?>

                                <p class="preco-servico">
                                    <?php echo 'R$ ' . number_format($servico['preco'], 2, ',', '.'); ?>
                                </p>

                            <?php endif; ?>

                            <a href="/agendamento" class="botao-agendar-servico">
                                Agendar <i class="ph ph-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </section>

        <section class="cuidados">
            <div class="container-cuidados">
                <p class="eyebrow" style="color: var(--pink-300);">✦ Chegou a sua vez</p>
                <h3 class="titulo-cuidados">Pronto para se cuidar?</h3>
                <p class="paragrafo-cuidados">Reserve seu horário agora mesmo rápido, fácil e sem complicação.</p>
                <a href="/agendamento" class="btn-marca btn-marca--claro">
                    Agendar Agora <i class="ph ph-arrow-right"></i>
                </a>
            </div>
        </section>
    </main>
    
   <?php
        $footer = __DIR__ . '/../layouts/footer.php';

        if (file_exists($footer)) {
            include $footer;
        } else {
            include __DIR__ . '/../erro/erro.php';
        }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>