<?php
require_once 'config/Database.php';

class Workshop {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM workshops WHERE data >= CURDATE()");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM workshops WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($titulo, $descricao, $data) {
        $stmt = $this->db->prepare("INSERT INTO workshops (titulo, descricao, data) VALUES (?, ?, ?)");
        return $stmt->execute([$titulo, $descricao, $data]);
    }
}
