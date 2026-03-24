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
    }
}

if (isset($_POST['simpan'])) {
    $total_kas = 0;
    $stmt1 = $db->prepare("INSERT INTO uang_anggota(id_anggota, id_users, uang_total) VALUES (?,?,?)");
    foreach ($result as $row) {
        $id_anggota = $row['id_aggota'];
        $uang_total = $_POST['kas_' . $id_anggota] ?? 0;

        $total_kas += $uang_total;

        $stmt1->bind_param("iii", $id_anggota, $id_users, $uang_total);
        if (!$stmt1->execute()) {
            die("gagal" . $stmt1->error);
            echo "<script>alert('gagal menyimpan kas rutin')</script>";
        };
    }

    $stmt1->close();

    $stmt2 = $db->prepare("INSERT INTO uang_kelompok(id_users, kas_masuk, kas_keluar, kas_total, keterangan_users) VALUES (?,?,?,?,?)");
    $keterangan = "kas rutin bang";
    $kas_keluar = 0;
    $kas_masuk = $total_kas;
    $kas_total = $db->prepare("SELECT kas_total FROM uang_kelompok WHERE id_users = ? ORDER BY id_tanggal DESC LIMIT 1");
    $kas_total->bind_param("i", $id_users);
    $kas_total->execute();
    $kas_total->bind_result($sisa);
    $sisa_terakhir = $kas_total->fetch() ? (int) $sisa : 0;
    $kas_total->close();

    $kas_baru = $sisa_terakhir + $kas_masuk;

    $stmt2->bind_param("iiiis", $id_users, $kas_masuk, $kas_keluar, $kas_baru, $keterangan);
    if ($stmt2->execute()) {
        echo "<script>alert('uang kelompok berhasil di insert')</script>";
    } else {
        die("gagal insert ke uang kelompok" . $stmt2->error);
    }
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
                    
                    $ketemu = false;

                    foreach ($hasil as $h) {
                        if ($h[1] == $id) {
                            $ketemu = true;

                            for ($i = 0; $i < $max_kolom; $i++) {
                                if (isset($h[2][$i])) {
                                    echo "<td>" . $h[2][$i]['uang_total'] . "</td>";
                                } else {
                                   
                                    echo "<td style='background:red'></td>";
                                }
                            }
                        }
                    }

                    echo "</tr>";
                }

                //echo "<pre>";
                //print_r($hasil);
                //echo "</pre>";
                ?>
            </tbody>
        </table>

        <button type="submit" name="simpan">simpan</button>
    </form>

    <?php include 'layout/footer.html' ?>
</body>

</html>