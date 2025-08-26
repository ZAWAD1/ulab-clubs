<?php
session_start();
require_once 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $errors[] = 'Email and password are required.';
    } else {
        $stmt = $conn->prepare('SELECT id, student_id, name, password_hash FROM users WHERE email=? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->bind_result($id, $student_id, $name, $hash);
        if ($stmt->fetch()) {
            if (password_verify($password, $hash)) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $id;
                $_SESSION['student_id'] = $student_id;
                $_SESSION['name'] = $name;
                $_SESSION['flash'] = 'Welcome back, ' . $name . '!';
                header('Location: clubs.php');
                exit;
            } else {
                $errors[] = 'Incorrect password.';
            }
        } else {
            $errors[] = 'No account found with that email.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <div class="card" style="max-width:480px;margin:0 auto;">
            <h2>Login</h2>
            <?php if ($errors): ?>
                <div class="flash" style="background:#fef2f2;border-color:#fecaca;color:#7f1d1d;">
                    <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
                </div><?php endif; ?>
            <?php if ($msg = flash_pop()): ?>
                <div class="flash"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
            <form method="post">
                <div class="field">
                    <label class="label">Email</label>
                    <input class="input" type="email" name="email" required>
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <input class="input" type="password" name="password" required>
                </div>
                <button class="btn btn-primary" type="submit">Login</button>
                <a class="btn btn-outline" href="register.php">Create account</a>
            </form>
        </div>
    </div>
</body>

</html>