<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

// Проверяем, какая дисциплина выбрана
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Получаем информацию о дисциплине
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

// Получаем книги этой дисциплины
$stmt = $pdo->prepare("SELECT * FROM books WHERE course_id = ? ORDER BY created_at DESC");
$stmt->execute([$course_id]);
$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($course['name'] ?? 'Дисциплина') ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js" defer></script>
</head>
<body>
<?php require_once 'includes/header.php'; ?>

<main>
    <h2><?= htmlspecialchars($course['name'] ?? 'Дисциплина') ?></h2>

    <?php if ($books): ?>
        <div class="books">
            <?php foreach($books as $book): ?>
                <div class="book-card">
                    <h3><?= htmlspecialchars($book['title']) ?></h3>
                    <p><?= htmlspecialchars($book['description']) ?></p>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div style="display:flex; gap:10px;">
                            <!-- Кнопка Сохранить -->
                            <form method="POST" action="/includes/save_book.php">
                                <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                                <button type="submit" class="save-btn">⭐ Сохранить</button>
                            </form>

                            <!-- Кнопка Читать -->
                            <?php if (!empty($book['pdf_path'])): ?>
                                <a href="admin/uploads/<?= htmlspecialchars($book['pdf_path']) ?>" target="_blank" class="read-btn">
                                    📖 Читать
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Пока книг нет.</p>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>