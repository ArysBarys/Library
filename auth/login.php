<?php
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        die("Ошибка CSRF: неверный токен");
    }

    $username_or_email = trim($_POST['username_or_email']);
    $password = $_POST['password'];

    if (strlen($username_or_email) < 3 || strlen($password) < 3) {
        $error = "Неверные данные";
    } else {
            $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username_or_email, $username_or_email]);
            $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];

            if ($user['role'] === 'admin') {
                header('Location: ../admin/index.php');
            } else {
                header('Location: ../dashboard.php');
            }
            exit();
        } else {
            $error = "Неверный логин или пароль";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="/assets/js/script.js" defer></script>
</head>
<body>
<?php require_once '../includes/header.php'; ?>

<div class="auth-container">
    <h1>Вход</h1>
    <?php if (isset($error)) echo "<p class='error'>{$error}</p>"; ?>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
        <input type="text" name="username_or_email" placeholder="Логин или Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
    </form>

    <p>Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</div>

<?php require_once '../includes/footer.php'; ?>
</body>
</html>