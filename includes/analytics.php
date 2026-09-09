<?php

function chamarProcedure(PDO $pdo, string $sql, array $params = []): array
{
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    return $resultado;
}

function obterIndicadoresDashboard(PDO $pdo): array
{
    $linhas = chamarProcedure($pdo, 'CALL sp_dashboard_indicadores()');
    $indicadores = $linhas[0] ?? [];

    return [
        'total_hoje' => (int) ($indicadores['total_hoje'] ?? 0),
        'total_confirmados' => (int) ($indicadores['total_confirmados'] ?? 0),
        'total_pendentes' => (int) ($indicadores['total_pendentes'] ?? 0),
        'receita_hoje' => (float) ($indicadores['receita_hoje'] ?? 0),
    ];
}

function contarAgendamentosDashboard(PDO $pdo, ?string $status = null): int
{
    $linhas = chamarProcedure(
        $pdo,
        'CALL sp_dashboard_contar_agendamentos(?)',
        [$status ?? '']
    );

    return (int) ($linhas[0]['total'] ?? 0);
}

function listarAgendamentosDashboard(
    PDO $pdo,
    int $limite,
    int $offset,
    ?string $status = null
): array {
    return chamarProcedure(
        $pdo,
        'CALL sp_dashboard_listar_agendamentos(?, ?, ?)',
        [$limite, $offset, $status ?? '']
    );
}

function obterRankingServicos(PDO $pdo): array
{
    $linhas = chamarProcedure($pdo, 'CALL sp_ranking_servicos()');

    $ranking = [];

    foreach ($linhas as $linha) {
        $ranking[] = [
            'id_servico' => (int) ($linha['id_servico'] ?? 0),
            'nome_servico' => (string) ($linha['nome_servico'] ?? ''),
            'preco_servico' => (float) ($linha['preco_servico'] ?? 0),
            'total_agendamentos' => (int) ($linha['total_agendamentos'] ?? 0),
            'total_concluidos' => (int) ($linha['total_concluidos'] ?? 0),
        ];
    }

    return $ranking;
}

function obterResumoRelatorio(PDO $pdo): array
{
    $sql = <<<SQL
    SELECT
        COALESCE(SUM(CASE WHEN status = 'concluido' THEN receita ELSE 0 END), 0) AS receita_total,
        SUM(
            CASE
                WHEN DATE(data_hora_servico) = CURDATE()
                 AND status IN ('confirmado', 'concluido')
                THEN 1 ELSE 0
            END
        ) AS total_hoje,
        COUNT(DISTINCT id_cliente) AS total_clientes,
        COALESCE(
            SUM(
                CASE
                    WHEN status = 'concluido'
                     AND DATE(data_hora_servico) = CURDATE()
                    THEN receita ELSE 0
                END
            ),
            0
        ) AS receita_hoje
    FROM vw_agendamentos_completos
    WHERE status IN ('pendente', 'confirmado', 'cancelado', 'concluido')
    SQL;

    $linha = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC) ?: [];

    return [
        'receita_total' => (float) ($linha['receita_total'] ?? 0),
        'total_hoje' => (int) ($linha['total_hoje'] ?? 0),
        'total_clientes' => (int) ($linha['total_clientes'] ?? 0),
        'receita_hoje' => (float) ($linha['receita_hoje'] ?? 0),
    ];
}
