<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$id = $_GET['id'] ?? '';

$cashier = null;

if ($id !== '') {
    // Gunakan prepared statement
    $stmt = $conn->prepare("SELECT * FROM kasir WHERE id = ?");
    $stmt->bind_param("i", $id); // i = integer, kalau id varchar ganti "s"
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $cashier = $result->fetch_assoc();
    }
    $stmt->close();
}

if (!$cashier) {
    echo "<p>Cashier not found.</p>";
    include ROOTPATH . "/includes/footer.php";
    exit;
}
?>

<center>
    <h2>Edit Kasir</h2>
    <form action="/indomaret/process/cashiers_process.php" method="post">
        <link rel="stylesheet" href="/indomaret/assets/css/style.css">
        <table cellpadding="10">
            <input type="hidden" name="action" value="edit" />
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($cashier['id']); ?>" />
            <tr>
                <td><label>Nama Kasir:</label></td>
                <td>
                    <input type="text" name="nama_kasir" 
                           value="<?php echo htmlspecialchars($cashier['nama_kasir']); ?>" required />
                </td>
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