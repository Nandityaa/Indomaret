<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: /indomaret/pages/users/login.php");
    exit;
}

$user = $_SESSION['user'];
$role = $user['role']; // admin, kasir, user
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Indomaret</title>
<style>
body { margin:0; font-family: Arial,sans-serif; background:#f5f5f5; color:#fff; }
header { background:#005dd6; padding:15px 50px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; }
.logo-container { display:flex; align-items:center; gap:10px; }
.logo-container img { height:45px; }
.logo-container h1 { margin:0; font-size:20px; font-weight:bold; color:#fff; }
nav { flex:1 1 auto; margin-left:400px; }
nav ul { list-style:none; display:flex; gap:15px; margin:0; padding:0; }
nav ul li a { color:#fff; text-decoration:none; font-weight:600; padding:8px 16px; border-radius:6px; transition:0.3s; }
nav ul li a:hover { background:#ffcc00; color:#000; box-shadow:0 3px 6px rgba(0,0,0,0.2); }
.user-box { background:rgba(255,255,255,0.15); padding:8px 16px; border-radius:8px; display:flex; align-items:center; gap:12px; }
.logout-btn { background:#ff3b3b; border:none; color:#fff; padding:6px 10px; border-radius:6px; cursor:pointer; font-weight:bold; transition:.3s; }
.logout-btn:hover { background:#d60000; }
.indomaret-strip { height:5px; background: linear-gradient(to right,#e60012 33%,#ffcc00 33% 66%,#004aad 66%); }
</style>
</head>

<body>
<header>
<div class="logo-container">
<img src="/indomaret/assets/img/indomaret.png" alt="Logo Indomaret">
<h1>Aplikasi Indomaret (POS)</h1>
</div>

<nav>
<ul>
<li><a href="/indomaret/index.php">Home</a></li>
<?php if($role === 'admin'): ?>
<li><a href="/indomaret/pages/cashiers/list.php">Kasir</a></li>
<li><a href="/indomaret/pages/products/list.php">Produk</a></li>
<li><a href="/indomaret/pages/transactions/list.php">Transaksi</a></li>
<?php elseif($role === 'kasir'): ?>
<li><a href="/indomaret/pages/products/list.php">Produk</a></li>
<li><a href="/indomaret/pages/transactions/list.php">Transaksi</a></li>
<?php endif; ?>
</ul>
</nav>

<div class="user-box">
👤 <?= htmlspecialchars($user['username']) ?>
<form action="/indomaret/pages/users/logout.php" method="post" style="margin:0;">
<button type="submit" class="logout-btn">Logout</button>
</form>
</div>
</header>

<div class="indomaret-strip"></div>
<main>