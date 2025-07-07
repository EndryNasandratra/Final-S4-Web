<?php
require 'vendor/autoload.php';
require 'db.php';
require 'routes/pret_route.php';

// Test de la connexion à la base de données
try {
    $db = getDB();
    echo "Connexion à la base de données réussie\n";
    
    // Test de la requête getValidated
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
        WHERE sp.libelle = 'Validé'");
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Nombre de prêts validés trouvés : " . count($results) . "\n";
    
    if (count($results) > 0) {
        echo "Premier prêt :\n";
        print_r($results[0]);
    }
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage() . "\n";
}
?> 