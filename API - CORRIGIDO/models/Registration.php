<?php
require_once(__DIR__ . '/../config/Database.php');
require_once(__DIR__ . '/Workshop.php');

class Registration {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($usuarioId, $workshopId) {
        try {
            error_log("Tentando registrar usuário $usuarioId no workshop $workshopId");
            
            // Verifica se workshop existe
            $workshop = (new Workshop())->getById($workshopId);
            if (!$workshop) {
                error_log("Workshop $workshopId não encontrado");
                return false; // Workshop inválido
            }

            // Verifica se já está inscrito
            if ($this->isAlreadyRegistered($usuarioId, $workshopId)) {
                error_log("Usuário $usuarioId já está inscrito no workshop $workshopId");
                return false; // Já inscrito
            }

            $stmt = $this->db->prepare("INSERT INTO inscricoes (usuario_id, workshop_id) VALUES (?, ?)");
            $result = $stmt->execute([$usuarioId, $workshopId]);
            
            if ($result) {
                error_log("Inscrição realizada com sucesso");
            } else {
                error_log("Erro ao executar query de inscrição: " . print_r($stmt->errorInfo(), true));
            }
            
            return $result;

        } catch (PDOException $e) {
            error_log("Erro PDO ao registrar inscrição: " . $e->getMessage());
            return false;
        }
    }

    private function isAlreadyRegistered($usuarioId, $workshopId) {
        $stmt = $this->db->prepare("SELECT id FROM inscricoes WHERE usuario_id = ? AND workshop_id = ?");
        $stmt->execute([$usuarioId, $workshopId]);
        return $stmt->fetch() ? true : false;
    }
}