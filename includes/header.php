<?php
require_once __DIR__ . '/auth.php';
$user = isset($_SESSION['user_id']) ? getUser($pdo) : null;

// Проверка роли
$isAdmin = false;
if ($user) {
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id=?");
    $stmt->execute([$user['id']]);
    $row = $stmt->fetch();
    if ($row && isset($row['role']) && $row['role'] === 'admin') {
        $isAdmin = true;
    }
}

// Функция для построения корректных ссылок
function site_url($path) {
    $root = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if ($root === '/' || $root === '\\') $root = '';
    return $root . '/' . ltrim($path, '/');
}
?>
<header>
    <h1><a href="/index.php" style="color:inherit; text-decoration:none;">AI-library КазУТБ</a></h1>
    <nav>
        <?php if ($user): ?>
            <a href="/dashboard.php">Личный кабинет</a>

            <?php if ($isAdmin): ?>
                <a href="<?= site_url('admin/index.php') ?>" style="color:#ffd700; font-weight:bold;">Админ-панель</a>
            <?php endif; ?>

            <a href="/auth/logout.php">Выйти</a>
        <?php else: ?>
            <a href="/auth/login.php">Войти</a>
            <a href="/auth/register.php">Регистрация</a>
        <?php endif; ?>
        <button id="theme-toggle">🌙 Тёмная тема</button>
    </nav>
</header>


