<?php
require 'vendor/autoload.php';
require 'db.php';

try {
    $pdo = getDB();
    echo "Connexion à la base de données réussie\n";
    
    // Vérifier les modes de remboursement
    echo "\n=== Vérification des modes de remboursement ===\n";
    
    $stmt = $pdo->query("SELECT * FROM mode_remboursement");
    $modes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($modes) == 0) {
        echo "Aucun mode de remboursement trouvé. Insertion des modes par défaut...\n";
        
        $pdo->exec("INSERT INTO mode_remboursement (libelle, frequence_par_mois) VALUES 
            ('Mensuel', 1),
            ('Trimestriel', 3),
            ('Semestriel', 6),
            ('Annuel', 12)");
        
        echo "Modes de remboursement insérés avec succès!\n";
    } else {
        echo "Modes de remboursement existants:\n";
        foreach ($modes as $mode) {
            echo "- ID: {$mode['id']}, Libellé: {$mode['libelle']}, Fréquence: {$mode['frequence_par_mois']} mois\n";
        }
    }
    
    // Vérifier que l'ID 1 existe
    $stmt = $pdo->prepare("SELECT * FROM mode_remboursement WHERE id = 1");
    $stmt->execute();
    $mode1 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($mode1) {
        echo "\nMode de remboursement ID 1 trouvé: {$mode1['libelle']}\n";
    } else {
        echo "\nERREUR: Mode de remboursement ID 1 non trouvé!\n";
        echo "Création d'un mode mensuel avec ID 1...\n";
        
        // Supprimer tous les modes et recréer avec ID 1
        $pdo->exec("DELETE FROM mode_remboursement");
        $pdo->exec("ALTER TABLE mode_remboursement AUTO_INCREMENT = 1");
        $pdo->exec("INSERT INTO mode_remboursement (libelle, frequence_par_mois) VALUES 
            ('Mensuel', 1),
            ('Trimestriel', 3),
            ('Semestriel', 6),
            ('Annuel', 12)");
        
        echo "Modes de remboursement recréés avec succès!\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?> 