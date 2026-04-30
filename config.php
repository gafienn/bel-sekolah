<?php
session_start();
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_bell_sekolah');

 $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) die("Koneksi gagal: " . $mysqli->connect_error);
 $mysqli->set_charset("utf8mb4");
?>