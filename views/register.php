<?php
require_once '../models/config.php';
require_once '../models/Mahasiswa.php';
require_once '../models/Dosen.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['btn_register_mhs'])) {
        $mhs = new Mahasiswa($db, $_POST['nim'], $_POST['nama'], $_POST['jurusan'], $_POST['password']);
        if ($mhs->simpan()) {
            header("Location: login.php?pesan=sukses_register");
            exit;
        }
    } elseif (isset($_POST['btn_register_dsn'])) {
        $dsn = new Dosen($db, $_POST['nidn'], $_POST['nama'], $_POST['password']);
        if ($dsn->simpan()) {
            header("Location: login.php?pesan=sukses_register");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD - Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
            padding: 40px 0;
        }

        .card-soft {
            background: #ffffff;
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 50px rgba(112, 144, 176, 0.12);
            padding: 40px;
        }

        .nav-pills .nav-link {
            border-radius: 16px;
            font-weight: 700;
            padding: 12px 20px;
            color: #a3aed1;
            margin: 0 5px;
            transition: 0.3s;
        }

        .nav-pills .nav-link.active {
            background: #4318ff;
            color: #ffffff;
            box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2);
        }

        .nav-pills .nav-link:hover:not(.active) {
            background: #f4f7fe;
            color: #4318ff;
        }

        .form-control {
            border-radius: 16px;
            padding: 14px 20px;
            border: 2px solid #e9edf7;
            background: #fdfdfe;
            font-weight: 600;
            color: #2b3674;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: #4318ff;
            box-shadow: 0 0 0 4px rgba(67, 24, 255, 0.1);
            outline: none;
        }

        .form-label {
            font-weight: 700;
            color: #8f9bba;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .btn-soft-primary {
            background: #4318ff;
            color: white;
            border-radius: 16px;
            padding: 14px 24px;
            font-weight: 700;
            border: none;
            transition: 0.3s;
            width: 100%;
        }

        .btn-soft-primary:hover {
            background: #3311cc;
            box-shadow: 0 10px 20px rgba(67, 24, 255, 0.2);
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-0">Registrasi Akun</h3>
                    <p class="text-muted fw-bold small">Bergabung dengan Sistem Akademik</p>
                </div>

                <div class="card-soft">
                    <ul class="nav nav-pills nav-justified mb-4" id="regTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#mhs" type="button" role="tab">Sebagai Mahasiswa</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#dsn" type="button" role="tab">Sebagai Dosen</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="regTabContent">

                        <div class="tab-pane fade show active" id="mhs" role="tabpanel">
                            <form method="POST">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-5">
                                        <label class="form-label">NIM</label>
                                        <input type="text" name="nim" class="form-control" placeholder="Contoh: 12345" required>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Nama sesuai KTP" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jurusan Program Studi</label>
                                    <input type="text" name="jurusan" class="form-control" placeholder="Contoh: Teknik Informatika" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Buat Kata Sandi</label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                                </div>
                                <button type="submit" name="btn_register_mhs" class="btn-soft-primary">Buat Akun Mahasiswa</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="dsn" role="tabpanel">
                            <form method="POST">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-5">
                                        <label class="form-label">NIDN</label>
                                        <input type="text" name="nidn" class="form-control" placeholder="Contoh: 98765" required>
                                    </div>
                                    <div class="col-md-7">
                                        <label class="form-label">Nama Dosen</label>
                                        <input type="text" name="nama" class="form-control" placeholder="Beserta Gelar Akademik" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Buat Kata Sandi</label>
                                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                                </div>
                                <button type="submit" name="btn_register_dsn" class="btn-soft-primary" style="background: #00B4DB;">Buat Akun Dosen</button>
                            </form>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <a href="login.php" class="text-decoration-none fw-bold" style="color: #a3aed1;">Sudah punya akun? <span style="color: #4318ff;">Masuk di sini</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>