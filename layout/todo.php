<?php
$id_users = $_SESSION['id_users'];

if (isset($_POST['tambah'])) {
    $list = $_POST['list'];

    $query = $db->prepare("INSERT INTO wanna_buy (list, id_users, id_list) VALUES (?,?,?)");
    $query->bind_param("sii", $list, $id_users, $id_list);
    $query->execute();

    $query->close();
}

if (isset($_GET['hapus'])) {
    $id_list = $_GET['hapus'];
    $query3 = $db->prepare("DELETE FROM wanna_buy WHERE id_users = ? AND id_list = ? ");
    $query3->bind_param("ii", $id_users, $id_list);
    $query3->execute();
    $query3->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_list = $_POST['id_list'];

    // kalau checkbox dicentang
    $status = isset($_POST['status']) ? 1 : 0;

    $query = $db->prepare("UPDATE wanna_buy SET status = ? WHERE id_users = ? AND id_list = ?");
    $query->bind_param("iii", $status, $id_users, $id_list);
    $query->execute();
    $query->close();
}

$query2 = $db->prepare("SELECT list, id_list, status
 FROM wanna_buy
 WHERE id_users = ?
 ORDER BY id_list DESC
 ");

$query2->bind_param("i", $id_users);
$query2->execute();
$result2 = $query2->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>list kebutuhan kelas</title>

    <style>
        
    </style>
</head>

<body>
    <div class="todo">
        <form action="page.php" method="post">
            <label for="list">masukkan list</label>
            <input type="text" name="list" id="list" placeholder="ex: AC" required>
            <button type="submit" name="tambah">tambah</button>
            <br>
            <?php
            while ($row = $result2->fetch_assoc()) {
                echo "<form method='POST' style='display:inline'>
                <input type='hidden' name='id_list' value='" . $row['id_list'] . "'>

                <input type='checkbox'
                name='status'
                value='1'
                onchange='this.form.submit()'
                    " . ($row['status'] ? 'checked' : '') . ">";

                //echo "<span class='" . ($row['status'] ? 'selesai' : '') . "'>"
                //. 
                echo $row['list'];
                //"</span>";
                echo "<a href='?hapus=" . $row['id_list'] . "'>Hapus</a>";
                echo "</form>";
                echo "<br>";
            }
            ?>
        </form>
    </div>
</body>

</html>