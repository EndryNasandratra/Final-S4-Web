<?php
require_once __DIR__ . '/../controllers/RessourceController.php';
Flight::route('GET /all_type_ressources', ['RessourceController', 'displayFormCreationRessource']);
Flight::route('POST /create_ressource', ['RessourceController', 'createRessource']);
