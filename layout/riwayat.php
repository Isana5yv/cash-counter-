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
        
        body {
            margin: 0;
            padding: 0;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
            background-color: #f8fafc;
  font-family: 'Nunito', sans-serif;
  color: #1f2937;
        }

        button {
  background: #ef4444;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.2s;
}

button:hover {
  background: #dc2626;
}
        h1 {
            text-align: center;
            padding-top: 40px;
            font-size: 3em;
            margin-bottom: 10px;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            letter-spacing: 1px;
            color: #2b2b2b;
        }

h2 {
    font-size: 2em;
}

        #real h2 {
            text-align: center;
            justify-content: center;
            margin-top: 10px;
            margin: auto;
            color: #3a3a3a;
            font-size: 1.5em;
            width: 40%;
        }
        
        #real {
            margin-bottom: 120px;
        }
        

        .entry {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 50px;
            /*height: 40vh;*/
        }

        #now,
        #target {
            background-color: white;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.05);
            text-align: center;
            border-radius: 20px;
            padding: 25px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 70px;
        }

        #now {
            min-width: 20%;
            max-width: 30%;
        }

        #target {
            min-width: 60%;
            max-width: 90%;
            text-align: left;
            margin-left: 0;
        }

#target h2{
    font-size: 1.75em;
    margin-top: 0;
    color: #2b2b2b;
}
#now span{
    color: #3a3a3a;
}
button{
    size: 3em;
    padding: 5px 10px;
}
       .quotes {
            height: auto;
            margin-bottom: 120px;
            text-align: center;
            justify-content: center;
            padding-top: 50px;
            font-size: x-large;
        }

        .pendahuluan {
            width: 100%;
            display: inline-block;
            margin-bottom: 120px;
        }

        .text {
            width: 50%;
            text-align: end;
            justify-content: center;
            padding: 50px;
            margin: auto;
            margin-right: 10%;
  font-family: 'DM Serif Display', serif;
        }

@media (max-width: 768px) {
    #real h2 {
        width: 80%;
        font-size: 1.3em;
    }
    .entry{
        gap: 0px;
        justify-content: center;
    }
    
    #now, #target{
        margin-bottom: 120px;
    }
    #now {
       min-width: 60%;
        max-width: 70%;
        margin-bottom: 60px;
        margin-left: 0;
    }
    #target{
        min-width:100%;
        border-radius: 0;
        box-shadow: 0px 10px 25px rgba(0,0,0,0.05);
    }
    .text {
        width: 80%;
        margin: auto;
        text-align: center;
        font-size: 0.8rem;
    }
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