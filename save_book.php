<?php
require_once 'includes/auth.php';
checkAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrf_token)) die("Ошибка CSRF");

    $user_id = $_SESSION['user_id'];
    $book_id = intval($_POST['book_id']);

    $stmt = $pdo->prepare("INSERT IGNORE INTO saved_books (user_id, book_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $book_id]);
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit;