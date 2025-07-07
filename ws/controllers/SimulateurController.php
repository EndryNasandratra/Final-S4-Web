<?php
require_once __DIR__ . '/../models/SimulationModel.php';
require_once __DIR__ . '/../models/PDFModel.php';

class SimulationController
{

    public static function displaySimulator()
    {
        Flight::render('views/simulateur/simulateur.php');
    }

    public static function handleCalculation()
    {
        $data = Flight::request()->data;
        $id_taux_pret = $data['id_taux_pret'] ?? null;
        $montant = $data['montant'] ?? 0;
        $duree_mois = $data['duree_mois'] ?? 0;
        $include_assurance = filter_var($data['include_assurance'] ?? false, FILTER_VALIDATE_BOOLEAN);

        error_log("handleCalculation - include_assurance: " . ($include_assurance ? 'true' : 'false')); // Débogage

        try {
            $result = SimulationModel::calculerSimulation($id_taux_pret, $montant, $duree_mois, $include_assurance);
            Flight::json($result);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 500;
            Flight::json(['error' => $e->getMessage()], $status);
        }
    }

    public static function getAllTypePret()
    {
        try {
            $result = SimulationModel::getAllTypePret();
            Flight::json($result);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la récupération des types de prêts : ' . $e->getMessage()], 500);
        }
    }

    public static function getAllTauxPretById()
    {
        $id_type_pret = Flight::request()->query['id_type_pret'] ?? null;
        try {
            $result = SimulationModel::getAllTauxPretById($id_type_pret);
            Flight::json($result);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la récupération des taux : ' . $e->getMessage()], 500);
        }
    }

    public static function validerPret()
    {
        $data = Flight::request()->data;
        $id_taux_pret = $data['id_taux_pret'] ?? null;
        $montant = $data['montant'] ?? 0;
        $duree_mois = $data['duree_mois'] ?? 0;
        $include_assurance = filter_var($data['include_assurance'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $id_type_ressource = $data['id_type_ressource'] ?? null;

        error_log("validerPret - include_assurance: " . ($include_assurance ? 'true' : 'false')); // Débogage
        error_log("validerPret - id_type_ressource: " . $id_type_ressource); // Débogage

        try {
            $result = SimulationModel::validerPret($id_taux_pret, $montant, $duree_mois, $include_assurance, $id_type_ressource);
            Flight::json($result);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 500;
            Flight::json(['error' => $e->getMessage()], $status);
        }
    }

    public static function exportSimulationPDF()
    {
        $data = Flight::request()->data;
        $id_taux_pret = $data['id_taux_pret'] ?? null;
        $montant = $data['montant'] ?? 0;
        $duree_mois = $data['duree_mois'] ?? 0;
        $include_assurance = filter_var($data['include_assurance'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $id_type_ressource = $data['id_type_ressource'] ?? null;
        $year = date('Y');

        error_log("exportSimulationPDF - include_assurance: " . ($include_assurance ? 'true' : 'false')); // Débogage
        error_log("exportSimulationPDF - id_type_ressource: " . $id_type_ressource); // Débogage

        try {
            $simulationData = SimulationModel::calculerSimulation($id_taux_pret, $montant, $duree_mois, $include_assurance);

            $db = getDB();
            $stmt = $db->prepare('
                SELECT tp.libelle
                FROM type_pret tp
                JOIN taux_pret tpr ON tp.id = tpr.id_type_pret
                WHERE tpr.id = :id_taux_pret
            ');
            $stmt->execute(['id_taux_pret' => $id_taux_pret]);
            $typePret = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$typePret) {
                throw new Exception('Type de prêt non trouvé.', 400);
            }

            $stmt = $db->prepare('
                SELECT taux_annuel, duree, borne_inf, borne_sup
                FROM taux_pret
                WHERE id = :id_taux_pret
            ');
            $stmt->execute(['id_taux_pret' => $id_taux_pret]);
            $tauxPret = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$tauxPret) {
                throw new Exception('Taux de prêt non trouvé.', 400);
            }

            $stmt = $db->prepare('
                SELECT libelle
                FROM type_ressource
                WHERE id = :id_type_ressource
            ');
            $stmt->execute(['id_type_ressource' => $id_type_ressource]);
            $typeRessource = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$typeRessource) {
                throw new Exception('Type de ressource non trouvé.', 400);
            }

            $simulationData['montant'] = (float) $montant;

            PDFModel::generateSimulationPDF($simulationData, $typePret, $tauxPret, $typeRessource, $year);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 500;
            Flight::json(['error' => $e->getMessage()], $status);
        }
    }
}
