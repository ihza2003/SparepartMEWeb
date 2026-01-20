<?php
session_start();
require 'helper/authCheck.php';
$page_title = 'Data Stok Barang';
$page_css = 'css/styles.css';
$page_css_responsive = 'css/responsive.css';
include 'layouts/headerPage.php';
require 'database/koneksi.php';

$qHijau  = mysqli_query($konek, "SELECT COUNT(*) AS total FROM tb_stok WHERE stok > 5");
$qKuning = mysqli_query($konek, "SELECT COUNT(*) AS total FROM tb_stok WHERE stok BETWEEN 1 AND 5");
$qMerah  = mysqli_query($konek, "SELECT COUNT(*) AS total FROM tb_stok WHERE stok = 0");

$hijau  = mysqli_fetch_assoc($qHijau)['total'];
$kuning = mysqli_fetch_assoc($qKuning)['total'];
$merah  = mysqli_fetch_assoc($qMerah)['total'];

?>

<body class="sb-nav-fixed">

    <?php include 'components/header.php'; ?>

    <div id="layoutSidenav">

        <!-- Add Sidebar Menu -->
        <?php include "components/sidebar.php"; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h3 class="mt-4 header-content">Data Stok Sparepart Maintenance Site 2</h3>
                    <div class=" row g-4 justify-content-between my-4">
                        <!-- STOK AMAN -->
                        <div class="card-statistik col-xl-4 col-md-6 mb-4">
                            <div class="card shadow" style="border-radius: 12px;">
                                <div class="card-body py-3 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-secondary mb-1" style="font-size: 0.95rem; font-weight: 500;">Stok Aman</p>

                                            <h2 class="fw-bold mb-1" style="color: #2d3436; font-size: 2rem;"><?= $hijau ?></h2>

                                            <div class="d-flex align-items-center">
                                                <small class="text-muted">Barang siap digunakan</small>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center shadow-lg"
                                            style="width: 52px; height: 52px; border-radius: 14px; 
                                            background: linear-gradient(135deg, #42d392 0%, #159a5c 100%); 
                                            box-shadow: 0 8px 15px rgba(21, 154, 92, 0.25) !important;">
                                            <i class="fas fa-check-circle text-white fs-4"></i>
                                        </div>
                                    </div>

                                    <div class="mt-2 pt-1 border-top">
                                        <a href="#" class="text-decoration-none text-success small fw-bold">Lihat Detail →</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STOK MENIPIS -->
                        <div class=" card-statistik col-xl-4 col-md-6 mb-4">
                            <div class="card shadow" style="border-radius: 12px;">
                                <div class="card-body py-3 px-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-secondary mb-1" style="font-size: 0.95rem; font-weight: 500;">Stok Menipis</p>

                                            <h2 class="fw-bold mb-1" style="color: #2d3436; font-size: 2rem;"><?= $kuning ?></h2>

                                            <div class="d-flex align-items-center">
                                                <small class="text-muted">Segera lakukan re-stok</small>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center shadow-lg"
                                            style="width: 52px; height: 52px; border-radius: 14px; 
                                            background: linear-gradient(135deg, #ffce67 0%, #ffab00 100%); 
                                            box-shadow: 0 8px 15px rgba(255, 171, 0, 0.25) !important;">
                                            <i class="fas fa-exclamation-triangle text-white fs-4"></i>
                                        </div>
                                    </div>

                                    <div class="mt-2 pt-1 border-top">
                                        <a href="#" class="text-decoration-none small fw-bold" style="color: #ffab00;">Lihat Detail →</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STOK HABIS -->
                        <div class="card-statistik col-xl-4 col-md-6 mb-4">
                            <div class="card shadow" style="border-radius: 12px;">
                                <div class="card-body py-3 px-4">
                                    <div class="d-flex justify-content-between align-items-center ">
                                        <div>
                                            <p class="text-secondary mb-1" style="font-size: 0.95rem; font-weight: 500;">Stok Habis</p>

                                            <h2 class="fw-bold mb-1" style="color: #2d3436; font-size: 2rem;"><?= $merah ?></h2>

                                            <div class="d-flex align-items-center">
                                                <small class="text-muted">Perlu tindakan segera</small>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-center shadow-lg"
                                            style="width: 52px; height: 52px; border-radius: 14px; 
                                            background: linear-gradient(135deg, #ff5f6d 0%, #d90429 100%); 
                                            box-shadow: 0 8px 15px rgba(217, 4, 41, 0.25) !important;">
                                            <i class="fas fa-times-circle text-white fs-4"></i>
                                        </div>
                                    </div>

                                    <div class="mt-2 pt-1 border-top">
                                        <a href="#" class="text-decoration-none text-danger small fw-bold">Lihat Detail →</a>
                                    </div>
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
                            <?php if (isset($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fa fa-check-circle"></i>
                                    <?= $_SESSION['success']; ?>
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fa fa-times-circle"></i>
                                    <?= $_SESSION['error']; ?>
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <?php unset($_SESSION['error']); ?>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No.</th>
                                            <th style="text-align: center;">Nomor Barang</th>
                                            <th style="text-align: center;">Nama Barang</th>
                                            <th style="text-align: center;">Mesin</th>
                                            <th style="text-align: center;">Nomor Rak</th>
                                            <th style="text-align: center;">Gambar</th>
                                            <th style="text-align: center;">Stok</th>
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
                                                <td style="text-align: center;"> <img src="storage/<?php echo $dataIner['gambar']; ?>" width="50"></td>
                                                <td style="text-align: center;"> <?php echo $dataIner['stok']; ?></td>
                                                <td style="text-align: center;">
                                                    <button type="button"
                                                        class="btn btn-warning  btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalUpdateStok<?= $dataIner['idbarang']; ?>"
                                                        title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <!-- <button class="btn btn-danger btn-sm rounded"
                                                        title="Hapus"
                                                        data-toggle="modal"
                                                        data-target="#modalHapusMasuk<?= $dataIner['idmasuk'] ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button> -->
                                                </td>
                                            </tr>
                                            <?php include "components/modalStokUpdate.php"; ?>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include "components/modalMasukBaru.php"; ?>
                <?php include "components/modalMasukLama.php"; ?>
                <?php include "components/modalBarangKeluar.php"; ?>
            </main>

            <!-- Add Function Footer -->
            <?php include "components/footer.php"; ?>

        </div>
    </div>

    <!-- Add Function Footer -->
    <?php include "layouts/footerPage.php"; ?>
</body>