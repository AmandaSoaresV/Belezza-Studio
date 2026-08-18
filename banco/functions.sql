DROP FUNCTION IF EXISTS func_receita_agendamento;

DELIMITER $$

CREATE FUNCTION func_receita_agendamento(p_id_agendamento BIGINT UNSIGNED)
RETURNS DECIMAL(10, 2)
READS SQL DATA
BEGIN
    DECLARE v_receita DECIMAL(10, 2) DEFAULT 0.00;

    SELECT COALESCE(s.preco, 0.00)
    INTO v_receita
    FROM agendamentos AS a
    INNER JOIN servicos AS s ON s.id_servico = a.id_servico
    WHERE a.id_agendamento = p_id_agendamento
      AND a.status = 'concluido'
    LIMIT 1;

    RETURN COALESCE(v_receita, 0.00);
END$$

DELIMITER ;


--TESTES
-- SELECT func_receita_agendamento(999) AS receita;

-- INSERT INTO agendamentos
-- (id_cliente, id_profissional, id_servico, data_hora_servico, status, created_at, updated_at)
-- VALUES (3, 1, 1, NOW(), 'concluido', NOW(), NOW());
--
-- SELECT func_receita_agendamento(LAST_INSERT_ID()) AS receita;