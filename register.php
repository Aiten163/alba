<?php
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'message' => 'Method Not Allowed']));
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';
$password_confirm = $data['password_confirm'] ?? '';

$errors = [];

// Валидация
if (empty($name)) $errors['name'] = 'Введите имя';
if (empty($email)) {
    $errors['email'] = 'Введите email';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Некорректный email';
}

if (empty($password)) {
    $errors['password'] = 'Введите пароль';
} elseif (strlen($password) < 6) {
    $errors['password'] = 'Пароль от 6 символов';
}

if ($password !== $password_confirm) {
    $errors['password_confirm'] = 'Пароли не совпадают';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Проверка существования пользователя
$stmt = $mysqli->prepare("SELECT id_user FROM users WHERE mail = ?");
$stmt->bind_param('s', $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $errors['email'] = 'Email уже занят';
    echo json_encode(['success' => false, 'errors' => $errors]);
    exit;
}

// Регистрация
$hashed_password = $password;
$stmt = $mysqli->prepare("INSERT INTO users (login, mail, password, role) VALUES (?, ?, ?, 'client')");
$stmt->bind_param('sss', $name, $email, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['user'] = [
        'id' => $stmt->insert_id,
        'email' => $email,
        'name' => $name,
        'role' => 'client'
    ];
    echo json_encode(['success' => true, 'redirect' => 'index.php']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка регистрации']);
}
?>