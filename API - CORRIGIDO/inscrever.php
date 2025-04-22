<?php
require_once('models/Workshop.php');
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Processar inscrição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['workshop_id'])) {
    $workshopModel = new Workshop();
    try {
        if ($workshopModel->inscrever($_SESSION['user_id'], $_POST['workshop_id'])) {
            $mensagem = "Inscrição realizada com sucesso!";
            $tipo = "sucesso";
        } else {
            $mensagem = "Erro ao realizar inscrição.";
            $tipo = "erro";
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Código de erro para duplicate entry
            $mensagem = "Você já está inscrito neste workshop.";
        } else {
            $mensagem = "Erro ao processar inscrição: " . $e->getMessage();
        }
        $tipo = "erro";
    }
}

// Buscar workshops disponíveis
$workshopModel = new Workshop();
$workshops = $workshopModel->getAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkshopFácil - Inscrever-se</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            margin: 0;
            padding: 0;
            color: #fff;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #222;
            border-bottom: 1px solid #444;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        .navbar .menu a {
            margin-left: 20px;
            text-decoration: none;
            color: #fff;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
        }
        .container h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        .container p {
            text-align: center;
            color: #ccc;
            margin-bottom: 30px;
        }
        .workshop-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }
        .workshop-card {
            background-color: #222;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }
        .workshop-card h3 {
            margin: 0 0 10px 0;
            color: #4CAF50;
        }
        .workshop-card p {
            margin: 0 0 15px 0;
            color: #ccc;
            text-align: left;
        }
        .workshop-card .data {
            font-weight: bold;
            color: #fff;
            margin-bottom: 15px;
        }
        .workshop-card button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .workshop-card button:hover {
            background-color: #45a049;
        }
        .mensagem {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .mensagem.sucesso {
            background-color: #4CAF50;
            color: #fff;
        }
        .mensagem.erro {
            background-color: #f44336;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">WorkshopFácil</div>
        <div class="menu">
            <a href="Home.php">Início</a>
            <a href="inscrever.php">Workshops</a>
            <a href="inscricoes.php">Inscrições</a>
            <a href="users.php">Usuários</a>
            <a href="admin.php">Admin</a>
        </div>
    </div>

    <div class="container">
        <h1>Workshops Disponíveis</h1>
        <p>Escolha os workshops de seu interesse e inscreva-se</p>

        <?php if (isset($mensagem)): ?>
            <div class="mensagem <?php echo $tipo; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <div class="workshop-grid">
            <?php if (empty($workshops)): ?>
                <p>Nenhum workshop disponível no momento.</p>
            <?php else: ?>
                <?php foreach ($workshops as $workshop): ?>
                    <div class="workshop-card">
                        <h3><?php echo htmlspecialchars($workshop['titulo']); ?></h3>
                        <p><?php echo htmlspecialchars($workshop['descricao']); ?></p>
                        <p class="data">Data: <?php echo date('d/m/Y', strtotime($workshop['data'])); ?></p>
                        <form method="POST">
                            <input type="hidden" name="workshop_id" value="<?php echo $workshop['id']; ?>">
                            <button type="submit">Inscrever-se</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>