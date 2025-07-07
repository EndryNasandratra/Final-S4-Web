DROP DATABASE IF EXISTS banque;

CREATE DATABASE banque;

USE banque;

CREATE TABLE clients (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE type_ressource (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle BIGINT NOT NULL
);

CREATE TABLE Ressources (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_type_resssource BIGINT NOT NULL,
    valeur DECIMAL(8, 2) NOT NULL
);

CREATE TABLE historique_ressource (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_ressource BIGINT NOT NULL,
    valeur BIGINT NOT NULL,
    date_historique DATE NOT NULL
);

CREATE TABLE type_pret (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    duree_max INT NOT NULL,
    montant_max DECIMAL(8, 2) NOT NULL
);

CREATE TABLE taux_pret (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_type_pret BIGINT NOT NULL,
    taux DECIMAL(8, 2) NOT NULL,
    duree BIGINT NOT NULL
);

CREATE TABLE statut_pret (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

CREATE TABLE demande_pret (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_client BIGINT NULL,
    id_employe BIGINT NOT NULL,
    id_type_pret BIGINT NOT NULL,
    id_taux_pret BIGINT NOT NULL,
    id_remboursement BIGINT NOT NULL,
    id_statut_demande BIGINT NOT NULL,
    montant_emprunte DECIMAL(8, 2) NOT NULL,
    date_demande DATE NOT NULL
);

CREATE TABLE mode_remboursement (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL,
    frequence_par_mois INT NOT NULL
);

CREATE TABLE statut_demande (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(255) NOT NULL
);

CREATE TABLE pret (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_demande_pret BIGINT NOT NULL,
    id_statut_pret BIGINT NOT NULL,
    date_pret DATE NOT NULL
);

CREATE TABLE employes (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE remboursement (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_pret BIGINT NOT NULL,
    montant_retour DECIMAL(8, 2) NOT NULL,
    date_retour DATE NOT NULL
);

ALTER TABLE type_ressource
ADD CONSTRAINT type_ressource_id_foreign FOREIGN KEY (id) REFERENCES Ressources (id);

ALTER TABLE demande_pret
ADD CONSTRAINT demande_pret_id_type_pret_foreign FOREIGN KEY (id_type_pret) REFERENCES type_pret (id);

ALTER TABLE demande_pret
ADD CONSTRAINT demande_pret_id_remboursement_foreign FOREIGN KEY (id_remboursement) REFERENCES mode_remboursement (id);

ALTER TABLE demande_pret
ADD CONSTRAINT demande_pret_id_client_foreign FOREIGN KEY (id_client) REFERENCES clients (id);

ALTER TABLE historique_ressource
ADD CONSTRAINT historique_ressource_id_ressource_foreign FOREIGN KEY (id_ressource) REFERENCES Ressources (id);

ALTER TABLE demande_pret
ADD CONSTRAINT demande_pret_id_taux_pret_foreign FOREIGN KEY (id_taux_pret) REFERENCES taux_pret (id);

ALTER TABLE pret
ADD CONSTRAINT pret_id_statut_pret_foreign FOREIGN KEY (id_statut_pret) REFERENCES statut_pret (id);

ALTER TABLE pret
ADD CONSTRAINT pret_id_demande_pret_foreign FOREIGN KEY (id_demande_pret) REFERENCES demande_pret (id);

ALTER TABLE pret
ADD CONSTRAINT pret_id_foreign FOREIGN KEY (id) REFERENCES remboursement (id);

ALTER TABLE demande_pret
ADD CONSTRAINT demande_pret_id_statut_demande_foreign FOREIGN KEY (id_statut_demande) REFERENCES statut_demande (id);

ALTER TABLE taux_pret
ADD CONSTRAINT taux_pret_id_type_pret_foreign FOREIGN KEY (id_type_pret) REFERENCES type_pret (id);

ALTER TABLE demande_pret
ADD CONSTRAINT demande_pret_id_employe_foreign FOREIGN KEY (id_employe) REFERENCES employes (id);