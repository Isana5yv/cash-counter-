<?php
session_start();
include 'service/database.php';

$id_users = $_SESSION['id_users'];
$query = $db->prepare("SELECT id_aggota, anggota FROM daftar_anggota WHERE id_users = ?");
$query->bind_param("i", $id_users);
$query->execute();

$result = $query->get_result();
$data_anggota = $result->fetch_all(MYSQLI_ASSOC);

$query->close();

$hasil = [];
foreach ($data_anggota as $row) {
    $id_anggota = $row['id_aggota'];
    
    $query2 = $db->prepare("SELECT uang_total FROM uang_anggota WHERE id_users = ? AND id_anggota = ? ORDER BY id_uang DESC");
    $query2->bind_param("ii", $id_users, $id_anggota);
    $query2->execute();
    $all = $query2->get_result();
    //while ()
    $data = $all->fetch_all(MYSQLI_ASSOC); {
        $hasil[] = [$id_users, $id_anggota, $data];
    //}
    $query2->close();
}}

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
echo "<pre>";
                print_r($hasil);
                echo "</pre>";
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
                $max_kolom = 10;
                //$data_uang = [];
                foreach ($data_anggota as $row) {

                    $id = $row['id_aggota'];
                    //$list = $data_uang[$id] ?? [];

                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['anggota'] . "</td>";
                    echo "<td>$id</td>"; // id_anggota
                    echo "<td>$id_users</td>"; // id_users
                    echo "<td><input type='number' name='kas_$id'></td>";

                    // 🔥 kolom mengikuti data terbanyak
                    $ketemu = false;

    foreach ($hasil as $h) {
        if ($h[1] == $id) {
            $ketemu = true;

            for ($i = 0; $i < $max_kolom; $i++) {
                if (isset($h[2][$i])) {
                    echo "<td>" . $h[2][$i]['uang_total'] . "</td>";
                } else {
                    // 🔴 kalau index ga ada
                    echo "<td style='background:red'></td>";
                }
            }
        }}

                    echo "</tr>";
                }

                //echo "<pre>";
                //print_r($hasil[$data]);
                //echo "</pre>";
                ?>
            </tbody>
        </table>

        <button type="submit" name="simpan">simpan</button>
    </form>

    <?php include 'layout/footer.html' ?>
</body>

</html>