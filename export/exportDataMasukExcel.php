<?php
require '../database/koneksi.php';

/* ================= HEADER EXCEL ================= */
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Data-Barang-Masuk.xls");
header("Pragma: no-cache");
header("Expires: 0");

/* ================= AMBIL FILTER ================= */
$firstDate = $_POST['firstDate'] ?? null;
$endDate   = $_POST['endDate'] ?? null;

/* ================= QUERY ================= */
if ($firstDate && $endDate) {
    $query = mysqli_query($konek, "
        SELECT m.tanggal, m.jumlah, s.nomorbarang, s.namabarang, s.mesin, s.norak, u.user AS pengirim
        FROM tb_masuk m
        JOIN tb_stok s ON m.idbarang = s.idbarang
        JOIN admin u ON m.iduser = u.iduser
        WHERE m.tanggal BETWEEN '$firstDate' AND '$endDate'
        ORDER BY m.tanggal DESC
    ");
} else {
    $query = mysqli_query($konek, "
        SELECT m.tanggal, m.jumlah, s.nomorbarang, s.namabarang, s.mesin, s.norak, u.user AS pengirim
        FROM tb_masuk m
        JOIN tb_stok s ON m.idbarang = s.idbarang
        JOIN admin u ON m.iduser = u.iduser
        ORDER BY m.tanggal DESC
    ");
}

/* ================= OUTPUT HTML (DIBACA EXCEL) ================= */
?>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th colspan="8" style="font-size:14px; text-align:center;">
            MAINTENANCE SITE 2<br>
            LAPORAN DATA BARANG MASUK
        </th>
    </tr>

    <?php if ($firstDate && $endDate): ?>
        <tr>
            <td colspan="8" style="text-align:center;">
                Periode: <?= date('d-m-Y', strtotime($firstDate)) ?>
                s/d
                <?= date('d-m-Y', strtotime($endDate)) ?>
            </td>
        </tr>
    <?php endif; ?>

    <tr style="background:#f2f2f2; text-align:center;">
        <th>No</th>
        <th>Nomor Barang</th>
        <th>Nama Barang</th>
        <th>Mesin</th>
        <th>Rak</th>
        <th>Pengirim</th>
        <th>Jumlah</th>
        <th>Tanggal</th>
    </tr>

    <?php
    $no = 1;
    if (mysqli_num_rows($query) == 0):
    ?>
        <tr>
            <td colspan="8" style="text-align:center;">
                Tidak Ada Aktivitas Barang Masuk
            </td>
        </tr>
    <?php else: ?>
        <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td align="center"><?= $no++ ?></td>
                <td align="center"><?= $row['nomorbarang'] ?></td>
                <td><?= $row['namabarang'] ?></td>
                <td><?= $row['mesin'] ?></td>
                <td align="center"><?= $row['norak'] ?></td>
                <td><?= $row['pengirim'] ?></td>
                <td align="center"><?= $row['jumlah'] ?></td>
                <td align="center"><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
            </tr>
        <?php endwhile; ?>
    <?php endif; ?>
</table>

<br>
<p>Dicetak pada: <?= date('d-m-Y H:i') ?></p>