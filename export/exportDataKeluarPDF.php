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
        SELECT m.tanggal, m.jumlah, s.nomorbarang, s.namabarang, s.mesin, s.norak, m.penerima
        FROM tb_keluar m
        JOIN tb_stok s ON m.idbarang = s.idbarang
        WHERE m.tanggal BETWEEN '$firstDate' AND '$endDate'
        ORDER BY m.tanggal DESC
    ");
} else {
    $query = mysqli_query($konek, "
        SELECT m.tanggal, m.jumlah, s.nomorbarang, s.namabarang, s.mesin, s.norak, m.penerima
        FROM tb_keluar m
        JOIN tb_stok s ON m.idbarang = s.idbarang
        ORDER BY m.tanggal DESC
    ");
}

// simpan data ke array
$dataKeluar = [];
while ($row = mysqli_fetch_assoc($query)) {
    $dataKeluar[] = $row;
}

// render template
ob_start();
include 'templateDataKeluarPDF.php';
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
    'Data-Barang-Keluar.pdf',
    ['Attachment' => false] // true = download, false = preview
);
