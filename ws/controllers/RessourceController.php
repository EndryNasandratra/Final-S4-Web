<?php
require_once __DIR__ . '/../models/AppModel.php';
require_once __DIR__ . '/../models/Ressource.php';

class RessourceController
{
    public static function getAll()
    {
        $ressources = Ressource::getAll();
        Flight::json($ressources);
    }

    public static function getById($id)
    {
        $ressource = Ressource::getById($id);
        Flight::json($ressource);
    }

    public static function create()
    {
        $data = Flight::request()->data;

        if (empty($data->id_type_ressource) || empty($data->valeur)) {
            Flight::json(['error' => 'Le type et la valeur sont obligatoires'], 400);
            return;
        }

        try {
            $ressource = Ressource::create($data);
            Flight::json(['success' => true, 'data' => $ressource], 201);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function getTotal()
    {
        try {
            $total = Ressource::getTotal();
            Flight::json(['success' => true, 'data' => ['total' => $total]]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public static function displayFormCreationRessource()
    {
        try {
            $types = AppModel::getAll('type_ressource');
            Flight::json($types);
        } catch (Exception $e) {
            Flight::json(['error' => 'Impossible de récupérer les types de ressources', 'message' => $e->getMessage()], 500);
        }
    }
    public static function createRessource()
    {
        $data = Flight::request()->data;

        if (empty($data->id_type_ressource) || !isset($data->valeur)) {
            Flight::json(['error' => 'Données manquantes.'], 400);
            return;
        }
        try {
            $ressource = AppModel::getById("ressources", "id_type_resssource", $data->id_type_ressource);
            $newRessouceValue =  $data->valeur + $ressource["valeur"];
            $now = new DateTime();
            $date = $now->format('Y-m-d');
            AppModel::insert('historique_ressource', [
                'id_ressource' => $ressource["id"],
                'valeur' => $data->valeur,
                "estEntree" => true,
                "date_historique" => $date
            ]);

            AppModel::update("ressources", ["valeur" => $newRessouceValue, "id" => $ressource["id"]], "id");

            Flight::json(['status' => 'success', 'message' => 'Ressource ajoutée avec succès !'], 201);
        } catch (Exception $e) {
            Flight::json(['error' => 'Erreur lors de la création de la ressource', 'message' => $e->getMessage()], 500);
        }
    }
    public static function diminuerRessource($montant)
    {
        $data = Flight::request()->data;
        $ressource = AppModel::getById("ressources", "id_type_resssource", $data->id_type_ressource);
        $newRessouceValue =   $ressource["valeur"] - $data->valeur;
        if ($newRessouceValue < 0) {
            return false;
        }
        $now = new DateTime();
        $date = $now->format('Y-m-d');
        AppModel::insert('historique_ressource', [
            'id_ressource' => $ressource["id"],
            'valeur' => $data->valeur,
            "estEntree" => false,
            "date_historique" => $date
        ]);

        AppModel::update("ressources", ["valeur" => $newRessouceValue, "id" => $ressource["id"]], "id");

        Flight::json(['status' => 'success', 'message' => 'Ressource ajoutée avec succès !'], 201);
    }
}
