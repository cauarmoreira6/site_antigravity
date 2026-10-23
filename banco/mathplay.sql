CREATE TABLE IF NOT EXISTS usuarios (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    senha      VARCHAR(255) NOT NULL,        -- Senha armazenada como hash (password_hash)
    tipo       ENUM('aluno', 'admin') NOT NULL DEFAULT 'aluno',
    serie      VARCHAR(10) NULL,
    turma      CHAR(1) NULL,
    avatar_path VARCHAR(255) NULL,
    criado_em  DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS progresso (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id   INT NOT NULL UNIQUE,        -- Cada usuário tem apenas 1 registro de progresso
    xp           INT DEFAULT 0,             -- Experiência total acumulada
    nivel        INT DEFAULT 1,             -- Nível atual (calculado a partir do XP)
    pontuacao    INT DEFAULT 0,             -- Pontuação total
    acertos      INT DEFAULT 0,             -- Total de respostas corretas
    erros        INT DEFAULT 0,             -- Total de respostas erradas
    jogos_feitos INT DEFAULT 0,             -- Quantidade de partidas jogadas
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS resultados (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id  INT NOT NULL,
    jogo_id     INT NOT NULL,               -- IDs dos jogos disponíveis (1 a 8)
    jogo_nome   VARCHAR(100) NOT NULL,      -- Nome amigável do jogo
    pontuacao   INT DEFAULT 0,             -- Pontuação obtida nesta partida
    acertos     INT DEFAULT 0,             -- Acertos nesta partida
    erros       INT DEFAULT 0,             -- Erros nesta partida
    dificuldade ENUM('facil','medio','dificil') DEFAULT 'facil',
    jogado_em   DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


CREATE TABLE IF NOT EXISTS conquistas (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id     INT NOT NULL,
    medalha        VARCHAR(100) NOT NULL,    -- Nome da medalha
    descricao      VARCHAR(255) NOT NULL,    -- Descrição do que foi feito
    icone          VARCHAR(10)  NOT NULL,    -- Emoji da medalha
    conquistado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);


INSERT INTO usuarios (nome, email, senha, tipo)
VALUES ('Administrador', 'admin@mathplay.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin');

INSERT INTO progresso (usuario_id, xp, nivel, pontuacao, acertos, erros, jogos_feitos)
VALUES (LAST_INSERT_ID(), 0, 1, 0, 0, 0, 0);