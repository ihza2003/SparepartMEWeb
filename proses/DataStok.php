<?php
session_start();
require '../database/koneksi.php';

// jika tombol update ditekan
if (isset($_POST['btnUpdateStok'])) {
    date_default_timezone_set('Asia/Jakarta');

    // ambil data dari form
    $idbarang    = trim($_POST['idbarang']);
    $nomorbarang = trim($_POST['nomorbarang']);
    $namabarang  = trim($_POST['namabarang']);
    $mesin       = trim($_POST['mesin']);
    $norak       = trim($_POST['norak']);

    //validasi update
    if (
        empty($nomorbarang) ||
        empty($namabarang) ||
        empty($mesin) ||
        empty($norak)
    ) {
        $_SESSION['error'] = 'Data tidak boleh ada yang kosong!';
        header('Location: ../StokPage.php');
        exit();
    }

    // uppdate data ke tabel stok
    $updateStok = mysqli_query($konek, "UPDATE tb_stok SET
        nomorbarang = '$nomorbarang',
        namabarang  = '$namabarang',
        mesin       = '$mesin',
        norak       = '$norak'
        WHERE idbarang = '$idbarang' ");

    if ($updateStok) {
        $_SESSION['success'] = 'Data barang berhasil diupdate.';
        header('Location: ../StokPage.php');
        exit();
    } else {
        $_SESSION['error'] = 'Data barang gagal diupdate!';
        header('Location: ../StokPage.php');
        exit();
    }
}
