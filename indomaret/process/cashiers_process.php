<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id     = (int) $_POST['id']; // langsung casting ke integer
    $action = $_POST['action'];
    $name   = mysqli_real_escape_string($conn, $_POST['nama_kasir']);
    
    if ($action == 'add') {
        $query = "INSERT INTO kasir (id, nama_kasir) VALUES ($id, '$name')";
        mysqli_query($conn, $query);
    } elseif ($action == 'edit') {
        $query = "UPDATE kasir SET nama_kasir='$name' WHERE id=$id";
        mysqli_query($conn, $query);
    } elseif ($action == 'delete') {
        $query = "DELETE FROM kasir WHERE id=$id";
        mysqli_query($conn, $query);
    }

    header("Location: ../pages/cashiers/list.php");
    exit;
}
?>