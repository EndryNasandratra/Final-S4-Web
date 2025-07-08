<?php
require_once __DIR__ . '/../controllers/InteretController.php';

Flight::route('GET /interets_mensuels', ['InteretController', 'showInterestsReport']);
Flight::route('GET /filtrer_interets_mensuels', ['InteretController', 'getDetailedFilteredInterestsReport']);
