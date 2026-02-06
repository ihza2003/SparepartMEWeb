<?php
require '../vendor/autoload.php';
require '../database/koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// ambil filter (boleh kosong)
$firstDate = $_POST['firstDate'] ?? null;
$endDate   = $_POST['endDate'] ?? null;

// query data
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

// simpan data ke array
$dataMasuk = [];
while ($row = mysqli_fetch_assoc($query)) {
    $dataMasuk[] = $row;
}

// render template
ob_start();
include 'templateDataMasukPDF.php';
$html = ob_get_clean();

// dompdf setting
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('chroot', realpath(__DIR__ . '/..'));

$pdf = new Dompdf($options);
$pdf->setPaper('A4', 'portrait');
$pdf->loadHtml($html);
$pdf->render();

// output
$pdf->stream(
    'Data-Barang-Masuk.pdf',
    ['Attachment' => false] // true = download, false = preview
);
