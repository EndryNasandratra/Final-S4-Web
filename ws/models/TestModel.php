<?php

namespace models;

require '../vendor/autoload.php';
require '../db.php';

use PDO;

class TestModel
{
    final private  $db;
    public function __construct()
    {
        $this->db = getDB();
    }
    public function getAllEtudiant()
    {
        $stmt = $this->db->query("SELECT * FROM etudiant");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
