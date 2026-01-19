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


    // validasi
    if (
        empty($mesin) ||
        empty($norak) ||
        empty($pengirim) ||
        empty($nomorbarang) ||
        empty($namabarang) ||
        $jumlah <= 0 ||
        empty($iduser)
    ) {
        $_SESSION['error'] = 'Data tidak boleh kosong atau jumlah tidak valid';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    // cek nomor barang
    $cek = mysqli_query($konek, "SELECT 1 FROM tb_stok WHERE nomorbarang='$nomorbarang'");
    if (mysqli_num_rows($cek) > 0) {
        $_SESSION['error'] = 'Nomor barang sudah ada, silahkan input di barang lama';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    // transaksi
    mysqli_begin_transaction($konek);
    try {
        $insertStok = mysqli_query($konek, "INSERT INTO tb_stok (nomorbarang, namabarang, mesin, norak, stok)
            VALUES ('$nomorbarang','$namabarang','$mesin','$norak',$jumlah)
        ");

        if (!$insertStok) {
            throw new Exception('Gagal simpan data stok');
        }

        $idbarang = mysqli_insert_id($konek);

        $insertMasuk = mysqli_query($konek, "INSERT INTO tb_masuk (idbarang, iduser, pengirim, jumlah)
            VALUES ($idbarang, $iduser, '$pengirim', $jumlah)
        ");

        if (!$insertMasuk) {
            throw new Exception('Gagal simpan data masuk');
        }

        mysqli_commit($konek);
        $_SESSION['success'] = 'Barang baru berhasil disimpan';
        header('Location: ../DataMasukPage.php');
        exit;
    } catch (Exception $e) {
        mysqli_rollback($konek);
        $_SESSION['error'] = $e->getMessage();
        header('Location: ../DataMasukPage.php');
        exit;
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

    // validasi input
    // =====================
    // VALIDASI INPUT
    // =====================
    if (
        empty($idbarang) ||
        empty($jumlah) ||
        $jumlah <= 0 ||
        empty($pengirim) ||
        empty($iduser)
    ) {
        $_SESSION['error'] = 'Input tidak valid';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    // =====================
    // MULAI TRANSAKSI
    // =====================
    mysqli_begin_transaction($konek);

    try {

        // =====================
        // LOCK DATA STOK
        // =====================
        $qStok = mysqli_query($konek, "
            SELECT stok 
            FROM tb_stok 
            WHERE idbarang = $idbarang 
            FOR UPDATE
        ");

        if (mysqli_num_rows($qStok) == 0) {
            throw new Exception('Barang tidak ditemukan');
        }

        $stokData = mysqli_fetch_assoc($qStok);
        $stokSekarang = (int) $stokData['stok'];

        // =====================
        // UPDATE STOK
        // =====================
        $stokAkhir = $stokSekarang + $jumlah;

        $updateStok = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = $stokAkhir 
            WHERE idbarang = $idbarang
        ");

        if (!$updateStok) {
            throw new Exception('Gagal update stok');
        }

        // =====================
        // SIMPAN HISTORI MASUK
        // =====================
        $insertMasuk = mysqli_query($konek, "INSERT INTO tb_masuk (idbarang, iduser, pengirim, jumlah)
            VALUES ($idbarang, $iduser, '$pengirim', $jumlah)
        ");

        if (!$insertMasuk) {
            throw new Exception('Gagal simpan histori barang masuk');
        }

        // =====================
        // COMMIT
        // =====================
        mysqli_commit($konek);

        $_SESSION['success'] = 'Barang lama berhasil ditambahkan';
        header('Location: ../DataMasukPage.php');
        exit;
    } catch (Exception $e) {

        // =====================
        // ROLLBACK
        // =====================
        mysqli_rollback($konek);

        $_SESSION['error'] = $e->getMessage();
        header('Location: ../DataMasukPage.php');
        exit;
    }
}


//jika tombol update barang masuk diklik
if (isset($_POST['btnUpdateIn'])) {
    // kode untuk update data masuk
    date_default_timezone_set('Asia/Jakarta');

    // ambil data dari form
    $idmasuk   = (int) $_POST['idmasuk'];
    $idbarang  = (int) $_POST['idbarang'];
    $pengirim  = trim($_POST['pengirim']);
    $jumlah    = (int) $_POST['jumlah'];

    if (
        empty($idmasuk) ||
        empty($idbarang) ||
        empty($pengirim) ||
        empty($jumlah) ||
        $jumlah <= 0
    ) {
        $_SESSION['error'] = 'Input tidak valid';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    /* =========================
   TRANSACTION START
   ========================= */
    mysqli_begin_transaction($konek);

    try {
        /* =========================
       1. AMBIL DATA LAMA
       ========================= */
        $qOld = mysqli_query($konek, "SELECT idbarang, jumlah 
        FROM tb_masuk 
        WHERE idmasuk = $idmasuk
        FOR UPDATE");

        if (mysqli_num_rows($qOld) == 0) {
            throw new Exception('Data lama tidak ditemukan');
        }

        $old = mysqli_fetch_assoc($qOld);
        $idbarangLama = (int) $old['idbarang'];
        $jumlahLama   = (int) $old['jumlah'];

        /* =========================
         2. Ambil STOK LAMA
            ========================= */
        $stoqLama = mysqli_query($konek, "SELECT stok FROM tb_stok WHERE idbarang = $idbarangLama FOR UPDATE");

        if (mysqli_num_rows($stoqLama) == 0) {
            throw new Exception('Data stok lama tidak ditemukan');
        }

        $stokData = mysqli_fetch_assoc($stoqLama);
        $stokLama = (int) $stokData['stok'];

        //validasi kalo barang lama dan barang baru sama
        if ($idbarangLama == $idbarang) {
            //hitung stok akhir
            $stokAkhir = $stokLama - $jumlahLama + $jumlah;

            //validasi stok tidak boleh minus
            if ($stokAkhir < 0) {
                throw new Exception('Update dibatalkan karena stok tidak mencukupi.');
            }
            //update stok
            $UpdateStokLama = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = $stokAkhir 
            WHERE idbarang = $idbarang");

            if (!$UpdateStokLama) {
                throw new Exception('Gagal update stok');
            }
        } else {
            //hitung stok akhir barang lama
            $stokAkhirLama = $stokLama - $jumlahLama;

            //validasi stok barang lama tidak boleh minus
            if ($stokAkhirLama < 0) {
                throw new Exception('Update dibatalkan karena stok tidak mencukupi.');
            }

            //update stok barang lama
            $updateStokLama = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = $stokAkhirLama 
            WHERE idbarang = $idbarangLama");

            if (!$updateStokLama) {
                throw new Exception('Gagal update stok barang lama');
            }

            //ambil stok barang baru
            $stoqBaru = mysqli_query($konek, "SELECT stok FROM tb_stok WHERE idbarang = $idbarang FOR UPDATE");

            if (mysqli_num_rows($stoqBaru) == 0) {
                throw new Exception('Data stok baru tidak ditemukan');
            }

            $stokDataBaru = mysqli_fetch_assoc($stoqBaru);
            $stokBaru = (int) $stokDataBaru['stok'];

            //hitung stok akhir barang baru
            $stokAkhirBaru = $stokBaru + $jumlah;
            //update stok barang baru
            $updateStokBaru = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = $stokAkhirBaru 
            WHERE idbarang = $idbarang");
        }

        /* =========================
       4. UPDATE tb_masuk
       ========================= */
        $updateMasuk = mysqli_query($konek, "UPDATE tb_masuk 
        SET idbarang = $idbarang,
            jumlah   = $jumlah,
            pengirim = '$pengirim'
        WHERE idmasuk = $idmasuk
    ");

        if (!$updateMasuk) {
            throw new Exception('Gagal update data masuk');
        }

        /* =========================
       COMMIT
       ========================= */
        mysqli_commit($konek);

        $_SESSION['success'] = 'Data Barang Masuk Berhasil Diupdate';
        header('Location: ../DataMasukPage.php');
        exit;
    } catch (Exception $e) {

        mysqli_rollback($konek);

        $_SESSION['error'] = $e->getMessage();
        header('Location: ../DataMasukPage.php');
        exit;
    }
}

// menekan tombol hapus
if (isset($_POST['btnHapusIn'])) {
    // kode untuk menghapus data masuk
    $idmasuk = (int) $_POST['idmasuk'];
    // kode untuk menghapus data masuk
    if (empty($idmasuk)) {
        $_SESSION['error'] = 'Input tidak valid';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    // mulai transaksi
    mysqli_begin_transaction($konek);
    try {
        // ambil data masuk
        $q = mysqli_query($konek, "SELECT idbarang, jumlah 
            FROM tb_masuk 
            WHERE idmasuk = $idmasuk
            FOR UPDATE
        ");

        if (mysqli_num_rows($q) == 0) {
            throw new Exception('Data masuk tidak ditemukan');
        }



        $data = mysqli_fetch_assoc($q);
        $idbarang = (int) $data['idbarang'];
        $jumlah   = (int) $data['jumlah'];

        //validasi stok sebelum dikurangi
        $stok = mysqli_query($konek, "SELECT stok FROM tb_stok WHERE idbarang = $idbarang FOR UPDATE");

        if (mysqli_num_rows($stok) == 0) {
            throw new Exception('Data stok tidak ditemukan');
        }

        $stokData = mysqli_fetch_assoc($stok);
        $stokSekarang = (int) $stokData['stok'];

        if ($stokSekarang < $jumlah) {
            throw new Exception('Tidak bisa menghapus data masuk karena stok tidak mencukupi');
        }

        // kurangi stok
        $updateStok = mysqli_query($konek, "UPDATE tb_stok 
            SET stok = stok - $jumlah 
            WHERE idbarang = $idbarang");

        if (!$updateStok) {
            throw new Exception('Gagal mengurangi stok');
        }

        // hapus data masuk
        $deleteMasuk = mysqli_query($konek, "DELETE FROM tb_masuk 
            WHERE idmasuk = $idmasuk
        ");

        if (!$deleteMasuk) {
            throw new Exception('Gagal menghapus data masuk');
        }

        // commit transaksi
        mysqli_commit($konek);

        $_SESSION['success'] = 'Data Barang Masuk Berhasil Dihapus';
        header('Location: ../DataMasukPage.php');
        exit;
    } catch (Exception $e) {
        // rollback transaksi
        mysqli_rollback($konek);

        $_SESSION['error'] = $e->getMessage();
        header('Location: ../DataMasukPage.php');
        exit;
    }
}
