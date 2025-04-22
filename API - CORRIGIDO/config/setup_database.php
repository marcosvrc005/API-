<?php
require_once('Database.php');

try {
    $db = Database::getInstance()->getConnection();
    
    // Criar banco de dados se não existir
    $db->exec("CREATE DATABASE IF NOT EXISTS workshop_facil");
    $db->exec("USE workshop_facil");
    
    // Criar tabela de usuários
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Criar tabela de workshops
    $db->exec("CREATE TABLE IF NOT EXISTS workshops (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(100) NOT NULL,
        descricao TEXT,
        data DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Criar tabela de inscrições
    $db->exec("CREATE TABLE IF NOT EXISTS inscricoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        workshop_id INT NOT NULL,
        status ENUM('Confirmado', 'Cancelado', 'Em espera') NOT NULL DEFAULT 'Confirmado',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (workshop_id) REFERENCES workshops(id) ON DELETE CASCADE,
        UNIQUE KEY unique_inscricao (user_id, workshop_id)
    )");
    
    echo "Banco de dados e tabelas criados com sucesso!\n";
    
} catch (PDOException $e) {
    die("Erro ao configurar banco de dados: " . $e->getMessage() . "\n");
} 