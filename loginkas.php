<?php
session_start();
include "service/database.php";

if(!isset($_SESSION['step'])) {
    $_SESSION['step'] = 1;
}
$step = $_SESSION['step'];
if(isset($_POST['submit1'])){
    $users = $_POST['users'];
    $sandi = ($_POST['sandi']);
    $nomorhp = $_POST['hp'];

    $stmt1 = $db->prepare("INSERT INTO register_pemilik(users, password, nomorHP) VALUES (?, ?, ?)");
    $stmt1->bind_param("sss", $users, $sandi, $nomorhp);
    if ($stmt1->execute()) {
        echo "register pemilik berhasil diinsert";
    } else {
        die("register pemilik gagal diinsert" . $stmt1->error);
    }

    $_SESSION['id_users'] = $stmt1->insert_id;
    $_SESSION['step'] = 2;
    header("Location:".$_SERVER['PHP_SELF']);
    exit();
};

if(isset($_POST['submit'])){
    if(!isset($_SESSION['id_users'])) {
        die("SESSION id_users hilang, ulangi dari Form 1");
    }
    $id_users = $_SESSION['id_users'];
    $awal = $_POST['awal'];

    $db->begin_transaction();

    try {

        if(isset($_POST['nama']) && is_array($_POST['nama'])){

            $stmt = $db->prepare("INSERT INTO daftar_anggota(id_users, anggota) VALUES (?, ?)");

            foreach ($_POST['nama'] as $nama) {

                if(!empty($nama)){
                    $stmt->bind_param("is", $id_users, $nama);
                    if ($stmt->execute()) {
                        echo "daftar anggota berhasil diinsert";
                    } else {
                        die("gagal insert daftar anggota" . $stmt->error);
                    }
                }
            }
        }
        $stmt2 = $db->prepare("INSERT INTO uang_kelompok(id_users, kas_total) VALUES (?, ?)");
        $stmt2->bind_param("ii", $id_users, $awal);
        if($stmt2->execute()){
            echo "uang kelompok berhasil diinsert";
        } else {
            die("uang kelompok gagal" . $stmt2->error);
        }

        $db->commit();
        echo "BERHASIL BROKKK";
    } catch (Exception $e) {
        $db->rollback();
        die("gagal brokkk" . $e->getMessage());
    }

    session_destroy();

    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login page, coba coba</title>
    <style>
        * {
            padding: 0;
            margin: 10px;
            box-sizing: border-box;
        }

        .container {
            width: 50%;
            margin: auto;
            box-shadow: 0 0 10px gray;
            padding: 20px;
            margin-top: 50px;
            margin-bottom: 50px;
            font-size: larger;
        }

        input {
            width: 50%;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        p {
            color: red;
        }

        fieldset {
            border: 0;
            padding: 0;
            min-width: 0;
            border-bottom: 1px solid grey;
            margin-top: 20px;
        }

        .hidden {
            display: none;
        }

        .row {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <?php if($step == 1): ?>
            <form action="loginkas.php" method="post" id="form1">

                <fieldset>
                    <label for="their_name">atas nama</label><br>
                    <input type="text" name="users" id="their_name" required>
                </fieldset>
                <fieldset>
                    <label for="sandi">password</label><br>
                    <input type="password" name="sandi" id="sandi" required>
                </fieldset>
                <fieldset>
                    <label for="their_number">nomor telepon</label><br>
                    <input type="number" name="hp" id="their_number" required>
                </fieldset>
                <button type="submit" id="submit1" name="submit1">submit!</button>

            </form>

        <?php elseif($step == 2): ?>
            <form action="index.php" method="post" id="form2">

                <fieldset>
                    <label>keperluan pribadi/kelompok</label><br>
                    <button type="button" id="yourself">pribadi</button><br>
                    <button type="button" id="other">kelompok</button>
                </fieldset>
                <div id="kelompok" class="hidden">
                    <fieldset>
                        <input type="hidden" name="id_users" id="id_users">
                        <label>masukkan nama</label><br>
                        <div id="tabel_input">
                            <!--iki tabel input e-->
                        </div>
                        <button type="button" id="tambah_input">+</button>
                        <p>*lebihkan 1 nama random untuk mengukur pendapatan seharusnya</p>
                    </fieldset>
                    <fieldset>
                        <label for="nominal">nominal per-orang</label><br>
                        <input type="number" name="nominal" id="nominal" required>
                    </fieldset>
                    <fieldset>
                        <label for="awal">jumlah kas sekarang</label><br>
                        <input type="number" name="awal" id="awal" required>
                    </fieldset>

                </div>

                <div id="pribadi" class="hidden">

                    <label>nominal menabung</label> <!-- nggo js ikii -->
                    <input type="number" name="tabung" id="tabung" placeholder="20000">

                </div>

                <script>
                    // document.getElementById('submit1').addEventListener('click', function() {
                       // document.getElementById('form2').style.display = 'block';
                    //}) ;

                    const pribadi = document.getElementById('yourself');
                    const kelompok = document.getElementById('other');
                    const div_kel = document.getElementById('kelompok');
                    const tabel = document.getElementById('tabel_input');
                    const tambah = document.getElementById('tambah_input');
                    const div_prib = document.getElementById('pribadi');

                    pribadi.addEventListener('click', function() {
                        div_prib.classList.remove('hidden');
                        div_kel.classList.add('hidden');

                        kelompok.disabled = true;
                    })

                    kelompok.addEventListener('click', function() {
                        div_kel.classList.remove('hidden');
                        div_prib.classList.add('hidden');

                        pribadi.disabled = true;
                    })

                    tambah.addEventListener('click', function() {
                        const row = document.createElement('div');
                        row.className = 'row';

                        const input_baru = document.createElement('input');
                        input_baru.type = 'text';
                        input_baru.name = 'nama[]';

                        const hapus = document.createElement('button');
                        hapus.type = 'button';
                        hapus.textContent = 'x';
                        hapus.addEventListener('click', function() {
                            row.remove();
                        })


                        row.appendChild(input_baru);
                        row.appendChild(hapus);
                        tabel.appendChild(row);

                    })
                </script>
                <button type="submit" name="submit">submit!</button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>