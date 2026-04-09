<?php
interface FormatLaporan
{
    public function generate($nim, $nama, $dataKHS);
}

class LaporanModal implements FormatLaporan
{
    public function generate($nim, $nama, $dataKHS)
    {
        $html = "<div class='text-center mb-4'>";
        $html .= "<h5 class='fw-bold text-dark mb-1'>KARTU HASIL STUDI (KHS)</h5>";
        $html .= "<p class='text-muted small mb-0'>NIM: {$nim} | Nama: {$nama}</p>";
        $html .= "</div>";
        $html .= "<div class='table-responsive'><table class='table table-bordered table-striped'>";
        $html .= "<thead><tr class='bg-light'><th>Kode</th><th>Mata Kuliah</th><th class='text-center'>SKS</th><th class='text-center'>Nilai</th><th class='text-center'>Grade</th></tr></thead><tbody>";

        if (empty($dataKHS['data'])) {
            $html .= "<tr><td colspan='5' class='text-center'>Tidak ada data.</td></tr>";
        } else {
            foreach ($dataKHS['data'] as $row) {
                $html .= "<tr><td>{$row['kode_mk']}</td><td>{$row['nama_mk']}</td><td class='text-center'>{$row['sks']}</td><td class='text-center'>{$row['nilai_angka']}</td><td class='text-center fw-bold text-primary'>{$row['huruf']}</td></tr>";
            }
        }

        $html .= "</tbody></table></div>";
        $html .= "<div class='d-flex justify-content-end mt-3'><div class='px-4 py-2 rounded-3 fw-bold' style='background: #e4eeff; color: #4318ff;'>IPK Total: " . number_format($dataKHS['ipk'], 2) . "</div></div>";
        return $html;
    }
}

class LaporanPDF implements FormatLaporan
{
    public function generate($nim, $nama, $dataKHS)
    {
        $html = "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'><title>Cetak KHS - {$nim}</title>";
        $html .= "<style>";
        $html .= "body { font-family: 'Times New Roman', Times, serif; color: #000; line-height: 1.5; margin: 40px; }";
        $html .= ".header { text-align: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 30px; }";
        $html .= ".header h2, .header h3 { margin: 0; padding: 2px; }";
        $html .= "table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }";
        $html .= "th, td { border: 1px solid #000; padding: 10px; text-align: left; }";
        $html .= "th { background-color: #f2f2f2; text-align: center; }";
        $html .= ".text-center { text-align: center; }";
        $html .= ".ttd-box { width: 250px; float: right; margin-top: 50px; text-align: center; }";
        $html .= "</style></head><body onload='window.print()'>";

        $html .= "<div class='header'><h2>SISTEM AKADEMIK MINI (SIAKAD)</h2><h3>KARTU HASIL STUDI MAHASISWA</h3></div>";
        $html .= "<table style='width: 50%; border: none; margin-bottom: 20px; margin-top:0;'>";
        $html .= "<tr><td style='border: none; padding: 3px; font-weight: bold; width: 100px;'>NIM</td><td style='border: none; padding: 3px;'>: {$nim}</td></tr>";
        $html .= "<tr><td style='border: none; padding: 3px; font-weight: bold;'>Nama</td><td style='border: none; padding: 3px;'>: {$nama}</td></tr>";
        $html .= "</table>";

        $html .= "<table><thead><tr><th>Kode MK</th><th>Mata Kuliah</th><th>SKS</th><th>Nilai Angka</th><th>Grade</th></tr></thead><tbody>";
        foreach ($dataKHS['data'] as $row) {
            $html .= "<tr><td class='text-center'>{$row['kode_mk']}</td><td>{$row['nama_mk']}</td><td class='text-center'>{$row['sks']}</td><td class='text-center'>{$row['nilai_angka']}</td><td class='text-center'><strong>{$row['huruf']}</strong></td></tr>";
        }
        $html .= "</tbody></table>";

        $html .= "<h3 style='text-align: right; margin-top: 20px;'>Indeks Prestasi Kumulatif (IPK): " . number_format($dataKHS['ipk'], 2) . "</h3>";

        $tanggal = date('d F Y');
        $html .= "<div class='ttd-box'><p>Diterbitkan pada,<br>{$tanggal}</p><br><br><br><p><strong><u>Bagian Akademik</u></strong></p></div>";
        $html .= "</body></html>";

        return $html;
    }
}

class PencetakLaporan
{
    public function cetak(FormatLaporan $format, $nim, $nama, $dataKHS)
    {
        return $format->generate($nim, $nama, $dataKHS);
    }
}
