<?php
// transaction_details.php (robust versi)
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    echo "<div style='color:#c0392b'>ID transaksi tidak valid.</div>";
    include ROOTPATH . "/includes/footer.php";
    exit;
}

/* ambil header transaksi */
$qHeader = mysqli_query($conn, "
    SELECT t.*, k.nama_kasir AS cashiername
    FROM transaksi t
    LEFT JOIN kasir k ON k.id = t.id_kasir
    WHERE t.id = '$id'
");
if (!$qHeader) die("SQL ERROR (header): " . mysqli_error($conn));
$trx = mysqli_fetch_assoc($qHeader);
if (!$trx) {
    echo "<div style='color:#c0392b'>Transaksi tidak ditemukan.</div>";
    include ROOTPATH . "/includes/footer.php";
    exit;
}

/* cek apakah detail_transaksi punya kolom 'id' (primary key) */
$colCheck = mysqli_query($conn, "SHOW COLUMNS FROM detail_transaksi LIKE 'id'");
$has_detail_id = ($colCheck && mysqli_num_rows($colCheck) > 0);

/* ambil detail sesuai struktur yang ada:
   - jika ada kolom id: SELECT d.id AS id_detail, ...
   - jika tidak: SELECT d.id_transaksi, d.id_produk, ... (tanpa id)
*/
if ($has_detail_id) {
    $qDetail = mysqli_query($conn, "
        SELECT d.id AS id_detail, d.id_produk, d.qty, d.harga_satuan, d.discount, d.sub_total, p.nama_produk
        FROM detail_transaksi d
        JOIN produk p ON p.id = d.id_produk
        WHERE d.id_transaksi = '$id'
        ORDER BY d.id ASC
    ");
} else {
    $qDetail = mysqli_query($conn, "
        SELECT d.id_transaksi, d.id_produk, d.qty, d.harga_satuan, d.discount, d.sub_total, p.nama_produk
        FROM detail_transaksi d
        JOIN produk p ON p.id = d.id_produk
        WHERE d.id_transaksi = '$id'
    ");
}
if ($qDetail === false) die("SQL ERROR (detail): " . mysqli_error($conn));

/* ambil daftar produk (untuk dropdown) */
$qProduk = mysqli_query($conn, "SELECT id, nama_produk, harga_satuan, stok FROM produk ORDER BY nama_produk ASC");
if ($qProduk === false) die("SQL ERROR (produk): " . mysqli_error($conn));

function rupiah($n){ return number_format((int)$n,0,',','.'); }
?>

<!-- (HTML + display sama seperti sebelumnya, saya ringkas ke bagian tabel) -->
<div style="max-width:1000px;margin:24px auto;padding:12px;">
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">
    <h2>Detail Transaksi - <?= htmlspecialchars($trx['kode_transaksi']) ?></h2>
    <p>Tanggal: <?= htmlspecialchars($trx['tanggal']) ?> | Kasir: <?= htmlspecialchars($trx['cashiername'] ?? '-') ?></p>

    <table style="width:100%;border-collapse:collapse;">
        <thead style="background:#005dd6; color:#fff;">
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Qty</th>
                <th style="text-align:right">Harga</th>
                <th style="text-align:right">Diskon</th>
                <th style="text-align:right">Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $no = 1; $sum = 0;
        if (mysqli_num_rows($qDetail) === 0) {
            echo "<tr><td colspan='7' style='text-align:center;padding:14px;color:#666'>Belum ada item.</td></tr>";
        } else {
            while ($d = mysqli_fetch_assoc($qDetail)) {
                $sum += (int)$d['sub_total'];
                echo "<tr>";
                echo "<td>{$no}</td>";
                echo "<td>" . htmlspecialchars($d['nama_produk']) . "</td>";
                echo "<td style='text-align:center'>" . (int)$d['qty'] . "</td>";
                echo "<td style='text-align:right'>" . rupiah($d['harga_satuan']) . "</td>";
                echo "<td style='text-align:right'>" . (is_numeric($d['discount']) ? $d['discount'].'%' : '-') . "</td>";
                echo "<td style='text-align:right'>" . rupiah($d['sub_total']) . "</td>";
                echo "<td style='text-align:center'>";

                // Delete form: kirim id_detail jika tersedia, kalau tidak kirim id_produk + id_transaksi
                echo "<form method='post' action='/indomaret/process/transaction_process.php' style='display:inline' onsubmit=\"return confirm('Hapus item ini?')\">";
                echo "<input type='hidden' name='action' value='delete_item'>";
                echo "<input type='hidden' name='id_transaksi' value='".(int)$id."'>";
                if ($has_detail_id) {
                    echo "<input type='hidden' name='id_detail' value='".(int)$d['id_detail']."'>";
                } else {
                    echo "<input type='hidden' name='id_produk' value='".(int)$d['id_produk']."'>";
                    echo "<input type='hidden' name='id_transaksi' value='".(int)$id."'>";
                    // Note: will delete one matching row (LIMIT 1) in process file
                }
                echo "<button class='btn' style='background:#e53935;color:#fff;padding:6px 10px;border:none;border-radius:6px; cursor:pointer;'>Hapus</button>";
                echo "</form>";

                echo "</td>";
                echo "</tr>";
                $no++;
            }
        }
        ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align:right;font-weight:700">Total</td>
                <td style="text-align:right;font-weight:700"><?= rupiah($sum) ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <!-- Pembayaran & tambah item (sama seperti sebelumnya) -->
    <?php if (($trx['status'] ?? '') !== 'paid'): ?>
        <div style="margin-top:12px; display:flex; gap:8px; align-items:center;">
            <form method="post" action="/indomaret/process/transaction_process.php" style="display:inline-flex; gap:8px;">
                <input type="hidden" name="action" value="pay">
                <input type="hidden" name="id_transaksi" value="<?= $id ?>">
                <div style="font-weight:700">Total: Rp <?= rupiah($sum) ?></div>
                <input type="number" name="bayar" min="<?= (int)$sum ?>" required placeholder="Masukkan nominal..." style="padding:8px; margin-left:8px;">
                <button type="submit" class="btn" style="background:#27ae60;color:#fff;border:none;padding:8px 12px;border-radius:6px;">Bayar</button>
            </form>

            <form method="post" action="/indomaret/process/transaction_process.php" style="margin-left:auto; display:flex; gap:8px;">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="id_transaksi" value="<?= $id ?>">
                <select name="produk" required style="padding:8px;">
                    <?php
                    mysqli_data_seek($qProduk, 0);
                    while ($p = mysqli_fetch_assoc($qProduk)) {
                        echo "<option value='".(int)$p['id']."'>".htmlspecialchars($p['nama_produk'])." - Rp ".rupiah($p['harga_satuan'])." (stok:".(int)$p['stok'].")</option>";
                    }
                    ?>
                </select>
                <input type="number" name="qty" value="1" min="1" required style="width:90px;padding:8px;">
                <button type="submit" class="btn" style="background:#005dd6;color:#fff;padding:8px;border-radius:6px;border:none;">Tambah</button>
            </form>
        </div>
    <?php else: ?>
        <div style="margin-top:12px;background:#e8f8f0;padding:10px;border-radius:6px;color:#116644;">
            <strong>✔ LUNAS</strong> — Dibayar: Rp <?= rupiah($trx['dibayar'] ?? 0) ?>, Kembalian: Rp <?= rupiah($trx['kembalian'] ?? 0) ?>
        </div>
    <?php endif; ?>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>