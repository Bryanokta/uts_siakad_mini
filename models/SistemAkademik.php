<?php
class SistemAkademik
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getStats()
    {
        $stats = [];
        $stats['total_mhs'] = $this->db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
        $stats['total_mk'] = $this->db->query("SELECT COUNT(*) FROM mata_kuliah")->fetchColumn();
        $queryIPK = "SELECT AVG(nilai_angka) FROM nilai";
        $avgNilai = $this->db->query($queryIPK)->fetchColumn();
        $stats['avg_ipk'] = $avgNilai ? number_format(($avgNilai / 100) * 4, 2) : "0.00";

        $stats['pending_mhs'] = $this->db->query("SELECT COUNT(*) FROM mahasiswa WHERE nim NOT IN (SELECT DISTINCT nim FROM nilai)")->fetchColumn();
        $stats['pending_mk'] = $this->db->query("SELECT COUNT(*) FROM mata_kuliah WHERE kode_mk NOT IN (SELECT DISTINCT kode_mk FROM nilai)")->fetchColumn();

        return $stats;
    }

    public function getMahasiswaPerMatkul($kode_mk)
    {
        $query = "SELECT m.nim, m.nama, m.jurusan, n.nilai_angka 
                  FROM mahasiswa m 
                  LEFT JOIN nilai n ON m.nim = n.nim AND n.kode_mk = :kode 
                  ORDER BY m.nama ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':kode', $kode_mk);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function simpanBanyakNilaiPerMatkul($kode_mk, $array_nilai)
    {
        foreach ($array_nilai as $nim => $nilai) {
            if ($nilai === '' || $nilai === null) continue;
            $check = $this->db->prepare("SELECT id FROM nilai WHERE nim = ? AND kode_mk = ?");
            $check->execute([$nim, $kode_mk]);
            if ($check->rowCount() > 0) {
                $this->db->prepare("UPDATE nilai SET nilai_angka = ? WHERE nim = ? AND kode_mk = ?")->execute([$nilai, $nim, $kode_mk]);
            } else {
                $this->db->prepare("INSERT INTO nilai (nim, kode_mk, nilai_angka) VALUES (?, ?, ?)")->execute([$nim, $kode_mk, $nilai]);
            }
        }
        return true;
    }

    public function hapusMahasiswa($nim)
    {
        $this->db->prepare("DELETE FROM nilai WHERE nim = ?")->execute([$nim]);
        return $this->db->prepare("DELETE FROM mahasiswa WHERE nim = ?")->execute([$nim]);
    }

    public function hapusMataKuliah($kode)
    {
        $this->db->prepare("DELETE FROM nilai WHERE kode_mk = ?")->execute([$kode]);
        return $this->db->prepare("DELETE FROM mata_kuliah WHERE kode_mk = ?")->execute([$kode]);
    }

    public function tambahMataKuliah($kode_mk, $nama_mk, $sks)
    {
        $query = "INSERT IGNORE INTO mata_kuliah (kode_mk, nama_mk, sks) VALUES (:kode, :nama, :sks)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':kode', $kode_mk);
        $stmt->bindParam(':nama', $nama_mk);
        $stmt->bindParam(':sks', $sks);
        return $stmt->execute();
    }

    public function getMatkulBesertaNilai($nim)
    {
        $query = "SELECT m.kode_mk, m.nama_mk, m.sks, n.nilai_angka FROM mata_kuliah m LEFT JOIN nilai n ON m.kode_mk = n.kode_mk AND n.nim = :nim ORDER BY m.nama_mk ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function simpanBanyakNilai($nim, $array_nilai)
    {
        foreach ($array_nilai as $kode_mk => $nilai) {
            if ($nilai === '' || $nilai === null) continue;
            $check = $this->db->prepare("SELECT id FROM nilai WHERE nim = ? AND kode_mk = ?");
            $check->execute([$nim, $kode_mk]);
            if ($check->rowCount() > 0) {
                $this->db->prepare("UPDATE nilai SET nilai_angka = ? WHERE nim = ? AND kode_mk = ?")->execute([$nilai, $nim, $kode_mk]);
            } else {
                $this->db->prepare("INSERT INTO nilai (nim, kode_mk, nilai_angka) VALUES (?, ?, ?)")->execute([$nim, $kode_mk, $nilai]);
            }
        }
        return true;
    }

    public function getSemuaMataKuliah()
    {
        return $this->db->query("SELECT * FROM mata_kuliah ORDER BY nama_mk ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSemuaMahasiswa()
    {
        return $this->db->query("SELECT * FROM mahasiswa ORDER BY nama ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKHS($nim)
    {
        $query = "SELECT m.kode_mk, m.nama_mk, m.sks, n.nilai_angka FROM nilai n JOIN mata_kuliah m ON n.kode_mk = m.kode_mk WHERE n.nim = :nim";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nim', $nim);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $totalBobot = 0;
        $totalSKS = 0;
        foreach ($data as &$row) {
            $row['huruf'] = $this->konversiHuruf($row['nilai_angka']);
            $bobot = 0;
            if ($row['nilai_angka'] >= 85) $bobot = 4;
            elseif ($row['nilai_angka'] >= 70) $bobot = 3;
            elseif ($row['nilai_angka'] >= 55) $bobot = 2;
            elseif ($row['nilai_angka'] >= 40) $bobot = 1;
            $totalBobot += ($bobot * $row['sks']);
            $totalSKS += $row['sks'];
        }
        return ['data' => $data, 'ipk' => ($totalSKS > 0 ? $totalBobot / $totalSKS : 0)];
    }

    public function konversiHuruf($angka)
    {
        if ($angka >= 85) return 'A';
        if ($angka >= 70) return 'B';
        if ($angka >= 55) return 'C';
        if ($angka >= 40) return 'D';
        return 'E';
    }

    public function gantiPassword($id, $role, $passBaru)
    {
        $table = ($role == 'Mahasiswa') ? 'mahasiswa' : 'dosen';
        $field = ($role == 'Mahasiswa') ? 'nim' : 'nidn';
        $hash = password_hash($passBaru, PASSWORD_DEFAULT);
        return $this->db->prepare("UPDATE $table SET password = ? WHERE $field = ?")->execute([$hash, $id]);
    }
}
