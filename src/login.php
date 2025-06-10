<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method Not Allowed']));
}

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

$errors = [];

if (empty($email)) $errors['email'] = 'Введите email';
if (empty($password)) $errors['password'] = 'Введите пароль';

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

$stmt = $mysqli->prepare("SELECT * FROM users WHERE mail = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        $_SESSION['user'] = [
            'id' => $user['id_user'],
            'email' => $user['mail'],
            'name' => $user['login'],
            'role' => $user['role']
        ];
        echo json_encode(['success' => true, 'redirect' => 'index.php']);
        exit;
    }
}

$errors['general'] = 'Неверный email или пароль';
echo json_encode(['success' => false, 'errors' => $errors]);
?>