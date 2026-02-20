<?php
require_once 'includes/auth_admin.php';
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $teacher = trim($_POST['teacher']); // <--- обязательно!

    $stmt = $pdo->prepare("INSERT INTO courses (name, description, teacher) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $teacher]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить дисциплину</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<main class="dashboard">
    <h2>Добавить дисциплину</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Название дисциплины" required>
        <input type="text" name="description" placeholder="Описание" required>
        <input type="text" name="teacher" placeholder="Руковадитель дисциплины" required>
        <button type="submit">Добавить дисциплину</button>
    </form>
</main>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>