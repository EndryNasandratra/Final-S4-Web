<?php
require_once __DIR__ . '/../db.php';

class TauxAssurance {
    public static function getAll() {
        $stmt = getDB()->query("SELECT id, taux FROM taux_assurance ORDER BY taux");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $stmt = getDB()->prepare("SELECT id, taux FROM taux_assurance WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>