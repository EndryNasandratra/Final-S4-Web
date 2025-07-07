-- Clients
INSERT INTO clients (nom, prenom, email, password) VALUES
('Randria', 'Mickael', 'mickael@email.com', 'pass1'),
('Rajaona', 'Fanja', 'fanja@email.com', 'pass2'),
('Rakoto', 'Jean', 'jean@email.com', 'pass3'),
('Andria', 'Lova', 'lova@email.com', 'pass4'),
('Rasoanaivo', 'Sarah', 'sarah@email.com', 'pass5');

-- Employés
INSERT INTO employes (nom, prenom, email, password) VALUES
('Andrianina', 'Marie', 'marie@banque.com', 'admin1'),
('Rabetsaroana', 'Eric', 'eric@banque.com', 'admin2');

-- Type de ressource (ex: 1 = liquide, 2 = actifs immobilisés)
INSERT INTO type_ressource (libelle) VALUES
(1),
(2);

-- Ressources (fonds disponibles)
INSERT INTO ressources (id_type_resssource, valeur) VALUES
(1, 10000000.00),
(1, 25000000.00),
(2, 6000000.00);

-- Historique des ressources (évolution dans le temps)
INSERT INTO historique_ressource (id_ressource, valeur, date_historique) VALUES
(1, 10000000, '2025-01-01'),
(1, 10500000, '2025-02-01'),
(2, 25000000, '2025-03-01'),
(3, 6000000, '2025-01-10');

-- Types de prêt
INSERT INTO type_pret (libelle, duree_max, montant_max) VALUES
('Prêt personnel', 36, 5000000),
('Crédit immobilier', 120, 100000000),
('Crédit auto', 60, 30000000);

-- Taux de prêt
INSERT INTO taux_pret (id_type_pret, taux_annuel, duree, borne_inf, borne_sup) VALUES
(1, 7.5, 12, 1000, 2000000),
(1, 9.0, 24, 2000001, 5000000),
(2, 5.5, 60, 5000000, 50000000),
(2, 6.0, 120, 50000001, 100000000),
(3, 8.0, 36, 1000000, 15000000),
(3, 7.2, 60, 15000001, 30000000);

-- Mode de remboursement
INSERT INTO mode_remboursement (libelle, frequence_par_mois) VALUES
('Mensuel', 1),
('Bimensuel', 2),
('Trimestriel', 0);

-- Statut de prêt (optionnel, peut être ajouté si tu ajoutes ce champ)
INSERT INTO statut_pret (libelle) VALUES
('En cours'),
('Terminé'),
('En retard');

-- Prêts
INSERT INTO pret (id_client, id_employe, id_taux_pret, id_remboursement, montant_emprunte, date_pret) VALUES
(1, 1, 1, 1, 2000000.00, '2025-06-01'),
(2, 1, 3, 2, 75000000.00, '2025-05-15'),
(3, 2, 5, 1, 12000000.00, '2025-06-20'),
(4, 1, 2, 1, 3500000.00, '2025-04-10'),
(5, 2, 6, 2, 18000000.00, '2025-03-25');

-- Remboursements (exemples)
INSERT INTO remboursement (id_pret, montant_retour, date_retour) VALUES
(1, 1000000.00, '2025-06-30'),
(1, 1000000.00, '2025-07-30'),
(2, 6000000.00, '2025-05-15'),
(2, 6000000.00, '2025-06-15');
