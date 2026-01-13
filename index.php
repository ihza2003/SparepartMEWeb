<?php
session_start();
require 'helper/authCheck.php';
$page_title = 'Dashboard';
$page_css = 'css/styles.css';
include 'layouts/headerPage.php';
require 'database/koneksi.php';
?>

<body class="sb-nav-fixed">

    <?php include 'components/header.php'; ?>

    <div id="layoutSidenav">

        <!-- Add Function Menu -->
        <?php include "components/sidebar.php"; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <!-- H1 Judul Halaman -->
                    <h3 class="mt-4">Data Akses Ruang Sparepart Site 2</h3><br>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            <!-- Judul Tabel -->
                            Tabel Akses Ruang Sparepart Maintenance Site 2
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nomor Kartu</th>
                                            <th>Nama </th>
                                            <th>Departement</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //baca data base tabel Akses dan relasikan dengan tabel berdasarkan nomor kartu RFID untuk tanggal hari ini
                                        //baca tanggal saat ini
                                        date_default_timezone_set('Asia/Jakarta');
                                        $tanggal = date('Y-m-d');

                                        //filter absensi berdasarkan tanggal saat ini
                                        $sqlIner = mysqli_query($konek, "SELECT * FROM tb_akses");


                                        $no = 0;
                                        while ($dataIner = mysqli_fetch_array($sqlIner)) {

                                            $no++;
                                        ?>
                                            <tr style="text-align: center;">
                                                <td> <?php echo $no; ?> </td>
                                                <td> <?php echo $dataIner['nomorkartu']; ?> </td>
                                                <td> <?php echo $dataIner['namalengkap']; ?> </td>
                                                <td> <?php echo $dataIner['departement']; ?> </td>
                                                <td> <?php echo $dataIner['jam']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add Modal -->
                <!-- <?php include "modal.php"; ?> -->

            </main>

            <!-- Add Function Footer -->
            <?php include "components/footer.php"; ?>

        </div>
    </div>

    <!-- Add Function Footer -->
    <?php include "layouts/footerPage.php"; ?>
</body>