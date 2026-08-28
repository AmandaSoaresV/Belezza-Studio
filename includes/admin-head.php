<?php

$tituloPagina    = $tituloPagina ?? 'Painel';
$classeBody      = $classeBody ?? 'body-dashboard';
$cssPagina       = $cssPagina ?? [];
$usarFormularios = $usarFormularios ?? false;
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina; ?> — Belezza Studio</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">

    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
    
<?php foreach ($cssPagina as $arquivoCss): ?>
    <link rel="stylesheet" href="/assets/css/<?php echo $arquivoCss; ?>">
<?php endforeach; ?>
<?php if ($usarFormularios): ?>
    <?php include __DIR__ . '/form-validacao-head.php'; ?>
<?php endif; ?>
</head>

<body class="<?php echo $classeBody; ?>">
