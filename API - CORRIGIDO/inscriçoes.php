<?php
// Simulando uma lista de inscrições (você pode substituir por um modelo real)
$inscricoes = [
    ['email' => 'joao@email.com', 'workshop_id' => 1, 'titulo' => 'Workshop de PHP', 'data' => '2025-05-01'],
    ['email' => 'joao@email.com', 'workshop_id' => 2, 'titulo' => 'Workshop de JavaScript', 'data' => '2025-05-10'],
    ['email' => 'maria@email.com', 'workshop_id' => 3, 'titulo' => 'Workshop de Design', 'data' => '2025-05-15'],
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>WorkshopFácil - Minhas Inscrições</title>
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
            color: #fff;
        }
        .container p {
            font-size: 16px;
            color: #aaa;
            margin-bottom: 30px;
        }
        .inscricoes-table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .inscricoes-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .inscricoes-table th, .inscricoes-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #333;
        }
        .inscricoes-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
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
        <h1>Minhas Inscrições</h1>
        <p>Veja os workshops em que você está inscrito</p>

        <div class="inscricoes-table">
            <table>
                <thead>
                    <tr>
                        <th>Workshop</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody id="inscricoesBody">
                    <?php
                    $userEmail = isset($_GET['email']) ? $_GET['email'] : (isset(localStorage['userEmail']) ? localStorage['userEmail'] : null);
                    $userInscricoes = array_filter($inscricoes, function($inscricao) use ($userEmail) {
                        return $inscricao['email'] === $userEmail;
                    });

                    if (empty($userInscricoes)) {
                        echo '<tr><td colspan="2">Nenhuma inscrição encontrada.</td></tr>';
                    } else {
                        foreach ($userInscricoes as $inscricao) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($inscricao['titulo']) . '</td>';
                            echo '<td>' . htmlspecialchars($inscricao['data']) . '</td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userEmail = localStorage.getItem('userEmail');
            if (!userEmail) {
                window.location.href = 'Home.php';
                return;
            }

            // Redirecionar para recarregar a página com o