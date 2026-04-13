<?php
ob_start();
session_start();
include 'service/database.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: index.php");
    exit;
}

$id_users = $_SESSION['id_users'];
$stmt2 = $db->prepare("SELECT kas_total, tanggal 
 FROM uang_kelompok 
 WHERE id_users = ?
 ORDER BY id_tanggal DESC
 LIMIT 1
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
    <title>home</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Oswald:wght@449&display=swap" rel="stylesheet">

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
            box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.05);
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

        #target h2 {
            font-size: 1.75em;
            margin-top: 0;
            color: #2b2b2b;
        }

        #now span {
            color: #3a3a3a;
        }

        button {
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

            .entry {
                gap: 0px;
                justify-content: center;
            }

            #now,
            #target {
                margin-bottom: 120px;
            }

            #now {
                min-width: 60%;
                max-width: 70%;
                margin-bottom: 60px;
                margin-left: 0;
            }

            #target {
                min-width: 100%;
                border-radius: 0;
                box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.05);
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

<body>
    <?php include 'layout/header.html' ?>

    <section id="real">
        <h1>welcome to website</h1>
        <h2 id="typing"></h2>
        <script>
            const text = "I hope you guys can make me feel useful by this website!";
            const typingEl = document.getElementById("typing");
            let index = 0;

            function type() {
                if (index < text.length) {
                    typingEl.textContent += text[index];
                    index++;
                    setTimeout(type, 100); // kecepatan 100ms per huruf
                }
            }

            type();
        </script>
    </section>

    <section class="entry" id="login">
        <div class="container" id="now">
            <?php
            if ($result2->num_rows > 0) {
                $row = $result2->fetch_assoc();
                echo "<span>" . $row['tanggal'] . "</span>";
                echo "<h2>" . $row['kas_total'] . "</h2>";
            } else {
                echo "data error";
            }
            ?>
            <!--dinggo fungsi waeeee gek neng php pertambahan uang kas kwi nganggo js sek-->
        </div>
        <!--div 2 sek kanan ditambahi diagram progress-->
        <div class="container" id="target">
            <h2>mau mulai kas hari ini?</h2>
            <a href="rutinan.php"><button type="button">mulai!</button></a>
        </div>
    </section>

    <section class="quotes">
        <i>"barang siapa yang barangnya membawa barang"</i>
    </section>

    <?php include 'layout/riwayat.php' ?>

    <?php include 'layout/todo.php' ?>

    <section class="pendahuluan">
        <div class="text">
            <h2>website ini dibuat untuk memudahkan para bebdahara dalam membuat perhitungan uang walaupun belum sempurna</h2>
        </div>
    </section>

    <?php include 'layout/footer.html' ?>
</body>

</html>