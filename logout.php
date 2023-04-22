<?php
session_start();
include 'xyz.php';
unset($_SESSION['user_logado']);
session_destroy();
header("Location: ".BASE_URL . "login.php");
?>