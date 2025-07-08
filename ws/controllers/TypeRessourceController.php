<?php
require_once __DIR__ . '/../models/TypeRessource.php';

class TypeRessourceController {
    public static function getAll() {
        $types = TypeRessource::getAll();
        Flight::json($types);
    }

    public static function getById($id) {
        $type = TypeRessource::getById($id);
        Flight::json($type);
    }

    public static function create() {
        $data = Flight::request()->data;
        
        if (empty($data->libelle)) {
            Flight::json(['error' => 'Le libelle est obligatoire'], 400);
            return;
        }
        
        try {
            $type = TypeRessource::create($data);
            Flight::json(['success' => true, 'data' => $type], 201);
        } catch (Exception $e) {
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }
}
?> 