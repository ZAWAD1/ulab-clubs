<?php
session_start();
require_once 'config.php';
$_SESSION = [];
session_destroy();
session_start();
$_SESSION['flash'] = 'You have been logged out.';
header('Location: index.php');
exit;
