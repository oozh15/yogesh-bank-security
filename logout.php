<?php
session_start();
session_destroy();
session_start(); // Restart to carry the success message
$_SESSION['success'] = "Session Terminated. Security Cache Wiped.";
header("Location: login.php");
exit;
?>