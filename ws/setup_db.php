<?php
// Script de configuration de la base de données
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connexion à MySQL sans spécifier de base de données
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à MySQL réussie\n";
    
    // Vérifier si la base de données 'banque' existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'banque'");
    $exists = $stmt->rowCount() > 0;
    
    if (!$exists) {
        echo "La base de données 'banque' n'existe pas. Création...\n";
        
        // Lire le fichier SQL
        $sqlFile = __DIR__ . '/../sql/script_banque.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            
            // Exécuter le script SQL
            $pdo->exec($sql);
            echo "Base de données 'banque' créée avec succès\n";
        } else {
            echo "Erreur: Fichier SQL non trouvé: $sqlFile\n";
        }
    } else {
        echo "La base de données 'banque' existe déjà\n";
    }
    
    // Tester la connexion à la base de données 'banque'
    $pdo = new PDO("mysql:host=$host;dbname=banque", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion à la base de données 'banque' réussie\n";
    
    // Vérifier les tables
    $tables = ['clients', 'employes', 'pret', 'statut_pret', 'taux_pret', 'type_pret'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "Table '$table' existe\n";
        } else {
            echo "Table '$table' n'existe pas\n";
        }
    }
    
    // Insérer des données de test si les tables sont vides
    $stmt = $pdo->query("SELECT COUNT(*) FROM statut_pret");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        echo "Insertion de données de test...\n";
        
        // Insérer des statuts de test
        $pdo->exec("INSERT INTO statut_pret (libelle, id_pret) VALUES ('En attente', 1), ('Validé', 1), ('Refusé', 1)");
        echo "Statuts de test insérés\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?> 