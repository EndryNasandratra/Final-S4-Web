<?php
require_once __DIR__ . '/../models/Pret.php';

class PretController {
    public static function getAll() {
        $prets = Pret::getAll();
        Flight::json($prets);
    }

    public static function getValidated() {
        $prets = Pret::getValidated();
        Flight::json($prets);
    }

    public static function getNotValidated() {
        $prets = Pret::getNotValidated();
        Flight::json($prets);
    }

    public static function getById($id) {
        $pret = Pret::getById($id);
        Flight::json($pret);
    }

    public static function validerPret($id) {
        try {
            $result = Pret::validerPret($id);
            if ($result) {
                Flight::json(['success' => true, 'message' => 'Pret valide avec succes']);
            } else {
                Flight::json(['error' => 'Erreur lors de la validation du pret'], 500);
            }
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function rejeterPret($id) {
        try {
            $data = Flight::request()->data;
            $raison = isset($data->raison) ? $data->raison : '';
            
            $result = Pret::rejeterPret($id, $raison);
            if ($result) {
                Flight::json(['success' => true, 'message' => 'Pret rejete avec succes']);
            } else {
                Flight::json(['error' => 'Erreur lors du rejet du pret'], 500);
            }
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function create() {
        $data = Flight::request()->data;
        
        // Validation des donnees (id_client peut etre null)
        if (empty($data->id_employe) || empty($data->id_taux_pret) || 
            empty($data->id_taux_assurance) || empty($data->montant_emprunte) || 
            empty($data->date_pret)) {
            Flight::json(['error' => 'Tous les champs obligatoires doivent etre remplis'], 400);
            return;
        }

        // Recuperer l'ID du mode de remboursement mensuel
        try {
            $stmt = getDB()->prepare("SELECT id FROM mode_remboursement WHERE libelle = 'Mensuel' LIMIT 1");
            $stmt->execute();
            $modeRemboursement = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$modeRemboursement) {
                Flight::json(['error' => 'Mode de remboursement mensuel non trouve'], 500);
                return;
            }
            
            $data->id_remboursement = $modeRemboursement['id'];
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la recuperation du mode de remboursement'], 500);
            return;
        }
        
        // Convertir id_client en null si vide
        if (empty($data->id_client)) {
            $data->id_client = null;
        }
        
        try {
            $pret = Pret::create($data);
            
            if ($pret === false) {
                Flight::json(['error' => 'Erreur lors de la recuperation du pret cree'], 500);
                return;
            }
            
            Flight::json(['success' => true, 'data' => $pret], 201);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

}
?>
