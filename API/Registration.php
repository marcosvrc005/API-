<?php
require_once 'config/Database.php';
require_once 'models/Workshop.php';

class Registration {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($usuarioId, $workshopId) {
        $this->db->beginTransaction();
        try {
            $workshop = (new Workshop())->getById($workshopId);

            $stmt = $this->db->prepare("INSERT INTO inscricoes (usuario_id, workshop_id) VALUES (?, ?)");
            $stmt->execute([$usuarioId, $workshopId]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
