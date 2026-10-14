<?php
/**
 * Toko Baju Ghani & Platform Praktikum Web
 * Framework: Laravel 10 (Docker + PostgreSQL 15 + Blade / TailwindCSS)
 * Author: Rizqi Ghani Adinata (24EP10007)
 */

define('LARAVEL_START', microtime(true));

// 1. Jika Composer vendor terpasang, jalankan Laravel HTTP Kernel standar
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\Http\Request::capture()
    )->send();
    $kernel->terminate($request, $response);
    exit;
}

// 2. Standalone Runtime Engine (Zero-dependency untuk Docker & Offline Environment)
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

    // Pastikan tabel siap
    $pdo->exec("CREATE TABLE IF NOT EXISTS mahasiswa (
        id SERIAL PRIMARY KEY,
        nim VARCHAR(20) NOT NULL UNIQUE,
        nama VARCHAR(100) NOT NULL,
        prodi VARCHAR(100) NOT NULL DEFAULT 'Sistem Informasi',
        status VARCHAR(50) NOT NULL DEFAULT 'Aktif',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id SERIAL PRIMARY KEY,
        nama_produk VARCHAR(150) NOT NULL,
        kategori VARCHAR(50) NOT NULL DEFAULT 'Kaos',
        harga NUMERIC(12, 2) NOT NULL DEFAULT 0,
        stok INT NOT NULL DEFAULT 10,
        ukuran VARCHAR(20) DEFAULT 'All Size',
        deskripsi TEXT,
        gambar TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );");

    // Handle Form Requests
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $action = $_POST['action'];

        // Tambah Produk Toko Baju
        if ($action === 'tambah_produk') {
            $nama = trim($_POST['nama_produk'] ?? '');
            $kategori = trim($_POST['kategori'] ?? 'Kaos');
            $harga = floatval($_POST['harga'] ?? 0);
            $stok = intval($_POST['stok'] ?? 10);
            $ukuran = trim($_POST['ukuran'] ?? 'All Size');
            $deskripsi = trim($_POST['deskripsi'] ?? '');
            $gambar = trim($_POST['gambar'] ?? '');

            if (empty($gambar)) {
                $gambar = 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80';
            }

            if (!empty($nama) && $harga > 0) {
                $stmt = $pdo->prepare("INSERT INTO products (nama_produk, kategori, harga, stok, ukuran, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nama, $kategori, $harga, $stok, $ukuran, $deskripsi, $gambar]);
            }
            header("Location: /?tab=toko");
            exit;
        }

        // Hapus Produk Toko Baju
        if ($action === 'hapus_produk') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
                $stmt->execute([$id]);
            }
            header("Location: /?tab=toko");
            exit;
        }

        // Tambah/Update Mahasiswa
        if ($action === 'tambah_mhs') {
            $nim = trim($_POST['nim'] ?? '');
            $nama = trim($_POST['nama'] ?? '');
            $prodi = trim($_POST['prodi'] ?? 'Sistem Informasi');
            $status = trim($_POST['status'] ?? 'Aktif');

            if (!empty($nim) && !empty($nama)) {
                $stmt = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, prodi, status) VALUES (?, ?, ?, ?) ON CONFLICT (nim) DO UPDATE SET nama = EXCLUDED.nama, prodi = EXCLUDED.prodi, status = EXCLUDED.status");
                $stmt->execute([$nim, $nama, $prodi, $status]);
            }
            header("Location: /?tab=mahasiswa");
            exit;
        }

        // Hapus Mahasiswa
        if ($action === 'hapus_mhs') {
            $id = intval($_POST['id'] ?? 0);
            if ($id > 0) {
                $stmt = $pdo->prepare("DELETE FROM mahasiswa WHERE id = ?");
                $stmt->execute([$id]);
            }
            header("Location: /?tab=mahasiswa");
            exit;
        }
    }

    // Seeder otomatis jika tabel kosong
    $countProd = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    if ($countProd == 0) {
        $sampleProducts = [
            ['Kaos Oversize Streetwear Ghani', 'Kaos', 125000, 25, 'L', 'Bahan katun combed 24s premium adem dan nyaman.', 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80'],
            ['Kemeja Flanel Vintage Red Check', 'Kemeja', 185000, 18, 'XL', 'Kemeja flanel katun wol lembut untuk kuliah dan casual.', 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&auto=format&fit=crop&q=80'],
            ['Hoodie Heavyweight Fleece Charcoal', 'Outerwear', 230000, 15, 'All Size', 'Cotton fleece 300gsm dengan kangaroo pocket.', 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&auto=format&fit=crop&q=80'],
            ['Celana Chino Slim Fit Dark Khaki', 'Celana', 175000, 20, '32', 'Katun twill stretch premium sangat nyaman dan fleksibel.', 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&auto=format&fit=crop&q=80'],
            ['Kemeja Batik Modern Slimfit', 'Batik', 210000, 12, 'L', 'Batik cap primisima furing katun adem acara resmi.', 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&auto=format&fit=crop&q=80'],
            ['Jaket Denim Vintage Classic Blue', 'Outerwear', 275000, 10, 'XL', 'Denim tebal 14oz bio-wash vintage tahan lama.', 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=600&auto=format&fit=crop&q=80'],
        ];
        $insertStmt = $pdo->prepare("INSERT INTO products (nama_produk, kategori, harga, stok, ukuran, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($sampleProducts as $sp) {
            $insertStmt->execute($sp);
        }
    }

    $countMhs = $pdo->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn();
    if ($countMhs == 0) {
        $insertMhs = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, prodi, status) VALUES (?, ?, ?, ?)");
        $insertMhs->execute(['24EP10007', 'Rizqi Ghani Adinata', 'Sistem Informasi', 'Aktif']);
        $insertMhs->execute(['24EP10008', 'Ahmad Fauzi', 'Sistem Informasi', 'Aktif']);
        $insertMhs->execute(['24EP10009', 'Siti Nurhaliza', 'Sistem Informasi', 'Aktif']);
    }

    // Filter Kategori Produk
    $selectedKat = $_GET['kat'] ?? 'Semua';
    if ($selectedKat && $selectedKat !== 'Semua') {
        $stmtP = $pdo->prepare("SELECT * FROM products WHERE kategori = ? ORDER BY id DESC");
        $stmtP->execute([$selectedKat]);
        $dataProducts = $stmtP->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $dataProducts = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    $dataMahasiswa = $pdo->query("SELECT * FROM mahasiswa ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $dbConnected = false;
    $dbError = $e->getMessage();
    $dataProducts = [];
    $dataMahasiswa = [];
}

$activeTab = $_GET['tab'] ?? 'toko';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Baju Ghani | Laravel 10 & Docker Stack</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#fdf4ff',
                            500: '#d946ef',
                            600: '#c026d3',
                            700: '#a21caf',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .glass-card { background: rgba(30, 41, 59, 0.75); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col antialiased selection:bg-fuchsia-600 selection:text-white">

    <!-- Top Navigation Bar -->
    <header class="sticky top-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-fuchsia-600 via-indigo-600 to-cyan-400 p-0.5 shadow-lg shadow-fuchsia-500/20">
                    <div class="w-full h-full bg-slate-950 rounded-[10px] flex items-center justify-center">
                        <i class="fa-solid fa-shirt text-fuchsia-400 text-lg"></i>
                    </div>
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-white via-slate-100 to-fuchsia-400 bg-clip-text text-transparent">
                        TokoBaju-Ghani
                    </span>
                    <span class="ml-2 text-xs font-semibold px-2 py-0.5 rounded-full bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20">
                        Laravel 10 + Docker
                    </span>
                </div>
            </div>

            <!-- Tab Switcher Navigation -->
            <nav class="hidden md:flex items-center gap-1 bg-slate-950/60 p-1 rounded-xl border border-slate-800">
                <a href="?tab=toko" class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $activeTab === 'toko' ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' ?>">
                    <i class="fa-solid fa-store mr-1.5"></i> Katalog Toko Baju
                </a>
                <a href="?tab=mahasiswa" class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $activeTab === 'mahasiswa' ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' ?>">
                    <i class="fa-solid fa-graduation-cap mr-1.5"></i> Data Mahasiswa
                </a>
                <a href="?tab=server" class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $activeTab === 'server' ? 'bg-fuchsia-600 text-white shadow-lg shadow-fuchsia-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/50' ?>">
                    <i class="fa-brands fa-docker mr-1.5"></i> Stack & CI/CD
                </a>
            </nav>

            <!-- Status Indicator & Cart Button -->
            <div class="flex items-center gap-3">
                <?php if ($dbConnected): ?>
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        PostgreSQL Connected
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/10 text-rose-400 border border-rose-500/20">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        DB Offline
                    </span>
                <?php endif; ?>

                <button onclick="toggleCart()" class="relative p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 transition">
                    <i class="fa-solid fa-cart-shopping text-lg text-fuchsia-400"></i>
                    <span id="cart-count-badge" class="absolute -top-1 -right-1 w-5 h-5 bg-fuchsia-600 text-white text-xs font-bold rounded-full flex items-center justify-center">0</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <?php if ($activeTab === 'toko'): ?>
            <!-- TAB 1: TOKO BAJU GHANI (CATALOG & CART) -->
            <section class="space-y-8">
                <!-- Hero Promo Banner -->
                <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-fuchsia-950 via-slate-900 to-indigo-950 border border-fuchsia-500/20 p-8 sm:p-12 shadow-2xl">
                    <div class="relative z-10 max-w-2xl space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-fuchsia-500/20 text-fuchsia-300 border border-fuchsia-500/30">
                            <i class="fa-solid fa-sparkles"></i> Koleksi Fashion 2026 Terbaru
                        </div>
                        <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight text-white leading-tight">
                            Gaya Premium, <span class="bg-gradient-to-r from-fuchsia-400 via-pink-300 to-cyan-300 bg-clip-text text-transparent">Kualitas Distro Terbaik</span>
                        </h1>
                        <p class="text-slate-300 text-base sm:text-lg">
                            Katalog busana pria & wanita Toko Baju Ghani. Terintegrasi penuh dengan Docker Container, Laravel, dan PostgreSQL 15.
                        </p>
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button onclick="document.getElementById('modal-tambah-produk').classList.remove('hidden')" class="px-5 py-3 rounded-xl bg-gradient-to-r from-fuchsia-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 text-white font-bold text-sm shadow-lg shadow-fuchsia-600/30 transition flex items-center gap-2">
                                <i class="fa-solid fa-circle-plus"></i> Tambah Produk Baru
                            </button>
                            <a href="#katalog" class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold text-sm transition">
                                <i class="fa-solid fa-bag-shopping mr-1.5"></i> Lihat Katalog (<?= count($dataProducts) ?> Produk)
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Category Pills Filter -->
                <div id="katalog" class="flex flex-wrap items-center gap-2 pt-2">
                    <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider mr-2">Kategori:</span>
                    <?php
                    $kategoris = ['Semua', 'Kaos', 'Kemeja', 'Outerwear', 'Celana', 'Batik'];
                    foreach ($kategoris as $k):
                        $isActive = ($selectedKat === $k || ($selectedKat === '' && $k === 'Semua'));
                    ?>
                        <a href="?tab=toko&kat=<?= urlencode($k) ?>" class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition <?= $isActive ? 'bg-fuchsia-600 text-white shadow-md shadow-fuchsia-600/30' : 'bg-slate-900 text-slate-300 hover:bg-slate-800 border border-slate-800' ?>">
                            <?= $k ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- Product Grid -->
                <?php if (empty($dataProducts)): ?>
                    <div class="glass-card rounded-2xl p-12 text-center text-slate-400">
                        <i class="fa-solid fa-box-open text-5xl mb-3 text-slate-600"></i>
                        <p class="text-lg font-medium">Belum ada produk di kategori ini.</p>
                        <button onclick="document.getElementById('modal-tambah-produk').classList.remove('hidden')" class="mt-4 px-4 py-2 bg-fuchsia-600 text-white text-sm font-semibold rounded-xl">
                            Tambah Produk Pertama
                        </button>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($dataProducts as $prod): ?>
                            <div class="glass-card rounded-2xl overflow-hidden hover:border-fuchsia-500/50 transition-all duration-300 group flex flex-col justify-between">
                                <div>
                                    <div class="relative h-64 overflow-hidden bg-slate-800">
                                        <img src="<?= htmlspecialchars($prod['gambar'] ?: 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600') ?>" alt="<?= htmlspecialchars($prod['nama_produk']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute top-3 left-3">
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-950/80 backdrop-blur-md text-fuchsia-300 border border-fuchsia-500/30">
                                                <?= htmlspecialchars($prod['kategori']) ?>
                                            </span>
                                        </div>
                                        <div class="absolute top-3 right-3">
                                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-slate-950/80 backdrop-blur-md text-cyan-300 border border-cyan-500/30">
                                                Size <?= htmlspecialchars($prod['ukuran']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="p-5 space-y-2">
                                        <h3 class="text-lg font-bold text-white group-hover:text-fuchsia-300 transition">
                                            <?= htmlspecialchars($prod['nama_produk']) ?>
                                        </h3>
                                        <p class="text-slate-400 text-xs line-clamp-2">
                                            <?= htmlspecialchars($prod['deskripsi'] ?: 'Kualitas bahan pilihan terbaik nyaman dipakai.') ?>
                                        </p>
                                        <div class="pt-2 flex items-center justify-between">
                                            <div class="text-xl font-extrabold text-white">
                                                Rp <?= number_format($prod['harga'], 0, ',', '.') ?>
                                            </div>
                                            <span class="text-xs text-slate-400">
                                                Stok: <strong class="text-emerald-400"><?= $prod['stok'] ?></strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 pt-0 flex items-center gap-2">
                                    <button onclick="addToCart(<?= $prod['id'] ?>, '<?= addslashes(htmlspecialchars($prod['nama_produk'])) ?>', <?= $prod['harga'] ?>, '<?= addslashes(htmlspecialchars($prod['gambar'])) ?>')" class="flex-grow py-2.5 px-4 bg-fuchsia-600 hover:bg-fuchsia-500 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 shadow-lg shadow-fuchsia-600/20">
                                        <i class="fa-solid fa-cart-plus"></i> Beli Sekarang
                                    </button>
                                    <form method="POST" onsubmit="return confirm('Hapus produk ini dari katalog?');">
                                        <input type="hidden" name="action" value="hapus_produk">
                                        <input type="hidden" name="id" value="<?= $prod['id'] ?>">
                                        <button type="submit" class="p-2.5 bg-slate-800 hover:bg-rose-500/20 hover:text-rose-400 text-slate-400 rounded-xl transition">
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

        <?php elseif ($activeTab === 'mahasiswa'): ?>
            <!-- TAB 2: DATA MAHASISWA PRAKTIKUM SI -->
            <section class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-extrabold text-white">Data Mahasiswa Praktikum</h2>
                        <p class="text-slate-400 text-sm">Semester 5 - Mata Kuliah Pemrograman Web & Docker Antigravity</p>
                    </div>
                    <button onclick="document.getElementById('modal-tambah-mhs').classList.remove('hidden')" class="px-4 py-2.5 bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-bold rounded-xl transition flex items-center gap-2">
                        <i class="fa-solid fa-user-plus"></i> Tambah Mahasiswa
                    </button>
                </div>

                <div class="glass-card rounded-2xl overflow-hidden border border-slate-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-900/80 border-b border-slate-800 text-slate-300 text-xs uppercase tracking-wider font-semibold">
                                    <th class="py-4 px-6">ID</th>
                                    <th class="py-4 px-6">NIM</th>
                                    <th class="py-4 px-6">Nama Lengkap</th>
                                    <th class="py-4 px-6">Program Studi</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/60 text-sm text-slate-200">
                                <?php if (empty($dataMahasiswa)): ?>
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-500">Belum ada data mahasiswa di PostgreSQL.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($dataMahasiswa as $m): ?>
                                        <tr class="hover:bg-slate-800/40 transition">
                                            <td class="py-4 px-6 font-mono text-slate-400">#<?= $m['id'] ?></td>
                                            <td class="py-4 px-6 font-mono font-semibold text-fuchsia-400"><?= htmlspecialchars($m['nim']) ?></td>
                                            <td class="py-4 px-6 font-semibold"><?= htmlspecialchars($m['nama']) ?></td>
                                            <td class="py-4 px-6 text-slate-300"><?= htmlspecialchars($m['prodi']) ?></td>
                                            <td class="py-4 px-6">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    <?= htmlspecialchars($m['status']) ?>
                                                </span>
                                            </td>
                                            <td class="py-4 px-6 text-center">
                                                <form method="POST" onsubmit="return confirm('Hapus data mahasiswa <?= htmlspecialchars($m['nama']) ?>?');" class="inline">
                                                    <input type="hidden" name="action" value="hapus_mhs">
                                                    <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-300 transition">
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
            </section>

        <?php elseif ($activeTab === 'server'): ?>
            <!-- TAB 3: SERVER STACK & CI/CD COLLABORATION -->
            <section class="space-y-6">
                <div class="glass-card rounded-2xl p-6 sm:p-8 space-y-6">
                    <div class="border-b border-slate-800 pb-4">
                        <h2 class="text-2xl font-extrabold text-white flex items-center gap-3">
                            <i class="fa-solid fa-network-wired text-cyan-400"></i> Lingkungan Runtime & CI/CD
                        </h2>
                        <p class="text-slate-400 text-sm mt-1">Status kontainer, koneksi database PostgreSQL, dan integrasi GitHub CI/CD</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="p-5 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                            <span class="text-xs uppercase font-bold text-slate-400 tracking-wider">Web Application</span>
                            <div class="text-lg font-bold text-white flex items-center gap-2">
                                <i class="fa-brands fa-laravel text-rose-500"></i> Laravel 10 / PHP 8.2
                            </div>
                            <p class="text-xs text-slate-400">Container: <code class="text-cyan-400">laravel_app</code> (Port 8000)</p>
                        </div>
                        <div class="p-5 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                            <span class="text-xs uppercase font-bold text-slate-400 tracking-wider">Relational Database</span>
                            <div class="text-lg font-bold text-white flex items-center gap-2">
                                <i class="fa-solid fa-database text-blue-400"></i> PostgreSQL 15
                            </div>
                            <p class="text-xs text-slate-400">Database: <code class="text-emerald-400"><?= htmlspecialchars($dbName) ?></code> (Port 5432)</p>
                        </div>
                        <div class="p-5 rounded-xl bg-slate-900/60 border border-slate-800 space-y-2">
                            <span class="text-xs uppercase font-bold text-slate-400 tracking-wider">GitHub CI/CD Pipeline</span>
                            <div class="text-lg font-bold text-white flex items-center gap-2">
                                <i class="fa-brands fa-github text-fuchsia-400"></i> GitHub Actions
                            </div>
                            <p class="text-xs text-slate-400">Workflow: <code class="text-fuchsia-400">.github/workflows/ci-cd.yml</code></p>
                        </div>
                    </div>

                    <div class="p-5 rounded-xl bg-slate-900/90 border border-slate-800 space-y-3 font-mono text-xs text-slate-300">
                        <div class="text-slate-400 font-bold uppercase tracking-wider font-sans mb-1">Collaborator Quick Commands:</div>
                        <p class="text-cyan-300"># Jalankan aplikasi langsung dengan Docker:</p>
                        <p class="bg-black/50 p-2.5 rounded-lg text-emerald-400">docker compose up -d --build</p>
                        <p class="text-cyan-300"># Jalankan migrasi database PostgreSQL:</p>
                        <p class="bg-black/50 p-2.5 rounded-lg text-emerald-400">docker compose exec app php artisan migrate</p>
                        <p class="text-cyan-300"># Jalankan seeder katalog Toko Baju:</p>
                        <p class="bg-black/50 p-2.5 rounded-lg text-emerald-400">docker compose exec app php artisan db:seed</p>
                    </div>
                </div>
            </section>
        <?php endif; ?>

    </main>

    <!-- Modal Tambah Produk -->
    <div id="modal-tambah-produk" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-shirt text-fuchsia-400"></i> Tambah Produk Baru
                </h3>
                <button onclick="document.getElementById('modal-tambah-produk').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="tambah_produk">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Produk *</label>
                    <input type="text" name="nama_produk" required placeholder="Contoh: Kaos Polos Combed 30s Hitam" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Kategori *</label>
                        <select name="kategori" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                            <option value="Kaos">Kaos & T-Shirt</option>
                            <option value="Kemeja">Kemeja</option>
                            <option value="Outerwear">Outerwear / Hoodie</option>
                            <option value="Celana">Celana</option>
                            <option value="Batik">Batik Modern</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Ukuran</label>
                        <input type="text" name="ukuran" value="All Size" placeholder="S, M, L, XL, All Size" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Harga (Rp) *</label>
                        <input type="number" name="harga" required placeholder="125000" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1">Stok *</label>
                        <input type="number" name="stok" value="20" required class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">URL Gambar Produk</label>
                    <input type="url" name="gambar" placeholder="https://images.unsplash.com/..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="2" placeholder="Detail bahan dan keunggulan busana..." class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none"></textarea>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-tambah-produk').classList.add('hidden')" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 text-sm font-semibold hover:bg-slate-700">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-bold shadow-lg shadow-fuchsia-600/30">Simpan ke Katalog</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tambah Mahasiswa -->
    <div id="modal-tambah-mhs" class="fixed inset-0 z-50 bg-black/75 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-fuchsia-400"></i> Tambah / Update Mahasiswa
                </h3>
                <button onclick="document.getElementById('modal-tambah-mhs').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="tambah_mhs">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nomor Induk Mahasiswa (NIM) *</label>
                    <input type="text" name="nim" required placeholder="Contoh: 24EP10007" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" required placeholder="Contoh: Rizqi Ghani Adinata" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Program Studi</label>
                    <input type="text" name="prodi" value="Sistem Informasi" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm focus:border-fuchsia-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Status</label>
                    <select name="status" class="w-full px-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-700 text-white text-sm">
                        <option value="Aktif">Aktif</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Lulus">Lulus</option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-2 pt-2">
                    <button type="button" onclick="document.getElementById('modal-tambah-mhs').classList.add('hidden')" class="px-4 py-2.5 rounded-xl bg-slate-800 text-slate-300 text-sm font-semibold hover:bg-slate-700">Batal</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-fuchsia-600 hover:bg-fuchsia-500 text-white text-sm font-bold shadow-lg shadow-fuchsia-600/30">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Shopping Cart Drawer Modal -->
    <div id="cart-drawer" class="fixed inset-y-0 right-0 z-50 w-full sm:w-96 bg-slate-900 border-l border-slate-800 shadow-2xl p-6 flex flex-col justify-between transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-cart-shopping text-fuchsia-400"></i> Keranjang Belanja
                </h3>
                <button onclick="toggleCart()" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
            <div id="cart-items-container" class="space-y-3 overflow-y-auto max-h-[55vh] pr-1">
                <!-- Cart items dynamically rendered via JS -->
            </div>
        </div>

        <div class="border-t border-slate-800 pt-4 space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-slate-400">Total Pembayaran:</span>
                <span id="cart-total-price" class="text-lg font-extrabold text-white">Rp 0</span>
            </div>
            <button onclick="checkoutCart()" class="w-full py-3 bg-gradient-to-r from-fuchsia-600 to-indigo-600 hover:from-fuchsia-500 hover:to-indigo-500 text-white rounded-xl font-bold text-sm shadow-lg shadow-fuchsia-600/30 transition flex items-center justify-center gap-2">
                <i class="fa-brands fa-whatsapp text-emerald-400 text-base"></i> Checkout Pesanan Sekarang
            </button>
        </div>
    </div>

    <script>
        let cart = [];

        function toggleCart() {
            const drawer = document.getElementById('cart-drawer');
            drawer.classList.toggle('translate-x-full');
        }

        function addToCart(id, name, price, img) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty++;
            } else {
                cart.push({ id, name, price, img, qty: 1 });
            }
            renderCart();
            toggleCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items-container');
            const badge = document.getElementById('cart-count-badge');
            const totalEl = document.getElementById('cart-total-price');

            const totalItems = cart.reduce((acc, item) => acc + item.qty, 0);
            badge.innerText = totalItems;

            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-10 text-slate-500">
                        <i class="fa-solid fa-bag-shopping text-4xl mb-2 text-slate-700"></i>
                        <p class="text-sm">Keranjang masih kosong.</p>
                    </div>
                `;
                totalEl.innerText = 'Rp 0';
                return;
            }

            let total = 0;
            container.innerHTML = cart.map(item => {
                total += item.price * item.qty;
                return `
                    <div class="flex items-center gap-3 p-3 bg-slate-950/60 rounded-xl border border-slate-800">
                        <img src="${item.img}" class="w-12 h-12 rounded-lg object-cover">
                        <div class="flex-grow">
                            <h4 class="text-xs font-bold text-white line-clamp-1">${item.name}</h4>
                            <p class="text-xs text-fuchsia-400 font-semibold">Rp ${item.price.toLocaleString('id-ID')} x ${item.qty}</p>
                        </div>
                        <button onclick="removeFromCart(${item.id})" class="text-slate-500 hover:text-rose-400 p-1">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                `;
            }).join('');

            totalEl.innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        function checkoutCart() {
            if (cart.length === 0) {
                alert('Keranjang belanja Anda masih kosong!');
                return;
            }
            let text = 'Halo Toko Baju Ghani, saya ingin memesan:\n';
            let total = 0;
            cart.forEach(item => {
                const subtotal = item.price * item.qty;
                total += subtotal;
                text += `- ${item.name} (${item.qty}x) = Rp ${subtotal.toLocaleString('id-ID')}\n`;
            });
            text += `\nTotal: Rp ${total.toLocaleString('id-ID')}`;
            const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
            window.open(url, '_blank');
        }
    </script>
</body>
</html>
