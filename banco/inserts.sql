-- =============================================================
-- Belezza Studio — Dados iniciais, vindo do PHPMyAdmin
-- =============================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------
-- Usuários
-- -------------------------------------------------------------
INSERT INTO `usuarios` (`id_usuario`, `nome`, `cpf`, `email`, `hash_senha`, `telefone`, `tipo_perfil`, `data_nasc`, `created_at`, `updated_at`) VALUES
(1, 'Admin Belezza', '00000000000', 'admin@belezzastudio.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44999990000', 'admin', '1990-01-01', '2026-07-31 23:01:23', '2026-07-31 23:01:23'),
(2, 'Maria Eduarda Santos', '111.222.333-44', 'maria.eduarda@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44988887777', 'cliente', '1995-05-10', '2026-02-05 10:12:00', '2026-02-05 10:12:00'),
(3, 'Ana Carolina Ribeiro', '222.333.444-55', 'ana.ribeiro@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44991234567', 'cliente', '1988-11-23', '2026-02-06 14:30:00', '2026-02-06 14:30:00'),
(4, 'Fernanda Costa Lima', '333.444.555-66', 'fernanda.lima@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44987654321', 'cliente', '1992-03-02', '2026-02-08 09:45:00', '2026-02-08 09:45:00'),
(5, 'Beatriz Fernandes Rocha', '444.555.666-77', 'beatriz.rocha@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44999871234', 'cliente', '1993-12-05', '2026-02-12 16:00:00', '2026-02-12 16:00:00'),
(6, 'Juliana Pereira Martins', '555.666.777-88', 'juliana.martins@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44992345678', 'cliente', '1998-01-30', '2026-02-15 11:20:00', '2026-02-15 11:20:00'),
(7, 'Camila Rodrigues Alves', '666.777.888-99', 'camila.alves@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44993456789', 'cliente', '1991-06-08', '2026-03-01 08:50:00', '2026-03-01 08:50:00'),
(8, 'Patrícia Gomes Barbosa', '777.888.999-00', 'patricia.barbosa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44994567890', 'cliente', '1987-10-27', '2026-03-04 13:10:00', '2026-03-04 13:10:00'),
(9, 'Larissa Almeida Souza', '888.999.000-11', 'larissa.almeida@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44995678901', 'cliente', '1996-04-22', '2026-03-10 17:40:00', '2026-03-10 17:40:00'),
(10, 'Gabriela Ribeiro Nunes', '999.000.111-22', 'gabriela.nunes@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44996789012', 'cliente', '1994-08-16', '2026-03-18 09:00:00', '2026-03-18 09:00:00'),
(11, 'Isabela Fernandes Costa', '000.111.222-33', 'isabela.costa@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44997890123', 'cliente', '1990-06-08', '2026-03-25 15:30:00', '2026-03-25 15:30:00'),
(12, 'Amanda Soares Vieira', '10527153976', 'amandasoaresvieira5@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(44) 99887-0670', 'cliente', '2008-06-22', '2026-08-08 19:33:50', '2026-08-08 19:33:50'),
(14, 'Amanda Soares Vieira', '10527153974', 'amandasoares@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '(44) 99887-0670', 'cliente', '2006-06-22', '2026-08-08 19:48:49', '2026-08-08 19:48:49'),
(16, 'ana clara', '344444444444', 'ana5@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '44678965678', 'admin', '4444-03-22', '2026-08-08 20:03:02', '2026-08-08 20:03:02'),
(17, 'testando', '6789999999', 'ana@gneyh.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0054567865', 'cliente', '6666-07-06', '2026-08-08 20:06:47', '2026-08-08 20:06:47');

-- -------------------------------------------------------------
-- Profissionais
-- -------------------------------------------------------------
INSERT INTO `profissionais` (`id_profissional`, `nome`, `especialidade`, `created_at`, `updated_at`) VALUES
(1, 'Juliana Alves', 'Cabelo', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(2, 'Renata Souza', 'Estética', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(3, 'Camila Ferreira', 'Unhas', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(4, 'Patrícia Lima', 'Maquiagem', '2026-03-15 09:30:00', '2026-03-15 09:30:00'),
(5, 'Bianca Martins', 'Cílios e Sobrancelhas', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(6, 'Vanessa Oliveira', 'Estética Facial', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(7, 'Débora Santos', 'Massoterapia', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(8, 'Larissa Cardoso', 'Estética Corporal', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(9, 'Tatiane Ferreira', 'Tratamentos Capilares', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(10, 'Sabrina Rocha', 'Cabelo', '2026-07-10 08:00:00', '2026-07-10 08:00:00');

-- -------------------------------------------------------------
-- Serviços
-- -------------------------------------------------------------
INSERT INTO `servicos` (`id_servico`, `preco`, `duracao_em_minutos`, `nome`, `descricao`, `created_at`, `updated_at`) VALUES
(1, 80.00, 60, 'Corte Feminino', 'Corte de cabelo feminino com lavagem e finalização.', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(2, 350.00, 120, 'Coloração Completa', 'Coloração completa com produtos premium e hidratação.', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(3, 120.00, 50, 'Manicure e Pedicure', 'Cuidado completo para unhas das mãos e dos pés.', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(4, 450.00, 150, 'Escova Progressiva', 'Alisamento e tratamento premium para cabelos lisos.', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(5, 90.00, 45, 'Design de Sobrancelhas', 'Design e correção de sobrancelhas com henna opcional.', '2026-02-03 08:00:00', '2026-02-03 08:00:00'),
(6, 200.00, 70, 'Maquiagem Profissional', 'Maquiagem para eventos, festas e ocasiões especiais.', '2026-03-15 09:30:00', '2026-03-15 09:30:00'),
(7, 180.00, 90, 'Hidratação Capilar Profunda', 'Tratamento intensivo de hidratação para cabelos ressecados.', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(8, 400.00, 122, 'Botox Capila', 'Reconstrução capilar com redução de frizz e efeito liso.', '2026-07-10 08:00:00', '2026-08-16 12:29:51'),
(9, 150.00, 75, 'Extensão de Cílios', 'Aplicação fio a fio para alongamento e volume natural.', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(11, 220.00, 60, 'Massagem Relaxante', 'Massagem corporal relaxante com óleos essenciais.', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(12, 320.00, 50, 'Limpeza de Pele', 'Limpeza de pele profunda com extração e máscara calmante.', '2026-07-10 08:00:00', '2026-07-10 08:00:00'),
(13, 250.00, 80, 'Limpeza de Pele Premium', 'Limpeza de pele profunda com aparelhos de alta frequência.', '2026-08-03 08:00:00', '2026-08-03 08:00:00');

-- -------------------------------------------------------------
-- Profissional x Serviço
-- -------------------------------------------------------------
INSERT INTO `profissional_servico` (`id`, `id_servico`, `id_profissional`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 4, 1),
(4, 5, 2),
(5, 6, 2),
(6, 3, 3),
(7, 6, 4),
(8, 5, 4),
(9, 7, 9),
(10, 7, 1),
(11, 8, 9),
(12, 9, 5),
(14, 11, 7),
(15, 12, 6),
(16, 1, 10),
(17, 2, 10),
(18, 4, 10),
(20, 13, 6);

SET FOREIGN_KEY_CHECKS = 1;