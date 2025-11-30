<?php
// config/config.php

// Database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "indomaret_rpl4"; // ganti kalau nama DB lo beda

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Koneksi DB gagal: " . $conn->connect_error);
}

// Base URL (sesuaikan jika dipakai di server)
$base_url = "/indomaret";

// timezone
date_default_timezone_set("Asia/Makassar");
?>