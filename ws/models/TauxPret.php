<?php
require_once __DIR__ . '/../db.php';

class TauxPret {
    public static function getAll() {
        $stmt = getDB()->query("SELECT id, taux_annuel, duree FROM taux_pret ORDER BY taux_annuel");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $stmt = getDB()->prepare("SELECT id, taux_annuel, duree FROM taux_pret WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>