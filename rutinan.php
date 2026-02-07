<?php
include 'service/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>kas rutin</title>

    <style>

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table{
            margin: 20px;
            border-collapse: collapse;
        }

        th, td{
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <?php include 'layout/header.html' ?>
    <h2>mulai kas hari ini?</h2>
    <button type="button" id="mulai">mulai!</button>
    <table>
        <tr>
            <th>no.</th>
            <th>nama</th>
            <th>tanggal</th>
        </tr>
        <tr>
            <td>1</td>
            <td>isa</td>
            <td><input type="checkbox" name="sudah" id="sudah"></td>
        </tr>
        <tbody>
        </tbody>
    </table>

    <script>
        const mulai = document.getElementById('mulai');
        const wadah = document.getElementById('table-container');

        mulai.addEventListener('click', function(){
            const table = document.createElement('table');
            table.innerHTML =
            '<tr> <th>no.</th> <th>nama</th> <th>tanggal</th></tr>'
        })
    </script>

    <?php include 'layout/riwayat.php' ;
     include 'layout/footer.html' ?>
</body>
</html>