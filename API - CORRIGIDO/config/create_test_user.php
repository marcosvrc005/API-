<?php
require_once('Database.php');

try {
    $db = Database::getInstance()->getConnection();
    
    // Inserir usuário de teste
    $stmt = $db->prepare("INSERT INTO users (nome, email) VALUES (?, ?)");
    $stmt->execute(['Teste', 'teste@email.com']);

    echo "Usuário de teste criado com sucesso!\n";
    echo "Use os seguintes dados para login:\n";
    echo "Nome: Teste\n";
    echo "Email: teste@email.com\n";
} catch (PDOException $e) {
    if ($e->getCode() == 23000) { // Código de erro para duplicate entry
        echo "Usuário de teste já existe!\n";
        echo "Use os seguintes dados para login:\n";
        echo "Nome: Teste\n";
        echo "Email: teste@email.com\n";
    } else {
        die("Erro ao criar usuário de teste: " . $e->getMessage());
    }
} 