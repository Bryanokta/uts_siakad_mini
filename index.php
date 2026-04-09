<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: views/login.php");
    exit;
}

if ($_SESSION['role'] == 'Mahasiswa') {
    header("Location: views/dashboard_mahasiswa.php");
} else {
    header("Location: views/dashboard_dosen.php");
}
exit;
