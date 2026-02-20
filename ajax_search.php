<?php
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

    // Книги
    $stmt = $pdo->prepare("SELECT books.id, books.title, books.author, books.description, courses.name AS course_name, 'book' AS type
                           FROM books
                           JOIN courses ON books.course_id = courses.id
                           WHERE books.title LIKE ? OR books.author LIKE ? OR books.description LIKE ?
                           ORDER BY books.title ASC");
    $stmt->execute([$like, $like, $like]);
    $books = $stmt->fetchAll();

    $results = array_merge($courses, $books);
}

// Отправляем JSON
header('Content-Type: application/json');
echo json_encode($results);