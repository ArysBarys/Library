<?php
require_once __DIR__ . '/../../includes/auth.php'; // обычная авторизация
checkAuth(); // проверка, что пользователь вошел

$user = isset($_SESSION['user_id']) ? getUser($pdo) : null;

// Если пользователь не авторизован — редирект на login
if (!$user) {
    header("Location: /auth/login.php");
    exit();
}

// Проверка роли
$stmt = $pdo->prepare("SELECT role FROM users WHERE id=?");
$stmt->execute([$user['id']]);
$row = $stmt->fetch();

if (!$row || $row['role'] !== 'admin') {
    // Если не админ — редирект на dashboard
    header("Location: /dashboard.php");
    exit();
}
?>