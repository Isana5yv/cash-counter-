<?php
session_start();
include "service/database.php";

if (isset($_POST['login'])) {
    $user = $_POST['users_login'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM register_pemilik WHERE users = ? AND password = ?");
    $stmt->bind_param("ss", $user, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
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
            height: 90vh;
            background: linear-gradient(to bottom, #f3eed9 0%, #ff99cc 100%);
        }

        .form_login {
            background-color: #bd2525;
            padding-top: 30px;
            padding-bottom: 40px;
            border-radius: 80px 8px;
            box-shadow: 0 0 10px #bd2525, 0 0 20px #bd2525, 0 0 30px #bd2525;
            width: 50%;
            height: auto;
            align-items: center;
            text-align: center;
            justify-content: center;
            margin: auto;
            margin-top: 100px;
            font-size: large;
        }

        .form_login button {
            margin-top: 15px;
            padding: 8px 15px;
            outline: none;
            background: #f3eed9;
            color: #798897;
            border-radius: 10px;
            border: 2px solid #e09a8e;
        }

        .form_login button:hover {
            background-color: #dbdacc;
            color: #bd2525;
            scale: 1.1;
            box-shadow: 2px 3px 10px #f3eed9;
            transition: 0.5s ease;
        }

        h1 {
            color: antiquewhite;
        }

        p a {
            color: #e09a8e;
            transition: 0.5s ease;
        }

        p a:hover {
            color: antiquewhite;
            text-shadow: 2px 3px 10px;
        }

        .floatinglabel {
            width: 60%;
            /*margin-left: 150px;*/
            margin: auto;
            position: relative;
        }

        .floatinglabel input {
            width: 100%;
            border-radius: 7px;
            margin-top: 10px;
            padding: 10px;
            outline: none;
            border: 2px solid #e09a8e;
            background: #f3eed9;
        }

        .floatinglabel label {
            pointer-events: none;
            position: absolute;
            left: 10px;
            top: 19px;
            color: #798897;
            background: #f3eed9;
            transition: 0.2s ease;
            border-radius: 7px;
            padding: 0 4px;
        }

        .floatinglabel input:focus+label,
        .floatinglabel input:not(:placeholder-shown)+label {
            top: -3px;
            left: -1px;
            color: #bd2525;
            background: #f3eed9;
            border: 2px solid #e09a8e;
        }
    </style>
</head>

<body>
    <div class="login_page">
        <div class="form_login">
            <h1> selamat datang!</h1>
            <form action="" method="POST">
                <div class="floatinglabel">
                    <input type="text" id="atasnama" placeholder="  " name="users_login" required>
                    <label for="atasnama">atas nama</label>
                </div>
                <br>
                <div class="floatinglabel">
                    <input type="password" name="password" id="sandi" placeholder="  " required>
                    <label for="sandi">password</label>
                </div>
                <br>
                <button name="login" type="submit">login</button>
            </form>
            <p><a href="loginkas.php">belum punya akun?daftar disini</a></p>
        </div>
    </div>


</body>

</html>