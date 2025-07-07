DROP DATABASE IF EXISTS banque;

CREATE DATABASE banque;

USE banque;

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE type_ressource (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255)
);

CREATE TABLE ressources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_type_resssource INT NOT NULL,
    valeur DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (id_type_resssource) REFERENCES type_ressource (id)
);

CREATE TABLE taux_assurance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    taux DECIMAL(5, 2)
);

CREATE TABLE historique_ressource (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_ressource INT NOT NULL,
    valeur INT NOT NULL,
    estEntree BOOLEAN NOT NULL,
    date_historique DATE NOT NULL,
    FOREIGN KEY (id_ressource) REFERENCES ressources (id)
);

CREATE TABLE type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    duree_max INT NOT NULL,
    montant_max DECIMAL(12, 2) NOT NULL
);

CREATE TABLE taux_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_type_pret INT NOT NULL,
    taux_annuel DECIMAL(12, 2) NOT NULL,
    duree INT NOT NULL,
    borne_inf DECIMAL(12, 2) NOT NULL,
    borne_sup DECIMAL(12, 2) NOT NULL,
    FOREIGN KEY (id_type_pret) REFERENCES type_pret (id)
);

CREATE TABLE mode_remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    frequence_par_mois INT NOT NULL
);

CREATE TABLE employes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE statut_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

CREATE TABLE pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_client INT NULL,
    id_employe INT NOT NULL,
    id_taux_assurance INT NOT NULL,
    id_taux_pret INT NOT NULL,
    id_statut_pret INT NOT NULL,
    id_remboursement INT NOT NULL,
    montant_emprunte DECIMAL(12, 2) NOT NULL,
    date_pret DATE NOT NULL,
    FOREIGN KEY (id_client) REFERENCES clients (id),
    FOREIGN KEY (id_taux_assurance) REFERENCES taux_assurance (id),
    FOREIGN KEY (id_employe) REFERENCES employes (id),
    FOREIGN KEY (id_taux_pret) REFERENCES taux_pret (id),
    FOREIGN KEY (id_statut_pret) REFERENCES statut_pret (id),
    FOREIGN KEY (id_remboursement) REFERENCES mode_remboursement (id)
);

CREATE TABLE remboursement (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_pret INT NOT NULL,
    montant_retour DECIMAL(12, 2) NOT NULL,
    date_retour DATE NOT NULL,
    FOREIGN KEY (id_pret) REFERENCES pret (id)
);