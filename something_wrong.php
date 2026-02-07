<?php
if (isset($_POST['login'])) {
    $atas_nama = $_POST['atasnama'];
    $sandi = $_POST['password'];

    echo $atas_nama . "dengan sandi" . $sandi;
}

#gpt 

session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_users'])) {
    header("Location: login.php");
    exit;
}

$id_users = $_SESSION['id_users'];

$data = $db->prepare("SELECT * FROM kas WHERE id_users = ? ORDER BY id_kas DESC");
$data->bind_param("i", $id_users);
$data->execute();
$riwayat = $data->get_result();

if (isset($_POST['simpan'])) {

    // 1. ambil saldo terakhir
    $q = $db->prepare("
        SELECT uang_total 
        FROM kas 
        WHERE id_users = ?
        ORDER BY id_kas DESC 
        LIMIT 1
    ");
    $q->bind_param("i", $id_users);
    $q->execute();
    $r = $q->get_result();

    $saldo_terakhir = ($r->num_rows > 0)
        ? (int) $r->fetch_assoc()['uang_total']
        : 0;

    // 2. ambil input
    $masuk  = empty($_POST['uang_masuk']) ? 0 : (int) $_POST['uang_masuk'];
    $keluar = empty($_POST['uang_keluar']) ? 0 : (int) $_POST['uang_keluar'];
    $ket    = trim($_POST['ket_uang'] ?? '') ?: '-';

    // 3. hitung saldo baru
    $sisa = $saldo_terakhir + $masuk - $keluar;

    if ($sisa < 0) {
        echo "<script>alert('Saldo tidak cukup')</script>";
        return;
    }

    // 4. simpan ke database
    $ins = $db->prepare("
        INSERT INTO kas 
        (id_users, uang_masuk, uang_keluar, uang_total, keterangan)
        VALUES (?, ?, ?, ?, ?)
    ");
    $ins->bind_param("iiiis", $id_users, $masuk, $keluar, $sisa, $ket);
    $ins->execute();

    header("Location: home.php");
    exit;
}


?>

<body>
    <div class="login_page">
        <div class="form_login">
            <h1> selamat datang!</h1>
            <form action="something_wrong.php" method="POST">
                <input type="text" name="atasnama" id="atasnama" placeholder="atas nama">
                <br>
                <input type="password" name="password" id="sandi" placeholder="password">
                <br>
                <button type="submit" name="login">klik dulu aja</button>
            </form>
        </div>
    </div>
<table border="1">
<tr>
    <th>Masuk</th>
    <th>Keluar</th>
    <th>Total</th>
    <th>Keterangan</th>
</tr>

<?php while ($row = $riwayat->fetch_assoc()) { ?>
<tr>
    <td><?= $row['uang_masuk'] ?></td>
    <td><?= $row['uang_keluar'] ?></td>
    <td><?= $row['uang_total'] ?></td>
    <td><?= $row['keterangan'] ?></td>
</tr>
<?php } ?>
</table>


</body>

