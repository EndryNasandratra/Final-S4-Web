<?php
require_once __DIR__ . '/../db.php';

class Ressource {
    public static function getAll() {
        $stmt = getDB()->query("SELECT 
                r.id, 
                r.valeur, 
                tr.libelle as type_ressource_libelle,
                r.id_type_resssource
            FROM ressources r
            LEFT JOIN type_ressource tr ON r.id_type_resssource = tr.id
            ORDER BY tr.libelle, r.valeur");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $stmt = getDB()->prepare("SELECT 
                r.id, 
                r.valeur, 
                tr.libelle as type_ressource_libelle,
                r.id_type_resssource
            FROM ressources r
            LEFT JOIN type_ressource tr ON r.id_type_resssource = tr.id
            WHERE r.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $stmt = getDB()->prepare("INSERT INTO ressources (id_type_resssource, valeur) VALUES (?, ?)");
        $stmt->execute([$data->id_type_ressource, $data->valeur]);
        
        $id = getDB()->lastInsertId();
        return self::getById($id);
    }

    public static function getTotal() {
        $stmt = getDB()->query("SELECT SUM(valeur) as total FROM ressources");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?> 