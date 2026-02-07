<?php
session_start();
include 'service/database.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
        }


        h1 {
            text-align: center;
            padding-top: 40px;
            font-size: 3em;
            margin-bottom: 0;
            justify-content: center;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }

        header {
            height: 40vh;
        }

        header h2 {
            text-align: center;
            justify-content: center;
            margin-top: 10px;
        }

        .entry {
            text-align: center;
            height: 40vh;
            display: flex;
            justify-content: center;
            gap: 50px;
        }

        #now,
        #target {
            background-color: blueviolet;
            text-align: center;
            border-radius: 20px;
            height: 30vh;
        }

        #now {
            width: 20%;
        }

        #target {
            width: 50%;
        }

        .quotes {
            height: 20vh;
            text-align: center;
            justify-content: center;
            padding-top: 50px;
            font-size: x-large;
        }

        .pendahuluan {
            height: 70vh;
            width: 100%;
            display: inline-block;
        }
        .text {
            width: 30%;
            text-align: end;
            justify-content: center;
            padding: 50px;
            margin: auto;
            margin-right: 10%;
        }

        
    </style>
</head>

<body>
<?php include 'layout/header.html' ?>
    
    <header>
        <h1>welcome to website</h1>
        <h2>i hope you guys can make me feel <br> usefull by this website</h2>
    </header>

    <section class="entry" id="login">
        <div class="container" id="now">
            <span id="tampilTanggal">aku waktu</span>
            <!--dinggo fungsi waeeee gek neng php pertambahan uang kas kwi nganggo js sek-->
            <br>
            <span id="nomin">aku total uang</span>
        </div>
        <!--div 2 sek kanan ditambahi diagram progress-->
        <div class="container" id="target">
            <span id="tujuan">aku kebutuhan</span>
            <br>
            <span id="tampilTujuan">list disini</span>
        </div>
    </section>

    <section class="go_to_rutinan">
        <h2>mau mulai kas hari ini?</h2>
        <a href="rutinan.php"><button type="button">mulai!</button></a>
    </section>

    <section class="quotes">
        <i>"barang siapa yang barangnya membawa barang"</i>
    </section>

    <?php include 'layout/riwayat.php' ?>

    <section class="pendahuluan">
        <div class="text">
            <h2>website ini dibuat untuk memudahkan para bebdahara dalam membuat perhitungan uang walaupun belum sempurna</h2>
        </div>
    </section>

  <?php include 'layout/footer.html' ?>  
</body>

</html>