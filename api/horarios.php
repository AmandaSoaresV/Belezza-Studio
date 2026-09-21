<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/../includes/horarios.php';
require_once __DIR__ . '/../includes/profissionais.php';

$idProfissional = isset($_GET['id_profissional']) ? (int) $_GET['id_profissional'] : 0;
$idServico = isset($_GET['id_servico']) ? (int) $_GET['id_servico'] : 0;
$data = trim($_GET['data'] ?? '');
$ignorarId = isset($_GET['ignorar']) ? (int) $_GET['ignorar'] : 0;

if ($idProfissional < 1 || $idServico < 1 || dataValida($data) === null) {
    http_response_code(400);
    echo json_encode([
        'error' => 'Informe o serviço, o profissional e a data.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    if (!profissionalAtendeServico($pdo, $idProfissional, $idServico)) {
        echo json_encode([
            'aberto' => false,
            'mensagem' => 'Esse profissional não atende o serviço escolhido.',
            'horarios' => [],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!salaoAbreNaData($data)) {
        echo json_encode([
            'aberto' => false,
            'mensagem' => 'O salão não atende nesse dia. Escolha outra data.',
            'horarios' => [],
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $grade = montarGradeDeHorarios($pdo, $idProfissional, $idServico, $data, $ignorarId);
    $livres = array_filter($grade, static fn(array $slot): bool => $slot['disponivel']);

    echo json_encode([
        'aberto' => true,
        'mensagem' => $livres === []
            ? 'Nenhum horário livre nesse dia. Tente outra data.'
            : '',
        'horarios' => $grade,
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Não foi possível carregar os horários, tente novamente.',
    ], JSON_UNESCAPED_UNICODE);
}
