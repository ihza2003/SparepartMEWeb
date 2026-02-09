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
        $sqlUpdateStok = "UPDATE tb_stok 
            SET stok = stok - ?
            WHERE idbarang = ?
            AND stok >= ?";

        $stmtUpdate = mysqli_prepare($konek, $sqlUpdateStok);
        mysqli_stmt_bind_param($stmtUpdate, "iii", $jumlah, $idbarang, $jumlah);
        mysqli_stmt_execute($stmtUpdate);

        // Jika stok tidak cukup / barang tidak ada
        if (mysqli_stmt_affected_rows($stmtUpdate) == 0) {
            throw new Exception('Stok tidak mencukupi');
        }

        /* =========================
           2. SIMPAN KE tb_keluar
           ========================= */
        $sqlInsertKeluar = "INSERT INTO tb_keluar (idbarang, iduser, penerima, jumlah)
            VALUES (?, ?, ?, ?)";

        $stmtInsert = mysqli_prepare($konek, $sqlInsertKeluar);
        mysqli_stmt_bind_param($stmtInsert, "iisi", $idbarang, $iduser, $penerima, $jumlah);
        mysqli_stmt_execute($stmtInsert);

        if (mysqli_stmt_affected_rows($stmtInsert) == 0) {
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

        /* =========================
           ambil data lama dari tb_keluar (AMAN)
           ========================= */
        $stmtOld = mysqli_prepare($konek, "SELECT idbarang, jumlah
            FROM tb_keluar
            WHERE idkeluar = ?
            FOR UPDATE
        ");
        mysqli_stmt_bind_param($stmtOld, "i", $idkeluar);
        mysqli_stmt_execute($stmtOld);
        mysqli_stmt_store_result($stmtOld);

        if (mysqli_stmt_num_rows($stmtOld) == 0) {
            throw new Exception('Data lama tidak ditemukan');
        }

        // ambil hasil SELECT ke variabel
        mysqli_stmt_bind_result($stmtOld, $idbarangLama, $jumlahLama);
        mysqli_stmt_fetch($stmtOld);
        mysqli_stmt_close($stmtOld);

        /* =========================
           mengambil data stok saat ini (AMAN)
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
        mysqli_stmt_close($stmtStokLama);

        //validasi barang
        if ($idbarangLama == $idbarang) {

            //hitung stok akhir
            $stokAkhir = $stokLama + $jumlahLama - $jumlah;

            //validasi stok tidak boleh minus
            if ($stokAkhir < 0) {
                throw new Exception('Stok tidak mencukupi');
            }

            //update stok (AMAN)
            $stmtUpdateStokLama = mysqli_prepare($konek, "UPDATE tb_stok
                SET stok = ?
                WHERE idbarang = ?
            ");
            mysqli_stmt_bind_param($stmtUpdateStokLama, "ii", $stokAkhir, $idbarangLama);

            if (!mysqli_stmt_execute($stmtUpdateStokLama)) {
                throw new Exception('Gagal update stok lama');
            }

            mysqli_stmt_close($stmtUpdateStokLama);
        } else {

            //hitung stok untuk barang lama
            $stokAkhirLama = $stokLama + $jumlahLama;

            //update stok barang lama (AMAN)
            $stmtUpdateStokLama = mysqli_prepare($konek, "UPDATE tb_stok
                SET stok = ?
                WHERE idbarang = ?
            ");
            mysqli_stmt_bind_param($stmtUpdateStokLama, "ii", $stokAkhirLama, $idbarangLama);
            if (!mysqli_stmt_execute($stmtUpdateStokLama)) {
                throw new Exception('Gagal update stok lama');
            }

            mysqli_stmt_close($stmtUpdateStokLama);

            //ambil data stok barang baru (AMAN)
            $stmtStokBaru = mysqli_prepare($konek, "
                SELECT stok
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
            mysqli_stmt_close($stmtStokBaru);

            //hitung stok akhir barang baru
            $stokAkhirBaru = $stokBaru - $jumlah;

            //validasi stok barang baru tidak boleh minus
            if ($stokAkhirBaru < 0) {
                throw new Exception('Stok tidak mencukupi');
            }

            //update stok barang baru (AMAN)
            $stmtUpdateStokBaru = mysqli_prepare($konek, "
                UPDATE tb_stok
                SET stok = ?
                WHERE idbarang = ?
            ");
            mysqli_stmt_bind_param($stmtUpdateStokBaru, "ii", $stokAkhirBaru, $idbarang);

            if (!mysqli_stmt_execute($stmtUpdateStokBaru)) {
                throw new Exception('Gagal update stok baru');
            }
            mysqli_stmt_close($stmtUpdateStokBaru);
        }

        /* =========================
           update data di tb_keluar (AMAN)
           ========================= */
        $stmtUpdateKeluar = mysqli_prepare($konek, "
            UPDATE tb_keluar
            SET idbarang = ?, jumlah = ?, penerima = ?
            WHERE idkeluar = ?
        ");
        mysqli_stmt_bind_param(
            $stmtUpdateKeluar,
            "iisi",
            $idbarang,
            $jumlah,
            $penerima,
            $idkeluar
        );
        if (!mysqli_stmt_execute($stmtUpdateKeluar)) {
            throw new Exception('Gagal update data keluar');
        }
        mysqli_stmt_close($stmtUpdateKeluar);

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

        /* =========================
           ambil data keluar (AMAN)
           ========================= */
        $stmtKeluar = mysqli_prepare($konek, "
            SELECT idbarang, jumlah
            FROM tb_keluar
            WHERE idkeluar = ?
            FOR UPDATE
        ");
        mysqli_stmt_bind_param($stmtKeluar, "i", $idkeluar);
        mysqli_stmt_execute($stmtKeluar);
        mysqli_stmt_store_result($stmtKeluar);

        if (mysqli_stmt_num_rows($stmtKeluar) == 0) {
            throw new Exception('Data keluar tidak ditemukan');
        }

        mysqli_stmt_bind_result($stmtKeluar, $idbarang, $jumlah);
        mysqli_stmt_fetch($stmtKeluar);
        mysqli_stmt_close($stmtKeluar);

        /* =========================
           validasi stok sebelum dikurangi (AMAN)
           ========================= */
        $stmtStok = mysqli_prepare($konek, "
            SELECT stok
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
        mysqli_stmt_close($stmtStok);

        // if ($stokSekarang < $jumlah) {
        //     throw new Exception('Tidak bisa menghapus data keluar karena stok sudah digunakan (stok kurang)');
        // }

        /* =========================
           kembalikan stok (AMAN)
           ========================= */
        $stmtUpdateStok = mysqli_prepare($konek, "
            UPDATE tb_stok
            SET stok = stok + ?
            WHERE idbarang = ?
        ");
        mysqli_stmt_bind_param($stmtUpdateStok, "ii", $jumlah, $idbarang);

        if (!mysqli_stmt_execute($stmtUpdateStok)) {
            throw new Exception('Gagal mengurangi stok');
        }
        mysqli_stmt_close($stmtUpdateStok);

        /* =========================
           hapus data keluar (AMAN)
           ========================= */
        $stmtDelete = mysqli_prepare($konek, "
            DELETE FROM tb_keluar
            WHERE idkeluar = ?
        ");
        mysqli_stmt_bind_param($stmtDelete, "i", $idkeluar);

        if (!mysqli_stmt_execute($stmtDelete)) {
            throw new Exception('Gagal menghapus data keluar');
        }
        mysqli_stmt_close($stmtDelete);

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
