<?php

$hostname = "localhost";
$username = "root";
$password = "";
$database_name = "kas_kelas";

$db = mysqli_connect($hostname, $username, $password, $database_name, 3067);

if ($db->connect_error) {
    echo "koneksi gagal";
};

?>