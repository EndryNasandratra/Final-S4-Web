<?php
require_once __DIR__ . '/../controllers/SimulateurController.php';

Flight::route('GET /simulateur', ['SimulationController', 'displaySimulator']);

Flight::route('POST /simulateur/calculer', ['SimulationController', 'handleCalculation']);

Flight::route('GET /type_pret', ['SimulationController', 'getAllTypePret']);
