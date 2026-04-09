<?php
session_start();
require_once '../models/config.php';
require_once '../models/SistemAkademik.php';
require_once '../models/FormatLaporan.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Dosen') {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$siakad = new SistemAkademik($db);
$pencetak = new PencetakLaporan();

$active_tab = "beranda";
$input_mode = isset($_POST['input_mode']) ? $_POST['input_mode'] : "mhs";
$nim_input = "";
$mk_input = "";
$nim_cari = "";
$dataKHS = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btn_mk'])) {
        $siakad->tambahMataKuliah($_POST['kode_mk'], $_POST['nama_mk'], $_POST['sks']);
        $_SESSION['swal'] = ['icon' => 'success', 'title' => 'Selesai!', 'text' => 'Mata Kuliah Berhasil Ditambahkan'];
        $active_tab = "master";
    } elseif (isset($_POST['btn_pilih_mhs'])) {
        $nim_input = $_POST['nim_pilihan'];
        $input_mode = "mhs";
        $active_tab = "input_nilai_group";
    } elseif (isset($_POST['btn_pilih_mk'])) {
        $mk_input = $_POST['mk_pilihan'];
        $input_mode = "mk";
        $active_tab = "input_nilai_group";
    } elseif (isset($_POST['btn_simpan_mhs'])) {
        $siakad->simpanBanyakNilai($_POST['nim_target'], $_POST['nilai']);
        $_SESSION['swal'] = ['icon' => 'success', 'title' => 'Berhasil!', 'text' => 'Nilai Mahasiswa Diperbarui'];
        $nim_input = $_POST['nim_target'];
        $input_mode = "mhs";
        $active_tab = "input_nilai_group";
    } elseif (isset($_POST['btn_simpan_mk'])) {
        $siakad->simpanBanyakNilaiPerMatkul($_POST['mk_target'], $_POST['nilai']);
        $_SESSION['swal'] = ['icon' => 'success', 'title' => 'Berhasil!', 'text' => 'Nilai Satu Kelas Berhasil Disimpan'];
        $mk_input = $_POST['mk_target'];
        $input_mode = "mk";
        $active_tab = "input_nilai_group";
    } elseif (isset($_POST['btn_cetak'])) {
        $nim_cari = $_POST['nim_cetak'];
        $dataKHS = $siakad->getKHS($nim_cari);
        $active_tab = "laporan";
    } elseif (isset($_POST['btn_hapus_mhs'])) {
        $siakad->hapusMahasiswa($_POST['target_id']);
        $_SESSION['swal'] = ['icon' => 'warning', 'title' => 'Dihapus', 'text' => 'Mahasiswa telah dihapus'];
        $active_tab = "master";
    } elseif (isset($_POST['btn_hapus_mk'])) {
        $siakad->hapusMataKuliah($_POST['target_id']);
        $_SESSION['swal'] = ['icon' => 'warning', 'title' => 'Dihapus', 'text' => 'Mata kuliah telah dihapus'];
        $active_tab = "master";
    } elseif (isset($_POST['btn_ganti_pass'])) {
        $siakad->gantiPassword($_SESSION['user_id'], 'Dosen', $_POST['pass_baru']);
        $_SESSION['swal'] = ['icon' => 'success', 'title' => 'Berhasil', 'text' => 'Password diperbarui'];
    }
}

$stats = $siakad->getStats();
$list_mhs = $siakad->getSemuaMahasiswa();
$list_mk = $siakad->getSemuaMataKuliah();

$nama_input = "";
if ($nim_input != '') {
    $list_input_nilai = $siakad->getMatkulBesertaNilai($nim_input);
    foreach ($list_mhs as $m) {
        if ($m['nim'] == $nim_input) {
            $nama_input = $m['nama'];
            break;
        }
    }
}

if ($mk_input != '') {
    $data_kelas = $siakad->getMahasiswaPerMatkul($mk_input);
}

