<?php
require_once __DIR__ . '/../../config/config.php';
include "../../includes/header.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>ID transaksi tidak ditemukan.</p>";
    exit;
}

// Biar gak warning
$error = $success = "";

// Ambil data transaksi berdasarkan ID
$stmt = $conn->prepare("SELECT * FROM transaksi WHERE id = ?");
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$transaksi = $result->fetch_assoc();

if (!$transaksi) {
    exit("<p>Data transaksi tidak ditemukan.</p>");
}

// PROSES UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $status = ($_POST['status'] == "paid") ? "paid" : "unpaid";
    $dibayar = trim($_POST['dibayar']);

    // Hitung kembalian
    $kembalian = $dibayar - $transaksi['total_harga'];

    $update = $conn->prepare("UPDATE transaksi SET status=?, dibayar=?, kembalian=? WHERE id=?");
    $update->bind_param("siii", $status, $dibayar, $kembalian, $id);

    if ($update->execute()) {
        $success = "Transaksi berhasil diperbarui!";

        // Refresh data setelah update
        $stmt->execute();
        $result = $stmt->get_result();
        $transaksi = $result->fetch_assoc();
    } else {
        $error = "Gagal memperbarui transaksi.";
    }

    $update->close();
}
?>

<link rel="stylesheet" href="/indomaret/assets/css/style.css">

<div class="container py-8">
    <h2 class="text-2xl font-bold mb-4">Edit Transaksi</h2>

    <?php if ($error): ?>
        <div style="color: red; margin-bottom: 10px;"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div style="color: green; margin-bottom: 10px;"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" class="bg-white p-6 rounded shadow-md max-w-lg mx-auto text-black">

        <p><strong>Kode Transaksi:</strong> <?= htmlspecialchars($transaksi['kode_transaksi']) ?></p>
        <p><strong>Total Harga:</strong> Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></p>
        <p><strong>Kembalian Sekarang:</strong> Rp <?= number_format($transaksi['kembalian'], 0, ',', '.') ?></p>
        <hr class="my-3">

        <label>Status Pembayaran:</label>
        <select name="status" class="w-full p-2 border rounded mb-4 text-black">
            <option value="unpaid" <?= $transaksi['status']=="unpaid" ? "selected" : "" ?>>Belum Dibayar</option>
            <option value="paid" <?= $transaksi['status']=="paid" ? "selected" : "" ?>>Dibayar</option>
        </select>

        <label>Jumlah Dibayar:</label>
        <input type="number" name="dibayar" value="<?= htmlspecialchars($transaksi['dibayar']) ?>" class="w-full p-2 border rounded mb-4 text-black bg-white" required>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
        <a href="/indomaret/pages/transactions/list.php" class="ml-2 text-gray-700">Batal</a>
    </form>
</div>

<?php include "../../includes/footer.php"; ?>