<?php
require_once('models/Workshop.php');
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Buscar as inscrições do usuário
$workshopModel = new Workshop();
$inscricoes = $workshopModel->getInscricoesByUserId($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkshopFácil - Minhas Inscrições</title>
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
        .inscricoes-list {
            background-color: #222;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            padding: 20px;
        }
        .inscricoes-list table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .inscricoes-list th, .inscricoes-list td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        .inscricoes-list th {
            background-color: #333;
            font-weight: bold;
        }
        .no-inscricoes {
            text-align: center;
            padding: 20px;
            color: #ccc;
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
        <h1>Minhas Inscrições</h1>
        <p>Veja os workshops em que você está inscrito</p>

        <div class="inscricoes-list">
            <table>
                <thead>
                    <tr>
                        <th>Workshop</th>
                        <th>Data</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($inscricoes)): ?>
                        <tr>
                            <td colspan="3" class="no-inscricoes">Você ainda não está inscrito em nenhum workshop.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($inscricoes as $inscricao): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($inscricao['titulo']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($inscricao['data'])); ?></td>
                                <td><?php echo $inscricao['status']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html> 