<?php
require_once __DIR__ . '/../models/Ressource.php';

class RessourceController {
    public static function getAll() {
        $ressources = Ressource::getAll();
        Flight::json($ressources);
    }

    public static function getById($id) {
        $ressource = Ressource::getById($id);
        Flight::json($ressource);
    }

    public static function create() {
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

    public static function getTotal() {
        try {
            $total = Ressource::getTotal();
            Flight::json(['success' => true, 'data' => ['total' => $total]]);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
?> 
