CREATE TABLE wp_temas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    descricao TEXT,
    tipo ENUM('longa', 'curta'),
    data_inicio DATE,
    data_fim DATE,
    tema_id INT,
    subtema_id INT,
    FOREIGN KEY (tema_id) REFERENCES wp_temas(id),
    FOREIGN KEY (subtema_id) REFERENCES wp_subtemas(id)
);

CREATE TABLE wp_temas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255)
);
CREATE TABLE wp_subtemas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(255)
);