<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/../includes/analytics.php';

$statusPermitidos = ['pendente', 'confirmado', 'cancelado', 'concluido'];

$limite = isset($_GET['limite']) ? (int) $_GET['limite'] : 10;
$limite = max(1, min(100, $limite));

$offset = isset($_GET['offset']) ? (int) $_GET['offset'] : 0;
$offset = max(0, $offset);

$status = isset($_GET['status']) && in_array($_GET['status'], $statusPermitidos, true)
    ? $_GET['status']
    : null;

try {
    echo json_encode([
        'indicadores' => obterIndicadoresDashboard($pdo),
        'ranking' => obterRankingServicos($pdo),
        'agendamentos' => listarAgendamentosDashboard($pdo, $limite, $offset, $status),
        'paginacao' => [
            'total' => contarAgendamentosDashboard($pdo, $status),
            'limite' => $limite,
            'offset' => $offset,
            'status' => $status,
        ],
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao executar consulta no banco de dados']);
}
