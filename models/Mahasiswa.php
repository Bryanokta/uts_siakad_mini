<?php
require_once 'User.php';
require_once 'CetakLaporan.php';

class Mahasiswa extends User implements CetakLaporan
{
    private $jurusan;
    private $password;
    private $db;

    public function __construct($db, $nim, $nama, $jurusan, $password = '')
    {
        parent::__construct($nim, $nama);
        $this->jurusan = $jurusan;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->db = $db;
    }

    public function getRole()
    {
        return "Mahasiswa";
    }

    public function simpan()
    {
        $query = "INSERT IGNORE INTO mahasiswa (nim, nama, jurusan, password) VALUES (:nim, :nama, :jurusan, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nim', $this->id);
        $stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':jurusan', $this->jurusan);
        $stmt->bindParam(':password', $this->password);
        return $stmt->execute();
    }

    public function cetakLaporan()
    {
        echo "=== LAPORAN MAHASISWA ===\n";
        echo "NIM     : " . $this->id . "\n";
        echo "Nama    : " . $this->nama . "\n";
        echo "Jurusan : " . $this->jurusan . "\n";
    }
}
