<?php
require_once 'includes/auth_admin.php';
require_once '../includes/db.php';

// Получаем все дисциплины
$stmt = $pdo->query("SELECT * FROM courses ORDER BY name ASC");
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление дисциплинами</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<main class="dashboard">
    <h2>Все дисциплины</h2>
    <a href="add_course.php" class="save-btn">➕ Добавить дисциплину</a>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Действия</th>
        </tr>
        <?php foreach($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= htmlspecialchars($course['name']) ?></td>
            <td><?= htmlspecialchars($course['description']) ?></td>
            <td>
                <a href="edit_course.php?id=<?= $course['id'] ?>">✏️ Редактировать</a> |
                <a href="delete_course.php?id=<?= $course['id'] ?>" onclick="return confirm('Удалить дисциплину?')">🗑️ Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>