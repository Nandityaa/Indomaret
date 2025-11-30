<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

$action = $_POST['action'] ?? null;
$id_transaksi = $_POST['id_transaksi'] ?? null;

if (!$action || !$id_transaksi) {
    die("Aksi tidak dikenali.");
}


// =============================
// 1. TAMBAH PRODUK
// =============================
if ($action === "add") {
    $id_produk = (int)$_POST['produk'];
    $qty = (int)$_POST['qty'];

    $q = mysqli_query($conn, "SELECT harga_satuan FROM produk WHERE id='$id_produk'");
    $p = mysqli_fetch_assoc($q);

    $harga = (int)$p['harga_satuan'];
    $subtotal = $qty * $harga;

    mysqli_query($conn, "
        INSERT INTO detail_transaksi (id_transaksi, id_produk, qty, harga_satuan, discount, sub_total)
        VALUES ('$id_transaksi', '$id_produk', '$qty', '$harga', 0, '$subtotal')
    ");

    // update total transaksi
    mysqli_query($conn,"
        UPDATE transaksi 
        SET total_harga = (SELECT COALESCE(SUM(sub_total),0) FROM detail_transaksi WHERE id_transaksi = '$id_transaksi')
        WHERE id = '$id_transaksi'
    ");

    header("Location: /indomaret/pages/transactions/transaction_details.php?id=$id_transaksi");
    exit;
}



// =============================
// 2. DELETE ITEM
// =============================
if ($action === "delete_item") {

    if (isset($_POST['id_detail'])) {
        $id_detail = (int)$_POST['id_detail'];
        mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id = '$id_detail' LIMIT 1");
    } else {
        $id_produk = (int)$_POST['id_produk'];
        mysqli_query($conn, "DELETE FROM detail_transaksi WHERE id_transaksi = '$id_transaksi' AND id_produk = '$id_produk' LIMIT 1");
    }

    // update total
    mysqli_query($conn,"
        UPDATE transaksi 
        SET total_harga = (SELECT COALESCE(SUM(sub_total),0) FROM detail_transaksi WHERE id_transaksi = '$id_transaksi')
        WHERE id = '$id_transaksi'
    ");

    header("Location: /indomaret/pages/transactions/transaction_details.php?id=$id_transaksi");
    exit;
}



// =============================
// 3. PAYMENT
// =============================
if ($action === "pay") {
    $bayar = (int)$_POST['bayar'];

    $q = mysqli_query($conn, "SELECT total_harga FROM transaksi WHERE id='$id_transaksi'");
    $t = mysqli_fetch_assoc($q);

    $total = (int)$t['total_harga'];

    if ($bayar < $total) {
        header("Location: /indomaret/pages/transactions/transaction_details.php?id=$id_transaksi&error=kurang");
        exit;
    }

    $kembalian = $bayar - $total;

    mysqli_query($conn, "
        UPDATE transaksi 
        SET status='paid', dibayar='$bayar', kembalian='$kembalian'
        WHERE id='$id_transaksi'
    ");

    header("Location: /indomaret/pages/transactions/transaction_details.php?id=$id_transaksi&paid=success");
    exit;
}



// =============================
// 4. DELETE TRANSACTION
// =============================
if ($action === "delete_transaction") {

    $cek = mysqli_query($conn, "SELECT 1 FROM detail_transaksi WHERE id_transaksi='$id_transaksi'");
    if (!$cek) die("SQL ERROR: " . mysqli_error($conn));

    if (mysqli_num_rows($cek) > 0) {
        header("Location: /indomaret/pages/transactions/list.php?error=masih_ada_detail");
        exit;
    }

    mysqli_query($conn, "DELETE FROM transaksi WHERE id='$id_transaksi' LIMIT 1");

    header("Location: /indomaret/pages/transactions/list.php?deleted=success");
    exit;
}



echo "Aksi tidak dikenali.";
?>