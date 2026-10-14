<?php

$host = getenv('DB_HOST') ?: 'postgres';
$port = getenv('DB_PORT') ?: '5432';
$dbname = getenv('DB_DATABASE') ?: 'laravel_db';
$user = getenv('DB_USERNAME') ?: 'laravel_user';
$password = getenv('DB_PASSWORD') ?: 'secret_password';

echo "Menghubungkan ke PostgreSQL di host={$host}:{$port}, database={$dbname}...\n";

$maxRetries = 10;
$retryDelay = 2;
$pdo = null;

for ($i = 1; $i <= $maxRetries; $i++) {
    try {
        $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        break;
    } catch (PDOException $e) {
        echo "Percobaan ke-{$i} gagal, menunggu database siap ({$e->getMessage()})...\n";
        sleep($retryDelay);
    }
}

if (!$pdo) {
    echo "\033[31m[Error]\033[0m Gagal terhubung ke database setelah beberapa percobaan.\n";
    exit(1);
}

// 1. Buat tabel mahasiswa
$sqlMhs = "CREATE TABLE IF NOT EXISTS mahasiswa (
    id SERIAL PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL DEFAULT 'Sistem Informasi',
    status VARCHAR(50) NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
$pdo->exec($sqlMhs);
echo "\033[32m[Sukses]\033[0m Tabel 'mahasiswa' berhasil dibuat/dimigrasikan di PostgreSQL!\n";

// Tambahkan sample data mahasiswa jika masih kosong
$stmtMhs = $pdo->query("SELECT COUNT(*) FROM mahasiswa");
if ($stmtMhs->fetchColumn() == 0) {
    $insert = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, prodi, status) VALUES (?, ?, ?, ?)");
    $insert->execute(['24EP10007', 'Rizqi Ghani Adinata', 'Sistem Informasi', 'Aktif']);
    $insert->execute(['24EP10008', 'Ahmad Fauzi', 'Sistem Informasi', 'Aktif']);
    $insert->execute(['24EP10009', 'Siti Nurhaliza', 'Sistem Informasi', 'Aktif']);
    echo "\033[32m[Sukses]\033[0m Data seeder mahasiswa berhasil ditambahkan.\n";
}

// 2. Buat tabel products (Toko Baju Ghani)
$sqlProducts = "CREATE TABLE IF NOT EXISTS products (
    id SERIAL PRIMARY KEY,
    nama_produk VARCHAR(150) NOT NULL,
    kategori VARCHAR(50) NOT NULL DEFAULT 'Kaos',
    harga NUMERIC(12, 2) NOT NULL DEFAULT 0,
    stok INT NOT NULL DEFAULT 10,
    ukuran VARCHAR(20) DEFAULT 'All Size',
    deskripsi TEXT,
    gambar TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";
$pdo->exec($sqlProducts);
echo "\033[32m[Sukses]\033[0m Tabel 'products' (Toko Baju) berhasil dibuat/dimigrasikan di PostgreSQL!\n";

// Tambahkan sample catalog produk Toko Baju Ghani jika kosong
$stmtProd = $pdo->query("SELECT COUNT(*) FROM products");
if ($stmtProd->fetchColumn() == 0) {
    $insertProd = $pdo->prepare("INSERT INTO products (nama_produk, kategori, harga, stok, ukuran, deskripsi, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $products = [
        ['Kaos Oversize Streetwear Ghani Edition', 'Kaos', 125000, 25, 'L', 'Bahan katun combed 24s premium sablon discharge tahan lama.', 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?w=600&auto=format&fit=crop&q=80'],
        ['Kemeja Flanel Casual Vintage', 'Kemeja', 185000, 18, 'XL', 'Motif tartan klasik bahan wol katun tebal dan nyaman.', 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600&auto=format&fit=crop&q=80'],
        ['Hoodie Zipper Minimalist Charcoal', 'Outerwear', 230000, 15, 'All Size', 'Cotton fleece 300gsm dengan ritsleting YKK presisi tinggi.', 'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=600&auto=format&fit=crop&q=80'],
        ['Celana Chino Slim Fit Dark Khaki', 'Celana', 175000, 20, '32', 'Katun twill stretch premium, jahitan bar-tack kuat dan rapi.', 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600&auto=format&fit=crop&q=80'],
        ['Kemeja Batik Modern Slimfit Eksklusif', 'Batik', 210000, 12, 'L', 'Batik cap katun primisima berlapis furing nyaman untuk acara formal.', 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?w=600&auto=format&fit=crop&q=80'],
        ['Jaket Denim Vintage Classic Blue', 'Outerwear', 275000, 10, 'XL', 'Denim 14oz bio-wash vintage dengan kancing metal tembaga.', 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=600&auto=format&fit=crop&q=80'],
    ];

    foreach ($products as $p) {
        $insertProd->execute($p);
    }
    echo "\033[32m[Sukses]\033[0m Data seeder katalog Toko Baju Ghani berhasil ditambahkan.\n";
}
