
DELIMITER $$

DROP PROCEDURE IF EXISTS sp_dashboard_indicadores$$
CREATE PROCEDURE sp_dashboard_indicadores()
BEGIN
    WITH agendamentos_limpos AS (
        SELECT
            DATE(data_hora_servico) AS data_servico,
            status,
            receita
        FROM vw_agendamentos_completos
        WHERE status IN ('pendente', 'confirmado', 'cancelado', 'concluido')
    )
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
    FROM agendamentos_limpos;
END$$

DROP PROCEDURE IF EXISTS sp_dashboard_listar_agendamentos$$
CREATE PROCEDURE sp_dashboard_listar_agendamentos(
    IN p_limite INT,
    IN p_offset INT,
    IN p_status VARCHAR(20)
)
BEGIN
    SELECT
        id_agendamento,
        nome_cliente,
        data_hora_servico,
        nome_servico,
        status
    FROM vw_agendamentos_completos
    WHERE p_status IS NULL
       OR p_status = ''
       OR status = p_status
    ORDER BY data_hora_servico ASC
    LIMIT p_limite OFFSET p_offset;
END$$

DROP PROCEDURE IF EXISTS sp_dashboard_contar_agendamentos$$
CREATE PROCEDURE sp_dashboard_contar_agendamentos(
    IN p_status VARCHAR(20)
)
BEGIN
    SELECT COUNT(*) AS total
    FROM vw_agendamentos_completos
    WHERE p_status IS NULL
       OR p_status = ''
       OR status = p_status;
END$$

DROP PROCEDURE IF EXISTS sp_ranking_servicos$$
CREATE PROCEDURE sp_ranking_servicos()
BEGIN
    WITH agendamentos_limpos AS (
        SELECT
            id_servico,
            nome_servico,
            status
        FROM vw_agendamentos_completos
        WHERE status IN ('pendente', 'confirmado', 'concluido')
    )
    SELECT
        id_servico,
        nome_servico,
        COUNT(*) AS total_agendamentos
    FROM agendamentos_limpos
    GROUP BY id_servico, nome_servico
    ORDER BY total_agendamentos DESC, nome_servico ASC;
END$$

DELIMITER ;


-- CONSULTAS DE TESTE

-- CALL sp_dashboard_indicadores();

-- CALL sp_dashboard_listar_agendamentos(10, 0, NULL);

-- CALL sp_dashboard_listar_agendamentos(10, 0, 'pendente');

-- CALL sp_dashboard_contar_agendamentos(NULL);


-- CALL sp_ranking_servicos();