<?php
require_once(__DIR__ . '/models/Workshop.php');
$workshopModel = new Workshop();
$workshops = $workshopModel->getAll();

// Debugging: Verificar se os workshops estão sendo carregados
if (!$workshops) {
    error_log("Nenhum workshop encontrado ou erro ao carregar os dados.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>WorkshopFácil - Painel de Controle</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            border-bottom: 1px solid #ddd;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2e7d32;
        }
        .navbar .menu a {
            margin-left: 20px;
            text-decoration: none;
            color: #333;
        }
        .container {
            text-align: center;
            padding: 50px;
        }
        .container h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .container p {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        .dashboard {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .dashboard-item {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 200px;
            text-align: center;
        }
        .dashboard-item .icon {
            font-size: 40px;
            color: #2e7d32;
            margin-bottom: 10px;
        }
        .dashboard-item h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .dashboard-item p {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }
        .dashboard-item button {
            background-color: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .dashboard-item button:hover {
            background-color: #1b5e20;
        }
        .login-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }
        .login-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .login-container button {
            background-color: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
        }
        .login-container button:hover {
            background-color: #1b5e20;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="navbar" id="navbar">
        <div class="logo">WorkshopFácil</div>
        <div class="menu">
            <a href="Home.php">Início</a>
            <a href="inscrever.php">Workshops</a>
            <a href="inscriçoes.php">Inscrições</a>
            <a href="users.php">Usuários</a>
            <a href="admin.php">Admin</a>
        </div>
    </div>

    <div class="container">
        <h1>Painel de Controle</h1>
        <p>Agende seu Workshop</p>

        <div class="login-container" id="loginContainer">
            <form id="loginForm">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Acessar</button>
            </form>
        </div>

        <div class="dashboard hidden" id="dashboard">
            <div class="dashboard-item">
                <div class="icon">📚</div>
                <h2>Workshops</h2>
                <p>Gerencie as inscrições nos Workshops</p>
                <button onclick="window.location.href='inscrever.php'">Acessar</button>
            </div>
            <div class="dashboard-item">
                <div class="icon">📅</div>
                <h2>Inscrições</h2>
                <p>Gerenciamento para inscrições</p>
                <button onclick="window.location.href='inscricoes.php'">Acessar</button>
            </div>
            <div class="dashboard-item">
                <div class="icon">👥</div>
                <h2>Usuários</h2>
                <p>Gerencie usuários</p>
                <button onclick="window.location.href='users.php'">Acessar</button>
            </div>
            <div class="dashboard-item">
                <div class="icon">🛠️</div>
                <h2>Admin</h2>
                <p>Gerencie workshops</p>
                <button onclick="window.location.href='admin.php'">Acessar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userEmail = localStorage.getItem('userEmail');
            const userName = localStorage.getItem('userName');

            if (userEmail && userName) {
                showDashboard();
            }

            document.getElementById('loginForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const nome = document.getElementById('nome').value;
                const email = document.getElementById('email').value;

                if (nome && email) {
                    localStorage.setItem('userEmail', email);
                    localStorage.setItem('userName', nome);
                    showDashboard();
                } else {
                    alert("Por favor, preencha todos os campos.");
                }
            });

            function showDashboard() {
                document.getElementById('loginContainer').classList.add('hidden');
                document.getElementById('dashboard').classList.remove('hidden');
            }
        });
    </script>
</body>
</html>