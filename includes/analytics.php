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

function contarUsuarios(PDO $pdo): int
{
    $linha = $pdo->query('SELECT COUNT(*) AS total FROM usuarios')->fetch(PDO::FETCH_ASSOC) ?: [];

    return (int) ($linha['total'] ?? 0);
}

function listarUsuarios(PDO $pdo, int $limite, int $offset): array
{
    $sql = <<<SQL
    SELECT u.id_usuario, u.nome, u.cpf, u.email, u.telefone, u.tipo_perfil, u.data_nasc,
           COUNT(a.id_agendamento) AS total_agendamentos
    FROM usuarios u
    LEFT JOIN agendamentos a ON a.id_cliente = u.id_usuario
    GROUP BY u.id_usuario, u.nome, u.cpf, u.email, u.telefone, u.tipo_perfil, u.data_nasc
    ORDER BY u.id_usuario DESC
    LIMIT :limite OFFSET :offset
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $usuarios = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $usuarios[] = [
            'id_usuario' => (int) ($linha['id_usuario'] ?? 0),
            'nome' => (string) ($linha['nome'] ?? ''),
            'cpf' => (string) ($linha['cpf'] ?? ''),
            'email' => (string) ($linha['email'] ?? ''),
            'telefone' => (string) ($linha['telefone'] ?? ''),
            'tipo_perfil' => (string) ($linha['tipo_perfil'] ?? ''),
            'data_nasc' => (string) ($linha['data_nasc'] ?? ''),
            'total_agendamentos' => (int) ($linha['total_agendamentos'] ?? 0),
        ];
    }

    return $usuarios;
}

function existeUsuarioComCpf(PDO $pdo, string $cpf, int $ignorarId = 0): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE cpf = ? AND id_usuario <> ?');
    $stmt->execute([$cpf, $ignorarId]);

    return ((int) $stmt->fetchColumn()) > 0;
}

function existeUsuarioComEmail(PDO $pdo, string $email, int $ignorarId = 0): bool
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = ? AND id_usuario <> ?');
    $stmt->execute([$email, $ignorarId]);

    return ((int) $stmt->fetchColumn()) > 0;
}

function obterUsuarioPorEmail(PDO $pdo, string $email): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id_usuario, nome, email, hash_senha, tipo_perfil FROM usuarios WHERE email = ?'
    );
    $stmt->execute([$email]);
    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$linha) {
        return null;
    }

    return [
        'id_usuario' => (int) $linha['id_usuario'],
        'nome' => (string) $linha['nome'],
        'email' => (string) $linha['email'],
        'hash_senha' => (string) $linha['hash_senha'],
        'tipo_perfil' => (string) $linha['tipo_perfil'],
    ];
}

function obterUsuario(PDO $pdo, int $idUsuario): ?array
{
    $stmt = $pdo->prepare(
        'SELECT id_usuario, nome, cpf, email, telefone, tipo_perfil, data_nasc FROM usuarios WHERE id_usuario = ?'
    );
    $stmt->execute([$idUsuario]);
    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$linha) {
        return null;
    }

    return [
        'id_usuario' => (int) $linha['id_usuario'],
        'nome' => (string) $linha['nome'],
        'cpf' => (string) $linha['cpf'],
        'email' => (string) $linha['email'],
        'telefone' => (string) $linha['telefone'],
        'tipo_perfil' => (string) $linha['tipo_perfil'],
        'data_nasc' => (string) $linha['data_nasc'],
    ];
}

function contarAgendamentosDoUsuario(PDO $pdo, int $idUsuario): int
{
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM agendamentos WHERE id_cliente = ?');
    $stmt->execute([$idUsuario]);

    return (int) $stmt->fetchColumn();
}

function excluirUsuario(PDO $pdo, int $idUsuario): void
{
    $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id_usuario = ?');
    $stmt->execute([$idUsuario]);
}

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

function listarUsuariosParaSelecao(PDO $pdo): array
{
    $linhas = $pdo->query(
        'SELECT id_usuario, nome, tipo_perfil FROM usuarios ORDER BY nome ASC'
    )->fetchAll(PDO::FETCH_ASSOC);

    $usuarios = [];

    foreach ($linhas as $linha) {
        $usuarios[] = [
            'id_usuario' => (int) $linha['id_usuario'],
            'nome' => (string) $linha['nome'],
            'tipo_perfil' => (string) $linha['tipo_perfil'],
        ];
    }

    return $usuarios;
}

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

function criarProfissional(PDO $pdo, string $nome, string $especialidade): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO profissionais (nome, especialidade, created_at, updated_at) VALUES (?, ?, NOW(), NOW())'
    );
    $stmt->execute([$nome, $especialidade]);

    return (int) $pdo->lastInsertId();
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
