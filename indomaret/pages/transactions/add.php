<?php
session_start();
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

// Proses form saat tombol "Selanjutnya" diklik
if (isset($_POST['selanjutnya'])) {

    // Generate kode transaksi baru
    $data_terakhir = mysqli_fetch_assoc(mysqli_query($conn, "SELECT kode_transaksi FROM transaksi ORDER BY id DESC LIMIT 1"));
    if ($data_terakhir) {
        $urutan = (int) substr($data_terakhir['kode_transaksi'], 3, 4) + 1;
        $kode_transaksi = "TRX" . str_pad($urutan, 4, "0", STR_PAD_LEFT);
    } else {
        $kode_transaksi = "TRX0001";
    }

    // Ambil ID kasir dari dropdown
    $id_kasir = mysqli_real_escape_string($conn, $_POST['id_kasir']);

    // Set waktu dan total awal
    date_default_timezone_set('Asia/Makassar');
    $tanggal = date("Y-m-d H:i:s");
    $total_harga = 0;

    // Simpan transaksi baru
    $insert = mysqli_query($conn, "INSERT INTO transaksi (tanggal, kode_transaksi, id_kasir, total_harga) 
                                   VALUES ('$tanggal', '$kode_transaksi', '$id_kasir', '$total_harga')");

    if ($insert) {
        $id_transaksi = mysqli_insert_id($conn);
        $_SESSION['id_transaksi'] = $id_transaksi;
        header("Location: transaction_details.php?id=" . $id_transaksi);
        exit;
    } else {
        echo "<p>Gagal membuat transaksi: " . mysqli_error($conn) . "</p>";
    }
}
?>

<div style="padding: 20px; max-width: 500px; margin: 40px auto; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
    <h2 style="text-align: center; color: #333; margin-bottom: 20px;">Tambah Transaksi Indomaret</h2>
    <hr style="border-color: #ddd;">
    <link rel="stylesheet" href="/indomaret/assets/css/style.css">

    <form action="" method="POST">
        <div style="margin-bottom: 20px;">
            <label for="id_kasir" style="display: block; margin-bottom: 5px; font-weight: bold;">Pilih Kasir:</label>
            <select name="id_kasir" style="width: 100%; padding: 10px; border-radius: 4px; border:1px solid #ccc;" required>
                <option value="">-- Pilih Kasir --</option>
                <?php
                $kasir_q = mysqli_query($conn, "SELECT id, nama_kasir FROM kasir WHERE status='Aktif'");
                while ($k = mysqli_fetch_assoc($kasir_q)) {
                    echo "<option value='{$k['id']}'>{$k['nama_kasir']}</option>";
                }
                ?>
            </select>
        </div>

        <input type="submit" name="selanjutnya" value="Selanjutnya" 
               style="width: 100%; padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
    </form>
</div>

<?php include ROOTPATH . "/includes/footer.php"; ?>