<?php
session_start();
include 'service/database.php';

$id_users = $_SESSION['id_users'];
$query = $db->prepare("SELECT anggota FROM daftar_anggota WHERE id_users = ?");
$query->bind_param("i", $id_users);
$query->execute();

$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kas rutin</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table{
            margin: 20px;
            border-collapse: collapse;
        }

        th, td{
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <?php include 'layout/header.html' ?>
    <h2>mulai kas hari ini?</h2>
    <form action="page.php" method="post">
    <table style="border: 1px solid black;">
        <tr>
            <th>no.</th>
            <th>nama</th>
            <th>kas berapa</th>
        </tr>
        <tbody>
            <?php 
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                <td>".$no++."</td>
                <td>".$row['anggota']."</td>
                <td><input type='number' name='kas_".$no."'></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <button type="submit" name="simpan">simpan</button>
    </form>

    <?php include 'layout/footer.html' ?>
</body>
</html>