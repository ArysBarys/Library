<?php
require_once 'includes/auth_admin.php';
require_once '../includes/db.php';

$course_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM courses WHERE id=?");
$stmt->execute([$course_id]);
$course = $stmt->fetch();

if(!$course) die("Дисциплина не найдена");

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);

    $stmt = $pdo->prepare("UPDATE courses SET name=?, description=? WHERE id=?");
    $stmt->execute([$name, $description, $course_id]);

    header("Location: courses.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать дисциплину</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<main class="dashboard">
    <h2>Редактировать дисциплину</h2>
    <form method="POST">
        <input type="text" name="name" value="<?= htmlspecialchars($course['name']) ?>" required><br>
        <textarea name="description" required><?= htmlspecialchars($course['description']) ?></textarea><br>
        <button type="submit">Сохранить изменения</button>
    </form>
</main>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>