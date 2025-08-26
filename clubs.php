<?php
session_start();
require_once 'config.php';

// Fetch clubs
$clubs = [];
$res = $conn->query('SELECT id, name, description FROM clubs ORDER BY name');
while ($row = $res->fetch_assoc()) {
    $clubs[] = $row;
}

// Current membership if logged in
$current_membership = null;
if (!empty($_SESSION['user_id'])) {
    $stmt = $conn->prepare('SELECT m.club_id FROM memberships m WHERE m.user_id=?');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $stmt->bind_result($cid);
    if ($stmt->fetch()) {
        $current_membership = (int) $cid;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Clubs</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <!-- Navbar -->
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

    <!-- Page Content -->
    <div class="container">
        <?php if ($msg = flash_pop()): ?>
            <div class="flash"><?= htmlspecialchars($msg) ?></div>
        <?php endif; ?>

        <h2>Browse Clubs</h2>

        <div class="grid grid-2">
            <?php foreach ($clubs as $c): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($c['name']) ?></h3>
                    <p class="muted"><?= htmlspecialchars($c['description'] ?? '') ?></p>
                    <?php if (empty($_SESSION['user_id'])): ?>
                        <a class="btn btn-outline" href="login.php">Login to join</a>
                    <?php else: ?>
                        <?php if ($current_membership && $current_membership === (int) $c['id']): ?>
                            <button class="btn btn-success" disabled>Already a member</button>
                        <?php elseif ($current_membership): ?>
                            <button class="btn btn-outline" disabled>Membership locked (one club only)</button>
                        <?php else: ?>
                            <form method="post" action="join.php">
                                <input type="hidden" name="club_id" value="<?= (int) $c['id'] ?>">
                                <button class="btn btn-primary" type="submit">Join this club</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>