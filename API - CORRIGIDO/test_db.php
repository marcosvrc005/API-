<?php
// Habilitar exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config/Database.php');

try {
    // Conectar ao banco de dados
    $db = Database::getInstance()->getConnection();
    echo "<h3>Status da Conexão:</h3>";
    echo "✅ Conectado ao banco de dados com sucesso!<br><br>";

    // Verificar se a tabela existe
    $tables = $db->query("SHOW TABLES LIKE 'usuarios'")->fetchAll();
    if (empty($tables)) {
        // Criar a tabela de usuários
        $db->exec("CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "✅ Tabela 'usuarios' criada com sucesso!<br><br>";
    } else {
        echo "✅ Tabela 'usuarios' já existe!<br><br>";
    }

    // Inserir dados de teste
    $usuarios_teste = [
        ['nome' => 'João Silva', 'email' => 'joao@teste.com'],
        ['nome' => 'Maria Santos', 'email' => 'maria@teste.com'],
        ['nome' => 'Pedro Oliveira', 'email' => 'pedro@teste.com']
    ];

    echo "<h3>Inserindo usuários de teste:</h3>";
    $stmt = $db->prepare("INSERT IGNORE INTO usuarios (nome, email) VALUES (?, ?)");
    foreach ($usuarios_teste as $usuario) {
        try {
            $stmt->execute([$usuario['nome'], $usuario['email']]);
            echo "✅ Usuário '{$usuario['nome']}' inserido/verificado<br>";
        } catch (PDOException $e) {
            echo "❌ Erro ao inserir {$usuario['nome']}: " . $e->getMessage() . "<br>";
        }
    }

    // Verificar usuários cadastrados
    echo "<br><h3>Usuários cadastrados no banco:</h3>";
    $stmt = $db->query("SELECT * FROM usuarios ORDER BY nome");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($usuarios)) {
        echo "❌ Nenhum usuário encontrado no banco de dados.";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Data de Cadastro</th></tr>";
        foreach ($usuarios as $usuario) {
            echo "<tr>";
            echo "<td>{$usuario['id']}</td>";
            echo "<td>{$usuario['nome']}</td>";
            echo "<td>{$usuario['email']}</td>";
            echo "<td>{$usuario['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

} catch (PDOException $e) {
    die("❌ Erro na conexão: " . $e->getMessage());
} 