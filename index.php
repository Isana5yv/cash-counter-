<?php 
session_start();
include "service/database.php";

if(isset($_POST['login'])){
    $user = $_POST['users_login'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM register_pemilik WHERE users = ? AND password = ?");
    $stmt->bind_param("ss", $user, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows>0){
        $data = $result->fetch_assoc();
        $_SESSION['id_users'] = $data['id'];
        header("location: page.php");
        exit();
    } else {
        echo "<script>alert('we ki sopo?')</script>";
    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kelola kas</title>

    <style>
        body {
            padding: 0;
            margin: 0;
        }

        .form_login {
            background-color: aquamarine;
            padding-top: 60px;
            width: 50%;
            height: 50vh;
            align-items: center;
            text-align: center;
            justify-content: center;
            margin: auto;
            margin-top: 100px;
            font-size: large;
        }

        .form_login button {
            margin-top: 20px;
            padding: 10px;
        }

        input {
            border-radius: 7px;
            margin-top: 10px;
            padding: 10px;
        }

        .form_after_login {
            display: none;
            background-color: rgb(174, 198, 211);
        }

        span {
            font-size: large;
            font-weight: bold;
        }

        #rutin {
            display: none;
        }
    </style>
</head>

<body>
    <div class="login_page">
        <div class="form_login">
            <h1> selamat datang!</h1>
            <form action="" method="POST">
                <input type="text" id="atasnama" placeholder="atas nama" name="users_login" required>
                <br>
                <input type="password" name="password" id="sandi" placeholder="password" required>
                <br>
                <button name="login" type="submit">login</button>
            </form>
            <p><a href="loginkas.php">belum punya akun?daftar disini</a></p>
        </div>
    </div>


</body>

</html>