<?php
session_start();
require_once 'config.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    if ($student_id === '' || $name === '' || $email === '' || $password === '') {
        $errors[] = 'All fields are required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }


    if (!$errors) {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email=? OR student_id=? LIMIT 1');
        $stmt->bind_param('ss', $email, $student_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = 'Account already exists with this email or student ID.';
        }
        $stmt->close();
    }


    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (student_id, name, email, password_hash) VALUES (?,?,?,?)');
        $stmt->bind_param('ssss', $student_id, $name, $email, $hash);
        if ($stmt->execute()) {
            $_SESSION['flash'] = 'Registration successful. Please log in.';
            header('Location: login.php');
            exit;
        } else {
            $errors[] = 'Failed to register. Please try again.';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="nav">
        <div class="container">
            <div class="logo">ULAB Club Membership</div>
        </div>
    </div>
    <div class="container">
        <div class="card" style="max-width:560px;margin:0 auto;">
            <h2>Create Account</h2>
            <?php if ($errors): ?>
                <div class="flash" style="background:#fef2f2;border-color:#fecaca;color:#7f1d1d;">
                    <?= implode('<br>', array_map('htmlspecialchars', $errors)) ?>
                </div><?php endif; ?>
            <form method="post">
                <div class="field">
                    <label class="label">Student ID</label>
                    <input class="input" type="text" name="student_id" required
                        value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>">
                </div>
                <div class="field">
                    <label class="label">Full Name</label>
                    <input class="input" type="text" name="name" required
                        value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                </div>
                <div class="field">
                    <label class="label">Email</label>
                    <input class="input" type="email" name="email" required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="field">
                    <label class="label">Password</label>
                    <input class="input" type="password" name="password" required>
                </div>
                <button class="btn btn-primary" type="submit">Register</button>
                <a class="btn btn-outline" href="login.php">Have an account? Login</a>
            </form>
        </div>
    </div>
</body>

</html>