<?php
// Script de configuration de la base de donnees
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connexion a MySQL sans specifier de base de donnees
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion a MySQL reussie\n";
    
    // Verifier si la base de donnees 'banque' existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'banque'");
    $exists = $stmt->rowCount() > 0;
    
    if (!$exists) {
        echo "La base de donnees 'banque' n'existe pas. Creation...\n";
        
        // Lire le fichier SQL
        $sqlFile = __DIR__ . '/../sql/script_banque.sql';
        if (file_exists($sqlFile)) {
            $sql = file_get_contents($sqlFile);
            
            // Executer le script SQL
            $pdo->exec($sql);
            echo "Base de donnees 'banque' creee avec succes\n";
        } else {
            echo "Erreur: Fichier SQL non trouve: $sqlFile\n";
        }
    } else {
        echo "La base de donnees 'banque' existe deja\n";
    }
    
    // Tester la connexion a la base de donnees 'banque'
    $pdo = new PDO("mysql:host=$host;dbname=banque", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connexion a la base de donnees 'banque' reussie\n";
    
    // Verifier les tables
    $tables = ['clients', 'employes', 'pret', 'statut_pret', 'taux_pret', 'type_pret'];
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "Table '$table' existe\n";
        } else {
            echo "Table '$table' n'existe pas\n";
        }
    }
    
    // Inserer des donnees de test si les tables sont vides
    $stmt = $pdo->query("SELECT COUNT(*) FROM statut_pret");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        echo "Insertion de donnees de test...\n";
        
        // Inserer des statuts de test
        $pdo->exec("INSERT INTO statut_pret (libelle, id_pret) VALUES ('En attente', 1), ('Valide', 1), ('Refuse', 1)");
        echo "Statuts de test inseres\n";
    }
    
} catch (PDOException $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
}
?> 