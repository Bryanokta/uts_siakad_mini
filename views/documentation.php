<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi SIAKAD POLIJE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            line-height: 1.7;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--dark-navy) 0%, #1a296c 100%);
            color: white;
            padding: 80px 0 60px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            margin-bottom: -40px;
            box-shadow: 0 20px 40px rgba(17, 28, 68, 0.15);
        }

        .hero-title {
            font-weight: 800;
            font-size: 2.5rem;
            letter-spacing: -1px;
        }

        .hero-subtitle {
            color: var(--secondary-text);
            font-weight: 500;
            font-size: 1.1rem;
        }

        /* Content Cards */
        .doc-container {
            position: relative;
            z-index: 10;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .card-doc {
            background: white;
            border-radius: 24px;
            border: none;
            box-shadow: 0 10px 40px rgba(112, 144, 176, 0.08);
            padding: 40px;
            margin-bottom: 30px;
        }

        .section-title {
            font-weight: 800;
            color: var(--dark-navy);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: var(--primary);
            background: #f4f7fe;
            padding: 10px;
            border-radius: 12px;
            font-size: 1.3rem;
        }

        /* Feature List */
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .feature-list li i {
            color: #05cd99;
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .feature-list li strong {
            color: var(--dark-navy);
        }

        .feature-list li p {
            margin: 0;
            color: #6b7a99;
        }

        /* Tech Badges */
        .tech-badge {
            background: #f4f7fe;
            color: var(--primary);
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 10px;
            display: inline-block;
            margin: 0 8px 8px 0;
            border: 1px solid #e9edf7;
        }

        /* Code Block (Terminal Style) */
        .code-block {
            background: #0f172a;
            color: #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            font-family: 'Courier New', Courier, monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            position: relative;
        }

        .code-block::before {
            content: "• • •";
            position: absolute;
            top: 10px;
            left: 15px;
            color: #475569;
            font-size: 1.5rem;
            line-height: 0.5;
            letter-spacing: 2px;
        }

        .code-content {
            margin-top: 20px;
        }

        .code-comment {
            color: #64748b;
        }

        .code-folder {
            color: #60a5fa;
            font-weight: bold;
        }

        .code-file {
            color: #e2e8f0;
        }

        /* Alert Info */
        .alert-info-custom {
            background: #fff0d4;
            border-left: 4px solid #ffb547;
            color: #a87422;
            padding: 20px;
            border-radius: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>

    <div class="hero-section text-center">
        <div class="container">
            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_Politeknik_Negeri_Jember.png" width="80" class="mb-4 bg-white p-2 rounded-4 shadow-sm">
            <h1 class="hero-title">Dokumentasi SIAKAD Mini</h1>
            <p class="hero-subtitle">Panduan Sistem Informasi Akademik</p>
        </div>
    </div>

    <div class="doc-container mt-5 pb-5">

        <div class="card-doc">
            <h3 class="section-title"><i class="bi bi-info-square-fill"></i> Pengantar Sistem</h3>
            <p class="text-muted fw-medium">
                Sistem Informasi Akademik (SIAKAD) Mini adalah aplikasi berbasis web yang dirancang untuk memudahkan manajemen data akademik kampus, seperti pendataan mahasiswa, mata kuliah, dan pengolahan nilai. Aplikasi ini dibangun dengan mengimplementasikan konsep <strong>Pseudo-MVC (Model-View-Controller)</strong> dan prinsip pemrograman berorientasi objek (OOP) seperti <strong>Polymorphism</strong>.
            </p>

        </div>

        <div class="card-doc">
            <h3 class="section-title"><i class="bi bi-stars"></i> Fitur Utama</h3>

            <h5 class="fw-bold mb-3 mt-4 text-primary">👨‍🏫 Panel Dosen (Admin)</h5>
            <ul class="feature-list mb-4">
                <li><i class="bi bi-check-circle-fill"></i>
                    <div><strong>Executive Dashboard:</strong>
                        <p>Ringkasan statistik real-time (Total Mahasiswa, Mata Kuliah, Rata-rata IPK, dan deteksi tugas/nilai tertunda).</p>
                    </div>
                </li>
                <li><i class="bi bi-check-circle-fill"></i>
                    <div><strong>Manajemen Data Master:</strong>
                        <p>Fungsi CRUD terintegrasi dengan pengamanan <em>Cascade Delete</em> (menghapus mahasiswa otomatis menghapus nilainya).</p>
                    </div>
                </li>
                <li><i class="bi bi-check-circle-fill"></i>
                    <div><strong>Unified Grade Input (Pusat Pengolahan Nilai):</strong>
                        <p>Dilengkapi dengan <em>Toggle Mode</em>. <strong>Mode Individu:</strong> Input semua nilai matkul untuk satu mahasiswa. <strong>Mode Kolektif:</strong> Input nilai satu kelas/matkul sekaligus (Batch Processing).</p>
                    </div>
                </li>
                <li><i class="bi bi-check-circle-fill"></i>
                    <div><strong>Pencetak Laporan (Polymorphism):</strong>
                        <p>Sistem mencetak KHS dengan satu fungsi induk yang menghasilkan dua wujud: <em>Pop-up Modal</em> (Pratinjau) dan <em>PDF Document</em>.</p>
                    </div>
                </li>
            </ul>

            <h5 class="fw-bold mb-3 mt-4 text-primary">👨‍🎓 Portal Mahasiswa</h5>
            <ul class="feature-list">
                <li><i class="bi bi-check-circle-fill"></i>
                    <div><strong>Dashboard Ringkas:</strong>
                        <p>Menampilkan langsung IPK komulatif dan detail Kartu Hasil Studi (KHS) mahasiswa yang bersangkutan dengan tampilan visual yang menarik.</p>
                    </div>
                </li>
            </ul>
        </div>

        <div class="card-doc">
            <h3 class="section-title"><i class="bi bi-cpu-fill"></i> Stack Teknologi</h3>
            <div class="mb-2">
                <span class="tech-badge"><i class="bi bi-code-slash me-1"></i> PHP 8+ (Native / OOP)</span>
                <span class="tech-badge"><i class="bi bi-database me-1"></i> MySQL / PDO</span>
                <span class="tech-badge"><i class="bi bi-filetype-html me-1"></i> HTML5 & CSS3</span>
                <span class="tech-badge"><i class="bi bi-bootstrap me-1"></i> Bootstrap 5.3</span>
                <span class="tech-badge"><i class="bi bi-plugin me-1"></i> Tom Select JS</span>
                <span class="tech-badge"><i class="bi bi-app-indicator me-1"></i> SweetAlert2</span>
            </div>
        </div>

        <div class="card-doc">
            <h3 class="section-title"><i class="bi bi-folder2-open"></i> Struktur Direktori (Pseudo-MVC)</h3>
            <p class="text-muted fw-medium">Proyek ini disusun dengan memisahkan logika database (Model), antarmuka pengguna (View), dan pemroses rute/aksi (Controller).</p>

            <div class="code-block">
                <div class="code-content">
                    <span class="code-folder">UTS/</span>
                    ├── <span class="code-folder">controllers/</span>
                    │ ├── <span class="code-file">cetak_pdf.php</span> <span class="code-comment"># Menangani eksekusi rendering cetak ke PDF</span>
                    │ └── <span class="code-file">logout.php</span> <span class="code-comment"># Menangani penghapusan sesi user</span>
                    ├── <span class="code-folder">models/</span>
                    │ ├── <span class="code-file">config.php</span> <span class="code-comment"># Kredensial dan koneksi database (PDO)</span>
                    │ ├── <span class="code-file">SistemAkademik.php</span> <span class="code-comment"># Core Model (Query nilai, matkul, mhs, IPK)</span>
                    │ ├── <span class="code-file">FormatLaporan.php</span> <span class="code-comment"># Interface & Class Polymorphism (Cetak KHS)</span>
                    │ ├── <span class="code-file">Mahasiswa.php</span> <span class="code-comment"># Model objek Mahasiswa (Registrasi)</span>
                    │ ├── <span class="code-file">Dosen.php</span> <span class="code-comment"># Model objek Dosen (Registrasi)</span>
                    │ └── <span class="code-file">User.php</span> <span class="code-comment"># Induk kelas pengguna</span>
                    ├── <span class="code-folder">views/</span>
                    │ ├── <span class="code-file">dashboard_dosen.php</span> <span class="code-comment"># Antarmuka panel Dosen (Command Center)</span>
                    │ ├── <span class="code-file">dashboard_mahasiswa.php</span> <span class="code-comment"># Antarmuka panel Mahasiswa</span>
                    │ ├── <span class="code-file">login.php</span> <span class="code-comment"># Halaman autentikasi</span>
                    │ ├── <span class="code-file">register.php</span> <span class="code-comment"># Halaman pendaftaran akun</span>
                    │ └── <span class="code-file">dokumentasi.html</span> <span class="code-comment"># File dokumentasi ini</span>
                    └── <span class="code-file">index.php</span> <span class="code-comment"># Gerbang utama aplikasi (Router Sesi)</span>
                </div>
            </div>
        </div>

        <div class="card-doc">
            <h3 class="section-title"><i class="bi bi-gear-fill"></i> Panduan Menjalankan Sistem</h3>
            <ol class="text-muted fw-medium fs-6" style="line-height: 2;">
                <li>Buka aplikasi XAMPP/MAMP/Laragon atau pengelola database lokal kamu.</li>
                <li>Buat database baru dengan nama <strong><code>siakad_mini</code></strong>.</li>
                <li>Lakukan <em>Import</em> file database SQL kamu ke dalam database tersebut.</li>
                <li>Buka file <strong><code>models/config.php</code></strong> lalu sesuaikan kredensial <em>username</em> dan <em>password</em> database (biasanya root dan kosong).</li>
                <li>Buka terminal/CMD, arahkan ke folder proyek ini (<code>cd path/ke/folder/UTS</code>).</li>
                <li>Jalankan perintah server bawaan PHP: <strong><code>php -S localhost:8000</code></strong>.</li>
                <li>Buka browser dan akses alamat <strong><code>http://localhost:8000</code></strong>. Aplikasi siap digunakan!</li>
            </ol>

            <div class="alert-info-custom mt-4">
                <i class="bi bi-lightbulb-fill me-2"></i> <strong>Catatan:</strong> Jika belum memiliki akun, silakan gunakan fitur Register terlebih dahulu dari halaman Login.
            </div>
        </div>

        <div class="text-center text-muted fw-bold small mt-5">
            &copy; 2024 SIAKAD POLIJE - Developed by bryan I43252033
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>