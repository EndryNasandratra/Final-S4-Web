<?php
require_once __DIR__ . '/../db.php';

class TestModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAllEtudiant(): array
    {
        $stmt = $this->db->query("SELECT * FROM etudiant");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
