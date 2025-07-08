<?php
require 'vendor/autoload.php';
require 'db.php';

try {
    $pdo = getDB();
    echo "Connexion a la base de donnees reussie\n";
    
    // Verifier les modes de remboursement
    echo "\n=== Verification des modes de remboursement ===\n";
    
    $stmt = $pdo->query("SELECT * FROM mode_remboursement");
    $modes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($modes) == 0) {
        echo "Aucun mode de remboursement trouve. Insertion des modes par defaut...\n";
        
        $pdo->exec("INSERT INTO mode_remboursement (libelle, frequence_par_mois) VALUES 
            ('Mensuel', 1),
            ('Trimestriel', 3),
            ('Semestriel', 6),
            ('Annuel', 12)");
        
        echo "Modes de remboursement inseres avec succes!\n";
    } else {
        echo "Modes de remboursement existants:\n";
        foreach ($modes as $mode) {
            echo "- ID: {$mode['id']}, Libelle: {$mode['libelle']}, Frequence: {$mode['frequence_par_mois']} mois\n";
        }
    }
    
    // Verifier que l'ID 1 existe
    $stmt = $pdo->prepare("SELECT * FROM mode_remboursement WHERE id = 1");
    $stmt->execute();
    $mode1 = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($mode1) {
        echo "\nMode de remboursement ID 1 trouve: {$mode1['libelle']}\n";
    } else {
        echo "\nERREUR: Mode de remboursement ID 1 non trouve!\n";
        echo "Creation d'un mode mensuel avec ID 1...\n";
        
        // Supprimer tous les modes et recreer avec ID 1
        $pdo->exec("DELETE FROM mode_remboursement");
        $pdo->exec("ALTER TABLE mode_remboursement AUTO_INCREMENT = 1");
        $pdo->exec("INSERT INTO mode_remboursement (libelle, frequence_par_mois) VALUES 
            ('Mensuel', 1),
            ('Trimestriel', 3),
            ('Semestriel', 6),
            ('Annuel', 12)");
        
        echo "Modes de remboursement recrees avec succes!\n";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?> 