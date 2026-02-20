<?php
require_once 'includes/auth_admin.php';
require_once '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админ панель</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<main class="dashboard">
    <h2>👑 Панель администратора</h2>

    <div class="stats-grid">
        <div class="stat-box">
            <h3>Дисциплины</h3>
            <p><a href="courses.php" class="save-btn">Управление дисциплинами</a></p>
        </div>

        <div class="stat-box">
            <h3>Книги</h3>
            <p><a href="books.php" class="save-btn">Управление книгами</a></p>
        </div>
    </div>
</main>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>