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
