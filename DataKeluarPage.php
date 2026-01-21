<?php
session_start();
require 'helper/authCheck.php';
$page_title = 'Data Barang Keluar';
$page_css = 'css/styles.css';
$page_css_responsive = 'css/responsive.css';
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
                    <h4 class="mt-4 header-content">Data Sparepart Keluar - Maintenance Site 2</h4><br>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table mr-1"></i>
                            Table Data Stok Barang Keluar - Maintenace Site 2
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
                            <form method="POST" class="mb-3">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="row h-100 align-items-end">
                                            <div class="col-md-5 mb-2">
                                                <label class="small font-weight-bold">Tanggal Awal</label>
                                                <input type="date"
                                                    name="firstDate"
                                                    class="form-control"
                                                    value="<?= $_POST['firstDate'] ?? '' ?>"
                                                    required>
                                            </div>

                                            <div class="col-md-5 mb-2">
                                                <label class="small font-weight-bold">Tanggal Akhir</label>
                                                <input type="date"
                                                    name="endDate"
                                                    class="form-control"
                                                    value="<?= $_POST['endDate'] ?? '' ?>"
                                                    required>
                                            </div>

                                            <div class="col-md-2 mb-2">
                                                <button type="submit"
                                                    name="btnFilterTanggal"
                                                    class="btn btn-primary mr-2">
                                                    <i class="fa fa-filter"></i> Filter
                                                </button>

                                                <a href="DataMasukPage.php"
                                                    class="btn btn-secondary">
                                                    Reset
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No.</th>
                                            <th style="text-align: center;">Nomor Barang</th>
                                            <th style="text-align: center;">Nama Barang</th>
                                            <th style="text-align: center;">Mesin</th>
                                            <th style="text-align: center;">Nomor Rak</th>
                                            <th style="text-align: center;">Penerima</th>
                                            <th style="text-align: center;">Jumlah</th>
                                            <th style="text-align: center;">Tanggal</th>
                                            <th style="text-align: center;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        //filter tabel sesuai rentang tanggal
                                        if (isset($_POST['btnFilterTanggal'])) {
                                            $firstDate = $_POST['firstDate'];
                                            $endDate = $_POST['endDate'];
                                            $sqlIner = mysqli_query($konek, "SELECT 
                                            m.idkeluar,
                                            m.tanggal,
                                            m.idbarang,
                                            m.penerima,
                                            m.jumlah,
                                            s.nomorbarang,
                                            s.namabarang,
                                            s.mesin,
                                            s.norak
                                            FROM tb_keluar m
                                            JOIN tb_stok s ON m.idbarang = s.idbarang
                                            WHERE m.tanggal BETWEEN '$firstDate' AND '$endDate'
                                            ORDER BY m.tanggal DESC");
                                        } else {
                                            $sqlIner = mysqli_query($konek, "SELECT 
                                            m.idkeluar,
                                            m.tanggal,
                                            m.idbarang,
                                            m.penerima,
                                            m.jumlah,
                                            s.nomorbarang,
                                            s.namabarang,
                                            s.mesin,
                                            s.norak
                                            FROM tb_keluar m
                                            JOIN tb_stok s ON m.idbarang = s.idbarang
                                            ORDER BY m.tanggal DESC");
                                        }

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
                                                <td style="text-align:center;"><?= $dataIner['penerima'] ?></td>
                                                <td style="text-align:center;"><?= $dataIner['jumlah'] ?></td>
                                                <td style="text-align:center;"><?= $dataIner['tanggal'] ?></td>
                                                <td style="text-align:center;">
                                                    <button type="button"
                                                        class="btn btn-warning  btn-sm"
                                                        data-toggle="modal"
                                                        data-target="#modalUpdateKeluar<?= $dataIner['idkeluar']; ?>"
                                                        title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <button class="btn btn-danger btn-sm rounded"
                                                        title="Hapus"
                                                        data-toggle="modal"
                                                        data-target="#modalHapuskeluar<?= $dataIner['idkeluar'] ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php include "components/modalKeluarUpdate.php"; ?>
                                            <?php include "components/modalHapusKeluar.php"; ?>
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
                <?php include "components/modalBarangKeluar.php"; ?>
                <!-- <?php include "modal.php"; ?> -->
            </main>
            <!-- Add Function Footer -->
            <?php include "components/footer.php"; ?>
        </div>
    </div>

    <!-- Add Function Footer -->
    <?php include "layouts/footerPage.php"; ?>
</body>