if ($nim_cari != '') {
    foreach ($list_mhs as $m) {
        if ($m['nim'] == $nim_cari) {
            $nama_mhs_cari = $m['nama'];
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD POLIJE - Panel Dosen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4318ff;
            --dark-navy: #111c44;
            --light-bg: #f4f7fe;
            --secondary-text: #a3aed1;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--light-bg);
            color: var(--dark-navy);
        }

        /* Sidebar Design */
        .sidebar {
            width: 280px;
            background: var(--dark-navy);
            position: fixed;
            height: 100vh;
            z-index: 1100;
            transition: 0.3s;
            padding: 30px 20px;
        }

        .sidebar .brand {
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 50px;
            padding-left: 15px;
        }

        .sidebar .nav-link {
            color: #8f9bba;
            border-radius: 12px;
            padding: 12px 18px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            transition: 0.2s;
            border: none;
        }

        .sidebar .nav-link i {
            font-size: 1.2rem;
            margin-right: 12px;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .sidebar .nav-link.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 10px 20px rgba(67, 24, 255, 0.3);
        }

        /* Main Layout */
        .main-content {
            margin-left: 280px;
            padding: 40px;
            min-height: 100vh;
            transition: 0.3s;
        }

        /* HEADER SECTION */
        .header-top {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 24px;
            padding: 16px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(112, 144, 176, 0.08);
        }

        .profile-pill {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-left: 20px;
            border-left: 1px solid #e9edf7;
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary) 0%, #868cff 100%);
            color: white;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(67, 24, 255, 0.2);
        }

        /* Cards */
        .card-custom {
            background: white;
            border-radius: 20px;
            border: none;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.02);
            padding: 30px;
            margin-bottom: 30px;
        }

        .stats-box {
            border-radius: 20px;
            padding: 25px;
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stats-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        /* Table & Inputs */
        .table-aurora thead th {
            background: #f8f9fa;
            border: none;
            color: var(--secondary-text);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px;
        }

        .table-aurora tbody td {
            padding: 18px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f4f7fe;
            font-weight: 600;
        }

        .input-nilai-aurora {
            max-width: 90px;
            border: 2px solid #e9edf7;
            border-radius: 10px;
            padding: 6px;
            text-align: center;
            font-weight: 800;
            color: var(--primary);
            transition: 0.2s;
        }

        .input-nilai-aurora:focus {
            outline: none;
            border-color: var(--primary);
            background: #f4f1ff;
        }

        /* Mode Switcher */
        .mode-switcher {
            background: #f4f7fe;
            padding: 5px;
            border-radius: 14px;
            display: inline-flex;
            margin-bottom: 20px;
            border: 1px solid #e9edf7;
        }

        .mode-btn {
            border: none;
            padding: 8px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            transition: 0.3s;
            color: var(--secondary-text);
            background: transparent;
        }

        .mode-btn.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* Responsive */
        .hamburger {
            display: none;
            cursor: pointer;
        }

        @media (max-width: 991px) {
            .sidebar {
                left: -300px;
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .hamburger {
                display: block;
            }
        }

        /* Buttons */
        .btn-aurora {
            border-radius: 14px;
            padding: 12px 25px;
            font-weight: 700;
            transition: 0.3s;
            border: none;
        }

        .btn-aurora-primary {
            background: var(--primary);
            color: white;
        }

        .btn-aurora-primary:hover {
            background: #3311cc;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(67, 24, 255, 0.2);
        }
    </style>
</head>

<body>

    <div class="sidebar" id="sidebar">
        <div class="brand">
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_Politeknik_Negeri_Jember.png" width="40">
            <span class="fw-bold fs-5">SIAKAD</span>
        </div>

        <nav class="nav flex-column" role="tablist">
            <button class="nav-link <?= $active_tab == 'beranda' ? 'active' : '' ?>" data-bs-toggle="list" href="#beranda"><i class="bi bi-grid-1x2-fill"></i> Beranda</button>
            <button class="nav-link <?= $active_tab == 'master' ? 'active' : '' ?>" data-bs-toggle="list" href="#master"><i class="bi bi-stack"></i> Data Master</button>
            <button class="nav-link <?= $active_tab == 'input_nilai_group' ? 'active' : '' ?>" data-bs-toggle="list" href="#input_nilai_group"><i class="bi bi-pencil-square"></i> Kelola Nilai</button>
            <button class="nav-link <?= $active_tab == 'laporan' ? 'active' : '' ?>" data-bs-toggle="list" href="#laporan"><i class="bi bi-file-earmark-bar-graph"></i> Laporan KHS</button>
            <a href="documentation.php" target="_blank" class="nav-link text-decoration-none"><i class="bi bi-journal-bookmark-fill"></i> Dokumentasi</a>
        </nav>

        <div style="position: absolute; bottom: 30px; width: calc(100% - 40px);">
            <button class="btn btn-link text-decoration-none w-100 text-start mb-3" style="color: #8f9bba;" data-bs-toggle="modal" data-bs-target="#modalPass"><i class="bi bi-lock-fill me-2"></i> Ganti Sandi</button>
            <a href="../controllers/logout.php" class="btn btn-danger w-100 btn-aurora" style="background: #ee5d50;"><i class="bi bi-power me-2"></i> Keluar</a>
        </div>
    </div>

    <div class="main-content">
        <div class="header-top">
            <div class="d-flex align-items-center">
                <div class="hamburger d-lg-none me-3" onclick="toggleSidebar()"><i class="bi bi-list fs-1"></i></div>
                <div>
                    <span class="text-uppercase fw-bold text-muted" style="font-size: 0.65rem; letter-spacing: 1.2px;">Panel Akademik Dosen</span>
                    <h3 class="fw-bold mb-0" style="color: var(--dark-navy);">Halo, <?= explode(' ', $_SESSION['nama'])[0] ?>! 👋</h3>
                </div>
            </div>

            <div class="d-flex align-items-center gap-4">
                <div class="text-end d-none d-md-block">
                    <p class="mb-0 fw-bold small text-muted"><?= date('l, d F Y') ?></p>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-bold" style="font-size: 0.6rem; padding: 4px 12px;">SIAKAD POLIJE v2.0</span>
                </div>

                <div class="profile-pill">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark" style="font-size: 0.85rem; line-height: 1.2;"><?= $_SESSION['nama'] ?></div>
                        <small class="text-primary fw-bold" style="font-size: 0.7rem;">Dosen Pengajar</small>
                    </div>
                    <div class="profile-avatar shadow-sm">
                        <?= strtoupper(substr($_SESSION['nama'], 0, 2)) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade <?= $active_tab == 'beranda' ? 'show active' : '' ?>" id="beranda">
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <div class="stats-box">
                            <div class="stats-icon" style="background: #e7e7ff; color: #4318ff;"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <p class="text-muted small fw-bold mb-0">TOTAL MAHASISWA</p>
                                <h3 class="fw-bold mb-0"><?= $stats['total_mhs'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-box">
                            <div class="stats-icon" style="background: #e2fbed; color: #05cd99;"><i class="bi bi-journal-text"></i></div>
                            <div>
                                <p class="text-muted small fw-bold mb-0">TOTAL MATKUL</p>
                                <h3 class="fw-bold mb-0"><?= $stats['total_mk'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stats-box">
                            <div class="stats-icon" style="background: #fff0d4; color: #ffb547;"><i class="bi bi-star-fill"></i></div>
                            <div>
                                <p class="text-muted small fw-bold mb-0">RATA-RATA IPK</p>
                                <h3 class="fw-bold mb-0"><?= $stats['avg_ipk'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-custom">
                    <div class="row align-items-center">
                        <div class="col-md-7">
                            <h3 class="fw-bold mb-3">Pusat Kendali Akademik</h3>
                            <p class="text-muted lead">Sistem mendeteksi ada <strong><?= $stats['pending_mk'] ?></strong> mata kuliah yang belum dinilai. Selesaikan hari ini untuk mempercepat proses KHS mahasiswa.</p>
                            <button class="btn btn-aurora btn-aurora-primary mt-3" onclick="document.querySelector('[href=\'#input_nilai_group\']').click()">Mulai Input Nilai</button>
                        </div>
                        <div class="col-md-5 d-none d-md-block text-end">
                            <img src="https://img.freepik.com/free-vector/data-extraction-concept-illustration_114360-4766.jpg" width="250">
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade <?= $active_tab == 'master' ? 'show active' : '' ?>" id="master">
                <div class="card-custom">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Mata Kuliah & Mahasiswa</h4>
                        <button class="btn btn-aurora btn-aurora-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalMK"><i class="bi bi-plus-lg me-2"></i> Tambah Matkul</button>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-aurora">
                                    <thead>
                                        <tr>
                                            <th>MATA KULIAH</th>
                                            <th>SKS</th>
                                            <th class="text-end">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list_mk as $mk): ?>
                                            <tr>
                                                <td><?= $mk['nama_mk'] ?><br><small class="text-muted"><?= $mk['kode_mk'] ?></small></td>
                                                <td><?= $mk['sks'] ?></td>
                                                <td class="text-end">
                                                    <form method="POST"><input type="hidden" name="target_id" value="<?= $mk['kode_mk'] ?>"><button type="submit" name="btn_hapus_mk" class="btn btn-sm btn-light text-danger rounded-pill" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></button></form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-aurora">
                                    <thead>
                                        <tr>
                                            <th>MAHASISWA</th>
                                            <th class="text-end">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($list_mhs as $m): ?>
                                            <tr>
                                                <td><?= $m['nama'] ?><br><small class="text-muted"><?= $m['nim'] ?></small></td>
                                                <td class="text-end">
                                                    <form method="POST"><input type="hidden" name="target_id" value="<?= $m['nim'] ?>"><button type="submit" name="btn_hapus_mhs" class="btn btn-sm btn-light text-danger rounded-pill" onclick="return confirm('Hapus?')"><i class="bi bi-trash"></i></button></form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade <?= $active_tab == 'input_nilai_group' ? 'show active' : '' ?>" id="input_nilai_group">
                <div class="card-custom">
                    <h5 class="fw-bold mb-4">Input & Pembaruan Nilai</h5>
                    <form method="POST" id="main-input-form">
                        <div class="mode-switcher">
                            <button type="button" class="mode-btn <?= $input_mode == 'mhs' ? 'active' : '' ?>" onclick="switchMode('mhs')">Per Mahasiswa</button>
                            <button type="button" class="mode-btn <?= $input_mode == 'mk' ? 'active' : '' ?>" onclick="switchMode('mk')">Per Mata Kuliah</button>
                        </div>
                        <input type="hidden" name="input_mode" id="input_mode_val" value="<?= $input_mode ?>">

                        <div class="row g-3">
                            <div class="col-md-9" id="container-mhs" style="<?= $input_mode == 'mhs' ? '' : 'display:none;' ?>">
                                <select name="nim_pilihan" class="searchable-select">
                                    <option value="">Cari Mahasiswa...</option>
                                    <?php foreach ($list_mhs as $m): ?><option value="<?= $m['nim'] ?>" <?= ($nim_input == $m['nim'] ? 'selected' : '') ?>><?= $m['nama'] ?> (<?= $m['nim'] ?>)</option><?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-9" id="container-mk" style="<?= $input_mode == 'mk' ? '' : 'display:none;' ?>">
                                <select name="mk_pilihan" class="searchable-select">
                                    <option value="">Cari Mata Kuliah...</option>
                                    <?php foreach ($list_mk as $mk): ?><option value="<?= $mk['kode_mk'] ?>" <?= ($mk_input == $mk['kode_mk'] ? 'selected' : '') ?>><?= $mk['nama_mk'] ?> (<?= $mk['kode_mk'] ?>)</option><?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" name="<?= $input_mode == 'mhs' ? 'btn_pilih_mhs' : 'btn_pilih_mk' ?>" id="submit-pilih" class="btn btn-aurora btn-aurora-primary w-100">Buka Lembar</button>
                            </div>
                        </div>
                    </form>

                    <?php if ($nim_input): $data_nilai = $siakad->getMatkulBesertaNilai($nim_input); ?>
                        <hr class="my-5 opacity-25">
                        <h5 class="fw-bold mb-4">Lembar Nilai: <span class="text-primary"><?= $nama_input ?></span></h5>
                        <form method="POST">
                            <input type="hidden" name="nim_target" value="<?= $nim_input ?>">
                            <table class="table table-aurora">
                                <thead>
                                    <tr>
                                        <th>MATA KULIAH</th>
                                        <th class="text-center">SKS</th>
                                        <th class="text-end">NILAI ANGKA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_nilai as $row): ?>
                                        <tr>
                                            <td><?= $row['nama_mk'] ?></td>
                                            <td class="text-center"><?= $row['sks'] ?></td>
                                            <td class="text-end"><input type="number" step="0.01" name="nilai[<?= $row['kode_mk'] ?>]" class="input-nilai-aurora" value="<?= $row['nilai_angka'] ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="text-end mt-4"><button type="submit" name="btn_simpan_mhs" class="btn btn-aurora" style="background:#05cd99; color:white;">Simpan Perubahan</button></div>
                        </form>
                    <?php endif; ?>

                    <?php if ($mk_input): ?>
                        <hr class="my-5 opacity-25">
                        <h5 class="fw-bold mb-4">Input Nilai Kelas: <span class="text-primary"><?= $mk_input ?></span></h5>
                        <form method="POST">
                            <input type="hidden" name="mk_target" value="<?= $mk_input ?>">
                            <table class="table table-aurora">
                                <thead>
                                    <tr>
                                        <th>NAMA MAHASISWA</th>
                                        <th>JURUSAN</th>
                                        <th class="text-end">NILAI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data_kelas as $row): ?>
                                        <tr>
                                            <td><?= $row['nama'] ?><br><small class="text-muted"><?= $row['nim'] ?></small></td>
                                            <td><?= $row['jurusan'] ?></td>
                                            <td class="text-end"><input type="number" step="0.01" name="nilai[<?= $row['nim'] ?>]" class="input-nilai-aurora" value="<?= $row['nilai_angka'] ?>"></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="text-end mt-4"><button type="submit" name="btn_simpan_mk" class="btn btn-aurora" style="background:#05cd99; color:white;">Simpan Nilai Satu Kelas</button></div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tab-pane fade <?= $active_tab == 'laporan' ? 'show active' : '' ?>" id="laporan">
                <div class="card-custom">
                    <h4 class="fw-bold mb-4">Cetak Laporan KHS</h4>
                    <form method="POST" class="d-flex gap-3 mb-5">
                        <select name="nim_cetak" class="searchable-select flex-grow-1">
                            <option value="">Pilih Mahasiswa...</option>
                            <?php foreach ($list_mhs as $m): ?><option value="<?= $m['nim'] ?>" <?= ($nim_cari == $m['nim'] ? 'selected' : '') ?>><?= $m['nama'] ?></option><?php endforeach; ?>
                        </select>
                        <button type="submit" name="btn_cetak" class="btn btn-aurora btn-aurora-primary px-5">Lihat</button>
                    </form>

                    <?php if ($dataKHS): ?>
                        <div class="p-4 rounded-4" style="background:#f8f9fa; border: 1px solid #e9edf7;">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Hasil Evaluasi: <?= $nama_mhs_cari ?></h5>
                                <button class="btn btn-aurora btn-sm" style="background:var(--dark-navy); color:white;" data-bs-toggle="modal" data-bs-target="#previewModal"><i class="bi bi-printer me-2"></i> Cetak PDF</button>
                            </div>
                            <table class="table table-aurora">
                                <thead>
                                    <tr>
                                        <th>MATKUL</th>
                                        <th class="text-center">SKS</th>
                                        <th class="text-center">NILAI</th>
                                        <th class="text-center">GRADE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dataKHS['data'] as $row): ?>
                                        <tr>
                                            <td><?= $row['nama_mk'] ?></td>
                                            <td class="text-center"><?= $row['sks'] ?></td>
                                            <td class="text-center text-primary"><?= $row['nilai_angka'] ?></td>
                                            <td class="text-center fw-bold"><?= $row['huruf'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="text-end mt-3">
                                <h4 class="fw-bold" style="color:var(--primary)">IPK: <?= number_format($dataKHS['ipk'], 2) ?></h4>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalMK" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 border-0" style="border-radius:24px;">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Mata Kuliah Baru</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body"><label class="small fw-bold mb-1">KODE MK</label><input type="text" name="kode_mk" class="form-control mb-3" required><label class="small fw-bold mb-1">NAMA MK</label><input type="text" name="nama_mk" class="form-control mb-3" required><label class="small fw-bold mb-1">SKS</label><input type="number" name="sks" class="form-control" required></div>
                    <div class="modal-footer border-0"><button type="submit" name="btn_mk" class="btn btn-aurora btn-aurora-primary w-100">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalPass" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4 border-0" style="border-radius:24px;">
                <div class="modal-header border-0">
                    <h5 class="fw-bold">Ganti Sandi</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body"><label class="small fw-bold mb-1">SANDI BARU</label><input type="password" name="pass_baru" class="form-control" required></div>
                    <div class="modal-footer border-0"><button type="submit" name="btn_ganti_pass" class="btn btn-aurora btn-aurora-primary w-100">Simpan</button></div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($dataKHS): ?>
        <div class="modal fade" id="previewModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content p-2 border-0" style="border-radius:24px;">
                    <div class="modal-header border-0 p-4">
                        <h5 class="fw-bold mb-0">KHS Mahasiswa</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body px-4 pb-4">
                        <div class="p-4 rounded-4 shadow-sm" style="background:white; border: 1px dashed #d1d1d1;"><?= $pencetak->cetak(new LaporanModal(), $nim_cari, $nama_mhs_cari, $dataKHS) ?></div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0"><a href="../controllers/cetak_pdf.php?nim=<?= $nim_cari ?>" target="_blank" class="btn btn-aurora btn-aurora-primary w-100">Unduh Dokumen PDF</a></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        function switchMode(mode) {
            document.getElementById('input_mode_val').value = mode;
            document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
            event.target.classList.add('active');
            if (mode === 'mhs') {
                document.getElementById('container-mhs').style.display = 'block';
                document.getElementById('container-mk').style.display = 'none';
                document.getElementById('submit-pilih').name = 'btn_pilih_mhs';
            } else {
                document.getElementById('container-mhs').style.display = 'none';
                document.getElementById('container-mk').style.display = 'block';
                document.getElementById('submit-pilih').name = 'btn_pilih_mk';
            }
        }
        document.querySelectorAll('.searchable-select').forEach((el) => {
            new TomSelect(el, {
                create: false
            });
        });
        <?php if (isset($_SESSION['swal'])): ?>
            Swal.fire({
                icon: '<?= $_SESSION['swal']['icon'] ?>',
                title: '<?= $_SESSION['swal']['title'] ?>',
                text: '<?= $_SESSION['swal']['text'] ?>',
                timer: 2000,
                showConfirmButton: false,
                borderRadius: '20px'
            });
        <?php unset($_SESSION['swal']);
        endif; ?>
    </script>
</body>

</html>