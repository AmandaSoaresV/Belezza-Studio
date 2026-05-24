-------------------------------------------------------------
-- Tabela Usuários
-------------------------------------------------------------
CREATE TABLE `usuarios`(
    `id_usuario` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(40) NOT NULL,
    `cpf` VARCHAR(14) NOT NULL,
    `email` VARCHAR(40) NOT NULL,
    `hash_senha` VARCHAR(255) NOT NULL,
    `telefone` VARCHAR(15) NOT NULL,
    `tipo_perfil` ENUM('admin', 'cliente') NOT NULL,
    `data_nasc` DATE NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL
);

ALTER TABLE `usuarios` ADD UNIQUE `usuarios_cpf_unique`(`cpf`);
ALTER TABLE `usuarios` ADD UNIQUE `usuarios_email_unique`(`email`);

-------------------------------------------------------------
-- Tabela Profissionais
-------------------------------------------------------------
CREATE TABLE `profissionais`(
    `id_profissional` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nome` VARCHAR(80) NOT NULL,
    `especialidade` VARCHAR(80) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL
);

-------------------------------------------------------------
-- Tabela Serviços
-------------------------------------------------------------
CREATE TABLE `servicos`(
    `id_servico` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `preco` DECIMAL(10, 2) NOT NULL,
    `duracao_em_minutos` INT NOT NULL,
    `nome` VARCHAR(100) NOT NULL,
    `descricao` TEXT NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL
);

-------------------------------------------------------------
-- Tabela Agendamentos (depois das dependências)
-------------------------------------------------------------
CREATE TABLE `agendamentos`(
    `id_agendamento` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_cliente` BIGINT UNSIGNED NOT NULL,
    `id_profissional` BIGINT UNSIGNED NOT NULL,
    `id_servico` BIGINT UNSIGNED NOT NULL,
    `data_hora_servico` DATETIME NOT NULL,
    `status` ENUM('pendente', 'confirmado', 'cancelado', 'concluido') NOT NULL,
    `observacao` TEXT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL
);

-------------------------------------------------------------
-- Tabela Profissional_Servico
-------------------------------------------------------------
CREATE TABLE `profissional_servico`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_servico` BIGINT UNSIGNED NOT NULL,
    `id_profissional` BIGINT UNSIGNED NOT NULL
);

-------------------------------------------------------------
-- Restrições de Chaves Estrangeiras
-------------------------------------------------------------
ALTER TABLE `agendamentos`
    ADD CONSTRAINT `agendamentos_id_cliente_foreign`
    FOREIGN KEY(`id_cliente`) REFERENCES `usuarios`(`id_usuario`);

ALTER TABLE `agendamentos`
    ADD CONSTRAINT `agendamentos_id_profissional_foreign`
    FOREIGN KEY(`id_profissional`) REFERENCES `profissionais`(`id_profissional`);

ALTER TABLE `agendamentos`
    ADD CONSTRAINT `agendamentos_id_servico_foreign`
    FOREIGN KEY(`id_servico`) REFERENCES `servicos`(`id_servico`);

ALTER TABLE `profissional_servico`
    ADD CONSTRAINT `profissional_servico_id_servico_foreign`
    FOREIGN KEY(`id_servico`) REFERENCES `servicos`(`id_servico`);

ALTER TABLE `profissional_servico`
    ADD CONSTRAINT `profissional_servico_id_profissional_foreign`
    FOREIGN KEY(`id_profissional`) REFERENCES `profissionais`(`id_profissional`);