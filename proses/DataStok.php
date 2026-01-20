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

    // ================= AMBIL GAMBAR LAMA =================
    $data = mysqli_query($konek, "SELECT gambar FROM tb_stok WHERE idbarang = '$idbarang'");
    $row  = mysqli_fetch_assoc($data);
    $gambarLama = $row['gambar'];

    $namaFileBaru = $gambarLama; // default: pakai gambar lama

    // ================= CEK JIKA UPLOAD GAMBAR BARU =================
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {

        $file = $_FILES['gambar'];
        $size = $file['size'];
        $tmp  = $file['tmp_name'];
        $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];
        $maxSize = 2 * 1024 * 1024;

        if (!in_array($ext, $allowed)) {
            $_SESSION['error'] = 'Format gambar harus JPG / JPEG / PNG';
            header('Location: ../StokPage.php');
            exit;
        }

        if ($size > $maxSize) {
            $_SESSION['error'] = 'Ukuran gambar maksimal 2MB';
            header('Location: ../StokPage.php');
            exit;
        }

        // nama file baru
        $namaFileBaru = uniqid('barang_', true) . '.' . $ext;
        $pathBaru     = '../storage/' . $namaFileBaru;

        // upload gambar baru
        if (!move_uploaded_file($tmp, $pathBaru)) {
            $_SESSION['error'] = 'Gagal upload gambar baru';
            header('Location: ../StokPage.php');
            exit;
        }

        // hapus gambar lama
        if (!empty($gambarLama) && file_exists('../storage/' . $gambarLama)) {
            unlink('../storage/' . $gambarLama);
        }
    }

    // ================= UPDATE DATA =================
    $updateStok = mysqli_query($konek, "
        UPDATE tb_stok SET
            nomorbarang = '$nomorbarang',
            namabarang  = '$namabarang',
            mesin       = '$mesin',
            norak       = '$norak',
            gambar      = '$namaFileBaru'
        WHERE idbarang = '$idbarang'
    ");

    if ($updateStok) {
        $_SESSION['success'] = 'Data barang berhasil diupdate.';
    } else {
        $_SESSION['error'] = 'Data barang gagal diupdate!';
    }

    header('Location: ../StokPage.php');
    exit;
}
