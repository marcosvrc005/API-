<?php
require_once(__DIR__ . '/../config/Database.php');

class User {
    private $db;

    public function __construct() {
        try {
            $this->db = Database::getInstance()->getConnection();
            error_log("Conexão com o banco estabelecida no modelo User");
        } catch (Exception $e) {
            error_log("Erro ao conectar com o banco no modelo User: " . $e->getMessage());
            throw $e;
        }
    }

    public function create($nome, $email) {
        try {
            // Verifica se o email já existe
            if ($this->findByEmail($email)) {
                error_log("Tentativa de criar usuário com email já existente: $email");
                return ['success' => false, 'error' => 'Email já cadastrado'];
            }

            $stmt = $this->db->prepare("INSERT INTO users (nome, email) VALUES (?, ?)");
            $success = $stmt->execute([$nome, $email]);
            
            if ($success) {
                $id = $this->db->lastInsertId();
                error_log("Usuário criado com sucesso. ID: $id");
                return ['success' => true, 'id' => $id];
            } else {
                error_log("Erro ao criar usuário: " . print_r($stmt->errorInfo(), true));
                return ['success' => false, 'error' => 'Erro ao criar usuário'];
            }
        } catch (PDOException $e) {
            error_log("Erro PDO ao criar usuário: " . $e->getMessage());
            return ['success' => false, 'error' => 'Erro interno ao criar usuário'];
        }
    }

    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por email: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            error_log("Iniciando busca de usuários");
            
            $stmt = $this->db->prepare("SELECT id, nome, email, created_at FROM users ORDER BY nome");
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Número de usuários encontrados: " . count($users));
            
            if ($users === false) {
                error_log("Erro ao listar usuários: " . print_r($stmt->errorInfo(), true));
                return [];
            }
            
            return $users;
        } catch (PDOException $e) {
            error_log("Erro PDO ao listar usuários: " . $e->getMessage());
            return [];
        }
    }

    public function register($nome, $email, $senha) {
        // Verificar se o email já existe
        $stmt = $this->db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return false; // Email já cadastrado
        }

        // Hash da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Inserir novo usuário
        $stmt = $this->db->prepare('INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)');
        return $stmt->execute([$nome, $email, $senhaHash]);
    }

    public function login($nome, $email) {
        $stmt = $this->db->prepare('SELECT id, nome FROM users WHERE nome = ? AND email = ?');
        $stmt->execute([$nome, $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT id, nome, email FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $nome, $email) {
        $stmt = $this->db->prepare('UPDATE users SET nome = ?, email = ? WHERE id = ?');
        return $stmt->execute([$nome, $email, $id]);
    }

    public function updatePassword($id, $novaSenha) {
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare('UPDATE users SET senha = ? WHERE id = ?');
        return $stmt->execute([$senhaHash, $id]);
    }
}