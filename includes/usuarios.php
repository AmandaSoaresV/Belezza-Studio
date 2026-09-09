<?php

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
