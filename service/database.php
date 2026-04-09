<?php

$hostname = "sql211.infinityfree.com";
$username = "if0_41500233";
$password = "HpQv0jy5gz";
$database_name = "if0_41500233_kas_kelas";

$db = mysqli_connect($hostname, $username, $password, $database_name);

if ($db->connect_error) {
    echo "koneksi gagal";
};

?>