<?php
session_start();
include 'service/database.php';

$id_users = $_SESSION['id_users'];
$query = $db->prepare("SELECT id_aggota, anggota FROM daftar_anggota WHERE id_users = ?");
$query->bind_param("i", $id_users);
$query->execute();

$result = $query->get_result();

$query->close();

if (isset($_POST['simpan'])) {
    $stmt1 = $db->prepare("INSERT INTO uang_anggota(id_anggota, id_users, uang_total) VALUES (?,?,?)");
    foreach ($result as $row) {
        $id_anggota = $row['id_aggota'];
        $uang_total = $_POST['kas_' . $id_anggota];

        $stmt1->bind_param("iii", $id_anggota, $id_users, $uang_total);
        if ($stmt1->execute()) {
            echo "sipppp" . $id_anggota . "berhasil diinsert<br>";
        } else {
            die("gagal" . $stmt1->error);
        };
    }

    $stmt1->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kas rutin</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            margin: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <?php include 'layout/header.html' ?>
    <h2>mulai kas hari ini?</h2>
    <form action="rutinan.php" method="post">
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
                <td>" . $no++ . "</td>
                <td>" . $row['anggota'] . "</td>
                <td><input type='number' name='kas_" . $row['id_aggota'] . "'></td>
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