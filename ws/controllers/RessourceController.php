<?php

require_once __DIR__ . '/../models/AppModel.php';
require_once __DIR__ . '/../helpers/Utils.php';
class RessourceController
{
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
}
