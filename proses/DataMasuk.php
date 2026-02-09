<?php
session_start();
require '../database/koneksi.php';
//jika tombol simpan barang baru diklik
if (isset($_POST['btnInBaru'])) {

    date_default_timezone_set('Asia/Jakarta');

    $nomorbarang = trim($_POST['nomorbarang']);
    $namabarang  = trim($_POST['namabarang']);
    $mesin       = trim($_POST['mesin']);
    $norak       = trim($_POST['nomorrak']);
    $jumlah      = (int) $_POST['jumlah'];
    $iduser      = $_SESSION['admin_id'];

    // ================= VALIDASI FORM =================
    if (
        empty($nomorbarang) ||
        empty($namabarang) ||
        empty($mesin) ||
        empty($norak) ||
        $jumlah <= 0
    ) {
        $_SESSION['error'] = 'Data tidak boleh kosong';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    // ================= VALIDASI GAMBAR (WAJIB) =================
    if (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] !== 0) {
        $_SESSION['error'] = 'Gambar barang wajib diupload';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    $file     = $_FILES['gambar'];
    $size     = $file['size'];
    $tmp      = $file['tmp_name'];
    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    $allowed  = ['jpg', 'jpeg', 'png'];
    $maxSize  = 2 * 1024 * 1024; // 2MB

    if (!in_array($ext, $allowed)) {
        $_SESSION['error'] = 'Format gambar harus JPG / JPEG / PNG';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    if ($size > $maxSize) {
        $_SESSION['error'] = 'Ukuran gambar maksimal 2MB';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    // nama file unik
    $namaFile = uniqid('barang_', true) . '.' . $ext;
    $path     = '../storage/' . $namaFile;

    // ================= CEK BARANG (AMAN) =================
    $stmtCek = mysqli_prepare(
        $konek,
        "SELECT 1 FROM tb_stok WHERE nomorbarang = ?"
    );
    mysqli_stmt_bind_param($stmtCek, "s", $nomorbarang);
    mysqli_stmt_execute($stmtCek);
    mysqli_stmt_store_result($stmtCek);

    if (mysqli_stmt_num_rows($stmtCek) > 0) {
        $_SESSION['error'] = 'Nomor barang sudah Digunakan';
        header('Location: ../DataMasukPage.php');
        exit;
    }

    // ================= TRANSAKSI =================
    mysqli_begin_transaction($konek);

    try {

        if (!move_uploaded_file($tmp, $path)) {
            throw new Exception('Gagal upload gambar');
        }

        // ================= INSERT STOK =================
        $stmtStok = mysqli_prepare($konek, "INSERT INTO tb_stok 
            (nomorbarang, namabarang, mesin, norak, stok, gambar)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $stmtStok,
            "ssssis",
            $nomorbarang,
            $namabarang,
            $mesin,
            $norak,
            $jumlah,
            $namaFile
        );

        if (!mysqli_stmt_execute($stmtStok)) {
            throw new Exception('Gagal simpan stok');
        }

        $idbarang = mysqli_insert_id($konek);

        // ================= INSERT BARANG MASUK (AMAN) =================
        $stmtMasuk = mysqli_prepare($konek, "INSERT INTO tb_masuk (idbarang, iduser, jumlah)
            VALUES (?, ?, ?)
        ");

        mysqli_stmt_bind_param(
            $stmtMasuk,
            "iii",
            $idbarang,
            $iduser,
            $jumlah
        );

        if (!mysqli_stmt_execute($stmtMasuk)) {
            throw new Exception('Gagal simpan barang masuk');
        }

        mysqli_commit($konek);
        $_SESSION['success'] = 'Barang baru berhasil disimpan';
        header('Location: ../DataMasukPage.php');
        exit;
    } catch (Exception $e) {

        mysqli_rollback($konek);

        //menghapus data 
        if (file_exists($path)) {
            unlink($path);
        }

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
    $jumlah   = (int) $_POST['jumlah'];
    $iduser   = $_SESSION['admin_id'];

    // =====================
    // VALIDASI INPUT
    // =====================
    if (
        empty($idbarang) ||
        empty($jumlah) ||
        $jumlah <= 0 ||
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
        // LOCK DATA STOK (AMAN)
        // =====================
        // prepare: menyiapkan query SQL dengan placeholder (?)
        $stmtStok = mysqli_prepare($konek, "SELECT stok 
            FROM tb_stok 
            WHERE idbarang = ?
            FOR UPDATE
        ");

        // bind_param: mengirim DATA INPUT ke query
        // "i" artinya integer → idbarang
        mysqli_stmt_bind_param($stmtStok, "i", $idbarang);

        // execute: menjalankan query ke MySQL
        // (query dijalankan, tapi hasil masih di server)
        mysqli_stmt_execute($stmtStok);

        // store_result: menyimpan hasil query di memory
        // agar bisa dicek jumlah barisnya
        mysqli_stmt_store_result($stmtStok);

        // cek apakah data barang ada
        if (mysqli_stmt_num_rows($stmtStok) == 0) {
            throw new Exception('Barang tidak ditemukan');
        }

        // bind_result: menyiapkan variabel PENAMPUNG hasil SELECT
        // setelah variabel berisi query harus sesuai dengna data yang ingin di porses di query
        // (belum ada data masuk ke variabel)
        mysqli_stmt_bind_result($stmtStok, $stokSekarang);

        // fetch: mengambil data hasil query dari MySQL
        // dan mengisi $stokSekarang dengan nilai kolom stok
        mysqli_stmt_fetch($stmtStok);

        // =====================
        // UPDATE STOK (AMAN)
        // =====================
        // hitung stok akhir
        $stokAkhir = $stokSekarang + $jumlah;

        // prepare query update stok
        $stmtUpdate = mysqli_prepare($konek, "UPDATE tb_stok 
            SET stok = ?
            WHERE idbarang = ?
        ");

        // bind_param: kirim data stok baru dan idbarang
        //isinya berdasarkan data yang ditampilin di query dan harus berurutan
        mysqli_stmt_bind_param(
            $stmtUpdate,
            "ii",
            $stokAkhir,
            $idbarang
        );

        // execute update stok
        if (!mysqli_stmt_execute($stmtUpdate)) {
            throw new Exception('Gagal update stok');
        }

        // =====================
        // SIMPAN HISTORI MASUK (AMAN)
        // =====================
        // prepare query insert histori barang masuk
        $stmtMasuk = mysqli_prepare($konek, "INSERT INTO tb_masuk (idbarang, iduser, jumlah)
            VALUES (?, ?, ?)
        ");

        // bind_param: kirim data histori masuk
        mysqli_stmt_bind_param(
            $stmtMasuk,
            "iii",
            $idbarang,
            $iduser,
            $jumlah
        );

        // execute insert histori
        if (!mysqli_stmt_execute($stmtMasuk)) {
            throw new Exception('Gagal simpan histori barang masuk');
        }

        // =====================
        // COMMIT
        // =====================
        // simpan semua perubahan ke database
        mysqli_commit($konek);

        $_SESSION['success'] = 'Barang lama berhasil ditambahkan';
        header('Location: ../DataMasukPage.php');
        exit;
    } catch (Exception $e) {

        // =====================
        // ROLLBACK
        // =====================
        // batalkan semua query jika ada error
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
    $jumlah    = (int) $_POST['jumlah'];

    if (
        empty($idmasuk) ||
        empty($idbarang) ||
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
           1. AMBIL DATA LAMA (AMAN)
           ========================= */
        // prepare query select data lama
        $stmtOld = mysqli_prepare($konek, "SELECT idbarang, jumlah 
            FROM tb_masuk 
            WHERE idmasuk = ?
            FOR UPDATE
        ");

        // bind_param: kirim idmasuk ke query
        mysqli_stmt_bind_param($stmtOld, "i", $idmasuk);
        mysqli_stmt_execute($stmtOld);

        // simpan hasil agar bisa cek jumlah baris
        mysqli_stmt_store_result($stmtOld);

        if (mysqli_stmt_num_rows($stmtOld) == 0) {
            throw new Exception('Data lama tidak ditemukan');
        }

        // bind_result: siapkan variabel penampung hasil
        mysqli_stmt_bind_result($stmtOld, $idbarangLama, $jumlahLama);
        // fetch: ambil data ke variabel
        mysqli_stmt_fetch($stmtOld);

        /* =========================
           2. AMBIL STOK LAMA (AMAN)
           ========================= */
        $stmtStokLama = mysqli_prepare($konek, "SELECT stok 
            FROM tb_stok 
            WHERE idbarang = ?
            FOR UPDATE
        ");

        mysqli_stmt_bind_param($stmtStokLama, "i", $idbarangLama);
        mysqli_stmt_execute($stmtStokLama);
        mysqli_stmt_store_result($stmtStokLama);

        if (mysqli_stmt_num_rows($stmtStokLama) == 0) {
            throw new Exception('Data stok lama tidak ditemukan');
        }

        mysqli_stmt_bind_result($stmtStokLama, $stokLama);
        mysqli_stmt_fetch($stmtStokLama);

        // validasi kalo barang lama dan barang baru sama
        if ($idbarangLama == $idbarang) {

            // hitung stok akhir
            $stokAkhir = $stokLama - $jumlahLama + $jumlah;

            // validasi stok tidak boleh minus
            if ($stokAkhir < 0) {
                throw new Exception('Update dibatalkan karena stok tidak mencukupi.');
            }

            // update stok (AMAN)
            $stmtUpdateStok = mysqli_prepare($konek, "UPDATE tb_stok 
                SET stok = ?
                WHERE idbarang = ?
            ");

            mysqli_stmt_bind_param(
                $stmtUpdateStok,
                "ii",
                $stokAkhir,
                $idbarang
            );

            if (!mysqli_stmt_execute($stmtUpdateStok)) {
                throw new Exception('Gagal update stok');
            }
        } else {

            // hitung stok akhir barang lama
            $stokAkhirLama = $stokLama - $jumlahLama;

            if ($stokAkhirLama < 0) {
                throw new Exception('Update dibatalkan karena stok tidak mencukupi.');
            }

            // update stok barang lama (AMAN)
            $stmtUpdateStokLama = mysqli_prepare($konek, "UPDATE tb_stok 
                SET stok = ?
                WHERE idbarang = ?
            ");

            mysqli_stmt_bind_param(
                $stmtUpdateStokLama,
                "ii",
                $stokAkhirLama,
                $idbarangLama
            );

            if (!mysqli_stmt_execute($stmtUpdateStokLama)) {
                throw new Exception('Gagal update stok barang lama');
            }

            // ambil stok barang baru (AMAN)
            $stmtStokBaru = mysqli_prepare($konek, "SELECT stok 
                FROM tb_stok 
                WHERE idbarang = ?
                FOR UPDATE
            ");

            mysqli_stmt_bind_param($stmtStokBaru, "i", $idbarang);
            mysqli_stmt_execute($stmtStokBaru);
            mysqli_stmt_store_result($stmtStokBaru);

            if (mysqli_stmt_num_rows($stmtStokBaru) == 0) {
                throw new Exception('Data stok baru tidak ditemukan');
            }

            mysqli_stmt_bind_result($stmtStokBaru, $stokBaru);
            mysqli_stmt_fetch($stmtStokBaru);

            // hitung stok akhir barang baru
            $stokAkhirBaru = $stokBaru + $jumlah;

            // update stok barang baru (AMAN)
            $stmtUpdateStokBaru = mysqli_prepare($konek, "UPDATE tb_stok 
                SET stok = ?
                WHERE idbarang = ?
            ");

            mysqli_stmt_bind_param(
                $stmtUpdateStokBaru,
                "ii",
                $stokAkhirBaru,
                $idbarang
            );

            if (!mysqli_stmt_execute($stmtUpdateStokBaru)) {
                throw new Exception('Gagal update stok barang baru');
            }
        }

        /* =========================
           4. UPDATE tb_masuk (AMAN)
           ========================= */
        $stmtUpdateMasuk = mysqli_prepare($konek, "UPDATE tb_masuk 
            SET idbarang = ?,
                jumlah   = ?
            WHERE idmasuk = ?
        ");

        mysqli_stmt_bind_param(
            $stmtUpdateMasuk,
            "iii",
            $idbarang,
            $jumlah,
            $idmasuk
        );

        if (!mysqli_stmt_execute($stmtUpdateMasuk)) {
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

        // =========================
        // AMBIL DATA MASUK (AMAN)
        // =========================
        // prepare query select data masuk
        $stmtMasuk = mysqli_prepare($konek, "SELECT idbarang, jumlah 
            FROM tb_masuk 
            WHERE idmasuk = ?
            FOR UPDATE
        ");

        // bind_param: kirim idmasuk ke query
        mysqli_stmt_bind_param($stmtMasuk, "i", $idmasuk);

        // execute: jalankan query
        mysqli_stmt_execute($stmtMasuk);

        // store_result: simpan hasil query agar bisa cek jumlah baris
        mysqli_stmt_store_result($stmtMasuk);

        if (mysqli_stmt_num_rows($stmtMasuk) == 0) {
            throw new Exception('Data masuk tidak ditemukan');
        }

        // bind_result: siapkan variabel penampung hasil
        mysqli_stmt_bind_result($stmtMasuk, $idbarang, $jumlah);

        // fetch: ambil data hasil query ke variabel
        mysqli_stmt_fetch($stmtMasuk);

        // =========================
        // VALIDASI STOK (AMAN)
        // =========================
        $stmtStok = mysqli_prepare($konek, "SELECT stok 
            FROM tb_stok 
            WHERE idbarang = ?
            FOR UPDATE
        ");

        mysqli_stmt_bind_param($stmtStok, "i", $idbarang);
        mysqli_stmt_execute($stmtStok);
        mysqli_stmt_store_result($stmtStok);

        if (mysqli_stmt_num_rows($stmtStok) == 0) {
            throw new Exception('Data stok tidak ditemukan');
        }

        mysqli_stmt_bind_result($stmtStok, $stokSekarang);
        mysqli_stmt_fetch($stmtStok);

        if ($stokSekarang < $jumlah) {
            throw new Exception('Tidak bisa menghapus data masuk karena stok tidak mencukupi');
        }

        // =========================
        // KURANGI STOK (AMAN)
        // =========================
        $stmtUpdateStok = mysqli_prepare($konek, "UPDATE tb_stok 
            SET stok = stok - ?
            WHERE idbarang = ?
        ");

        mysqli_stmt_bind_param(
            $stmtUpdateStok,
            "ii",
            $jumlah,
            $idbarang
        );

        if (!mysqli_stmt_execute($stmtUpdateStok)) {
            throw new Exception('Gagal mengurangi stok');
        }

        // =========================
        // HAPUS DATA MASUK (AMAN)
        // =========================
        $stmtDelete = mysqli_prepare($konek, "DELETE FROM tb_masuk 
            WHERE idmasuk = ?
        ");

        mysqli_stmt_bind_param($stmtDelete, "i", $idmasuk);

        if (!mysqli_stmt_execute($stmtDelete)) {
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
