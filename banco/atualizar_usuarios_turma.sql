-- Execute este comando uma vez no banco mathplay existente para habilitar
-- o armazenamento da série e da turma informadas no cadastro de alunos.
USE mathplay;

ALTER TABLE usuarios
    ADD COLUMN serie VARCHAR(10) NULL AFTER tipo,
    ADD COLUMN turma CHAR(1) NULL AFTER serie;
