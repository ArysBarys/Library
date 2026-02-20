<?php
require_once 'includes/auth_admin.php';
require_once '../includes/db.php';

$book_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

$courses = $pdo->query("SELECT id, name FROM courses ORDER BY name ASC")->fetchAll();

if(!$book) {
    die("Книга не найдена");
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $author = trim($_POST['author']);
    $course_id = intval($_POST['course_id']);
    $description = trim($_POST['description']);

    $pdf_path = $book['pdf_path'];
    if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['pdf']['name'], PATHINFO_EXTENSION);
        if(strtolower($ext) === 'pdf') {
            $pdf_path = uniqid() . ".pdf";
            move_uploaded_file($_FILES['pdf']['tmp_name'], "../uploads/$pdf_path");
        }
    }

    $stmt = $pdo->prepare("UPDATE books SET title=?, author=?, description=?, course_id=?, pdf_path=? WHERE id=?");
    $stmt->execute([$title, $author, $description, $course_id, $pdf_path, $book_id]);
    header("Location: books.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать книгу</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<main class="dashboard">
    <h2>Редактировать книгу</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br>
        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br>
        <select name="course_id" required>
            <?php foreach($courses as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$book['course_id']?'selected':'' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        <textarea name="description" required><?= htmlspecialchars($book['description']) ?></textarea><br>
        <p>Текущий PDF: <?= $book['pdf_path'] ? "<a href='../uploads/{$book['pdf_path']}' target='_blank'>Открыть</a>" : "Нет" ?></p>
        <input type="file" name="pdf" accept="application/pdf"><br>
        <button type="submit">Сохранить изменения</button>
    </form>
</main>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>