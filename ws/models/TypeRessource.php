<?php
require_once __DIR__ . '/../db.php';

class TypeRessource {
    public static function getAll() {
        $stmt = getDB()->query("SELECT id, libelle FROM type_ressource ORDER BY libelle");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $stmt = getDB()->prepare("SELECT id, libelle FROM type_ressource WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $stmt = getDB()->prepare("INSERT INTO type_ressource (libelle) VALUES (?)");
        $stmt->execute([$data->libelle]);
        
        $id = getDB()->lastInsertId();
        return self::getById($id);
    }
}
?> 