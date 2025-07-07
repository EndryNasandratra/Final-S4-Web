-- Base de données
CREATE DATABASE IF NOT EXISTS tp_flight;
USE tp_flight;

-- Table
CREATE TABLE IF NOT EXISTS etudiant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100),
    age INT
);

-- Données d'exemple
INSERT INTO etudiant (nom, prenom, email, age) VALUES
('Randria', 'Miarintsoa', 'miarintsoa.randria@example.com', 21),
('Rakoto', 'Jean', 'jean.rakoto@example.com', 23),
('Rabe', 'Finaritra', 'finaritra.rabe@example.com', 20),
('Raharinirina', 'Nomena', 'nomena.raha@example.com', 25),
('Andriamahery', 'Tojo', 'tojo.mahery@example.com', 22),
('Ratsimba', 'Herisoa', 'herisoa.rats@example.com', 24),
('Ando', 'Fitiavana', 'fitiavana.ando@example.com', 26),
('Razanaka', 'Tiana', 'tiana.razanaka@example.com', 19),
('Randrianarisoa', 'Hery', 'hery.randrianarisoa@example.com', 27),
('Rakotomanga', 'Fanja', 'fanja.rakotomanga@example.com', 21);
