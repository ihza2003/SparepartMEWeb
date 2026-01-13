<?php
session_start();
require '../database/koneksi.php';
//jika tombol simpan barang baru diklik
if (isset($_POST['btnInBaru'])) {

    date_default_timezone_set('Asia/Jakarta');

    // ambil data dari form
    $nomorbarang = trim($_POST['nomorbarang']);
    $namabarang  = trim($_POST['namabarang']);
    $mesin       = trim($_POST['mesin']);
    $norak       = trim($_POST['nomorrak']);
    $pengirim    = trim($_POST['pengirim']);
    $jumlah      = (int) $_POST['jumlah'];

    $iduser = $_SESSION['admin_id']; // Admin yang sedang login

    // validasi sederhana
    if ($nomorbarang == '' || $namabarang == '' || $jumlah <= 0) {
        echo "<script>alert('Data belum lengkap');history.back();</script>";
        exit;
    }

    if (mysqli_num_rows(mysqli_query($konek, "SELECT * FROM tb_stok WHERE nomorbarang = '$nomorbarang'")) > 0) {
        echo "<script>alert('Nomor barang sudah ada di stok. Silahkan Input di data lama.');history.back();</script>";
        exit;
    }

    /* =============================
       1. SIMPAN KE tb_stok (MASTER)
       ============================= */
    $insertStok = mysqli_query($konek, "
        INSERT INTO tb_stok (nomorbarang, namabarang, mesin, norak, stok)
        VALUES ('$nomorbarang', '$namabarang', '$mesin', '$norak', $jumlah)
    ");

    if ($insertStok) {

        // ambil idbarang terakhir
        $idbarang = mysqli_insert_id($konek);

        /* =============================
           2. SIMPAN KE tb_masuk (HISTORI)
           ============================= */
        $insertMasuk = mysqli_query($konek, "
            INSERT INTO tb_masuk (idbarang, iduser, pengirim, jumlah)
            VALUES ($idbarang, $iduser, '$pengirim', $jumlah)
        ");

        if ($insertMasuk) {
            echo "
                <script>
                    alert('Barang baru berhasil disimpan');
                    location.replace('../DataMasukPage.php');
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Gagal simpan data masuk');
                    history.back();
                </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Gagal simpan data stok');
                history.back();
            </script>
        ";
    }
}


//jika tombol simpan barang lama diklik
if (isset($_POST['btnInLama'])) {

    date_default_timezone_set('Asia/Jakarta');

    // ambil data dari form
    $idbarang = (int) $_POST['idbarang'];
    $pengirim = trim($_POST['pengirim']);
    $jumlah   = (int) $_POST['jumlah'];
    $iduser   = $_SESSION['admin_id'];

    // validasi
    if ($idbarang <= 0 || $jumlah <= 0) {
        echo "<script>alert('Barang Tidak Mencukupi');history.back();</script>";
        exit;
    }

    /* =========================
       1. UPDATE STOK
       ========================= */
    $updateStok = mysqli_query($konek, "
        UPDATE tb_stok 
        SET stok = stok + $jumlah 
        WHERE idbarang = $idbarang
    ");

    if ($updateStok) {

        /* =========================
           2. SIMPAN HISTORI MASUK
           ========================= */
        $insertMasuk = mysqli_query($konek, "
            INSERT INTO tb_masuk (idbarang, iduser, pengirim, jumlah)
            VALUES ($idbarang, $iduser, '$pengirim', $jumlah)
        ");

        if ($insertMasuk) {
            echo "
                <script>
                    alert('Barang lama berhasil ditambahkan');
                    location.replace('../DataMasukPage.php');
                </script>
            ";
        } else {
            echo "<script>alert('Gagal simpan histori');history.back();</script>";
        }
    } else {
        echo "<script>alert('Gagal update stok');history.back();</script>";
    }
}
