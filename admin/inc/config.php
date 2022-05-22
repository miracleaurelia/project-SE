<?php
ini_set('error_reporting', E_ALL);

date_default_timezone_set('Asia/Jakarta');

$dbhost = 'localhost';
$dbname = 'ecommerceweb';
$dbuser = 'root';
$dbpass = '';

define("ADMIN_URL", "admin" . "/");

try {
	$pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch( PDOException $exception ) {
	echo "Connection error :" . $exception->getMessage();
}