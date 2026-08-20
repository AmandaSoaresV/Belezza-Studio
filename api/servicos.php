<?php
    header('Content-Type: application/json, charset=utf-8');
    require_once __DIR__ . '/conexao.php';

    $sql = "SELECT * FROM servicos ORDER BY id_servico DESC";
    $consultaServicos = $pdo->prepare($sql);
    $consultaServicos->execute();

    $dadosServicos = $consultaServicos->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($dadosServicos);
?>