<?php

function listarProfissionais(PDO $pdo): array
{
    $linhas = $pdo->query(
        'SELECT id_profissional, nome, especialidade FROM profissionais ORDER BY nome ASC'
    )->fetchAll(PDO::FETCH_ASSOC);

    $profissionais = [];

    foreach ($linhas as $linha) {
        $profissionais[] = [
            'id_profissional' => (int) $linha['id_profissional'],
            'nome' => (string) $linha['nome'],
            'especialidade' => (string) $linha['especialidade'],
        ];
    }

    return $profissionais;
}

function contarProfissionais(PDO $pdo): int
{
    $linha = $pdo->query('SELECT COUNT(*) AS total FROM profissionais')->fetch(PDO::FETCH_ASSOC) ?: [];

    return (int) ($linha['total'] ?? 0);
}

function listarProfissionaisPaginado(PDO $pdo, int $limite, int $offset): array
{
    $sql = <<<SQL
    SELECT p.id_profissional, p.nome, p.especialidade,
           (SELECT COUNT(*) FROM agendamentos a WHERE a.id_profissional = p.id_profissional) AS total_agendamentos,
           (SELECT COUNT(*) FROM profissional_servico ps WHERE ps.id_profissional = p.id_profissional) AS total_servicos
    FROM profissionais p
    ORDER BY p.id_profissional DESC
    LIMIT :limite OFFSET :offset
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $profissionais = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $profissionais[] = [
            'id_profissional' => (int) ($linha['id_profissional'] ?? 0),
            'nome' => (string) ($linha['nome'] ?? ''),
            'especialidade' => (string) ($linha['especialidade'] ?? ''),
            'total_agendamentos' => (int) ($linha['total_agendamentos'] ?? 0),
            'total_servicos' => (int) ($linha['total_servicos'] ?? 0),
        ];
    }

    return $profissionais;
}

function obterProfissional(PDO $pdo, int $idProfissional): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id_profissional, nome, especialidade FROM profissionais WHERE id_profissional = ?'
    );
    $stmt->execute([$idProfissional]);
    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$linha) {
        return null;
    }

    return [
        'id_profissional' => (int) $linha['id_profissional'],
        'nome' => (string) $linha['nome'],
        'especialidade' => (string) $linha['especialidade'],
    ];
}

/** Ids dos serviços que o profissional atende. */
function servicosDoProfissional(PDO $pdo, int $idProfissional): array
{
    $stmt = $pdo->prepare(
        'SELECT id_servico FROM profissional_servico WHERE id_profissional = ?'
    );
    $stmt->execute([$idProfissional]);

    return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
}

function contarAgendamentosDoProfissional(PDO $pdo, int $idProfissional): int
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM agendamentos WHERE id_profissional = ?');
    $stmt->execute([$idProfissional]);

    return (int) $stmt->fetchColumn();
}

function atualizarProfissional(PDO $pdo, int $idProfissional, string $nome, string $especialidade, array $idsServicos): void
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare(
            'UPDATE profissionais SET nome = ?, especialidade = ?, updated_at = NOW() WHERE id_profissional = ?'
        );
        $stmt->execute([$nome, $especialidade, $idProfissional]);

        $stmt = $pdo->prepare('DELETE FROM profissional_servico WHERE id_profissional = ?');
        $stmt->execute([$idProfissional]);

        $vinculo = $pdo->prepare(
            'INSERT INTO profissional_servico (id_servico, id_profissional) VALUES (?, ?)'
        );

        foreach ($idsServicos as $idServico) {
            $vinculo->execute([(int) $idServico, $idProfissional]);
        }

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function excluirProfissional(PDO $pdo, int $idProfissional): void
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare('DELETE FROM profissional_servico WHERE id_profissional = ?');
        $stmt->execute([$idProfissional]);

        $stmt = $pdo->prepare('DELETE FROM profissionais WHERE id_profissional = ?');
        $stmt->execute([$idProfissional]);

        $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function listarProfissionaisDoServico(PDO $pdo, int $idServico): array
{
    $sql = <<<SQL
    SELECT p.id_profissional, p.nome, p.especialidade
    FROM profissionais p
    INNER JOIN profissional_servico ps ON ps.id_profissional = p.id_profissional
    WHERE ps.id_servico = :servico
    ORDER BY p.nome ASC
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':servico', $idServico, PDO::PARAM_INT);
    $stmt->execute();

    $profissionais = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $profissionais[] = [
            'id_profissional' => (int) ($linha['id_profissional'] ?? 0),
            'nome' => (string) ($linha['nome'] ?? ''),
            'especialidade' => (string) ($linha['especialidade'] ?? ''),
        ];
    }

    return $profissionais;
}

function listarServicosPorProfissional(PDO $pdo): array
{
    $linhas = $pdo->query(
        'SELECT id_profissional, id_servico FROM profissional_servico ORDER BY id_profissional ASC'
    )->fetchAll(PDO::FETCH_ASSOC);

    $mapa = [];

    foreach ($linhas as $linha) {
        $idProfissional = (int) ($linha['id_profissional'] ?? 0);
        $mapa[$idProfissional][] = (int) ($linha['id_servico'] ?? 0);
    }

    return $mapa;
}

function profissionalAtendeServico(PDO $pdo, int $idProfissional, int $idServico): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM profissional_servico WHERE id_profissional = ? AND id_servico = ?'
    );
    $stmt->execute([$idProfissional, $idServico]);

    return ((int) $stmt->fetchColumn()) > 0;
}

function criarProfissional(PDO $pdo, string $nome, string $especialidade, array $idsServicos = []): int
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare(
            'INSERT INTO profissionais (nome, especialidade, created_at, updated_at) VALUES (?, ?, NOW(), NOW())'
        );
        $stmt->execute([$nome, $especialidade]);

        $idProfissional = (int) $pdo->lastInsertId();

        $vinculo = $pdo->prepare(
            'INSERT INTO profissional_servico (id_servico, id_profissional) VALUES (?, ?)'
        );

        foreach ($idsServicos as $idServico) {
            $vinculo->execute([(int) $idServico, $idProfissional]);
        }

        $pdo->commit();

        return $idProfissional;
    } catch (PDOException $e) {
        $pdo->rollBack();
        throw $e;
    }
}
