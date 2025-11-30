<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak sah.");
}

$id_transaksi = mysqli_real_escape_string($conn, $_POST['id_transaksi']);
$bayar = mysqli_real_escape_string($conn, $_POST['bayar']);

// Ambil total transaksi
$q = mysqli_query($conn, "SELECT total_harga FROM transaksi WHERE id='$id_transaksi'");
$data = mysqli_fetch_assoc($q);
$total = $data['total_harga'] ?? 0;

// Cek uang cukup atau tidak
if ($bayar < $total) {
    header("Location: /indomaret/pages/transactions/transaction_details.php?id=$id_transaksi&error=kurang");
    exit;
}

$kembalian = $bayar - $total;

// Update pembayaran
mysqli_query($conn, "
    UPDATE transaksi 
    SET status='paid',
        dibayar='$bayar',
        kembalian='$kembalian'
    WHERE id='$id_transaksi'
");

// Redirect kembali ke halaman detail
header("Location: /indomaret/pages/transactions/transaction_details.php?id=$id_transaksi&paid=success");
exit;
?>
