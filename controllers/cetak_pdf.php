<?php
require_once '../models/config.php';
require_once '../models/SistemAkademik.php';
require_once '../models/FormatLaporan.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dosen') {
    header("Location: ../views/login.php");
    exit;
}

if (!isset($_GET['nim'])) {
    echo "NIM tidak valid.";
    exit;
}

$nim = $_GET['nim'];
$database = new Database();
$db = $database->getConnection();
$siakad = new SistemAkademik($db);

$dataKHS = $siakad->getKHS($nim);
$list_mhs = $siakad->getSemuaMahasiswa();

$nama_mhs = "Data Tidak Ditemukan";
foreach ($list_mhs as $m) {
    if ($m['nim'] == $nim) {
        $nama_mhs = $m['nama'];
        break;
    }
}

$pencetak = new PencetakLaporan();
echo $pencetak->cetak(new LaporanPDF(), $nim, $nama_mhs, $dataKHS);
