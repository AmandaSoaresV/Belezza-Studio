<?php
    header('Content-Type: application/json, charset=utf-8');
    require_once __DIR__ . '/conexao.php';

    $sql = "SELECT * FROM agendamentos ORDER BY id_agendamento DESC";
    $consultaAgendamentos = $pdo->prepare($sql);
    $consultaAgendamentos->execute();

    $dadosAgendamentos = $consultaAgendamentos->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($dadosAgendamentos);
?>
