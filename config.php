<?php
$DB_HOST = 'localhost';
$DB_USER = 'root'; // change if needed
$DB_PASS = '';
$DB_NAME = 'ulab_club';


$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}


// Simple helper: require login
function require_login()
{
    if (empty($_SESSION['user_id'])) {
        $_SESSION['flash'] = 'Please log in to continue.';
        header('Location: login.php');
        exit;
    }
}


// Flash message helpers
function flash_pop()
{
    if (!empty($_SESSION['flash'])) {
        $msg = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $msg;
    }
    return '';
}
?>