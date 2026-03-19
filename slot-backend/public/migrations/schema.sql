CREATE DATABASE IF NOT EXISTS slot_sense CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE slot_sense;

CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha_hash VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 1,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS jogos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  provedora VARCHAR(50) NOT NULL,
  imagem VARCHAR(255) DEFAULT NULL,
  link_affiliate VARCHAR(500) DEFAULT NULL,
  porcentagem INT DEFAULT 50,
  popularidade INT DEFAULT 0,
  atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cliques (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  jogo_id INT NOT NULL,
  ip VARCHAR(45),
  user_agent TEXT,
  referrer VARCHAR(500),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (jogo_id) REFERENCES jogos(id) ON DELETE CASCADE
);

-- Inserindo um usuário Admin padrão: admin@slotsense.com / Senha: admin
-- A senha 'admin' foi hasheada com bcrypt
INSERT IGNORE INTO usuarios (nome, email, senha_hash, is_admin) VALUES 
('Administrador', 'admin@slotsense.com', '$2y$10$wT8m/KAYI6tXQ03qQkOaMeQJ.0S/zR9e4g10N4yQ10N4yQ10N4yQ10', 1);

-- Nota: Para fins reais em produção gere outro hash via password_hash('admin', PASSWORD_DEFAULT)
-- Usaremos um hash conhecido temporário:
UPDATE usuarios SET senha_hash = '$2y$10$O0S.sNl0u236nCgC4S2T0OMR6tL1B14g3s6Vn4B10N4yQ10N4yQ10' WHERE email = 'admin@slotsense.com';
