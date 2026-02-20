<?php
// auth.php
session_start();
require_once __DIR__ . '/db.php';

function checkAuth($redirect = '/auth/login.php') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: $redirect");
        exit();
    }
}

function getUser($pdo) {
    if (isset($_SESSION['user_id'])) {
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch();
    }
    return null;
}

// CSRF токен
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        die("Ошибка CSRF");
    }
}

if (!function_exists('checkAuth')) {
    function checkAuth($redirect = '/auth/login.php') {
        if (!isset($_SESSION['user_id'])) {
            header("Location: $redirect");
            exit();
        }
    }
}

if (!function_exists('getUser')) {
    function getUser($pdo) {
        if (isset($_SESSION['user_id'])) {
            $stmt = $pdo->prepare("SELECT id, username, email, role FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        }
        return null;
    }
}

if (!function_exists('generateCSRFToken')) {
    function generateCSRFToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verifyCSRFToken')) {
    function verifyCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
?>