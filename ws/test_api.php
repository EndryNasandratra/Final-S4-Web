<?php
require 'vendor/autoload.php';
require 'db.php';
require 'routes/pret_route.php';

// Test de la connexion a la base de donnees
try {
    $db = getDB();
    echo "Connexion a la base de donnees reussie\n";
    
    // Test de la requete getValidated
    $stmt = $db->query("SELECT 
            p.id,
            c.nom as client_nom,
            c.prenom as client_prenom,
            e.nom as employe_nom,
            e.prenom as employe_prenom,
            tp.taux_annuel as taux,
            p.montant_emprunte as montant,
            p.date_pret as date_debut,
            tp.duree,
            tpret.libelle as type_pret
        FROM pret p
        LEFT JOIN clients c ON p.id_client = c.id
        INNER JOIN employes e ON p.id_employe = e.id
        INNER JOIN taux_pret tp ON p.id_taux_pret = tp.id
        INNER JOIN type_pret tpret ON tp.id_type_pret = tpret.id
        INNER JOIN statut_pret sp ON p.id = sp.id_pret
        WHERE sp.libelle = 'Valide'");
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Nombre de prets valides trouves : " . count($results) . "\n";
    
    if (count($results) > 0) {
        echo "Premier pret :\n";
        print_r($results[0]);
    }
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
?> 