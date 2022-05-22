<?php 
ob_start();
session_start();
include 'inc/config.php'; 
unset($_SESSION['seller']);
header("location: ../login.php");
?>