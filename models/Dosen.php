<?php
require_once 'User.php';
require_once 'CetakLaporan.php';

class Dosen extends User implements CetakLaporan
{
    private $password;
    private $db;

    public function __construct($db, $nidn, $nama, $password = '')
    {
        parent::__construct($nidn, $nama);
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->db = $db;
    }

    public function getRole()
    {
        return "Dosen";
    }

    public function simpan()
    {
        $query = "INSERT IGNORE INTO dosen (nidn, nama, password) VALUES (:nidn, :nama, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nidn', $this->id);
        $stmt->bindParam(':nama', $this->nama);
        $stmt->bindParam(':password', $this->password);
        return $stmt->execute();
    }

    public function cetakLaporan()
    {
        echo "=== LAPORAN DOSEN ===\n";
        echo "NIDN    : " . $this->id . "\n";
        echo "Nama    : " . $this->nama . "\n";
    }
}
