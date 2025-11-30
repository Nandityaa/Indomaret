<?php
// create_admin.php (jalankan sekali lalu hapus)
require_once __DIR__ . '/config/config.php';

$username = 'admin';
$password = 'admin123'; // ubah setelah berhasil
$role = 'admin';

// cek apakah sudah ada
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "User admin sudah ada. Hapus file ini kalau sudah selesai.";
    exit;
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hash, $role);
if ($stmt->execute()) {
    echo "Admin dibuat. Username: $username, Password: $password<br>";
    echo "Hapus file create_admin.php sekarang.";
} else {
    echo "Gagal: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>