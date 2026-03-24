<?php
session_start();
include 'service/database.php';

$id_users = $_SESSION['id_users'];
$stmt2 = $db->prepare("SELECT kas_masuk, kas_keluar, kas_total, keterangan_users, tanggal 
 FROM uang_kelompok 
 WHERE id_users = ?
 ORDER BY id_tanggal DESC
 ");

$stmt2->bind_param("i", $id_users);
$stmt2->execute();
$result2 = $stmt2->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>all pegeluaran</title>
</head>
<body>
    <?php include 'layout/header.html'; ?>
    <table>
        <tr>
            <th>no.</th>
            <th>tanggal</th>
            <th>pemasukan</th>
            <th>pengeluaran</th>
            <th>total uang</th>
            <th>keterangan</th>
        </tr>
        <?php 
        $no = 1;
        while ($row = $result2->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['tanggal'] . "</td>";
            echo "<td>" . $row['kas_masuk'] . "</td>";
            echo "<td>" . $row['kas_keluar'] . "</td>";
            echo "<td>" . $row['kas_total'] . "</td>";
            echo "<td>" . $row['keterangan_users'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <?php include 'layout/footer.html'; ?>
</body>
</html>