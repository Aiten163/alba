<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Метод не поддерживается', 405);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Ошибка формата данных', 400);
    }

    $errors = [];
    $required = [
        'name' => 'Введите имя',
        'phone' => 'Введите телефон',
        'email' => 'Введите email',
        'date' => 'Выберите дату'
    ];

    foreach ($required as $field => $message) {
        if (empty($data[$field])) {
            $errors[$field] = $message;
        }
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    // Проверка формата телефона
    if (!preg_match('/^\+?[0-9\s\-\(\)]{7,}$/', $data['phone'])) {
        $errors['phone'] = 'Неверный формат телефона';
    }

    // Проверка email
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Неверный формат email';
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $stmt = $mysqli->prepare("INSERT INTO orders 
        (name, number, mail, appointment_date, questions) 
        VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param('sssss',
        $data['name'],
        $data['phone'],
        $data['email'],
        $data['date'],
        $data['message']
    );

    if (!$stmt->execute()) {
        throw new Exception('Ошибка базы данных: ' . $stmt->error);
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'errors' => $errors ?? []
    ]);
}
