<?php
// Insert Data Step by Step
// File: insert_data_step_by_step.php

echo "🔧 Inserting Data Step by Step...\n\n";

// Database connection settings
$host = 'ballast.proxy.rlwy.net';
$port = 15609;
$username = 'root';
$password = 'bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv';
$database = 'railway';

try {
    // Create connection
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connected to Railway MySQL successfully!\n\n";

    // Step 1: Insert tbl_login
    echo "📊 Step 1: Inserting tbl_login...\n";
    
    // Clear table first
    $pdo->exec("TRUNCATE TABLE tbl_login");
    $pdo->exec("ALTER TABLE tbl_login AUTO_INCREMENT = 1");
    
    $sql = "INSERT INTO tbl_login (id_login, anggota_id, user, pass, nama, email, telepon, alamat, level, tgl_bergabung, foto, jenkel, tgl_lahir, tempat_lahir) VALUES
            (1, 'AG001', 'anang', '202cb962ac59075b964b07152d234b70', 'Anang', 'anang@admin.com', '08123456789', 'Jakarta', 'Admin', '2025-01-01', 'user_1751015778.JPG', 'Laki-Laki', '1990-01-01', 'Jakarta'),
            (2, 'AG002', 'fauzan', '202cb962ac59075b964b07152d234b70', 'Fauzan', 'fauzan@petugas.com', '08123456790', 'Bandung', 'Petugas', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Bandung'),
            (3, 'AG003', 'jaki', '202cb962ac59075b964b07152d234b70', 'Jaki', 'jaki@petugas.com', '08123456791', 'Surabaya', 'Petugas', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Surabaya'),
            (4, 'AG004', 'erwin', '202cb962ac59075b964b07152d234b70', 'Erwin', 'erwin@anggota.com', '08123456792', 'Medan', 'Anggota', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Medan'),
            (5, 'AG005', 'faima', '202cb962ac59075b964b07152d234b70', 'Faima', 'faima@anggota.com', '08123456793', 'Semarang', 'Anggota', '2025-01-01', '', 'Perempuan', '1990-01-01', 'Semarang'),
            (6, 'AG006', 'Bagus', '202cb962ac59075b964b07152d234b70', 'Bagus', 'bagus@petugas.com', '08123456794', 'Yogyakarta', 'Petugas', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Yogyakarta')";
    
    $pdo->exec($sql);
    echo "   ✅ Inserted 6 records into tbl_login\n";

    // Step 2: Insert tbl_pinjam
    echo "\n📊 Step 2: Inserting tbl_pinjam...\n";
    
    // Clear table first
    $pdo->exec("TRUNCATE TABLE tbl_pinjam");
    $pdo->exec("ALTER TABLE tbl_pinjam AUTO_INCREMENT = 1");
    
    $sql = "INSERT INTO tbl_pinjam (pinjam_id, anggota_id, buku_id, tgl_pinjam, lama_pinjam, tgl_balik, tgl_kembali, status, petugas_id) VALUES
            ('PJ001', 'AG001', 'BK001', '2025-08-01', 7, '2025-08-08', '2025-08-08', 'Dipinjam', 2),
            ('PJ002', 'AG002', 'BK009', '2025-08-02', 7, '2025-08-09', '2025-08-09', 'Dipinjam', 3)";
    
    $pdo->exec($sql);
    echo "   ✅ Inserted 2 records into tbl_pinjam\n";

    // Step 3: Insert tbl_denda
    echo "\n📊 Step 3: Inserting tbl_denda...\n";
    
    // Clear table first
    $pdo->exec("TRUNCATE TABLE tbl_denda");
    $pdo->exec("ALTER TABLE tbl_denda AUTO_INCREMENT = 1");
    
    $sql = "INSERT INTO tbl_denda (pinjam_id, denda, lama_waktu, tgl_denda, tgl_pinjam, tgl_kembali, status, petugas_id) VALUES
            ('PJ001', '4000', 7, '2025-08-01', '2025-08-01', '2025-08-08', 'Belum Lunas', 2)";
    
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record into tbl_denda\n";

    // Step 4: Insert sample data for other tables
    echo "\n📊 Step 4: Inserting sample data for other tables...\n";
    
    // tbl_buku_hilang
    $pdo->exec("TRUNCATE TABLE tbl_buku_hilang");
    $sql = "INSERT INTO tbl_buku_hilang (buku_id, anggota_id, keterangan, tgl_hilang, petugas_id, pinjam_id) VALUES
            ('BK001', 'AG001', 'Buku hilang saat dipinjam', '2025-08-03 22:15:23', 2, 'PJ001')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record into tbl_buku_hilang\n";

    // tbl_buku_rusak
    $pdo->exec("TRUNCATE TABLE tbl_buku_rusak");
    $sql = "INSERT INTO tbl_buku_rusak (buku_id, anggota_id, keterangan, tgl_rusak, petugas_id, pinjam_id) VALUES
            ('BK009', 'AG002', 'Buku rusak saat dipinjam', '2025-08-04 10:30:00', 3, 'PJ002')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record into tbl_buku_rusak\n";

    // tbl_history
    $pdo->exec("TRUNCATE TABLE tbl_history");
    $sql = "INSERT INTO tbl_history (pinjam_id, anggota_id, buku_id, tgl_pinjam, tgl_kembali, denda, status, petugas_id, tgl_history) VALUES
            ('PJ001', 'AG001', 'BK001', '2025-08-01', '2025-08-08', 4000, 'Lunas', 2, '2025-08-05 15:00:00')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record into tbl_history\n";

    // tbl_pengembalian
    $pdo->exec("TRUNCATE TABLE tbl_pengembalian");
    $sql = "INSERT INTO tbl_pengembalian (pinjam_id, anggota_id, buku_id, tgl_pinjam, tgl_kembali, tgl_dikembalikan, denda, status, petugas_id) VALUES
            ('PJ001', 'AG001', 'BK001', '2025-08-01', '2025-08-08', '2025-08-05', 4000, 'Lunas', 2)";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record into tbl_pengembalian\n";

    // Verify final data
    echo "\n🔍 Verifying final data...\n";
    $tables = ['tbl_login', 'tbl_pinjam', 'tbl_denda', 'tbl_buku_hilang', 'tbl_buku_rusak', 'tbl_history', 'tbl_pengembalian'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "📋 Table $table: $count records\n";
        } catch (PDOException $e) {
            echo "❌ Error checking $table: " . $e->getMessage() . "\n";
        }
    }

    echo "\n🎯 Data insertion completed successfully!\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 