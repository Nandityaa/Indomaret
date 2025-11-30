<?php
define('ROOTPATH', $_SERVER['DOCUMENT_ROOT'] . '/indomaret');

include ROOTPATH . "/config/config.php";
include ROOTPATH . "/includes/header.php";

$result = mysqli_query($conn, "SELECT * FROM kasir");
?>

<center>
    <h2>List kasir</h2>
    <a href="add.php">Add kasir</a><br><br>
    <table border="1" cellpadding="10" cellspacing="0">
        <link rel="stylesheet" href="/indomaret/assets/css/style.css">
        <thead>
            <tr>
                <th>No</th>
                <th>Name kasir</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)){ ?>
            <tr>
                <td><?= $no++?></td>
                <td><?= htmlspecialchars($row['nama_kasir']) ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                </td>
                <td>
                    <?php
                $id_kasir = $row['id'];
                $cek = mysqli_query($conn, "SELECT id FROM transaksi WHERE id_kasir = '$id_kasir'");
                if(mysqli_num_rows($cek) > 0){
                ?>
                    <input type="button" value="delete" disabled>
                    <?php
                }else{
                ?>
                    <form action="/indomaret/process/cashiers_process.php" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus data kasir ini?')">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?=$row['id']?>">
                        <input type="submit" value="delete">
                    </form>
                    <?php
                }
                ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</center>

<?php include "../../includes/footer.php"; ?>