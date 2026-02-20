<?php
require_once 'includes/auth.php';
checkAuth();

$user = getUser($pdo);
$user_id = $user['id'];

// Получаем сохранённые книги пользователя
$stmt = $pdo->prepare("
    SELECT books.*
    FROM saved_books
    JOIN books ON saved_books.book_id = books.id
    WHERE saved_books.user_id = ?
    ORDER BY saved_books.created_at DESC
");
$stmt->execute([$user_id]);
$savedBooks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once 'includes/header.php'; ?>

<main class="dashboard">
    <div class="profile-card">
        <h2>👤 <?= htmlspecialchars($user['username']) ?></h2>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    </div>

    <h3>⭐ Сохранённые книги</h3>
    <?php if ($savedBooks): ?>
        <div class="books-grid">
            <?php foreach ($savedBooks as $book): ?>
                <div class="book-card">
                    <h4><?= htmlspecialchars($book['title']) ?></h4>
                    <p><?= htmlspecialchars($book['description']) ?></p>

                    <?php if (!empty($book['pdf_path'])): ?>
                        <a href="admin/uploads/<?= htmlspecialchars($book['pdf_path']) ?>" target="_blank" class="read-btn">
                            📖 Читать
                        </a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>У вас пока нет сохранённых книг.</p>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>