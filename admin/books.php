<?php
require_once 'includes/auth_admin.php'; // Проверка роли admin
require_once '../includes/db.php';

// Получаем все книги
$stmt = $pdo->query("
    SELECT books.*, courses.name AS course_name
    FROM books
    JOIN courses ON books.course_id = courses.id
    ORDER BY books.created_at DESC
");
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Управление книгами</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<main class="dashboard">
    <h2>Все книги</h2>
    <a href="add_book.php" class="save-btn">➕ Добавить книгу</a>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Автор</th>
            <th>Дисциплина</th>
            <th>PDF</th>
            <th>Действия</th>
        </tr>
        <?php foreach($books as $book): ?>
        <tr>
            <td><?= $book['id'] ?></td>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td><?= htmlspecialchars($book['course_name']) ?></td>
            <td>
                <?php if($book['pdf_path']): ?>
                    <a href="../uploads/<?= $book['pdf_path'] ?>" target="_blank">PDF</a>
                <?php else: ?>
                    Нет
                <?php endif; ?>
            </td>
            <td>
                <a href="edit_book.php?id=<?= $book['id'] ?>">✏️ Редактировать</a> |
                <a href="delete_book.php?id=<?= $book['id'] ?>" onclick="return confirm('Удалить книгу?')">🗑️ Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>