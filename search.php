<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if ($keyword !== '') {
    $like = "%$keyword%";

    // Дисциплины
    $stmt = $pdo->prepare("SELECT id, name, description, 'course' AS type 
                           FROM courses 
                           WHERE name LIKE ? OR description LIKE ? 
                           ORDER BY name ASC");
    $stmt->execute([$like, $like]);
    $courses = $stmt->fetchAll();

    // Книги (добавляем pdf_path)
    $stmt = $pdo->prepare("SELECT books.id, books.title, books.author, books.description, books.pdf_path, courses.name AS course_name, 'book' AS type
                           FROM books
                           JOIN courses ON books.course_id = courses.id
                           WHERE books.title LIKE ? OR books.author LIKE ? OR books.description LIKE ?
                           ORDER BY books.title ASC");
    $stmt->execute([$like, $like, $like]);
    $books = $stmt->fetchAll();

    $results = array_merge($courses, $books);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результаты поиска</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<?php require_once 'includes/header.php'; ?>

<main>
    <h2>Поиск: "<?= htmlspecialchars($keyword) ?>"</h2>

    <?php if ($results): ?>
        <div class="search-results">
            <?php foreach ($results as $item): ?>
                <?php if ($item['type'] === 'course'): ?>
                    <div class="course-card">
                        <a href="discipline.php?id=<?= $item['id'] ?>">
                            <h3><?= htmlspecialchars($item['name']) ?> (Дисциплина)</h3>
                        </a>
                        <p><?= htmlspecialchars($item['description']) ?></p>
                    </div>
                <?php else: ?>
                    <div class="book-card">
                        <h3><?= htmlspecialchars($item['title']) ?> (Книга)</h3>
                        <p><strong>Автор:</strong> <?= htmlspecialchars($item['author']) ?></p>
                        <p><strong>Дисциплина:</strong> <?= htmlspecialchars($item['course_name']) ?></p>
                        <p><?= htmlspecialchars($item['description']) ?></p>

                        <?php if (!empty($item['pdf_path'])): ?>
                            <a href="admin/uploads/<?= htmlspecialchars($item['pdf_path']) ?>" target="_blank" class="read-btn">📖 Читать</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Ничего не найдено.</p>
    <?php endif; ?>
</main>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>