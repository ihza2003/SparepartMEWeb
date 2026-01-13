<?php
session_start();
require 'helper/authCheck.php';
$page_title = 'Data Barang Masuk';
$page_css = 'css/styles.css';
include 'layouts/headerPage.php';
require 'database/koneksi.php';

?>

<body class="sb-nav-fixed">

    <?php include 'components/header.php'; ?>

    <div id="layoutSidenav">

        <!-- Add Sidebar Menu -->
        <?php include "components/sidebar.php"; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h3 class="mt-4">Data Sparepart Masuk - Maintenance Site 2</h3><br>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Table Data Stok Barang Masuk - Maintenace Site 2
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No.</th>
                                            <th style="text-align: center;">Nomor Barang</th>
                                            <th style="text-align: center;">Nama Barang</th>
                                            <th style="text-align: center;">Mesin</th>
                                            <th style="text-align: center;">Nomor Rak</th>
                                            <th style="text-align: center;">Pengirim</th>
                                            <th style="text-align: center;">Jumlah</th>
                                            <th style="text-align: center;">Tanggal</th>
                                            <th style="text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //baca data base tabel Masuk dan relasikan dengan tabel untuk tanggal hari ini

                                        //filter tabel
                                        $sqlIner = mysqli_query($konek, "
                                            SELECT 
                                            m.idmasuk,
                                            m.tanggal,
                                            m.pengirim,
                                            m.jumlah,
                                            s.nomorbarang,
                                            s.namabarang,
                                            s.mesin,
                                            s.norak
                                            FROM tb_masuk m
                                            JOIN tb_stok s ON m.idbarang = s.idbarang
                                            ORDER BY m.tanggal DESC");
                                        $no = 0;
                                        while ($dataIner = mysqli_fetch_array($sqlIner)) {

                                            $no++;
                                        ?>
                                            <tr>
                                                <td style="text-align:center;"><?= $no ?></td>
                                                <td style="text-align:center;"><?= $dataIner['nomorbarang'] ?></td>
                                                <td><?= $dataIner['namabarang'] ?></td>
                                                <td><?= $dataIner['mesin'] ?></td>
                                                <td style="text-align:center;"><?= $dataIner['norak'] ?></td>
                                                <td style="text-align:center;"><?= $dataIner['pengirim'] ?></td>
                                                <td style="text-align:center;"><?= $dataIner['jumlah'] ?></td>
                                                <td style="text-align:center;"><?= $dataIner['tanggal'] ?></td>
                                                <td style="text-align:center;">
                                                    <button class="btn btn-warning btn-sm">Edit</button>
                                                    <button class="btn btn-danger btn-sm">Delete</button>
                                                </td>
                                            </tr>

                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Modal -->
                <?php include "components/modalMasukBaru.php"; ?>
                <?php include "components/modalMasukLama.php"; ?>
                <!-- <?php include "modal.php"; ?> -->
            </main>
            <!-- Add Function Footer -->
            <?php include "components/footer.php"; ?>
        </div>
    </div>

    <!-- Add Function Footer -->
    <?php include "layouts/footerPage.php"; ?>
</body>