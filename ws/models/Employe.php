<?php
require_once __DIR__ . '/../db.php';

class Employe {
    public static function getAll() {
        $stmt = getDB()->query("SELECT id, nom, prenom, email FROM employes ORDER BY nom, prenom");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $stmt = getDB()->prepare("SELECT id, nom, prenom, email FROM employes WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>