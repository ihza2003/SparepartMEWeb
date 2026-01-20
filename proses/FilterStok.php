<?php
session_start();
require '../database/koneksi.php';
$where = '';

if (isset($_GET['filter'])) {
    if ($_GET['filter'] == 'aman') {
        $where = 'WHERE stok > 5';
    } elseif ($_GET['filter'] == 'warning') {
        $where = 'WHERE stok BETWEEN 1 AND 5';
    } elseif ($_GET['filter'] == 'habis') {
        $where = 'WHERE stok = 0';
    }
}

$q = mysqli_query($konek, "SELECT * FROM tb_stok $where");
