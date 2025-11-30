<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = $_GET['id'];
$produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM produk WHERE id = '$id'"));
?>

<center>
    <h2>Edit Produk</h2>
    <form action="/indomaret/process/products_process.php" method="POST">
        <link rel="stylesheet" href="/indomaret/assets/css/style.css">
        <table cellpadding="10">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?= $produk['id'] ?>" />

            <tr>
                <td><label>Nama Produk:</label></td>
                <td><input type="text" name="nama_produk" value="<?= $produk['nama_produk'] ?>" required /></td>
            </tr>

            <datalist id="voucher_list">
                <?php
                $query = mysqli_query($conn, "SELECT id, nama_voucher, diskon FROM voucher WHERE status='aktif'");
                while ($voucher = mysqli_fetch_assoc($query)) {
                ?>
                    <option value="<?= $voucher['id'] ?>">
                        <?= $voucher['nama_voucher'] ?> - <?= $voucher['diskon'] * 100 ?>%
                    </option>
                <?php
                }
                ?>
            </datalist>

            <tr>
                <td><label>Voucher:</label></td>
                <td><input type="text" list="voucher_list" name="id_voucher" value="<?= $produk['id_voucher'] ?>" /></td>
            </tr>

            <tr>
                <td><label>Harga Satuan:</label></td>
                <td><input type="number" name="harga_satuan" value="<?= $produk['harga_satuan'] ?>" required /></td>
            </tr>

            <tr>
                <td><label>Stok:</label></td>
                <td><input type="number" name="stok" value="<?= $produk['stok'] ?>" required /></td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <button type="submit" style="float:right">Update</button>
                </td>
            </tr>
        </table>
    </form>
</center>

<?php include ROOTPATH . "/includes/footer.php"; ?>
