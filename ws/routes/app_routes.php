<?php
require_once __DIR__ . '/../controllers/AppController.php';
Flight::route('POST /addType_pret', ['AppController', 'createTypePret']);
Flight::route('POST /addTaux_pret', ['AppController', 'createTauxPret']);
