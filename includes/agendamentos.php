<?php

function obterAgendamento(PDO $pdo, int $idAgendamento): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id_agendamento, id_cliente, nome_cliente, id_profissional, nome_profissional,
                id_servico, nome_servico, data_hora_servico, status, observacao
         FROM vw_agendamentos_completos WHERE id_agendamento = ?'
    );
    $stmt->execute([$idAgendamento]);
    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$linha) {
        return null;
    }

    return [
        'id_agendamento' => (int) $linha['id_agendamento'],
        'id_cliente' => (int) $linha['id_cliente'],
        'nome_cliente' => (string) $linha['nome_cliente'],
        'id_profissional' => (int) $linha['id_profissional'],
        'nome_profissional' => (string) $linha['nome_profissional'],
        'id_servico' => (int) $linha['id_servico'],
        'nome_servico' => (string) $linha['nome_servico'],
        'data_hora_servico' => (string) $linha['data_hora_servico'],
        'status' => (string) $linha['status'],
        'observacao' => (string) ($linha['observacao'] ?? ''),
    ];
}

function excluirAgendamento(PDO $pdo, int $idAgendamento): void
{
    $stmt = $pdo->prepare('DELETE FROM agendamentos WHERE id_agendamento = ?');
    $stmt->execute([$idAgendamento]);
}

function atualizarAgendamento(PDO $pdo, int $idAgendamento, array $valores): void
{
    $sql = <<<SQL
    UPDATE agendamentos
    SET id_cliente = :cliente,
        id_profissional = :profissional,
        id_servico = :servico,
        data_hora_servico = :data_hora,
        status = :status,
        observacao = :observacao,
        updated_at = NOW()
    WHERE id_agendamento = :id
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cliente', $valores['id_cliente'], PDO::PARAM_INT);
    $stmt->bindValue(':profissional', $valores['id_profissional'], PDO::PARAM_INT);
    $stmt->bindValue(':servico', $valores['id_servico'], PDO::PARAM_INT);
    $stmt->bindValue(':data_hora', $valores['data_hora_servico']);
    $stmt->bindValue(':status', $valores['status']);
    $stmt->bindValue(':observacao', $valores['observacao']);
    $stmt->bindValue(':id', $idAgendamento, PDO::PARAM_INT);
    $stmt->execute();
}

function criarAgendamento(PDO $pdo, array $valores): int
{
    $sql = <<<SQL
    INSERT INTO agendamentos
        (id_cliente, id_profissional, id_servico, data_hora_servico, status, observacao, created_at, updated_at)
    VALUES
        (:cliente, :profissional, :servico, :data_hora, :status, :observacao, NOW(), NOW())
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':cliente', $valores['id_cliente'], PDO::PARAM_INT);
    $stmt->bindValue(':profissional', $valores['id_profissional'], PDO::PARAM_INT);
    $stmt->bindValue(':servico', $valores['id_servico'], PDO::PARAM_INT);
    $stmt->bindValue(':data_hora', $valores['data_hora_servico']);
    $stmt->bindValue(':status', $valores['status']);
    $stmt->bindValue(':observacao', $valores['observacao']);
    $stmt->execute();

    return (int) $pdo->lastInsertId();
}
