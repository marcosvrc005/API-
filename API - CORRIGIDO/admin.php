<?php
require_once('models/Workshop.php');

// Buscar workshops do banco de dados
$workshopModel = new Workshop();
$workshops = $workshopModel->getAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>WorkshopFácil - Administração</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333;
            margin: 0;
            padding: 0;
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
            color: #ccc;
            margin-bottom: 30px;
        }
        .workshop-list {
            background-color: #222;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            padding: 20px;
            max-width: 800px;
            margin: 0 auto 20px;
        }
        .workshop-list h2 {
            color: #fff;
            margin-bottom: 20px;
        }
        .workshop-list table {
            width: 100%;
            border-collapse: collapse;
            color: #fff;
        }
        .workshop-list th, .workshop-list td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #444;
        }
        .workshop-list th {
            background-color: #333;
            font-weight: bold;
        }
        .form-container {
            background-color: #222;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
        }
        .form-container h2 {
            color: #fff;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-size: 14px;
            color: #fff;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            font-size: 14px;
            background-color: #333;
            color: #fff;
        }
        .form-container textarea {
            height: 100px;
            resize: vertical;
        }
        .form-container button {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
        }
        .form-container button:hover {
            background-color: #45a049;
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
        <h1>Gerenciar Workshops</h1>
        <p>Adicione e gerencie os workshops disponíveis</p>

        <div class="workshop-list">
            <h2>Workshops Cadastrados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($workshops)): ?>
                        <tr>
                            <td colspan="3">Nenhum workshop cadastrado.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($workshops as $workshop): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($workshop['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($workshop['descricao']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($workshop['data'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="form-container">
            <h2>Adicionar Novo Workshop</h2>
            <form id="workshopForm">
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" required>
                
                <label for="descricao">Descrição</label>
                <textarea id="descricao" name="descricao" required></textarea>
                
                <label for="data">Data</label>
                <input type="date" id="data" name="data" required>
                
                <button type="submit">Adicionar Workshop</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('workshopForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const titulo = document.getElementById('titulo').value;
            const descricao = document.getElementById('descricao').value;
            const data = document.getElementById('data').value;

            if (titulo && descricao && data) {
                fetch('/dashboard/API/api.php/api/workshops', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ titulo, descricao, data })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Workshop adicionado com sucesso!');
                        document.getElementById('workshopForm').reset();
                        // Recarrega a página para mostrar o novo workshop
                        window.location.reload();
                    } else {
                        alert('Erro: ' + (data.error || 'Erro desconhecido'));
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao adicionar workshop: ' + error.message);
                });
            } else {
                alert("Por favor, preencha todos os campos.");
            }
        });
    </script>
</body>
</html>