<?php
require __DIR__ . '/config.php';

// Уничтожение сессии
$_SESSION = array();
session_destroy();

// Перенаправление на главную
header('Location: index.php');
exit;
?>