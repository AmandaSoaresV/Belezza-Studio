<?php

/**
 * Horário de funcionamento do salão, por dia da semana (0 = domingo … 6 = sábado).
 * Cada dia tem zero ou mais janelas de atendimento ['abre', 'fecha'].
 * Os mesmos horários estão anunciados no rodapé do site (includes/footer.php).
 */
const HORARIO_FUNCIONAMENTO = [
    0 => [],
    1 => [['09:00', '12:00'], ['14:00', '19:00']],
    2 => [['09:00', '12:00'], ['14:00', '19:00']],
    3 => [['09:00', '12:00'], ['14:00', '19:00']],
    4 => [['09:00', '12:00'], ['14:00', '19:00']],
    5 => [['09:00', '12:00'], ['14:00', '19:00']],
    6 => [['09:00', '12:00'], ['14:00', '17:00']],
];

/** De quantos em quantos minutos um horário pode começar. */
const INTERVALO_ENTRE_HORARIOS = 30;

/** Aceita apenas datas reais no formato Y-m-d (createFromFormat sozinho arredonda 2026-13-45). */
function dataValida(string $data): ?DateTimeImmutable
{
    $dia = DateTimeImmutable::createFromFormat('!Y-m-d', $data);

    if ($dia === false || $dia->format('Y-m-d') !== $data) {
        return null;
    }

    return $dia;
}

function janelasDeAtendimento(string $data): array
{
    $dia = dataValida($data);

    if ($dia === null) {
        return [];
    }

    return HORARIO_FUNCIONAMENTO[(int) $dia->format('w')] ?? [];
}

function salaoAbreNaData(string $data): bool
{
    return janelasDeAtendimento($data) !== [];
}

function obterDuracaoDoServico(PDO $pdo, int $idServico): ?int
{
    $stmt = $pdo->prepare('SELECT duracao_em_minutos FROM servicos WHERE id_servico = ?');
    $stmt->execute([$idServico]);
    $duracao = $stmt->fetchColumn();

    if ($duracao === false) {
        return null;
    }

    return max(1, (int) $duracao);
}

/**
 * Agendamentos que ocupam a agenda do profissional naquele dia.
 * Cancelados não ocupam. Busca desde o dia anterior porque um atendimento
 * longo pode invadir o começo do dia seguinte.
 */
function ocupacaoDoProfissional(PDO $pdo, int $idProfissional, string $data, int $ignorarId = 0): array
{
    $sql = <<<SQL
    SELECT a.data_hora_servico, GREATEST(s.duracao_em_minutos, 1) AS duracao
    FROM agendamentos a
    INNER JOIN servicos s ON s.id_servico = a.id_servico
    WHERE a.id_profissional = :profissional
      AND a.status <> 'cancelado'
      AND a.id_agendamento <> :ignorar
      AND a.data_hora_servico >= DATE_SUB(:data_inicio, INTERVAL 1 DAY)
      AND a.data_hora_servico < DATE_ADD(:data_fim, INTERVAL 1 DAY)
    SQL;

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':profissional', $idProfissional, PDO::PARAM_INT);
    $stmt->bindValue(':ignorar', $ignorarId, PDO::PARAM_INT);
    $stmt->bindValue(':data_inicio', $data);
    $stmt->bindValue(':data_fim', $data);
    $stmt->execute();

    $ocupados = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
        $inicio = strtotime((string) $linha['data_hora_servico']);

        if ($inicio === false) {
            continue;
        }

        $ocupados[] = [
            'inicio' => $inicio,
            'fim' => $inicio + ((int) $linha['duracao'] * 60),
        ];
    }

    return $ocupados;
}

function periodosSeCruzam(int $inicioA, int $fimA, int $inicioB, int $fimB): bool
{
    return $inicioA < $fimB && $inicioB < $fimA;
}

/**
 * Monta a grade do dia: todos os horários em que o serviço cabe dentro de uma
 * janela de atendimento, marcando quais ainda estão livres.
 */
