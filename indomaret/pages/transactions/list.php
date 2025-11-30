<?php
// Tentukan folder root
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

// Include konfigurasi dan header
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>
<?php if (isset($_GET['paid']) && $_GET['paid'] === 'success'): ?>
    <div style="background:#27ae60;color:#fff;padding:10px;border-radius:6px;margin:12px 0;text-align:center;">
        ✔ Transaksi berhasil dibayar.
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div style="background:#e74c3c;color:#fff;padding:10px;border-radius:6px;margin:12px 0;text-align:center;">
        ✔ Transaksi berhasil dihapus.
    </div>
<?php endif; ?>

<br>

<center>
    <h2>List Transaksi</h2>
    <!-- Tombol tambah transaksi -->
    <a href="add.php" class="btn btn-success">Add Transaksi</a><br><br>
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">

    <table class="tabel-data">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Kode Transaksi</th>
            <th>Nama Kasir</th>
            <th>Total</th>
            <th colspan="3">Aksi</th>
        </tr>

        <?php
        $no = 1;

        // Ambil semua transaksi + nama kasir
        $query = mysqli_query($conn, "
            SELECT transaksi.id AS id_transaksi, transaksi.tanggal, transaksi.kode_transaksi,
                   transaksi.total_harga, kasir.nama_kasir
            FROM transaksi
            LEFT JOIN kasir ON transaksi.id_kasir = kasir.id
            ORDER BY transaksi.id DESC
        ");

        while($t = mysqli_fetch_assoc($query)):
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $t['tanggal'] ?></td>
            <td><?= $t['kode_transaksi'] ?></td>
            <td><?= $t['nama_kasir'] ?></td>
            <td><?= $t['total_harga'] ?></td>
            <td>
                <a href="transaction_details.php?id=<?= $t['id_transaksi'] ?>" class="btn btn-info">Lihat Detail</a>
            </td>
            <td>
                <a href="edit.php?id=<?= $t['id_transaksi'] ?>" class="btn btn-warning">Edit</a>
            </td>
            <td>
                <?php
                // Cek apakah transaksi punya detail
                $cek = mysqli_query($conn, "SELECT id_transaksi FROM detail_transaksi WHERE id_transaksi = '{$t['id_transaksi']}'");
                if(mysqli_num_rows($cek) > 0):
                ?>
                    <!-- Jika punya detail, tombol delete disabled -->
                    <input type="button" value="Delete" class="btn btn-danger" disabled>
                <?php else: ?>
                <form action="/indomaret/process/transaction_process.php" method="POST" 
                    onsubmit="return confirm('Yakin ingin menghapus transaksi ini?');">
                    <input type="hidden" name="action" value="delete_transaction">
                    <input type="hidden" name="id_transaksi" value="<?= $t['id_transaksi'] ?>">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</center>

<?php include ROOTPATH . "/includes/footer.php"; ?>
