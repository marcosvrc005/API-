<?php
require_once 'config/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($nome, $email) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email) VALUES (?, ?)");
        return $stmt->execute([$nome, $email]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
