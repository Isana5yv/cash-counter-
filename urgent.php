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
    <table>
        <tr>
            <th>no.</th>
            <th>nama</th>
            <th>11/4</th>
        </tr>
        <tr>
            <td>1</td>
            <td>isa</td>
            <td><input type="number" name="sumbang" id="sumbang"></td>
        </tr>
    </table>

    <?php include 'layout/footer.html' ?>
</body>
</html>