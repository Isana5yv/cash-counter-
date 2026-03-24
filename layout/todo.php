<?php
$id_users = $_SESSION['id_users'];

$query2 = $db->prepare("SELECT list 
 FROM wanna_buy
 WHERE id_users = ?
 ORDER BY id_list DESC
 ");

$query2->bind_param("i", $id_users);
$query2->execute();
$result2 = $query2->get_result();

if (isset($_POST['tambah'])) {
    $list = $_POST['list'];

    $query = $db->prepare("INSERT INTO wanna_buy (list, id_users, id_list) VALUES (?,?,?)");
    $query->bind_param("sii", $list, $id_users, $id_list);
    $query->execute();

    $query->close();
}

if(isset($_GET['hapus'])) {
    $query3 = $db->prepare("DELETE FROM wanna_buy WHERE id_users = ? ");
    $query3->bind_param("i", $id_users);
    $query3->execute();
    $query3->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="todo">
        <form action="page.php" method="post">
            <label for="list">masukkan list</label>
            <input type="text" name="list" id="list" placeholder="ex: AC" required>
            <button type="submit" name="tambah">tambah</button>
            <?php
            while($row = $result2->fetch_assoc()){
                 echo "<input type='checkbox'
            name='status[]'
            value='" . $row['id'] . "'
            onchange='this.form.submit()'
            " . ($row['status'] ? 'checked' : '') . ">";

    echo "<span class='" . ($row['status'] ? 'selesai' : '') . "'>"
            . $row['list'] .
         "</span>";
                echo "<a href='?hapus=" . $row['id_list'] . "'>Hapus</a>";
            }
             ?>
        </form>
    </div>
</body>
</html>