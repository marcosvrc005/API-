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
    $tables = $db->query("SHOW TABLES LIKE 'workshops'")->fetchAll();
    if (empty($tables)) {
        // Criar a tabela de workshops
        $db->exec("CREATE TABLE IF NOT EXISTS workshops (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descricao TEXT,
            data DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "✅ Tabela 'workshops' criada com sucesso!<br><br>";
    } else {
        echo "✅ Tabela 'workshops' já existe!<br><br>";
    }

    // Inserir dados de teste
    $workshops_teste = [
        [
            'titulo' => 'Workshop de PHP Básico',
            'descricao' => 'Aprenda os fundamentos da programação PHP',
            'data' => '2024-04-01'
        ],
        [
            'titulo' => 'Workshop de MySQL',
            'descricao' => 'Banco de dados na prática',
            'data' => '2024-04-15'
        ],
        [
            'titulo' => 'Workshop de JavaScript',
            'descricao' => 'Desenvolvimento web com JavaScript',
            'data' => '2024-05-01'
        ]
    ];

    echo "<h3>Inserindo workshops de teste:</h3>";
    $stmt = $db->prepare("INSERT IGNORE INTO workshops (titulo, descricao, data) VALUES (?, ?, ?)");
    foreach ($workshops_teste as $workshop) {
        try {
            $stmt->execute([$workshop['titulo'], $workshop['descricao'], $workshop['data']]);
            echo "✅ Workshop '{$workshop['titulo']}' inserido/verificado<br>";
        } catch (PDOException $e) {
            echo "❌ Erro ao inserir {$workshop['titulo']}: " . $e->getMessage() . "<br>";
        }
    }

    // Verificar workshops cadastrados
    echo "<br><h3>Workshops cadastrados no banco:</h3>";
    $stmt = $db->query("SELECT * FROM workshops ORDER BY data");
    $workshops = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($workshops)) {
        echo "❌ Nenhum workshop encontrado no banco de dados.";
    } else {
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>ID</th><th>Título</th><th>Descrição</th><th>Data</th><th>Criado em</th></tr>";
        foreach ($workshops as $workshop) {
            echo "<tr>";
            echo "<td>{$workshop['id']}</td>";
            echo "<td>{$workshop['titulo']}</td>";
            echo "<td>{$workshop['descricao']}</td>";
            echo "<td>" . date('d/m/Y', strtotime($workshop['data'])) . "</td>";
            echo "<td>{$workshop['created_at']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }

} catch (PDOException $e) {
    die("❌ Erro na conexão: " . $e->getMessage());
} 