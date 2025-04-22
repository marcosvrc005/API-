<?php
require_once(__DIR__ . '/../models/Workshop.php');
require_once(__DIR__ . '/../models/User.php');
require_once(__DIR__ . '/../models/Registration.php');


header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Ajuste de caminho base
$basePath = '/dashboard/API/api.php';
$uri = str_replace($basePath, '', $uri);

try {
    switch (true) {
        // Listar workshops
        case $method === 'GET' && preg_match('#^/api/workshops$#', $uri):
            $workshop = new Workshop();
            $data = $workshop->getAll();
            echo json_encode($data);
            break;

        // Obter workshop por ID
        case $method === 'GET' && preg_match('#^/api/workshops/(\d+)$#', $uri, $matches):
            $id = (int)$matches[1];
            $workshop = new Workshop();
            $data = $workshop->getById($id);
            if (!$data) {
                http_response_code(404);
                echo json_encode(['error' => 'Workshop não encontrado']);
                break;
            }
            echo json_encode($data);
            break;

        // Criar novo workshop
        case $method === 'POST' && preg_match('#^/api/workshops$#', $uri):
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input['titulo']) || empty($input['descricao']) || empty($input['data'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados incompletos']);
                break;
            }
            $workshop = new Workshop();
            if ($workshop->create($input['titulo'], $input['descricao'], $input['data'])) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao criar workshop']);
            }
            break;

        // Realizar inscrição
        case $method === 'POST' && preg_match('#^/inscricao$#', $uri):
            $input = json_decode(file_get_contents('php://input'), true);
            error_log("Dados recebidos: " . print_r($input, true));
            
            if (empty($input['nome']) || empty($input['email']) || empty($input['workshop_id']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                error_log("Dados inválidos ou incompletos");
                http_response_code(400);
                echo json_encode(['error' => 'Dados inválidos ou incompletos']);
                break;
            }
            
            $userModel = new User();
            $user = $userModel->findByEmail($input['email']);
            if (!$user) {
                error_log("Criando novo usuário");
                $userModel->create($input['nome'], $input['email']);
                $user = $userModel->findByEmail($input['email']);
            }
            
            if (!$user) {
                error_log("Erro ao criar/encontrar usuário");
                http_response_code(500);
                echo json_encode(['error' => 'Erro ao processar usuário']);
                break;
            }
            
            error_log("Usuário encontrado/criado: " . print_r($user, true));
            
            $inscricao = new Registration();
            $success = $inscricao->register($user['id'], $input['workshop_id']);
            
            if ($success) {
                error_log("Inscrição realizada com sucesso");
                echo json_encode(['success' => true]);
            } else {
                error_log("Erro ao registrar inscrição");
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Erro ao registrar inscrição. Talvez já esteja inscrito.']);
            }
            break;

        // Criar usuário manualmente
        case $method === 'POST' && preg_match('#^/api/usuarios$#', $uri):
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input['nome']) || empty($input['email']) || !filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode(['error' => 'Dados inválidos']);
                break;
            }
            $userModel = new User();
            $result = $userModel->create($input['nome'], $input['email']);
            
            if ($result['success']) {
                http_response_code(201); // Created
                echo json_encode(['success' => true, 'id' => $result['id']]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => $result['error']]);
            }
            break;

        // Listar usuários
        case $method === 'GET' && preg_match('#^/api/usuarios$#', $uri):
            $userModel = new User();
            $users = $userModel->getAll();
            
            if (empty($users)) {
                echo json_encode(['data' => [], 'message' => 'Nenhum usuário encontrado']);
            } else {
                echo json_encode(['data' => $users]);
            }
            break;

        // Rota não encontrada
        default:
            http_response_code(404);
            echo json_encode(['error' => 'Endpoint não encontrado']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno do servidor', 'message' => $e->getMessage()]);
}
