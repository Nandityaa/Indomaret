<?php
define ('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

$action     = $_POST['action'];
$name       = $_POST['nama_produk'];
$unit_price = $_POST['harga_satuan'];
$stock      = $_POST['stok'];

if ($_POST['id_voucher'] == NULL) {
    $id_voucher = "NULL";
} else {  
    $id_voucher = "'" . $_POST['id_voucher'] . "'";
}   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'add') {
        $query = "INSERT INTO produk (id_voucher, nama_produk, harga_satuan, stok) 
                  VALUES ($id_voucher, '$name', '$unit_price', '$stock')";
        mysqli_query($conn, $query);

    } elseif ($action == 'edit') {
        $id = $_POST['id'];
        $query = "UPDATE produk 
                  SET id_voucher=$id_voucher, nama_produk='$name', harga_satuan='$unit_price', stok='$stock' 
                  WHERE id=$id";
        mysqli_query($conn, $query);

    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $query = "DELETE FROM produk WHERE id=$id";
        mysqli_query($conn, $query);
    }

    header("Location: ../pages/products/list.php");
    exit;
}
?>