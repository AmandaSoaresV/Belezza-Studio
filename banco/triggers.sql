DELIMITER $$

DROP TRIGGER IF EXISTS trg_servicos_valores_positivos_insert$$
CREATE TRIGGER trg_servicos_valores_positivos_insert
BEFORE INSERT ON servicos
FOR EACH ROW
BEGIN
    IF NEW.preco < 0 THEN
        SET NEW.preco = ABS(NEW.preco);
    END IF;

    IF NEW.duracao_em_minutos < 0 THEN
        SET NEW.duracao_em_minutos = ABS(NEW.duracao_em_minutos);
    END IF;
END$$

DROP TRIGGER IF EXISTS trg_servicos_valores_positivos_update$$
CREATE TRIGGER trg_servicos_valores_positivos_update
BEFORE UPDATE ON servicos
FOR EACH ROW
BEGIN
    IF NEW.preco < 0 THEN
        SET NEW.preco = ABS(NEW.preco);
    END IF;

    IF NEW.duracao_em_minutos < 0 THEN
        SET NEW.duracao_em_minutos = ABS(NEW.duracao_em_minutos);
    END IF;
END$$

DELIMITER ;




--INSERT DE TESTE PRA DEMONSTRAÇÃO
--INSERT INTO servicos (preco, duracao_em_minutos, nome, descricao, created_at, updated_at)
--VALUES (-50.00, -30, 'Teste Trigger', 'Servico de teste para trigger', NOW(), NOW());

---SELECT preco, duracao_em_minutos, nome
--FROM servicos
--WHERE nome = 'Teste Trigger';

--ATUALIZAÇÃO DE TESTE PRA DEMONSTRAÇÃO
--UPDATE servicos
--SET preco = -99.99, duracao_em_minutos = -45
--WHERE nome = 'Teste Trigger';

--SELECT preco, duracao_em_minutos
--FROM servicos
--WHERE nome = 'Teste Trigger';