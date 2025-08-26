<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ULAB Clubs</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="nav">
        <div class="container">
            <div class="logo">ULAB Club Membership</div>
            <div>
                <a href="index.php">Home</a>
                <a href="clubs.php">Clubs</a>
                <a href="membership.php">My Membership</a>
                <?php if (!empty($_SESSION['user_id'])): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <div class="container">
        <?php if ($msg = flash_pop()): ?>
            <div class="flash"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>


        <div class="card">
            <div class="header">
                <h2>Welcome to ULAB Clubs</h2>
            </div>
            <p class="muted">Discover student communities on campus. Log in to join exactly one club and view your
                membership details.</p>

        </div>
    </div>
</body>

</html>