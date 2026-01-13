<?php
session_start();
require 'helper/authCheck.php';
$page_title = 'Data Stok Barang';
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
                    <h3 class="mt-4 ">Data Stok Sparepart Maintenance Site 2</h3>
                    <div class="row my-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Primary Card</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">Warning Card</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Success Card</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">Danger Card</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Table Data Stok Barang Maintenace Site 2
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
                                            <th style="text-align: center;">Jumlah</th>
                                            <th style="text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //baca data base tabel Stok dan relasikan dengan tabel untuk tanggal hari ini

                                        //filter tabel
                                        $sqlIner = mysqli_query($konek, "SELECT * FROM tb_stok");

                                        $no = 0;
                                        while ($dataIner = mysqli_fetch_array($sqlIner)) {

                                            $no++;
                                        ?>
                                            <tr>
                                                <td style="text-align: center;"> <?php echo $no; ?> </td>
                                                <td style="text-align: center;"> <?php echo $dataIner['nomorbarang']; ?> </td>
                                                <td> <?php echo $dataIner['namabarang']; ?> </td>
                                                <td style="text-align: center;"> <?php echo $dataIner['mesin']; ?> </td>
                                                <td style="text-align: center;"> <?php echo $dataIner['norak']; ?></td>
                                                <td style="text-align: center;"> <?php echo $dataIner['stok']; ?></td>
                                                <td style="text-align: center;">
                                                    <button type="button" class="btn btn-warning">Edit</button>
                                                    <button type="button" class="btn btn-danger">Delete</button>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include "components/modalMasukBaru.php"; ?>
                <?php include "components/modalMasukLama.php"; ?>
            </main>

            <!-- Add Function Footer -->
            <?php include "components/footer.php"; ?>

        </div>
    </div>

    <!-- Add Function Footer -->
    <?php include "layouts/footerPage.php"; ?>
</body>