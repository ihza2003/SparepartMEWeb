<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar</title>

    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        /* ================= KOP SURAT ================= */
        .kop-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }

        .kop-logo {
            width: 80px;
        }

        .kop-text {
            text-align: center;
            line-height: 1.4;
        }

        .kop-text .title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text .subtitle {
            font-size: 12px;
        }

        /* ================= JUDUL ================= */
        .judul {
            text-align: center;
            font-size: 17px;
            font-weight: bold;
            margin: 15px 0 10px;
            text-transform: uppercase;
        }

        .periode {
            text-align: center;
            font-size: 11px;
            margin-bottom: 15px;
        }

        /* ================= TABEL ================= */
        table.data {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        table.data th,
        table.data td {
            border: 1px solid #000;
            padding: 6px 4px;
        }

        table.data th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        table.data td {
            vertical-align: middle;
        }

        .text-center {
            text-align: center;
        }

        /* ================= FOOTER ================= */
        .footer {
            margin-top: 20px;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <!-- ================= KOP ================= -->
    <table class="kop-table" cellpadding="0" cellspacing="0">
        <tr>
            <td width="15%" align="start">
                <img src="<?= __DIR__ ?>/../img/logo.png" width="70">
            </td>
            <td width="85%" class="kop-text">
                <div class="title">MAINTENANCE SITE 2</div>
                <div class="subtitle">Laporan Data Barang Keluar</div>
            </td>
            <td width="15%" align="start" valign="middle"></td>
        </tr>
    </table>

    <!-- ================= JUDUL ================= -->
    <div class="judul">DATA BARANG Keluar</div>

    <?php if (!empty($_POST['firstDate']) && !empty($_POST['endDate'])): ?>
        <div class="periode">
            Periode: <?= date('d-m-Y', strtotime($_POST['firstDate'])) ?>
            s/d
            <?= date('d-m-Y', strtotime($_POST['endDate'])) ?>
        </div>
    <?php endif; ?>

    <!-- ================= TABEL DATA ================= -->
    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">Nomor Barang</th>
                <th width="22%">Nama Barang</th>
                <th width="15%">Mesin</th>
                <th width="6%">Rak</th>
                <th width="15%">Pengirim</th>
                <th width="8%">Jumlah</th>
                <th width="12%">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($dataKeluar) == 0): ?>
                <tr>
                    <td colspan="8" class="text-center">Data tidak ditemukan</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dataKeluar as $i => $row): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-center"><?= $row['nomorbarang'] ?></td>
                        <td><?= $row['namabarang'] ?></td>
                        <td><?= $row['mesin'] ?></td>
                        <td class="text-center"><?= $row['norak'] ?></td>
                        <td><?= $row['penerima'] ?></td>
                        <td class="text-center"><?= $row['jumlah'] ?></td>
                        <td class="text-center">
                            <?= date('d-m-Y', strtotime($row['tanggal'])) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ================= FOOTER ================= -->
    <div class="footer">
        Dicetak pada: <?= date('d-m-Y H:i') ?>
    </div>

</body>

</html>