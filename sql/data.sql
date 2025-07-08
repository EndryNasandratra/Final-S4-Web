INSERT INTO clients (nom, prenom, email, password) VALUES
('Dupont', 'Jean', 'jean.dupont@email.com', SHA2('password123', 256)),
('Martin', 'Sophie', 'sophie.martin@email.com', SHA2('securepass', 256)),
('Bernard', 'Pierre', 'pierre.bernard@email.com', SHA2('bernard123', 256)),
('Petit', 'Marie', 'marie.petit@email.com', SHA2('marie2023', 256)),
('Moreau', 'Luc', 'luc.moreau@email.com', SHA2('lucmoreau', 256));

INSERT INTO employes (nom, prenom, email, password) VALUES
('Leroy', 'Thomas', 'thomas.leroy@banque.com', SHA2('emp123', 256)),
('Roux', 'Isabelle', 'isabelle.roux@banque.com', SHA2('emp456', 256)),
('Fournier', 'Nicolas', 'nicolas.fournier@banque.com', SHA2('emp789', 256));

INSERT INTO type_ressource (libelle) VALUES
('Salaire'), -- Salaire
('Revenus locatifs'), -- Revenus locatifs
('Investissements'), -- Investissements
('Autres revenus'), -- Autres revenus
('epargne'); -- epargne

INSERT INTO ressources (id_type_resssource, valeur) VALUES
(1, 2500.00),
(1, 3200.50),
(2, 1200.00),
(3, 500.75),
(4, 300.25),
(5, 15000.00),
(5, 8000.00);

INSERT INTO historique_ressource (id_ressource, valeur, date_historique) VALUES
(1, 2400, '2023-01-01'),
(1, 2450, '2023-02-01'),
(1, 2500, '2023-03-01'),
(2, 3100, '2023-01-01'),
(2, 3150, '2023-02-01'),
(2, 3200, '2023-03-01'),
(6, 14000, '2023-01-01'),
(6, 14500, '2023-02-01'),
(6, 15000, '2023-03-01');

INSERT INTO type_pret (libelle, duree_max, montant_max) VALUES
('Pret immobilier', 360, 500000.00),
('Pret automobile', 84, 50000.00),
('Pret personnel', 60, 30000.00),
('Pret travaux', 120, 75000.00);

INSERT INTO taux_pret (id_type_pret, taux_annuel, duree, borne_inf, borne_sup) VALUES
(1, 2.50, 180, 0, 200000),
(1, 2.30, 240, 200001, 350000),
(1, 2.10, 300, 350001, 500000),
(2, 3.50, 36, 0, 25000),
(2, 3.25, 48, 25001, 50000),
(3, 5.00, 24, 0, 15000),
(3, 4.75, 36, 15001, 30000),
(4, 3.75, 60, 0, 50000),
(4, 3.50, 84, 50001, 75000);

INSERT INTO taux_assurance (taux) VALUES
(0.25),
(0.30),
(0.35),
(0.40);

INSERT INTO mode_remboursement (libelle, frequence_par_mois) VALUES
('Mensuel', 1),
('Bimensuel', 2),
('Trimestriel', 3),
('Semestriel', 6);

INSERT INTO employes (nom, prenom, email, password) VALUES
('Leroy', 'Thomas', 'thomas.leroy@banque.com', SHA2('emp123', 256)),
('Roux', 'Isabelle', 'isabelle.roux@banque.com', SHA2('emp456', 256)),
('Fournier', 'Nicolas', 'nicolas.fournier@banque.com', SHA2('emp789', 256));

INSERT INTO pret (id_client, id_employe, id_taux_pret, id_remboursement, id_taux_assurance, montant_emprunte, date_pret) VALUES
(1, 1, 1, 1, 1, 180000.00, '2023-01-15'),
(2, 2, 5, 1, 2, 22000.00, '2023-02-10'),
(3, 3, 7, 2, 3, 20000.00, '2023-03-05'),
(4, 1, 9, 1, 1, 40000.00, '2023-01-20'),
(4, 2, 3, 1, 4, 400000.00, '2023-02-28');

INSERT INTO statut_pret (libelle, id_pret) VALUES
('En cours', 1),
('En cours', 2),
('Valide', 3),
('Rembourse', 4),
('En attente', 5);

INSERT INTO remboursement (id_pret, montant_retour, date_retour) VALUES
(1, 1000000.00, '2025-06-30'),
(1, 1000000.00, '2025-07-30'),
(2, 6000000.00, '2025-05-15'),
(2, 6000000.00, '2025-06-15');
-- Clients
