<?php
require_once '../includes/auth.php';

// Уничтожаем все данные сессии
session_unset();
session_destroy();

// Перенаправляем на страницу входа
header('Location: /auth/login.php');
exit();