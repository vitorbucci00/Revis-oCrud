
DROP DATABASE IF EXISTS revisao_crud;
CREATE DATABASE revisao_crud CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE revisao_crud;


CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB;


CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  descricao TEXT NOT NULL,
  setor VARCHAR(100) NOT NULL,
  prioridade ENUM('baixa','média','alta') NOT NULL,
  data_cadastro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  status ENUM('a fazer','fazendo','pronto') NOT NULL DEFAULT 'a fazer',
  CONSTRAINT fk_tasks_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE OR REPLACE VIEW view_tasks AS
SELECT t.*, u.nome AS usuario_nome, u.email AS usuario_email
FROM tasks t
JOIN users u ON u.id = t.user_id;


INSERT INTO users (nome, email) VALUES
('Exemplo Usuario','exemplo@dominio.com');

INSERT INTO tasks (user_id, descricao, setor, prioridade) VALUES
(1, 'Exemplo de tarefa 1', 'TI', 'alta'),
(1, 'Exemplo de tarefa 2', 'Financeiro', 'média');
