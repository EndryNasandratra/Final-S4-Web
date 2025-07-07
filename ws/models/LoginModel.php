<?php
require_once __DIR__ . '/../db.php';

class LoginModel
{
    public static function validateUser($email, $password)
    {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM clients WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['password'] === $password) {
            return $user;
        }

        return false;
    }
}
