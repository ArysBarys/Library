<?php
require_once 'includes/auth_admin.php';

$book_id = intval($_GET['id']);

// Получаем книгу
$stmt = $pdo->prepare("SELECT pdf_path FROM books WHERE id=?");
$stmt->execute([$book_id]);
$book = $stmt->fetch();

if ($book) {
    // Удаляем PDF
    if ($book['pdf_path'] && file_exists("../uploads/".$book['pdf_path'])) {
        unlink("../uploads/".$book['pdf_path']);
    }
    // Удаляем запись из БД
    $stmt = $pdo->prepare("DELETE FROM books WHERE id=?");
    $stmt->execute([$book_id]);
}

header("Location: index.php");
exit;