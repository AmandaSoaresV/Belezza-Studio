<?php
    header('Content-Type: application/json, charset=utf-8');
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET");
    require_once __DIR__ . '/conexao.php';

try {
    $sql = 'SELECT id_servico, preco, duracao_em_minutos, nome, descricao
            FROM servicos
            ORDER BY id_servico DESC';

    $consultaServicos = $pdo->prepare($sql);
    $consultaServicos->execute();

    $servicos = [];

    foreach ($consultaServicos->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $servicos[] = [
            'id_servico' => (int) ($linha['id_servico'] ?? 0),
            'preco' => (float) ($linha['preco'] ?? 0),
            'duracao_em_minutos' => (int) ($linha['duracao_em_minutos'] ?? 0),
            'nome' => (string) ($linha['nome'] ?? ''),
            'descricao' => (string) ($linha['descricao'] ?? ''),
        ];
    }

    echo json_encode($servicos, JSON_UNESCAPED_UNICODE);
} 
catch (PDOException $e) {
    http_response_code(500);
     echo json_encode(["error" => "Erro ao executar consulta no banco de dados"]);
}
?>
