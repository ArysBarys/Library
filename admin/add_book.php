<?php
require_once __DIR__ . '/includes/auth_admin.php';
require_once __DIR__ . '/../includes/db.php'; // база
require_once '../includes/auth.php'; // если там есть generateCSRFToken() и verifyCSRFToken()

// Генерируем CSRF токен для формы
$csrf_token = generateCSRFToken();

// === Обработка POST-запроса ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Получаем данные из формы
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $course_id = intval($_POST['course_id']);
    $description = trim($_POST['description']);

    // Загружаем PDF
    if (!empty($_FILES['pdf_file']['name'])) {
        $pdf_name = time() . '_' . basename($_FILES['pdf_file']['name']);
        $target_dir = __DIR__ . "/uploads/";
        move_uploaded_file($_FILES['pdf_file']['tmp_name'], $target_dir . $pdf_name);
    } else {
        $pdf_name = null;
    }

    // Вставляем в БД
    $stmt = $pdo->prepare("INSERT INTO books (title, author, course_id, description, pdf_path) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $author, $course_id, $description, $pdf_name]);

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить книгу</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
    <input type="text" name="title" placeholder="Название книги" required><br>
    <input type="text" name="author" placeholder="Автор" required><br>
    <select name="course_id" required>
        <?php
        $stmt = $pdo->query("SELECT id, name FROM courses ORDER BY name ASC");
        $courses = $stmt->fetchAll();
        foreach ($courses as $course) {
            echo "<option value=\"{$course['id']}\">" . htmlspecialchars($course['name']) . "</option>";
        }
        ?>
    </select><br>
    <textarea name="description" placeholder="Описание книги"></textarea><br>
    <input type="file" name="pdf_file" accept="application/pdf" required><br>
    <button type="submit">Добавить книгу</button>
</form>
</body>
</html>