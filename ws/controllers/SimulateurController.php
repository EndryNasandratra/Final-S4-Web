<?php
require_once __DIR__ . '/../models/SimulationModel.php';
require_once __DIR__ . '/../models/AppModel.php';
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

        error_log("handleCalculation - include_assurance: " . ($include_assurance ? 'true' : 'false')); // Debogage

        try {
            $result = SimulationModel::calculerSimulation($id_taux_pret, $montant, $duree_mois, $include_assurance);
            Flight::json($result);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 500;
            Flight::json(['error' => $e->getMessage()], $status);
        }
    }
    public static function calculerPretSansClient()
    {
        $data = Flight::request()->data;
        $id_taux_pret = $data['id_taux_pret'] ?? null;
        $montant = $data['montant'] ?? 0;
        $id_taux_assurance = $data['id_taux_assurance'] ?? null;

        try {
            if (!$id_taux_pret || !is_numeric($id_taux_pret)) {
                throw new Exception('Taux de prêt invalide.', 400);
            }
            if (!is_numeric($montant) || $montant <= 0) {
                throw new Exception('Montant invalide.', 400);
            }

            $db = getDB();
            $stmt = $db->prepare('
                SELECT taux_annuel, duree, borne_inf, borne_sup
                FROM taux_pret
                WHERE id = :id_taux_pret
            ');
            $stmt->execute(['id_taux_pret' => $id_taux_pret]);
            $taux_pret = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$taux_pret) {
                throw new Exception('Taux de prêt introuvable.', 400);
            }

            $taux_assurance_annuel = 0;
            if ($id_taux_assurance) {
                $stmt = $db->prepare('SELECT taux FROM taux_assurance WHERE id = :id_taux_assurance');
                $stmt->execute(['id_taux_assurance' => $id_taux_assurance]);
                $taux_assurance = $stmt->fetch(PDO::FETCH_ASSOC);
                if (!$taux_assurance) {
                    throw new Exception('Taux d\'assurance introuvable.', 400);
                }
                $taux_assurance_annuel = $taux_assurance['taux'];
            }

            $taux_mensuel = $taux_pret['taux_annuel'] / 1200;
            $duree_mois = $taux_pret['duree'];
            $mensualite_base = $montant * ($taux_mensuel / (1 - pow(1 + $taux_mensuel, -$duree_mois)));
            $mensualite_assurance = $id_taux_assurance ? ($montant * $taux_assurance_annuel / 1200) : 0;
            $mensualite_totale = $mensualite_base + $mensualite_assurance;
            $cout_total_credit = $mensualite_totale * $duree_mois - $montant;

            Flight::json([
                'duree_mois' => $duree_mois,
                'taux_interet_annuel' => $taux_pret['taux_annuel'],
                'taux_assurance_annuel' => $taux_assurance_annuel,
                'mensualite_assurance' => $mensualite_assurance,
                'mensualite_totale' => $mensualite_totale,
                'cout_total_credit' => $cout_total_credit,
                'id_taux_pret' => $id_taux_pret,
                'id_taux_assurance' => $id_taux_assurance
            ], 200);
        } catch (Exception $e) {
            error_log("Error in calculerPretSansClient: " . $e->getMessage());
            Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }

    public static function getAllTypePret()
    {
        try {
            $result = SimulationModel::getAllTypePret();
            Flight::json($result);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la recuperation des types de prets : ' . $e->getMessage()], 500);
        }
    }
    public static function getAllClients()
    {

        try {
            $result = AppModel::getAll("clients");
            Flight::json($result);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la recuperation des types de prets : ' . $e->getMessage()], 500);
        }
    }

    public static function getAllTauxPretById()
    {
        $id_type_pret = Flight::request()->query['id_type_pret'] ?? null;
        try {

            $result = SimulationModel::getAllTauxPretById($id_type_pret);

            Flight::json($result);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la recuperation des taux : ' . $e->getMessage()], 500);
        }
    }
    public static function getAllTauxPret()
    {
        try {

            $result = AppModel::getAll("taux_pret");

            Flight::json($result);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la recuperation des taux : ' . $e->getMessage()], 500);
        }
    }
    public static function getAllTauxAssurance()
    {
        try {

            $result = AppModel::getAll("taux_assurance");

            Flight::json($result);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la recuperation des taux : ' . $e->getMessage()], 500);
        }
    }

    public static function validerPret()
    {
        $data = Flight::request()->data;
        $id_taux_pret = $data['id_taux_pret'] ?? null;
        $montant = $data['montant'] ?? 0;
        $duree_mois = $data['duree_mois'] ?? 0;
        $id_client = $data["id_client"] ?? 1;
        $include_assurance = filter_var($data['include_assurance'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $id_type_ressource = $data['id_type_ressource'] ?? null;

        error_log("validerPret - include_assurance: " . ($include_assurance ? 'true' : 'false'));
        error_log("validerPret - id_type_ressource: " . $id_type_ressource);

        try {
            $result = SimulationModel::validerPret($id_taux_pret, $montant, $duree_mois, $include_assurance, $id_client);
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
        $id_type_ressource = 1;
        $year = date('Y');

        error_log("exportSimulationPDF - include_assurance: " . ($include_assurance ? 'true' : 'false'));
        error_log("exportSimulationPDF - id_type_ressource: " . $id_type_ressource);

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
                throw new Exception('Type de pret non trouve.', 400);
            }

            $stmt = $db->prepare('
                SELECT taux_annuel, duree, borne_inf, borne_sup
                FROM taux_pret
                WHERE id = :id_taux_pret
            ');
            $stmt->execute(['id_taux_pret' => $id_taux_pret]);
            $tauxPret = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$tauxPret) {
                throw new Exception('Taux de pret non trouve.', 400);
            }

            $stmt = $db->prepare('
                SELECT libelle
                FROM type_ressource
                WHERE id = :id_type_ressource
            ');
            $stmt->execute(['id_type_ressource' => $id_type_ressource]);
            $typeRessource = $stmt->fetch(PDO::FETCH_ASSOC);

            $simulationData['montant'] = (float) $montant;
            $pdfModel = new PDFModel();
            $pdfModel->generateSimulationPDF($simulationData, $typePret, $tauxPret, $typeRessource, $year);
        } catch (Exception $e) {
            $status = $e->getCode() ?: 500;
            Flight::json(['error' => $e->getMessage()], $status);
        }
    }
    public static function saveSimulation()
    {
        $data = Flight::request()->data;
        $id_taux_pret = $data['id_taux_pret'] ?? null;
        $montant = $data['montant'] ?? 0;
        $id_taux_assurance = $data['id_taux_assurance'] ?? null;

        try {
            if (!$id_taux_pret || !is_numeric($id_taux_pret)) {
                throw new Exception('Taux de prêt invalide.', 400);
            }
            if (!is_numeric($montant) || $montant <= 0) {
                throw new Exception('Montant invalide.', 400);
            }
            if (!$id_taux_assurance) {
                throw new Exception('Taux d\'assurance requis.', 400);
            }

            $simulation_id = AppModel::insert('simulation', [
                'id_taux_pret' => $id_taux_pret,
                'id_taux_assurance' => $id_taux_assurance,
                'montant_emprunte' => $montant
            ]);

            Flight::json(['success' => true, 'simulation_id' => $simulation_id], 201);
        } catch (PDOException $e) {
            error_log("Database error in saveSimulation: " . $e->getMessage());
            Flight::json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            error_log("Error in saveSimulation: " . $e->getMessage());
            Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
    public static function getAllSimulations()
    {
        try {
            Flight::json(SimulationModel::getAllSimulations());
        } catch (PDOException $e) {
            error_log("Error in getAllSimulations: " . $e->getMessage());
            Flight::json(['error' => 'Erreur lors de la récupération des simulations'], 500);
        }
    }
    public static function savePretBySimulation()
    {
        $data = Flight::request()->data;
        $id_simulation = $data['id_simulation'] ?? null;
        $id_client = $data['id_client'] ?? null;
        $date_pret = $data['date_pret'] ?? null;

        try {
            if (!$id_simulation || !is_numeric($id_simulation)) {
                throw new Exception('Simulation invalide.', 400);
            }
            if (!$id_client || !is_numeric($id_client)) {
                throw new Exception('Client invalide.', 400);
            }
            if (!$date_pret || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_pret)) {
                throw new Exception('Date de prêt invalide.', 400);
            }

            $db = getDB();
            $db->beginTransaction();
            $stmt = $db->prepare('
                SELECT id_taux_pret, id_taux_assurance, montant_emprunte
                FROM simulation
                WHERE id = :id_simulation
            ');
            $stmt->execute(['id_simulation' => $id_simulation]);
            $simulation = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$simulation) {
                throw new Exception('Simulation introuvable.', 400);
            }

            $stmt = $db->prepare('SELECT id FROM clients WHERE id = :id_client');
            $stmt->execute(['id_client' => $id_client]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception('Client introuvable.', 400);
            }
            // Selectionner la ressource par defaut (id_type_resssource = 1)
            $id_type_ressource = 1;
            $stmt = $db->prepare('
                SELECT id, valeur 
                FROM ressources 
                WHERE id_type_resssource = :id_type_ressource
            ');
            $stmt->execute(['id_type_ressource' => $id_type_ressource]);
            $ressource = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$ressource) {
                throw new Exception('Ressource par defaut non trouvee.', 400);
            }

            $newRessourceValue = $ressource['valeur'] - $simulation['montant_emprunte'];
            if ($newRessourceValue < 0) {
                throw new Exception('Solde insuffisant pour la ressource par defaut.', 400);
            }

            $pret_id = AppModel::insert('pret', [
                'id_client' => $id_client,
                'id_taux_pret' => $simulation['id_taux_pret'],
                'id_employe' => 1,
                'id_taux_assurance' => $simulation['id_taux_assurance'],
                'montant_emprunte' => $simulation['montant_emprunte'],
                'date_pret' => $date_pret,
                "id_remboursement" => 1
            ]);

            $stmt = $db->prepare('
                UPDATE ressources 
                SET valeur = :valeur 
                WHERE id = :id
            ');
            $stmt->execute([
                'valeur' => $newRessourceValue,
                'id' => $ressource['id']
            ]);
            $stmt = $db->prepare("INSERT INTO statut_pret (libelle, id_pret) VALUES (?, ?)");
            $stmt->execute(['Valide', $pret_id]);

            $db->commit();

            Flight::json(['success' => true, 'pret_id' => $pret_id], 201);
        } catch (PDOException $e) {
            $db->rollBack();
            error_log("Database error in savePret: " . $e->getMessage());
            Flight::json(['error' => 'Erreur serveur: ' . $e->getMessage()], 500);
        } catch (Exception $e) {
            error_log("Error in savePret: " . $e->getMessage());
            Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
    public static function compareSimulations()
    {
        $data = Flight::request()->data;
        $ids = $data['ids'] ?? [];

        try {
            if (!is_array($ids) || count($ids) !== 2 || !is_numeric($ids[0]) || !is_numeric($ids[1])) {
                throw new Exception('Veuillez sélectionner exactement deux simulations valides.', 400);
            }

            $db = getDB();
            $stmt = $db->prepare('
                SELECT 
                    s.id,
                    s.montant_emprunte,
                    tp.taux_annuel AS taux_pret,
                    tp.duree,
                    ta.taux AS taux_assurance
                FROM simulation s
                JOIN taux_pret tp ON s.id_taux_pret = tp.id
                LEFT JOIN taux_assurance ta ON s.id_taux_assurance = ta.id
                WHERE s.id IN (:id1, :id2)
            ');
            $stmt->execute(['id1' => $ids[0], 'id2' => $ids[1]]);
            $simulations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($simulations) !== 2) {
                throw new Exception('Une ou plusieurs simulations sélectionnées sont introuvables.', 400);
            }

            $result = [];
            foreach ($simulations as $s) {
                $taux_mensuel = $s['taux_pret'] / 1200;
                $duree_mois = $s['duree'];
                $montant = $s['montant_emprunte'];
                $mensualite_base = $montant * ($taux_mensuel / (1 - pow(1 + $taux_mensuel, -$duree_mois)));
                $mensualite_assurance = $s['taux_assurance'] ? ($montant * $s['taux_assurance'] / 1200) : 0;
                $mensualite_totale = $mensualite_base + $mensualite_assurance;
                $cout_total_credit = $mensualite_totale * $duree_mois - $montant;

                $result[] = [
                    'id' => $s['id'],
                    'montant_emprunte' => $montant,
                    'taux_pret' => $s['taux_pret'],
                    'duree' => $duree_mois,
                    'taux_assurance' => $s['taux_assurance'],
                    'mensualite_totale' => $mensualite_totale,
                    'cout_total_credit' => $cout_total_credit
                ];
            }

            Flight::json($result, 200);
        } catch (Exception $e) {
            error_log("Error in compareSimulations: " . $e->getMessage());
            Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
        }
    }
}
