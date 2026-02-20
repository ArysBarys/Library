<?php
require_once 'includes/auth.php';
if (isset($_SESSION['user_id'])) $user = getUser($pdo);

// Получаем все дисциплины
$stmt = $pdo->query("SELECT * FROM courses ORDER BY name ASC");
$courses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>AI-library КазУТБ</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once __DIR__ . '/includes/header.php'; ?>

<div class="page-content">
    <main>
        <!-- Поиск -->
        <div class="search-container">
            <input type="text" id="search-box" placeholder="Поиск дисциплин и книг...">
            <div id="search-results"></div>
        </div>
        <h2>Дисциплины</h2>
        <?php if ($courses): ?>
            <div class="courses">
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <a href="discipline.php?id=<?= $course['id'] ?>">
                            <h3><?= htmlspecialchars($course['name']) ?></h3>
                        </a>
                        <p><?= htmlspecialchars($course['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Пока дисциплин нет.</p>
        <?php endif; ?>
    </main>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>