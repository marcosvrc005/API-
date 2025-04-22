<?php
// Habilitar exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('models/User.php');

try {
    // Buscar usuários do banco de dados
    $userModel = new User();
    $users = $userModel->getAll();
    
    if ($users === false) {
        error_log("Erro ao buscar usuários");
        $users = [];
    }
} catch (Exception $e) {
    error_log("Erro ao carregar usuários: " . $e->getMessage());
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <title>WorkshopFácil - Gerenciar Usuários</title>
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
        .user-table {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            margin: 0 auto 20px;
        }
        .user-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .user-table th, .user-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: #333;
        }
        .user-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
        }
        .form-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
        .form-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        .form-container button {
            background-color: #2e7d32;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
        }
        .form-container button:hover {
            background-color: #1b5e20;
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
        <h1>Gerenciar Usuários</h1>
        <p>Veja e adicione usuários</p>

        <div class="user-table">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data de Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="3">Nenhum usuário cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['nome']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="form-container">
            <h2>Adicionar Novo Usuário</h2>
            <form id="userForm">
                <label for="nome">Nome</label>
                <input type="text" id="nome" name="nome" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <button type="submit">Adicionar</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('userForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const nome = document.getElementById('nome').value;
            const email = document.getElementById('email').value;

            if (nome && email) {
                fetch('/dashboard/API/api.php/api/usuarios', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ nome, email })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Usuário adicionado com sucesso!');
                        document.getElementById('userForm').reset();
                        // Recarrega a página para mostrar o novo usuário
                        window.location.reload();
                    } else {
                        alert('Erro: ' + (data.error || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao adicionar usuário: ' + error.message);
                });
            } else {
                alert("Por favor, preencha todos os campos.");
            }
        });
    </script>
</body>
</html>