<?php
require_once __DIR__ . '/../controllers/TypeRessourceController.php';
require_once __DIR__ . '/../controllers/RessourceController.php';

// Routes pour les types de ressources
Flight::route('GET /types-ressource', ['TypeRessourceController', 'getAll']);
Flight::route('GET /types-ressource/@id', ['TypeRessourceController', 'getById']);
Flight::route('POST /types-ressource', ['TypeRessourceController', 'create']);

// Routes pour les ressources
Flight::route('GET /ressources', ['RessourceController', 'getAll']);
Flight::route('GET /ressources/@id', ['RessourceController', 'getById']);
Flight::route('POST /ressources', ['RessourceController', 'create']);
Flight::route('GET /ressources/total', ['RessourceController', 'getTotal']);

?> 