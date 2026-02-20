<?php
require_once 'includes/auth_admin.php';
require_once '../includes/db.php';

$course_id = intval($_GET['id']);
$stmt = $pdo->prepare("DELETE FROM courses WHERE id=?");
$stmt->execute([$course_id]);

header("Location: courses.php");
exit;