<?php
/**
 * Aplikasi Web Praktikum Pemrograman Web
 * Stack: Laravel / PHP 8.2 + Docker + PostgreSQL 15
 */

$dbHost = getenv('DB_HOST') ?: 'postgres';
$dbPort = getenv('DB_PORT') ?: '5432';
$dbName = getenv('DB_DATABASE') ?: 'laravel_db';
$dbUser = getenv('DB_USERNAME') ?: 'laravel_user';
$dbPass = getenv('DB_PASSWORD') ?: 'secret_password';

$dbConnected = false;
$dbError = null;
$pdo = null;

try {
    $dsn = "pgsql:host={$dbHost};port={$dbPort};dbname={$dbName}";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 3
    ]);
    $dbConnected = true;

    // Pastikan tabel mahasiswa otomatis ada
    $pdo->exec("CREATE TABLE IF NOT EXISTS mahasiswa (
        id SERIAL PRIMARY KEY,
        nim VARCHAR(20) NOT NULL UNIQUE,
        nama VARCHAR(100) NOT NULL,
        prodi VARCHAR(100) NOT NULL DEFAULT 'Sistem Informasi',
        status VARCHAR(50) NOT NULL DEFAULT 'Aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // Handle form submission (Tambah Data)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'tambah') {
            $nim = trim($_POST['nim'] ?? '');
            $nama = trim($_POST['nama'] ?? '');
            $prodi = trim($_POST['prodi'] ?? 'Sistem Informasi');
            $status = trim($_POST['status'] ?? 'Aktif');

            if (!empty($nim) && !empty($nama)) {
                $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, prodi, status) VALUES (?, ?, ?, ?) ON CONFLICT (nim) DO UPDATE SET nama = EXCLUDED.nama, prodi = EXCLUDED.prodi, status = EXCLUDED.status");
                $stmt->execute([$nim, $nama, $prodi, $status]);
            }
            header("Location: /");
            exit;
        }

        if ($_POST['action'] === 'hapus') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
                $stmt->execute([$id]);
            }
            header("Location: /");
            exit;
        }
    }

    // Ambil data mahasiswa
    $dataMahasiswa = $pdo->query("SELECT * FROM mahasiswa ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $dbConnected = false;
    $dbError = $e->getMessage();
    $dataMahasiswa = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Web Laravel + Docker & PostgreSQL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- Header Section -->
        <div class="bg-slate-800/80 backdrop-blur-md rounded-2xl p-6 sm:p-8 border border-slate-700/60 shadow-2xl flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-rose-500 via-indigo-600 to-cyan-400 p-0.5 shadow-lg shadow-indigo-500/30">
                    <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center">
                        <i class="fa-brands fa-docker text-3xl text-cyan-400"></i>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-rose-400 bg-rose-500/10 px-2.5 py-0.5 rounded-full border border-rose-500/20">Laravel Blade</span>
                        <span class="text-xs font-bold uppercase tracking-wider text-cyan-400 bg-cyan-500/10 px-2.5 py-0.5 rounded-full border border-cyan-500/20">Docker Stack</span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-white mt-1">Sistem Informasi Akademik</h1>
                    <p class="text-slate-400 text-sm mt-0.5">Praktikum Pemrograman Web - Universitas Nahdlatul Ulama Al Ghazali</p>
                </div>
            </div>

            <!-- Database Status Indicator -->
            <div class="flex items-center gap-3 bg-slate-900/90 border border-slate-700/80 px-4 py-3 rounded-xl">
                <div class="relative flex h-3.5 w-3.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?= $dbConnected ? 'bg-emerald-400' : 'bg-rose-400' ?> opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 <?= $dbConnected ? 'bg-emerald-500' : 'bg-rose-500' ?>"></span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">PostgreSQL Status</p>
                    <p class="text-sm font-bold <?= $dbConnected ? 'text-emerald-400' : 'text-rose-400' ?>">
                        <?= $dbConnected ? 'Connected (Host: postgres)' : 'Connecting / Offline' ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Telemetry Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                <p class="text-xs text-slate-400 uppercase font-semibold">Web Server</p>
                <p class="text-lg font-bold text-white mt-1">PHP <?= phpversion() ?></p>
                <p class="text-xs text-slate-500">Container: laravel_app</p>
            </div>
            <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                <p class="text-xs text-slate-400 uppercase font-semibold">Port Mapping</p>
                <p class="text-lg font-bold text-indigo-400 mt-1">8000 : 8000</p>
                <p class="text-xs text-slate-500">Artisan Serve</p>
            </div>
            <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                <p class="text-xs text-slate-400 uppercase font-semibold">Database Engine</p>
                <p class="text-lg font-bold text-cyan-400 mt-1">PostgreSQL 15</p>
                <p class="text-xs text-slate-500">DB: <?= htmlspecialchars($dbName) ?></p>
            </div>
            <div class="bg-slate-800/60 border border-slate-700/50 rounded-xl p-4">
                <p class="text-xs text-slate-400 uppercase font-semibold">Docker Network</p>
                <p class="text-lg font-bold text-emerald-400 mt-1">laravel-net</p>
                <p class="text-xs text-slate-500">Bridge Driver</p>
            </div>
        </div>

        <?php if (!$dbConnected): ?>
        <div class="bg-rose-500/10 border border-rose-500/30 text-rose-300 p-4 rounded-xl flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold text-rose-200">Database Belum Siap</h4>
                <p class="text-xs mt-1">Container PostgreSQL sedang melakukan inisialisasi awal. Silakan refresh halaman dalam beberapa detik atau jalankan: <code class="bg-slate-900 px-2 py-0.5 rounded text-amber-300">docker compose exec app php artisan migrate</code></p>
                <p class="text-xs text-rose-400/80 mt-1">Pesan: <?= htmlspecialchars($dbError ?? '') ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Main Content Area: Form & Table -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Form Input Mahasiswa -->
            <div class="bg-slate-800/80 border border-slate-700/70 rounded-2xl p-6 shadow-xl h-fit">
                <div class="flex items-center gap-3 mb-5 border-b border-slate-700/80 pb-4">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center font-bold">
                        <i class="fa-solid fa-user-plus text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold text-white">Input Mahasiswa Baru</h2>
                        <p class="text-xs text-slate-400">Simpan langsung ke PostgreSQL</p>
                    </div>
                </div>

                <form method="POST" action="/" class="space-y-4">
                    <input type="hidden" name="action" value="tambah">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">NIM Mahasiswa</label>
                        <input type="text" name="nim" required placeholder="Contoh: 24EP10007" 
                            class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Contoh: Rizqi Ghani" 
                            class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Program Studi</label>
                        <select name="prodi" class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="Sistem Informasi">Sistem Informasi</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Manajemen Informatika">Manajemen Informatika</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Status Mahasiswa</label>
                        <select name="status" class="w-full bg-slate-900/90 border border-slate-700 rounded-xl px-3.5 py-2 text-sm text-white focus:outline-none focus:border-indigo-500">
                            <option value="Aktif">Aktif</option>
                            <option value="Cuti">Cuti</option>
                            <option value="Lulus">Lulus</option>
                        </select>
                    </div>

                    <button type="submit" <?= !$dbConnected ? 'disabled' : '' ?> 
                        class="w-full mt-2 bg-gradient-to-r from-indigo-500 to-cyan-500 hover:from-indigo-600 hover:to-cyan-600 text-white font-semibold py-2.5 px-4 rounded-xl shadow-lg shadow-indigo-500/25 transition flex items-center justify-center gap-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Simpan ke PostgreSQL
                    </button>
                </form>
            </div>

            <!-- Tabel Data Mahasiswa -->
            <div class="lg:col-span-2 bg-slate-800/80 border border-slate-700/70 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between border-b border-slate-700/80 pb-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-database text-sm"></i>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-white">Daftar Mahasiswa (PostgreSQL Live)</h2>
                                <p class="text-xs text-slate-400">Tabel: <code class="text-cyan-300">mahasiswa</code> | Total: <?= count($dataMahasiswa) ?> Record</p>
                            </div>
                        </div>
                        <a href="/" class="text-xs text-slate-400 hover:text-white bg-slate-700/50 hover:bg-slate-700 px-3 py-1.5 rounded-lg border border-slate-600/50 transition flex items-center gap-1.5">
                            <i class="fa-solid fa-arrows-rotate"></i> Refresh
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-slate-900/60 text-xs uppercase text-slate-400 border-b border-slate-700">
                                <tr>
                                    <th class="py-3 px-4">NIM</th>
                                    <th class="py-3 px-4">Nama</th>
                                    <th class="py-3 px-4">Program Studi</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700/60">
                                <?php if (empty($dataMahasiswa)): ?>
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-500 text-xs">
                                        <i class="fa-regular fa-folder-open text-2xl block mb-2 opacity-50"></i>
                                        Belum ada data mahasiswa di tabel PostgreSQL.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($dataMahasiswa as $mhs): ?>
                                    <tr class="hover:bg-slate-700/30 transition">
                                        <td class="py-3 px-4 font-mono font-semibold text-cyan-300 text-xs"><?= htmlspecialchars($mhs['nim']) ?></td>
                                        <td class="py-3 px-4 font-medium text-white"><?= htmlspecialchars($mhs['nama']) ?></td>
                                        <td class="py-3 px-4 text-xs text-slate-400"><?= htmlspecialchars($mhs['prodi']) ?></td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold <?= $mhs['status'] === 'Aktif' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-amber-500/10 text-amber-400 border border-amber-500/20' ?>">
                                                <?= htmlspecialchars($mhs['status']) ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <form method="POST" action="/" onsubmit="return confirm('Hapus data ini?');" class="inline">
                                                <input type="hidden" name="action" value="hapus">
                                                <input type="hidden" name="id" value="<?= $mhs['id'] ?>">
                                                <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs p-1.5 rounded hover:bg-rose-500/10 transition">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-700/60 flex items-center justify-between text-xs text-slate-500">
                    <span>Persistent Volume: <code class="text-slate-400">pgdata</code></span>
                    <span>Ready for Semester 5 MK Web Programming</span>
                </div>
            </div>

        </div>

    </div>
</body>
</html>
