<?php
require_once __DIR__ . '/../db.php';

class Remboursement {
    public static function getAll() {
        $stmt = getDB()->query("SELECT * FROM remboursement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByPretId($pretId) {
        $stmt = getDB()->prepare("SELECT * FROM remboursement WHERE id_pret = ?");
        $stmt->execute([$pretId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $db = getDB();
        
        try {
            $db->beginTransaction();
            
            $stmt = $db->prepare("INSERT INTO remboursement (id_pret, montant_retour, date_retour) VALUES (?, ?, ?)");
            $stmt->execute([
                $data->id_pret,
                $data->montant_retour,
                $data->date_retour
            ]);
            
            $id = $db->lastInsertId();
            $db->commit();
            
            return self::getById($id);
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public static function getById($id) {
        $stmt = getDB()->prepare("SELECT * FROM remboursement WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>