DROP VIEW IF EXISTS vw_agendamentos_completos;

CREATE VIEW vw_agendamentos_completos AS
SELECT
    a.id_agendamento,
    a.id_cliente,
    u.nome AS nome_cliente,
    a.id_profissional,
    p.nome AS nome_profissional,
    p.especialidade AS especialidade_profissional,
    a.id_servico,
    s.nome AS nome_servico,
    s.preco AS preco_servico,
    s.duracao_em_minutos,
    a.data_hora_servico,
    a.status,
    a.observacao,
    func_receita_agendamento(a.id_agendamento) AS receita
FROM agendamentos AS a
INNER JOIN usuarios AS u ON u.id_usuario = a.id_cliente
INNER JOIN profissionais AS p ON p.id_profissional = a.id_profissional
INNER JOIN servicos AS s ON s.id_servico = a.id_servico;


-- CONSULTAS DE TESTE
-- SELECT * FROM vw_agendamentos_completos;

-- SELECT nome_cliente, data_hora_servico, nome_servico, status
-- FROM vw_agendamentos_completos
-- WHERE id_cliente = 3;

-- receita total de agendamentos
-- SELECT SUM(receita) AS receita_total
-- FROM vw_agendamentos_completos;
