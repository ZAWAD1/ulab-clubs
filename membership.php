<?php
session_start();
require_once 'config.php';
require_login();

$stmt = $conn->prepare('SELECT u.student_id, u.name, u.email, c.name AS club_name, c.description, m.joined_at
                        FROM users u
                        LEFT JOIN memberships m ON m.user_id = u.id
                        LEFT JOIN clubs c ON c.id = m.club_id
                        WHERE u.id=?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$info = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>My Membership</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="container">
        <?php if ($msg = flash_pop()): ?>
            <div class="flash"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
        <div class="card">
            <h2>Membership Details</h2>
            <table class="table">
                <tr>
                    <th>Student ID</th>
                    <td><?= htmlspecialchars($info['student_id']) ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?= htmlspecialchars($info['name']) ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?= htmlspecialchars($info['email']) ?></td>
                </tr>
                <tr>
                    <th>Club</th>
                    <td><?= $info['club_name'] ? htmlspecialchars($info['club_name']) : '<span class="muted">Not a member yet</span>' ?>
                    </td>
                </tr>
                <tr>
                    <th>Joined At</th>
                    <td><?= $info['club_name'] ? htmlspecialchars($info['joined_at']) : '-' ?></td>
                </tr>
            </table>
            <?php if (!$info['club_name']): ?>
                <p class="muted">Browse clubs and pick one you like. You can only join one club.</p>
                <a class="btn btn-primary" href="clubs.php">Browse Clubs</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>