<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/../includes/analytics.php';

try {
    echo json_encode(obterIndicadoresDashboard($pdo));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao executar consulta no banco de dados']);
}
