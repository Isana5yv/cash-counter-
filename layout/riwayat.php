<?php

$id_users = $_SESSION['id_users'];

$stmt2 = $db->prepare("SELECT kas_masuk, kas_keluar, kas_total, keterangan_users, tanggal 
 FROM uang_kelompok 
 WHERE id_users = ?
 ORDER BY id_tanggal DESC");

$stmt2->bind_param("i", $id_users);
$stmt2->execute();
$result2 = $stmt2->get_result();

if (isset($_POST['operasi'])) {
    $masuk = empty($_POST['uang_masuk']) ? 0 : (int) $_POST['uang_masuk'];
    $keluar = empty($_POST['uang_keluar']) ? 0 : (int) $_POST['uang_keluar'];
    $keterangan = empty(trim($_POST['ket_uang'] ?? '')) ? '-' : trim($_POST['ket_uang']);

    //ambil sisa uang terakhir dari id users yang sama dengan yang login

    $query = $db->prepare("
    SELECT kas_total FROM uang_kelompok 
    WHERE id_users = ?
    ORDER BY id_tanggal DESC
    LIMIT 1
    ");

    $query->bind_param("i", $id_users);
    $query->execute();
    $query->bind_result($kas_total);

    $terakhir = $query->fetch() ? (int) $kas_total : 0;

    $query->close();

    $kas_total = $terakhir + $masuk - $keluar;

    $stmt1 = $db->prepare("INSERT INTO uang_kelompok(id_users, kas_masuk, kas_keluar, kas_total, keterangan_users) VALUES (?,?,?,?,?)");
    $stmt1->bind_param("iiiis", $id_users, $masuk, $keluar, $kas_total, $keterangan);
    if ($stmt1->execute()) {
        header("Location: page.php?status=berhasil");
        exit;
    } else {
       header("Location: page.php?status=gagal");
       exit;
    }

    $stmt1->close();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        .riwayat {
            background-color: blueviolet;
            width: 70%;
            height: 50vh;
            text-align: center;
            margin: auto;
        }

        .perTanggal {
            background-color: blue;
            display: inline-block;
            width: 80px;
            height: 100px;
            text-align: center;
            padding: 10px;
            padding-top: 0;
            margin: auto;
            border-radius: 7px;
        }

        .aktivitas {
            min-height: 70vh;
            width: 100%;
            text-align: center;
            justify-content: center;
            margin: auto;
        }

        .kebutuhan_tujuan {
            background-color: aquamarine;
            padding: 30px;
            width: 50%;
            text-align: left;
            justify-content: left;
            margin: auto;
        }

        input {
            margin-bottom: 10px;
        }

        p {
            color: red;
        }
    </style>
</head>

<body>
    <section class="kebutuhan_tujuan">
        <div class="kebutuhan">
            <h2>pemasukan dan pengeluaran</h2>
            <p>*kamu bisa mencatat pemasukan dan pengeluaran uang kas disini</p>
            <form action="page.php" method="post">
                <label for="pemasukan">pemasukan:</label>
                <input type="number" name="uang_masuk" id="uang_masuk">
                <br>
                <label for="pengeluaran">pengeluaran:</label>
                <input type="number" name="uang_keluar" id="uang_keluar">
                <br>
                <label for="ket_uang">keterangan:</label>
                <input type="text" name="ket_uang" id="keterangan_uang" placeholder="example: beli sapu dan dapat uang hilang">
                <button type="submit" name="operasi" id="operasi">submit</button>
                <p>sisa uang kamu adalah:</p>
                <span id="sisa_uang"></span><!--nomin.textContent - uang_keluar.value + uang_masuk.value-->
                <input type="hidden" name="sisa_uang" id="sisa_uang_input">
            </form>
        </div>
    </section>

    <section class="aktivitas">
        <h2>riwayat uang kas</h2>
        <div class="riwayat">

            <?php
            while ($row = $result2->fetch_assoc()) {
                echo "<span class='perTanggal'>
                <h3>" . $row['tanggal'] . "</h3>
                <p>+ " . $row['kas_masuk'] . "<br>" .
                    "- " . $row['kas_keluar'] . "<br>" .
                    "total: " . $row['kas_total'] .
                    "</p>" . "</span>";
            };
            ?>
        </div>
    </section>


</body>

</html>