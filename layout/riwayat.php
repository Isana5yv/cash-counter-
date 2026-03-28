<?php

$id_users = $_SESSION['id_users'];

$stmt2 = $db->prepare("SELECT kas_masuk, kas_keluar, kas_total, keterangan_users, tanggal 
 FROM uang_kelompok 
 WHERE id_users = ?
 ORDER BY id_tanggal DESC
 LIMIT 10
 ");

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

        .riwayatph{
            display: flex;
            gap: 25px;
            width: 80%;
            margin: auto;
            text-align: top center;
        }
        
        .riwayat {
            background-color: transparant;
            width: 320px;
            height: 180px;
            text-align: center;
            display: grid;
            grid-template-columns: repeat(2,1fr);
            overflow-y: auto;
            gap: 10px;
        }

        .perTanggal {
            background: #fee2e2;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
            display: inline-block;
            text-align: center;
            padding: 0 10px;
            /*margin-left: 30px;
            margin-top: 20px;*/
            border-radius: 10px;
            height: auto;
            width: auto;
        }

        .aktivitas {
            min-height: auto;
            text-align: left;
            margin: auto;
            margin-top: 0;
        }

        .kebutuhan_tujuan {

            text-align: left;
            justify-content: left;
            margin: auto;
            margin-bottom: 100px;
           
        }
        .kebutuhan {
        background-color: white;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
            padding: 30px;
             border-radius: 10px;
        }
        input {
            margin-bottom: 10px;
            width: 200px;
        }

        p {
            color: red;
        }

        input[type="checkbox"]{
            width: auto;
        }
    
    </style>
</head>

<section class="riwayatph">
    <div class="kebutuhan_tujuan">
        <div class="kebutuhan">
            <h2 style="margin-top: 0; margin-bottom: 10px;">pemasukan dan pengeluaran</h2>
            <p style="margin-top: 0;margin-bottom: 30px;">*kamu bisa mencatat pemasukan dan pengeluaran uang kas disini</p>
            <form action="page.php" method="post">
                <table>
                <tr><td><label for="pemasukan">pemasukan:</label></td><td>
                <input style="margin-bottom: 0;" type="number" name="uang_masuk" id="uang_masuk"></td></tr>
                <tr><td ><label for="pengeluaran">pengeluaran:</label></td><td>
                <input style="margin-bottom: 0;" type="number" name="uang_keluar" id="uang_keluar"></td></tr>
                
                <tr><td ><label for="ket_uang">keterangan:</label></td><td>
                <input style="margin-bottom: 0;" type="text" name="ket_uang" id="keterangan_uang" placeholder="example: beli sapu dan dapat uang hilang"></td></tr>
</table><button type="submit" name="operasi" id="operasi">submit</button>
                <p>sisa uang kamu adalah:</p>
                <span id="sisa_uang"></span><!--nomin.textContent - uang_keluar.value + uang_masuk.value-->
                <input type="hidden" name="sisa_uang" id="sisa_uang_input">
            </form>
        </div>
</div>

    <div class="aktivitas">
        <h2 style="margin-bottom: 30px; margin-left: 30px;">riwayat uang kas</h2>
        <div class="riwayat">

            <?php
            while ($row = $result2->fetch_assoc()) {
                echo "<div class='perTanggal'>";
                echo "<p style='color: #3a3a3a'>" . $row['tanggal'] . "</p>";
                echo "<h3>" . $row['kas_total'] . "</h3>";
                echo "<p style='color: green;margin-bottom:0;'>+ " . $row['kas_masuk'] . "<p style='color: #ef4444;margin-top:0;'>- " . $row['kas_keluar'] . "<br>" .
                    "</p>";
                echo "</div>";
            };
            ?>
        </div>
</div>


</section>

</html>