<?php

function getConnection(): PDO
{
    // PERBAIKAN: Titik di ujung IP 127.0.0.1 sudah dihapus
    $dsn = 'mysql:host=127.0.0.1;port=3306;dbname=blog_app_db'; 
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO($dsn, $username, $password);
        return $conn;
    } catch (PDOException $e) {
        die('database failed connection: ' . $e->getMessage());
    }
}

function getUserById(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare("SELECT id, name, email, password FROM users WHERE id = ?;");
    $stmt->execute([$id]);

    $user = $stmt->fetch();
    return $user;
}


echo "1. Membuka koneksi ke MySQL..." . PHP_EOL;
$pdo = getConnection();

echo "2. Mengambil data user ID 1..." . PHP_EOL;
$user = getUserById($pdo, 1);
print_r($user);

echo "3. KONEKSI MENGGANTUNG DIMULAI!" . PHP_EOL;
echo "Skrip ini sengaja ditahan selama 30 detik. Jangan ditutup terminal ini..." . PHP_EOL;

sleep(30); 

echo "4. Skrip selesai berjalan. Koneksi otomatis terputus." . PHP_EOL;
