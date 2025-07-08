<?php
require 'db.php';

try {
    $pdo = getDB();
    
    // Inserer des employes de test
    $pdo->exec("INSERT INTO employes (nom, prenom, email, password) VALUES 
        ('Martin', 'Sophie', 'sophie.martin@banque.com', 'password123'),
        ('Bernard', 'Paul', 'paul.bernard@banque.com', 'password123'),
        ('Leroy', 'Emma', 'emma.leroy@banque.com', 'password123')");
    
    // Inserer des clients de test
    $pdo->exec("INSERT INTO clients (nom, prenom, email, password) VALUES 
        ('Dupont', 'Jean', 'jean.dupont@email.com', 'password123'),
        ('Durand', 'Alice', 'alice.durand@email.com', 'password123'),
        ('Petit', 'Luc', 'luc.petit@email.com', 'password123')");
    
    // Inserer des types de pret
    $pdo->exec("INSERT INTO type_pret (libelle, duree_max, montant_max) VALUES 
        ('Pret personnel', 60, 50000.00),
        ('Pret immobilier', 300, 500000.00),
        ('Pret automobile', 84, 30000.00)");
    
    // Inserer des taux de pret
    $pdo->exec("INSERT INTO taux_pret (id_type_pret, taux_annuel, duree, borne_inf, borne_sup) VALUES 
        (1, 3.5, 24, 1000.00, 10000.00),
        (1, 2.9, 12, 1000.00, 5000.00),
        (2, 4.1, 36, 50000.00, 200000.00)");
    
    // Inserer des taux d'assurance
    $pdo->exec("INSERT INTO taux_assurance (taux) VALUES (0.5), (0.8), (1.2)");
    
    // Inserer des modes de remboursement
    $pdo->exec("INSERT INTO mode_remboursement (libelle, frequence_par_mois) VALUES 
        ('Mensuel', 1),
        ('Trimestriel', 3),
        ('Semestriel', 6)");
    
    // Inserer des prets de test
    $pdo->exec("INSERT INTO pret (id_client, id_employe, id_taux_pret, id_remboursement, id_taux_assurance, montant_emprunte, date_pret) VALUES 
        (1, 1, 1, 1, 1, 10000.00, '2024-06-01'),
        (2, 2, 2, 1, 2, 5000.00, '2024-05-15'),
        (3, 3, 3, 1, 3, 20000.00, '2024-04-20')");
    
    // Inserer des statuts de pret (tous valides)
    $pdo->exec("INSERT INTO statut_pret (libelle, id_pret) VALUES 
        ('Valide', 1),
        ('Valide', 2),
        ('Valide', 3)");
    
    echo "Donnees de test inserees avec succes !\n";
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?> 