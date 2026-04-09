<?php
require_once '../models/config.php';
require_once '../models/SistemAkademik.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Mahasiswa') {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$siakad = new SistemAkademik($db);

$khs = $siakad->getKHS($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
        }

        .sidebar-floating {
            width: 280px;
            margin: 20px 0 20px 20px;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(112, 144, 176, 0.12);
            padding: 24px 0;
            position: fixed;
            height: calc(100vh - 40px);
            overflow-y: auto;
        }

        .main-content {
            margin-left: 300px;
            padding: 20px 20px 20px 10px;
            min-height: 100vh;
        }

        .nav-floating {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(112, 144, 176, 0.08);
            padding: 15px 24px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-soft {
            background: #ffffff;
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 40px rgba(112, 144, 176, 0.08);
            padding: 24px;
            overflow: hidden;
        }

        .menu-item {
            margin: 8px 16px;
            padding: 14px 20px;
            border-radius: 16px;
            color: #a3aed1;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .menu-item i {
            font-size: 1.25rem;
            margin-right: 14px;
        }

        .menu-item:hover,
        .menu-item.active {
            background: #4318ff;
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2);
        }

        .table-soft th {
            border-bottom: 2px solid #f4f7fe;
            color: #a3aed1;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 16px;
        }

        .table-soft td {
            border-bottom: 1px solid #f4f7fe;
            padding: 16px;
            color: #2b3674;
            font-weight: 600;
            vertical-align: middle;
        }

        .badge-soft {
            width: 40px;
            height: 40px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 12px;
            font-weight: 800;
            font-size: 1.1rem;
        }

        .bg-a {
            background: #e2fbed;
            color: #05cd99;
        }

        .bg-b {
            background: #e4eeff;
            color: #4318ff;
        }

        .bg-c {
            background: #fff0d4;
            color: #ffb547;
        }

        .bg-d {
            background: #ffe2e5;
            color: #ee5d50;
        }

        @media (max-width: 991px) {
            .sidebar-floating {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <div class="sidebar-floating d-none d-lg-block">
            <div class="px-4 mb-4 text-center">
                <div class="bg-light rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 80px; height: 80px; background: linear-gradient(135deg, #868CFF 0%, #4318FF 100%); color: white;">
                    <i class="bi bi-mortarboard-fill fs-1"></i>
                </div>
                <h5 class="fw-bold mb-0">SIAKAD</h5>
                <p class="text-muted small fw-bold">Student Portal</p>
            </div>
            <div class="mt-4">
                <a href="#" class="menu-item active"><i class="bi bi-grid-fill"></i> Ringkasan Studi</a>
                <a href="../controllers/logout.php" class="menu-item text-danger mt-5"><i class="bi bi-box-arrow-right"></i> Keluar</a>
            </div>
        </div>

        <div class="main-content flex-grow-1">
            <div class="nav-floating">
                <div>
                    <h5 class="fw-bold mb-0">Selamat Datang, <?= $_SESSION['nama'] ?>! 👋</h5>
                    <span class="text-muted small fw-bold">NIM: <?= $_SESSION['user_id'] ?></span>
                </div>
                <div class="d-lg-none">
                    <a href="../controllers/logout.php" class="btn btn-danger rounded-pill px-4 fw-bold">Logout</a>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card-soft h-100 d-flex flex-column justify-content-center align-items-center text-center">
                        <div class="mb-3 p-3 rounded-circle" style="background: #e4eeff; color: #4318ff;">
                            <i class="bi bi-trophy-fill display-5"></i>
                        </div>
                        <h6 class="fw-bold text-muted mb-1">Indeks Prestasi Kumulatif</h6>
                        <h1 class="display-3 fw-black mb-0" style="color: #4318ff; font-weight: 800;"><?= number_format($khs['ipk'], 2) ?></h1>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card-soft h-100">
                        <h5 class="fw-bold mb-4">Kartu Hasil Studi (KHS)</h5>
                        <?php if (empty($khs['data'])): ?>
                            <div class="text-center py-5">
                                <h5 class="text-muted fw-bold">Belum Ada Data Nilai</h5>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-soft mb-0">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Mata Kuliah</th>
                                            <th class="text-center">SKS</th>
                                            <th class="text-center">Angka</th>
                                            <th class="text-center">Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($khs['data'] as $row): ?>
                                            <tr>
                                                <td class="text-muted"><?= $row['kode_mk'] ?></td>
                                                <td><?= $row['nama_mk'] ?></td>
                                                <td class="text-center"><?= $row['sks'] ?></td>
                                                <td class="text-center"><?= $row['nilai_angka'] ?></td>
                                                <td class="text-center">
                                                    <?php
                                                    $bg = 'bg-d';
                                                    if ($row['huruf'] == 'A') $bg = 'bg-a';
                                                    elseif ($row['huruf'] == 'B') $bg = 'bg-b';
                                                    elseif ($row['huruf'] == 'C') $bg = 'bg-c';
                                                    ?>
                                                    <span class="badge-soft <?= $bg ?>"><?= $row['huruf'] ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>