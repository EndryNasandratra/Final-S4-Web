<?php
class LoginModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function validateUser($nom, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM clients WHERE nom = :nom");
        $stmt->bindParam(':nom', $nom);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}
