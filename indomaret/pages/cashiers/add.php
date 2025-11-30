<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');
include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";
?>
<center>
    <h2>Add Kasir</h2>
    <form action="/indomaret/process/cashiers_process.php" method="POST">
        <link rel="stylesheet" href="/indomaret/assets/css/style.css">
        <table cellpadding="10">
            <input type="hidden" name="action" value="add" />
            <tr>
                <td><label>ID :</label></td>
                <td><input type="number" name="id" required /></td>
            </tr>
            <tr>
                <td><label>Nama Kasir:</label></td>
                <td><input type="text" name="nama_kasir" required /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button type="submit" style="float:right">Simpan</button>
                </td>
            </tr>
        </table>
    </form>
</center>
<?php include ROOTPATH . "/includes/footer.php"; ?>