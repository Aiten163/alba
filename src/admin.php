<?php
require_once __DIR__ . '/config.php';

// Проверка прав администратора
if (!isset($_SESSION['user'])){
    header('Location: index.php');
    exit;
}

if ($_SESSION['user']['role'] !== 'admin') {
    die('Доступ запрещен');
}

// Получение списка заказов
$orders = [];
$stmt = $mysqli->prepare("SELECT * FROM orders ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

// Обновление описания
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $orderId = (int)$_POST['order_id'];
    $description = $mysqli->real_escape_string(trim($_POST['description']));

    $stmt = $mysqli->prepare("UPDATE orders SET questions = ? WHERE id_order = ?");
    $stmt->bind_param('si', $description, $orderId);
    $stmt->execute();

    header("Location: admin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ-панель</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
</head>
<body class="bg-gray-100">
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Управление заявками</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Имя</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Телефон</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Дата записи</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Описание</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Действия</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap"><?= $order['id_order'] ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($order['name']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($order['number']) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?= $order['appointment_date'] ?></td>
                    <td class="px-6 py-4">
                        <div id="desc-<?= $order['id_order'] ?>"><?= htmlspecialchars($order['questions']) ?></div>
                        <textarea id="edit-<?= $order['id_order'] ?>"
                                  class="hidden w-full border rounded p-2"></textarea>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="toggleEdit(<?= $order['id_order'] ?>)"
                                class="text-blue-600 hover:text-blue-900">Изменить</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Модальное окно редактирования -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-bold mb-4">Редактировать описание</h3>
        <form id="editForm" method="POST">
            <input type="hidden" name="order_id" id="editOrderId">
            <textarea name="description" id="editDescription"
                      class="w-full border rounded p-2 mb-4" rows="4"></textarea>
            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800">Отмена</button>
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Сохранить</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEdit(orderId) {
        const modal = document.getElementById('editModal');
        const description = document.getElementById(`desc-${orderId}`).innerText;

        document.getElementById('editOrderId').value = orderId;
        document.getElementById('editDescription').value = description;
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Обработка отправки формы
    document.getElementById('editForm').addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);

        try {
            const response = await fetch('admin.php', {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                window.location.reload();
            }
        } catch (error) {
            console.error('Ошибка:', error);
        }
    });
</script>
</body>
</html>