function montarGradeDeHorarios(PDO $pdo, int $idProfissional, int $idServico, string $data, int $ignorarId = 0): array
{
    $duracao = obterDuracaoDoServico($pdo, $idServico);

    if ($duracao === null) {
        return [];
    }

    $janelas = janelasDeAtendimento($data);

    if ($janelas === []) {
        return [];
    }

    $ocupados = ocupacaoDoProfissional($pdo, $idProfissional, $data, $ignorarId);
    $agora = time();
    $grade = [];

    foreach ($janelas as [$abre, $fecha]) {
        $inicioJanela = strtotime($data . ' ' . $abre);
        $fimJanela = strtotime($data . ' ' . $fecha);

        if ($inicioJanela === false || $fimJanela === false) {
            continue;
        }

        for ($inicio = $inicioJanela; $inicio + ($duracao * 60) <= $fimJanela; $inicio += INTERVALO_ENTRE_HORARIOS * 60) {
            $fim = $inicio + ($duracao * 60);
            $livre = $inicio > $agora;

            foreach ($ocupados as $ocupado) {
                if (periodosSeCruzam($inicio, $fim, $ocupado['inicio'], $ocupado['fim'])) {
                    $livre = false;
                    break;
                }
            }

            $grade[] = [
                'horario' => date('H:i', $inicio),
                'disponivel' => $livre,
            ];
        }
    }

    return $grade;
}

function existeConflitoDeAgendamento(
    PDO $pdo,
    int $idProfissional,
    string $dataHora,
    int $duracao,
    int $ignorarId = 0
): bool {
    $inicio = strtotime($dataHora);

    if ($inicio === false) {
        return false;
    }

    $fim = $inicio + (max(1, $duracao) * 60);
    $ocupados = ocupacaoDoProfissional($pdo, $idProfissional, date('Y-m-d', $inicio), $ignorarId);

    foreach ($ocupados as $ocupado) {
        if (periodosSeCruzam($inicio, $fim, $ocupado['inicio'], $ocupado['fim'])) {
            return true;
        }
    }

    return false;
}

function horarioCabeNoExpediente(string $dataHora, int $duracao): bool
{
    $inicio = strtotime($dataHora);

    if ($inicio === false) {
        return false;
    }

    $data = date('Y-m-d', $inicio);
    $fim = $inicio + (max(1, $duracao) * 60);

    foreach (janelasDeAtendimento($data) as [$abre, $fecha]) {
        $inicioJanela = strtotime($data . ' ' . $abre);
        $fimJanela = strtotime($data . ' ' . $fecha);

        if ($inicio >= $inicioJanela && $fim <= $fimJanela) {
            return true;
        }
    }

    return false;
}

function dataHoraJaPassou(string $dataHora): bool
{
    $momento = strtotime($dataHora);

    return $momento !== false && $momento <= time();
}

/**
 * Validação única usada pelo agendamento do cliente e pelos formulários do admin.
 * $exigirExpediente fica em false no admin, que precisa registrar encaixes e
 * atendimentos fora do horário padrão.
 */
function validarHorarioDoAgendamento(
    PDO $pdo,
    int $idProfissional,
    int $idServico,
    string $dataHora,
    int $ignorarId = 0,
    bool $exigirExpediente = true
): array {
    $erros = [];
    $duracao = obterDuracaoDoServico($pdo, $idServico);

    if ($duracao === null) {
        return $erros;
    }

    if ($exigirExpediente) {
        if (dataHoraJaPassou($dataHora)) {
            $erros[] = 'Escolha uma data e um horário que ainda não passaram.';
        } elseif (!salaoAbreNaData(date('Y-m-d', (int) strtotime($dataHora)))) {
            $erros[] = 'O salão não atende nesse dia. Escolha outra data.';
        } elseif (!horarioCabeNoExpediente($dataHora, $duracao)) {
            $erros[] = 'Esse horário está fora do expediente ou o serviço não termina antes do fechamento.';
        }
    }

    if ($erros === [] && existeConflitoDeAgendamento($pdo, $idProfissional, $dataHora, $duracao, $ignorarId)) {
        $erros[] = 'Esse profissional já tem um atendimento nesse horário. Escolha outro.';
    }

    return $erros;
}
