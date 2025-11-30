<?php
require_once __DIR__ . '/../../config/config.php';
session_start();

if (isset($_SESSION['user'])) {
    header("Location: " . $base_url . "/index.php");
    exit;
}

// Inisialisasi variabel supaya tidak muncul "undefined variable" warning
$error = "";
$success = "";
$username = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if ($username === '' || $password === '' || $password2 === '') {
        $error = "Semua field wajib diisi.";
    } elseif ($password !== $password2) {
        $error = "Password tidak cocok.";
    } else {
        // cek username sudah ada
        $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $role = 'user';
            $stmt = $conn->prepare("INSERT INTO user (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hash, $role);

            if ($stmt->execute()) {
                header("Location: login.php?msg=registered");
                exit;
            } else {
                $error = "Gagal mendaftar, coba lagi.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Daftar User - Indomaret</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="/indomaret/assets/css/auth.css">
<style>
*{box-sizing:border-box;font-family:Arial,sans-serif;margin:0;padding:0}
body{background:#f5f5f5;display:flex;justify-content:center;align-items:center;height:100vh}
</style>
</head>
<body>
<div class="card">

    <!-- LOGO -->
    <img src="/indomaret/assets/img/indomaret.png" alt="Indomaret Logo">

    <h2>Daftar User Baru</h2>

    <?php if(isset($error) && $error !== ""): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if(isset($success) && $success !== ""): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <input type="text" name="username" placeholder="Username" required value="<?= htmlspecialchars($username) ?>">
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password2" placeholder="Konfirmasi Password" required>
        <button type="submit">Daftar</button>
    </form>

    <div class="note">
        Sudah punya akun? <a href="login.php">Login di sini</a>.
    </div>
</div>
</body>
</html>