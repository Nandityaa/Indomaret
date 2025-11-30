<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>
<br>
<center>
    <h2>List Produk</h2>
    <a href="add.php" class="btn btn-edit">Tambah Produk</a><br><br>
    <table class="tabel-data">
        <link rel="stylesheet" href="/indomaret/assets/css/style.css">

        <thead>
            <tr>
                <th>No</th>
                <th>ID Voucher</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Stok</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($conn, "SELECT * FROM produk");
            while ($produk = mysqli_fetch_assoc($query)) {
            ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $produk['id_voucher'] ?: '' ?></td>
                    <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
                    <?php
                    if (!empty($produk['id_voucher'])) {
                        $id_voucher = mysqli_real_escape_string($conn, $produk['id_voucher']);
                        $query_voucher = mysqli_query($conn, "SELECT diskon, maks_diskon FROM voucher WHERE id = '$id_voucher' AND status='aktif'");

                        if ($query_voucher && mysqli_num_rows($query_voucher) > 0) {
                            $voucher = mysqli_fetch_assoc($query_voucher);

                            // hitung potongan diskon
                            $potongan = $produk['harga_satuan'] * $voucher['diskon'];
                            if ($potongan > $voucher['maks_diskon']) {
                                $potongan = $voucher['maks_diskon'];
                            }
                            $harga_diskon = $produk['harga_satuan'] - $potongan;
                            ?>
                            <td>
                                <del style="color:red"><?= number_format($produk['harga_satuan'], 0, ',', '.') ?></del>
                                <b style="color:green"><?= number_format($harga_diskon, 0, ',', '.') ?></b>
                            </td>
                            <?php
                        } else {
                            // voucher tidak aktif / tidak ditemukan → harga normal
                            ?>
                            <td><?= number_format($produk['harga_satuan'], 0, ',', '.') ?></td>
                            <?php
                        }
                    } else {
                        // produk memang tidak ada voucher → harga normal
                        ?>
                        <td><?= number_format($produk['harga_satuan'], 0, ',', '.') ?></td>
                        <?php
                    }
                    ?>
                    <td>
                    <a href="edit.php?id=<?= $produk['id'] ?>" class="btn btn-edit">Edit</a>
                </td>
                <td>
                    <?php
                    $id_produk = $produk['id'];
                    $cek = mysqli_query($conn, "SELECT id_produk FROM detail_transaksi WHERE id_produk = '$id_produk'");
                    if (mysqli_num_rows($cek) > 0) {
                    ?>
                         <button class="btn btn-delete disabled" disabled>Delete</button>
                    <?php
                    } else {
                    ?>
                        <form action="/indomaret/process/products_process.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?')" style="display:inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $produk['id'] ?>">
                            <button type="submit" class="btn btn-delete">Delete</button>
                        </form>
                    <?php
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</center>

<?php
include ROOTPATH . "/includes/footer.php";
?>