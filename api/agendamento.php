<?php
    header('Content-Type: application/json, charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");

    require_once __DIR__ . '/conexao.php';

try {
    $sql = "SELECT * FROM agendamentos ORDER BY id_agendamento DESC";
    $consultaAgendamentos = $pdo->prepare($sql);
    $consultaAgendamentos->execute();

    $dadosAgendamentos = $consultaAgendamentos->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($dadosAgendamentos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Erro ao executar consulta no banco de dados"
    ]);
}
?>