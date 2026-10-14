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

// Buat tabel mahasiswa
$sql = "CREATE TABLE IF NOT EXISTS mahasiswa (
    id SERIAL PRIMARY KEY,
    nim VARCHAR(20) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    prodi VARCHAR(100) NOT NULL DEFAULT 'Sistem Informasi',
    status VARCHAR(50) NOT NULL DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);";

$pdo->exec($sql);
echo "\033[32m[Sukses]\033[0m Tabel 'mahasiswa' berhasil dibuat/dimigrasikan di PostgreSQL!\n";

// Tambahkan sample data jika masih kosong
$stmt = $pdo->query("SELECT COUNT(*) FROM mahasiswa");
if ($stmt->fetchColumn() == 0) {
    $insert = $pdo->prepare("INSERT INTO mahasiswa (nim, nama, prodi, status) VALUES (?, ?, ?, ?)");
    $insert->execute(['24EP10007', 'Rizqi Ghani Adinata', 'Sistem Informasi', 'Aktif']);
    $insert->execute(['24EP10008', 'Ahmad Fauzi', 'Sistem Informasi', 'Aktif']);
    $insert->execute(['24EP10009', 'Siti Nurhaliza', 'Sistem Informasi', 'Aktif']);
    echo "\033[32m[Sukses]\033[0m Data awal (seeder) mahasiswa berhasil ditambahkan.\n";
}
