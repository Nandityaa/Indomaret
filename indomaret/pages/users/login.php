<?php
// pages/users/login.php
require_once __DIR__ . '/../../config/config.php';
session_start();

// kalau sudah login, redirect ke index
if (isset($_SESSION['user'])) {
    header("Location: " . $base_url . "/index.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = "Isi username dan password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password, role FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res && $res->num_rows === 1) {
            $row = $res->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // sukses login
                session_regenerate_id(true);
                $_SESSION['user'] = [
                    'id' => $row['id'],
                    'username' => $row['username'],
                    'role' => $row['role']
                ];
                header("Location: " . $base_url . "/index.php");
                exit;
            } else {
                $error = "Username atau password salah.";
            }
        } else {
            $error = "Username atau password salah.";
        }
        $stmt->close();
    }
}
$message = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $message = "Anda sudah terdaftar, silakan login.";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Login - Indomaret</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="/indomaret/assets/css/auth.css">

<style>
* {
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
}
body {
    background: #f5f5f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
/* CARD */
.card {
    background: #fff;
    width: 360px;
    padding: 32px 28px;
    border-radius: 12px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.18);
}
/* LOGO */
.card img { height: 60px; margin-bottom: 20px; }
/* HEADING */
.card h2 { color: #004aad; font-size: 22px; margin-bottom: 20px; }
/* INPUT */
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: border 0.3s, box-shadow 0.3s;
}
input:focus {
    border-color: #004aad;
    box-shadow: 0 0 8px rgba(0,74,173,0.3);
    outline: none;
}
/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    margin-top: 12px;
    border-radius: 8px;
    border: none;
    background: #004aad;
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    font-size: 15px;
    transition: background 0.3s, transform 0.2s;
}
button:hover { background: #00327a; transform: translateY(-2px); }
/* ERROR */
.error { color: #c62828; font-weight: 600; margin-top: 8px; }
/* NOTE */
.note { color: #555; font-size: 13px; margin-top: 12px; }
/* RESPONSIVE */
@media screen and (max-width: 400px) { .card { width: 90%; padding: 24px; } }
</style>
</head>
<body>

<div class="container">

    <!-- LOGIN BOX -->
    <div class="card login-box">
        <img src="/indomaret/assets/img/indomaret.png" alt="Logo Indomaret">
        <?php if($message): ?>
        <div class="success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
                
        <h2>Login Indomaret</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="post" autocomplete="off">
            <input type="text" name="username" placeholder="Username" required value="<?= isset($username) ? htmlspecialchars($username) : '' ?>">
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Masuk</button>
        </form>

        <div class="note">
            Belum punya akun? <a href="daftar.php">Daftar di sini</a>.
        </div>
    </div>

    <!-- ROLE BOX -->
    <div class="card role-box">
        <h3>Role & Hak Akses</h3>

        <div class="role-item admin">
            <h4>👑 Admin</h4>
            <p><b>Username:</b> admin<br><b>Password:</b> admin123</p>
            <small>Akses penuh sistem</small>
        </div>

        <div class="role-item kasir">
            <h4>💳 Kasir</h4>
            <p><b>Username:</b> kasir<br><b>Password:</b> kasir123</p>
            <small>Input & lihat transaksi</small>
        </div>

        <div class="role-item user">
            <h4>👤 User</h4>
            <p><b>Username:</b> user<br><b>Password:</b> user123</p>
            <small>Akses halaman Home</small>
        </div>
    </div>

</div>

</div>
</body>
</html>
