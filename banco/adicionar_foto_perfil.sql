-- Execute uma vez no banco mathplay para habilitar fotos de perfil
-- em instalações existentes.
USE mathplay;

ALTER TABLE usuarios
    ADD COLUMN avatar_path VARCHAR(255) NULL AFTER turma;