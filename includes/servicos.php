<?php

function contarServicos(PDO $pdo): int
{
    $linha = $pdo->query('SELECT COUNT(*) AS total FROM servicos')->fetch(PDO::FETCH_ASSOC) ?: [];

    return (int) ($linha['total'] ?? 0);
}

function listarServicos(PDO $pdo, int $limite, int $offset): array
{
    $sql = <<<SQL
    SELECT s.id_servico, s.nome, s.descricao, s.preco, s.duracao_em_minutos,
           COUNT(a.id_agendamento) AS total_agendamentos
    FROM servicos s
    LEFT JOIN agendamentos a ON a.id_servico = s.id_servico
    GROUP BY s.id_servico, s.nome, s.descricao, s.preco, s.duracao_em_minutos
    ORDER BY s.id_servico DESC
    LIMIT :limite OFFSET :offset
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $servicos = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $servicos[] = [
            'id_servico' => (int) ($linha['id_servico'] ?? 0),
            'nome' => (string) ($linha['nome'] ?? ''),
            'descricao' => (string) ($linha['descricao'] ?? ''),
            'preco' => (float) ($linha['preco'] ?? 0),
            'duracao_em_minutos' => (int) ($linha['duracao_em_minutos'] ?? 0),
            'total_agendamentos' => (int) ($linha['total_agendamentos'] ?? 0),
        ];
    }

    return $servicos;
}

function obterServico(PDO $pdo, int $idServico): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id_servico, nome, descricao, preco, duracao_em_minutos FROM servicos WHERE id_servico = ?'
    );
    $stmt->execute([$idServico]);
    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$linha) {
        return null;
    }

    return [
        'id_servico' => (int) $linha['id_servico'],
        'nome' => (string) $linha['nome'],
        'descricao' => (string) $linha['descricao'],
        'preco' => (float) $linha['preco'],
        'duracao_em_minutos' => (int) $linha['duracao_em_minutos'],
    ];
}

function contarAgendamentosDoServico(PDO $pdo, int $idServico): int
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM agendamentos WHERE id_servico = ?');
    $stmt->execute([$idServico]);

    return (int) $stmt->fetchColumn();
}

function excluirServico(PDO $pdo, int $idServico): void
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('DELETE FROM profissional_servico WHERE id_servico = ?');
        $stmt->execute([$idServico]);

        $stmt = $pdo->prepare('DELETE FROM servicos WHERE id_servico = ?');
        $stmt->execute([$idServico]);

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function existeServicoComNome(PDO $pdo, string $nome, int $ignorarId = 0): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM servicos WHERE nome = ? AND id_servico <> ?'
    );
    $stmt->execute([$nome, $ignorarId]);

    return ((int) $stmt->fetchColumn()) > 0;
}

function listarServicosParaSelecao(PDO $pdo): array
{
    $linhas = $pdo->query(
        'SELECT id_servico, nome, preco FROM servicos ORDER BY nome ASC'
    )->fetchAll(PDO::FETCH_ASSOC);

    $servicos = [];

    foreach ($linhas as $linha) {
        $servicos[] = [
            'id_servico' => (int) $linha['id_servico'],
            'nome' => (string) $linha['nome'],
            'preco' => (float) $linha['preco'],
        ];
    }

    return $servicos;
}
