<?php

require_once '../models/Workshop.php';
require_once '../models/User.php';
require_once '../models/Registration.php';

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

switch (true) {
    case $method === 'GET' && preg_match('/\/api\/workshops$/', $uri):
        $workshop = new Workshop();
        echo json_encode($workshop->getAll());
        break;

    case $method === 'GET' && preg_match('/\/api\/workshops\/(\d+)/', $uri, $matches):
        $id = $matches[1];
        $workshop = new Workshop();
        echo json_encode($workshop->getById($id));
        break;

    case $method === 'POST' && preg_match('/\/api\/inscricao$/', $uri):
        $data = json_decode(file_get_contents('php://input'), true);

        $userModel = new User();
        $user = $userModel->findByEmail($data['email']);

        if (!$user) {
            $userModel->create($data['nome'], $data['email']);
            $user = $userModel->findByEmail($data['email']);
        }

        $inscricao = new Registration();
        $success = $inscricao->register($user['id'], $data['workshop_id']);

        echo json_encode(['success' => $success]);
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Endpoint não encontrado']);
        break;
}
