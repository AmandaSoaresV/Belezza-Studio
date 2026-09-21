<?php
require_once __DIR__ . '/../../../api/conexao.php';
require_once __DIR__ . '/../../../includes/agendamentos.php';
require_once __DIR__ . '/../../../includes/horarios.php';
require_once __DIR__ . '/../../../includes/sessao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /seushorarios');
    exit;
}

$idCliente = usuarioLogado()['id_usuario'];
$idAgendamento = isset($_POST['id_agendamento']) ? (int) $_POST['id_agendamento'] : 0;

if ($idAgendamento < 1) {
    header('Location: /seushorarios?naoencontrado=1');
    exit;
}

try {
    $agendamento = obterAgendamento($pdo, $idAgendamento);

    // Um cliente só cancela o próprio horário.
    if ($agendamento === null || $agendamento['id_cliente'] !== $idCliente) {
        header('Location: /seushorarios?naoencontrado=1');
        exit;
    }

    if (!in_array($agendamento['status'], ['pendente', 'confirmado'], true)) {
        header('Location: /seushorarios?naopodecancelar=1');
        exit;
    }

    if (dataHoraJaPassou($agendamento['data_hora_servico'])) {
        header('Location: /seushorarios?horariopassou=1');
        exit;
    }

    cancelarAgendamento($pdo, $idAgendamento);
    header('Location: /seushorarios?cancelado=1');
    exit;
} catch (PDOException $e) {
    header('Location: /seushorarios?errocancelar=1');
    exit;
}
