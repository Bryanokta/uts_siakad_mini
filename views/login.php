<?php
require_once '../models/config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['role'];
    $id = $_POST['id'];
    $password = $_POST['password'];

    if ($role == 'Mahasiswa') {
        $stmt = $db->prepare("SELECT * FROM mahasiswa WHERE nim = :id");
    } else {
        $stmt = $db->prepare("SELECT * FROM dosen WHERE nidn = :id");
    }

    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $role == 'Mahasiswa' ? $user['nim'] : $user['nidn'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['role'] = $role;
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Data yang dimasukkan tidak cocok atau tidak terdaftar!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD - Login Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f4f7fe;
            color: #2b3674;
            display: flex;
            align-items: center;
            min-height: 100vh;
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
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <div class="rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px; background: linear-gradient(135deg, #868CFF 0%, #4318FF 100%); color: white;">
                        <i class="bi bi-mortarboard-fill fs-2"></i>
                    </div>
                    <h3 class="fw-bold mb-0">Selamat Datang</h3>
                    <p class="text-muted fw-bold small">Silakan masuk ke akun SIAKAD Anda</p>
                </div>

                <div class="card-soft">
                    <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'sukses_register'): ?>
                        <div class="alert alert-success border-0 rounded-4 shadow-sm py-3 px-4 mb-4 fw-bold text-success text-center" style="background: #e2fbed;">
                            <i class="bi bi-check-circle-fill me-2 fs-5"></i> Pendaftaran berhasil!
                        </div>
                    <?php endif; ?>

                    <?php if ($error != ""): ?>
                        <div class="alert alert-danger border-0 rounded-4 shadow-sm py-3 px-4 mb-4 fw-bold text-danger text-center" style="background: #ffe2e5;">
                            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>

                    <ul class="nav nav-pills nav-justified mb-4" id="loginTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#mhs" type="button" role="tab">Mahasiswa</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#dsn" type="button" role="tab">Dosen / Admin</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="loginTabContent">

                        <div class="tab-pane fade show active" id="mhs" role="tabpanel">
                            <form method="POST">
                                <input type="hidden" name="role" value="Mahasiswa">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Induk Mahasiswa (NIM)</label>
                                    <input type="text" name="id" class="form-control" placeholder="Ketik NIM Anda..." required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Kata Sandi</label>
                                    <input type="password" name="password" class="form-control" placeholder="Ketik kata sandi..." required>
                                </div>
                                <button type="submit" class="btn-soft-primary">Masuk Portal Mahasiswa</button>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="dsn" role="tabpanel">
                            <form method="POST">
                                <input type="hidden" name="role" value="Dosen">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Induk Dosen (NIDN)</label>
                                    <input type="text" name="id" class="form-control" placeholder="Ketik NIDN Anda..." required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Kata Sandi</label>
                                    <input type="password" name="password" class="form-control" placeholder="Ketik kata sandi..." required>
                                </div>
                                <button type="submit" class="btn-soft-primary" style="background: #00B4DB;">Masuk Panel Dosen</button>
                            </form>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <a href="register.php" class="text-decoration-none fw-bold" style="color: #a3aed1;">Belum punya akun? <span style="color: #4318ff;">Daftar sekarang</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>