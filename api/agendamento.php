<?php
    header('Content-Type: application/json; charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");

    require_once __DIR__ . '/conexao.php';

try {
    $sql = "SELECT id_agendamento, id_cliente, id_profissional, id_servico,
                   data_hora_servico, status, observacao
            FROM agendamentos
            ORDER BY id_agendamento DESC";
    $consultaAgendamentos = $pdo->prepare($sql);
    $consultaAgendamentos->execute();

    $dadosAgendamentos = [];

    foreach ($consultaAgendamentos->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $dadosAgendamentos[] = [
            'id_agendamento' => (int) $linha['id_agendamento'],
            'id_cliente' => (int) $linha['id_cliente'],
            'id_profissional' => (int) $linha['id_profissional'],
            'id_servico' => (int) $linha['id_servico'],
            'data_hora_servico' => (string) $linha['data_hora_servico'],
            'status' => (string) $linha['status'],
            'observacao' => (string) ($linha['observacao'] ?? ''),
        ];
    }

    echo json_encode($dadosAgendamentos, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "error" => "Erro ao executar consulta no banco de dados"
    ]);
}
?>