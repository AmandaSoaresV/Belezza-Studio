WITH agendamentos_limpos AS (
    SELECT
        id_agendamento,
        id_cliente,
        nome_cliente,
        id_profissional,
        nome_profissional,
        id_servico,
        nome_servico,
        preco_servico,
        data_hora_servico,
        DATE(data_hora_servico) AS data_servico,
        status,
        receita
    FROM vw_agendamentos_completos
    WHERE status IN ('pendente', 'confirmado', 'cancelado', 'concluido')
)
SELECT *
FROM agendamentos_limpos
ORDER BY data_hora_servico ASC;


WITH agendamentos_limpos AS (
    SELECT
        id_agendamento,
        DATE(data_hora_servico) AS data_servico,
        status,
        receita
    FROM vw_agendamentos_completos
    WHERE status IN ('pendente', 'confirmado', 'cancelado', 'concluido')
),
indicadores_dashboard AS (
    SELECT
        SUM(
            CASE
                WHEN data_servico = CURDATE()
                 AND status IN ('confirmado', 'concluido')
                THEN 1 ELSE 0
            END
        ) AS total_hoje,
        SUM(CASE WHEN status = 'confirmado' THEN 1 ELSE 0 END) AS total_confirmados,
        SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) AS total_pendentes,
        COALESCE(
            SUM(CASE WHEN data_servico = CURDATE() THEN receita ELSE 0 END),
            0
        ) AS receita_hoje
    FROM agendamentos_limpos
)
SELECT *
FROM indicadores_dashboard;



WITH agendamentos_limpos AS (
    SELECT
        id_servico,
        nome_servico,
        status
    FROM vw_agendamentos_completos
    WHERE status IN ('pendente', 'confirmado', 'concluido')
),
ranking_servicos AS (
    SELECT
        id_servico,
        nome_servico,
        COUNT(*) AS total_agendamentos
    FROM agendamentos_limpos
    GROUP BY id_servico, nome_servico
)
SELECT
    id_servico,
    nome_servico,
    total_agendamentos
FROM ranking_servicos
ORDER BY total_agendamentos DESC, nome_servico ASC;



WITH agendamentos_limpos AS (
    SELECT
        DATE(data_hora_servico) AS data_servico,
        receita
    FROM vw_agendamentos_completos
    WHERE status = 'concluido'
      AND data_hora_servico >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
),
receita_diaria AS (
    SELECT
        data_servico,
        COALESCE(SUM(receita), 0) AS receita_dia
    FROM agendamentos_limpos
    GROUP BY data_servico
)
SELECT
    data_servico,
    receita_dia
FROM receita_diaria
ORDER BY data_servico ASC;