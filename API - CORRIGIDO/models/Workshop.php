<?php
require_once(__DIR__ . '/../config/Database.php');


class Workshop {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM workshops WHERE data >= CURDATE() ORDER BY data ASC");
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

    public function add($titulo, $descricao, $data) {
        $stmt = $this->db->prepare('INSERT INTO workshops (titulo, descricao, data) VALUES (?, ?, ?)');
        return $stmt->execute([$titulo, $descricao, $data]);
    }

    public function getInscricoesByUserId($userId) {
        $stmt = $this->db->prepare('
            SELECT w.titulo, w.data, i.status
            FROM inscricoes i
            JOIN workshops w ON i.workshop_id = w.id
            WHERE i.user_id = ?
            ORDER BY w.data
        ');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function inscrever($userId, $workshopId) {
        $stmt = $this->db->prepare('INSERT INTO inscricoes (user_id, workshop_id, status) VALUES (?, ?, "Confirmado")');
        return $stmt->execute([$userId, $workshopId]);
    }

    public function cancelarInscricao($userId, $workshopId) {
        $stmt = $this->db->prepare('DELETE FROM inscricoes WHERE user_id = ? AND workshop_id = ?');
        return $stmt->execute([$userId, $workshopId]);
    }
}
