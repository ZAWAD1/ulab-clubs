<?php
session_start();
require_once 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: clubs.php');
    exit;
}

$club_id = (int) ($_POST['club_id'] ?? 0);
if ($club_id <= 0) {
    $_SESSION['flash'] = 'Invalid club selection.';
    header('Location: clubs.php');
    exit;
}

// Check if user already has membership
$stmt = $conn->prepare('SELECT id FROM memberships WHERE user_id=? LIMIT 1');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    $_SESSION['flash'] = 'You are already a member of a club.';
    header('Location: clubs.php');
    exit;
}
$stmt->close();

// Insert membership
$stmt = $conn->prepare('INSERT INTO memberships (user_id, club_id) VALUES (?,?)');
$stmt->bind_param('ii', $_SESSION['user_id'], $club_id);
if ($stmt->execute()) {
    $_SESSION['flash'] = 'Membership confirmed!';
    header('Location: membership.php');
    exit;
}
$stmt->close();

$_SESSION['flash'] = 'Could not join the club.';
header('Location: clubs.php');
exit;
