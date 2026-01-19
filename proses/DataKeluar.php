<?php
session_start();
require '../database/koneksi.php';

if (isset($_POST['btnOut'])) {

    date_default_timezone_set('Asia/Jakarta');

    // =========================
    // AMBIL DATA FORM
    // =========================
    $idbarang = (int) $_POST['idbarang'];
    $penerima = trim($_POST['penerima']);
    $jumlah   = (int) $_POST['jumlah'];
    $iduser   = $_SESSION['admin_id'];

    // =========================
    // VALIDASI INPUT
    // =========================
    if (
        empty($idbarang) ||
        empty($jumlah) ||
        empty($penerima) ||
        $jumlah <= 0
    ) {
        $_SESSION['error'] = 'Input tidak valid';
        header('Location: ../DataKeluarPage.php');
        exit;
    }


    // =========================
    // TRANSACTION START
    // =========================
    mysqli_begin_transaction($konek);

    try {

        /* =========================
           1. KURANGI STOK (AMAN)
           ========================= */
        $updateStok = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = stok - $jumlah
            WHERE idbarang = $idbarang 
            AND stok >= $jumlah
        ");

        // Jika stok tidak cukup / barang tidak ada
        if (mysqli_affected_rows($konek) == 0) {
            throw new Exception('Stok tidak mencukupi');
        }

        /* =========================
           2. SIMPAN KE tb_keluar
           ========================= */
        $insertKeluar = mysqli_query($konek, "INSERT INTO tb_keluar (idbarang, iduser, penerima, jumlah)
            VALUES ($idbarang, $iduser, '$penerima', $jumlah)
        ");

        if (!$insertKeluar) {
            throw new Exception('Gagal simpan data keluar');
        }

        // =========================
        // COMMIT (SUKSES)
        // =========================
        mysqli_commit($konek);

        $_SESSION['success'] = 'Barang Keluar berhasil disimpan';
        header('Location: ../DataKeluarPage.php');
        exit;
    } catch (Exception $e) {

        // =========================
        // ROLLBACK (GAGAL)
        // =========================
        mysqli_rollback($konek);

        $_SESSION['error'] = $e->getMessage();
        header('Location: ../DataKeluarPage.php');
        exit;
    }
}
//jika tombol update barang keluar diklik
if (isset($_POST['btnUpdateOut'])) {
    //ambil data dari form
    $idkeluar = (int) $_POST['idkeluar'];
    $idbarang = (int) $_POST['idbarang'];
    $penerima = trim($_POST['penerima']);
    $jumlah   = (int) $_POST['jumlah'];

    //validasi input
    if (
        empty($idkeluar) ||
        empty($idbarang) ||
        empty($penerima) ||
        empty($jumlah) ||
        $jumlah <= 0
    ) {
        $_SESSION['error'] = 'Input tidak valid';
        header('Location: ../DataKeluarPage.php');
        exit;
    }

    //mulai transaksi
    mysqli_begin_transaction($konek);
    try {
        //ambil data lama dari tb_keluar
        $qOld = mysqli_query($konek, "SELECT idbarang, jumlah 
        FROM tb_keluar 
        WHERE idkeluar = $idkeluar
        FOR UPDATE");

        if (mysqli_num_rows($qOld) == 0) {
            throw new Exception('Data lama tidak ditemukan');
        }

        $old = mysqli_fetch_assoc($qOld);
        $idbarangLama = (int) $old['idbarang'];
        $jumlahLama   = (int) $old['jumlah'];

        //mengambil data stok saat ini
        $stoqLama = mysqli_query($konek, "SELECT stok FROM tb_stok WHERE idbarang = $idbarangLama FOR UPDATE");

        if (mysqli_num_rows($stoqLama) == 0) {
            throw new Exception('Data stok lama tidak ditemukan');
        }

        $stokData = mysqli_fetch_assoc($stoqLama);
        $stokLama = (int) $stokData['stok'];

        //validasi barang
        if ($idbarangLama == $idbarang) {
            //hitung stok akhir
            $stokAkhir = $stokLama + $jumlahLama - $jumlah;

            //validasi stok tidak boleh minus
            if ($stokAkhir < 0) {
                throw new Exception('Stok tidak mencukupi');
            }

            //update stok
            $updateStokLama = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = $stokAkhir
            WHERE idbarang = $idbarangLama");

            if (!$updateStokLama) {
                throw new Exception('Gagal update stok lama');
            }
        } else {
            //hitung stok untuk barang lama
            $stokAkhirLama = $stokLama + $jumlahLama;

            //update stok barang lama
            $updateStokLama = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = $stokAkhirLama 
            WHERE idbarang = $idbarangLama");
            if (!$updateStokLama) {
                throw new Exception('Gagal update stok lama');
            }

            //ambil data stok barang baru
            $stoqBaru = mysqli_query($konek, "SELECT stok FROM tb_stok WHERE idbarang = $idbarang FOR UPDATE");

            if (mysqli_num_rows($stoqBaru) == 0) {
                throw new Exception('Data stok baru tidak ditemukan');
            }

            $stokDataBaru = mysqli_fetch_assoc($stoqBaru);
            $stokBaru = (int) $stokDataBaru['stok'];

            //hitung stok akhir barang baru
            $stokAkhirBaru = $stokBaru - $jumlah;

            //validasi stok barang baru tidak boleh minus
            if ($stokAkhirBaru < 0) {
                throw new Exception('Stok tidak mencukupi');
            }
            //update stok barang baru
            $updateStokBaru = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = $stokAkhirBaru 
            WHERE idbarang = $idbarang");

            if (!$updateStokBaru) {
                throw new Exception('Gagal update stok baru');
            }
        }

        //update data di tb_keluar
        $updateKeluar = mysqli_query(
            $konek,
            "UPDATE tb_keluar SET idbarang = $idbarang,
            jumlah   = $jumlah,
            penerima = '$penerima'
        WHERE idkeluar = $idkeluar"
        );

        if (!$updateKeluar) {
            throw new Exception('Gagal update data keluar');
        }

        /* =========================
       COMMIT
       ========================= */
        mysqli_commit($konek);
        $_SESSION['success'] = 'Barang Keluar berhasil diupdate';
        header('Location: ../DataKeluarPage.php');
        exit;
    } catch (Exception $e) {
        //rollback jika gagal
        mysqli_rollback($konek);
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../DataKeluarPage.php');
        exit;
    }
}

//jika tombol hapus barang keluar diklik
if (isset($_POST['btnHapusOut'])) {
    // kode untuk menghapus data masuk
    $idkeluar = (int) $_POST['idkeluar'];
    // kode untuk menghapus data keluar
    if (empty($idkeluar)) {
        $_SESSION['error'] = 'Input tidak valid';
        header('Location: ../DataKeluarPage.php');
        exit;
    }

    // mulai transaksi
    mysqli_begin_transaction($konek);
    try {
        // ambil data keluar
        $q = mysqli_query($konek, "SELECT idbarang, jumlah 
            FROM tb_keluar 
            WHERE idkeluar = $idkeluar
            FOR UPDATE
        ");

        if (mysqli_num_rows($q) == 0) {
            throw new Exception('Data keluar tidak ditemukan');
        }

        $data = mysqli_fetch_assoc($q);
        $idbarang = (int) $data['idbarang'];
        $jumlah   = (int) $data['jumlah'];

        //validasi stok sebelum dikurangi
        $stok = mysqli_query($konek, "SeLECT stok FROM tb_stok WHERE idbarang = $idbarang FOR UPDATE");

        if (mysqli_num_rows($stok) == 0) {
            throw new Exception('Data stok tidak ditemukan');
        }

        $stokData = mysqli_fetch_assoc($stok);
        $stokSekarang = (int) $stokData['stok'];

        // if ($stokSekarang < $jumlah) {
        //     throw new Exception('Tidak bisa menghapus data keluar karena stok sudah digunakan (stok kurang)');
        // }

        // kurangi stok
        $updateStok = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = stok + $jumlah
            WHERE idbarang = $idbarang");

        if (!$updateStok) {
            throw new Exception('Gagal mengurangi stok');
        }

        // hapus data keluar
        $deleteKeluar = mysqli_query($konek, "DELETE FROM tb_keluar 
            WHERE idkeluar = $idkeluar
        ");

        if (!$deleteKeluar) {
            throw new Exception('Gagal menghapus data keluar');
        }

        // commit transaksi
        mysqli_commit($konek);

        $_SESSION['success'] = 'Barang Keluar berhasil dihapus';
        header('Location: ../DataKeluarPage.php');
        exit;
    } catch (Exception $e) {
        // rollback transaksi
        mysqli_rollback($konek);

        $_SESSION['error'] = $e->getMessage();
        header('Location: ../DataKeluarPage.php');
        exit;
    }
}
