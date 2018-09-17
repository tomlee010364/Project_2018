<?php
session_start();
$_SESSION['is_login'] = $_GET['id'];
?>
<meta http-equiv="refresh" content="0;url=homepage.php" />