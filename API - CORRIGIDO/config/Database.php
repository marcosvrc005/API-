<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        try {
            // Primeiro tenta conectar ao MySQL
            $this->conn = new PDO("mysql:host=localhost", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Cria o banco se não existir
            $this->conn->exec("CREATE DATABASE IF NOT EXISTS workshop_facil");
            
            // Conecta ao banco específico
            $this->conn = new PDO("mysql:host=localhost;dbname=workshop_facil", "root", "");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
