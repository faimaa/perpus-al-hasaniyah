<?php
// Populate Complete Data from perpus_new.sql
// File: populate_complete_data.php

echo "🔧 Populating Complete Data in Railway Database...\n\n";

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

    // Clear existing data first
    echo "🧹 Clearing existing data...\n";
    $tables = ['tbl_buku_hilang', 'tbl_buku_rusak', 'tbl_history', 'tbl_pengembalian', 'tbl_denda', 'tbl_pinjam', 'tbl_buku', 'tbl_kategori', 'tbl_rak', 'tbl_biaya_denda'];
    
    foreach ($tables as $table) {
        try {
            $pdo->exec("DELETE FROM $table");
            echo "   ✅ Cleared $table\n";
        } catch (PDOException $e) {
            echo "   ❌ Error clearing $table: " . $e->getMessage() . "\n";
        }
    }

    // Reset auto increment
    foreach ($tables as $table) {
        try {
            $pdo->exec("ALTER TABLE $table AUTO_INCREMENT = 1");
        } catch (PDOException $e) {
            // Ignore errors for tables without auto increment
        }
    }

    echo "\n📝 Inserting data...\n";

    // Insert tbl_biaya_denda
    echo "   📊 Inserting tbl_biaya_denda...\n";
    $sql = "INSERT INTO tbl_biaya_denda (id_biaya_denda, harga_denda, stat, tgl_tetap) VALUES 
            (12, 4000, 'Aktif', '2025-07-23'),
            (13, 6000, 'Tidak Aktif', '2025-07-23')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 2 records\n";

    // Insert tbl_kategori
    echo "   📊 Inserting tbl_kategori...\n";
    $sql = "INSERT INTO tbl_kategori (id_kategori, nama_kategori) VALUES 
            (1, 'Pendidikan'),
            (2, 'Novel'),
            (3, 'Teknologi'),
            (4, 'Agama'),
            (5, 'Sejarah')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 5 records\n";

    // Insert tbl_rak
    echo "   📊 Inserting tbl_rak...\n";
    $sql = "INSERT INTO tbl_rak (id_rak, nama_rak) VALUES 
            (1, 'Rak A'),
            (2, 'Rak B'),
            (3, 'Rak C')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 3 records\n";

    // Insert tbl_buku
    echo "   📊 Inserting tbl_buku...\n";
    $sql = "INSERT INTO tbl_buku (id_buku, buku_id, id_kategori, id_rak, sampul, isbn, judul_buku, penerbit, pengarang, thn_buku, isi, jml, tgl_masuk, status) VALUES 
            (9, 'BK009', 3, 1, '0cdd08c673a8a92ac2b6c99faf8d1e0f.jpg', '734830923', 'Matematika', 'Pusat Kurikulum dan ', 'Muhammad Nuh', '2011-06-29', '', 21, '2025-07-29', 'Tersedia'),
            (10, 'BK001', 4, 1, '4fd8c70132927b607b6773e7b13bbf47.jpg', '231231231314', 'bahasa indonesia', 'Pusat Kurikulum dan ', 'Lukman Surya Saputra', '2023-07-27', '', 21, '2025-07-29', 'Tersedia')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 2 records\n";

                    // Insert tbl_login
                echo "   📊 Inserting tbl_login...\n";
                $sql = "INSERT INTO tbl_login (id_login, anggota_id, user, pass, nama, email, telepon, alamat, level, tgl_bergabung, foto, jenkel, tgl_lahir, tempat_lahir) VALUES
                        (1, 'AG001', 'anang', '202cb962ac59075b964b07152d234b70', 'Anang', 'anang@admin.com', '08123456789', 'Jakarta', 'Admin', '2025-01-01', 'user_1751015778.JPG', 'Laki-Laki', '1990-01-01', 'Jakarta'),
                        (2, 'AG002', 'fauzan', '202cb962ac59075b964b07152d234b70', 'Fauzan', 'fauzan@petugas.com', '08123456790', 'Bandung', 'Petugas', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Bandung'),
                        (3, 'AG003', 'jaki', '202cb962ac59075b964b07152d234b70', 'Jaki', 'jaki@petugas.com', '08123456791', 'Surabaya', 'Petugas', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Surabaya'),
                        (4, 'AG004', 'erwin', '202cb962ac59075b964b07152d234b70', 'Erwin', 'erwin@anggota.com', '08123456792', 'Medan', 'Anggota', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Medan'),
                        (5, 'AG005', 'faima', '202cb962ac59075b964b07152d234b70', 'Faima', 'faima@anggota.com', '08123456793', 'Semarang', 'Anggota', '2025-01-01', '', 'Perempuan', '1990-01-01', 'Semarang'),
                        (6, 'AG006', 'Bagus', '202cb962ac59075b964b07152d234b70', 'Bagus', 'bagus@petugas.com', '08123456794', 'Yogyakarta', 'Petugas', '2025-01-01', '', 'Laki-Laki', '1990-01-01', 'Yogyakarta')";
                $pdo->exec($sql);
                echo "   ✅ Inserted 6 records\n";

                    // Insert sample tbl_pinjam
                echo "   📊 Inserting tbl_pinjam...\n";
                $sql = "INSERT INTO tbl_pinjam (pinjam_id, anggota_id, buku_id, tgl_pinjam, lama_pinjam, tgl_balik, tgl_kembali, status, petugas_id) VALUES
                        ('PJ001', 'AG001', 'BK001', '2025-08-01', 7, '2025-08-08', '2025-08-08', 'Dipinjam', 2),
                        ('PJ002', 'AG002', 'BK009', '2025-08-02', 7, '2025-08-09', '2025-08-09', 'Dipinjam', 3)";
                $pdo->exec($sql);
                echo "   ✅ Inserted 2 records\n";

    // Insert sample tbl_denda
    echo "   📊 Inserting tbl_denda...\n";
    $sql = "INSERT INTO tbl_denda (pinjam_id, anggota_id, buku_id, tgl_pinjam, tgl_kembali, denda, status, petugas_id) VALUES 
            ('PJ001', 'AG001', 'BK001', '2025-08-01', '2025-08-08', 4000, 'Belum Lunas', 2)";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record\n";

    // Insert sample tbl_buku_hilang
    echo "   📊 Inserting tbl_buku_hilang...\n";
    $sql = "INSERT INTO tbl_buku_hilang (buku_id, anggota_id, keterangan, tgl_hilang, petugas_id, pinjam_id) VALUES 
            ('BK001', 'AG001', 'Buku hilang saat dipinjam', '2025-08-03 22:15:23', 2, 'PJ001')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record\n";

    // Insert sample tbl_buku_rusak
    echo "   📊 Inserting tbl_buku_rusak...\n";
    $sql = "INSERT INTO tbl_buku_rusak (buku_id, anggota_id, keterangan, tgl_rusak, petugas_id, pinjam_id) VALUES 
            ('BK009', 'AG002', 'Buku rusak saat dipinjam', '2025-08-04 10:30:00', 3, 'PJ002')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record\n";

    // Insert sample tbl_history
    echo "   📊 Inserting tbl_history...\n";
    $sql = "INSERT INTO tbl_history (pinjam_id, anggota_id, buku_id, tgl_pinjam, tgl_kembali, denda, status, petugas_id, tgl_history) VALUES 
            ('PJ001', 'AG001', 'BK001', '2025-08-01', '2025-08-08', 4000, 'Lunas', 2, '2025-08-05 15:00:00')";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record\n";

    // Insert sample tbl_pengembalian
    echo "   📊 Inserting tbl_pengembalian...\n";
    $sql = "INSERT INTO tbl_pengembalian (pinjam_id, anggota_id, buku_id, tgl_pinjam, tgl_kembali, tgl_dikembalikan, denda, status, petugas_id) VALUES 
            ('PJ001', 'AG001', 'BK001', '2025-08-01', '2025-08-08', '2025-08-05', 4000, 'Lunas', 2)";
    $pdo->exec($sql);
    echo "   ✅ Inserted 1 record\n";

    // Verify data
    echo "\n🔍 Verifying data...\n";
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "📋 Table $table: $count records\n";
        } catch (PDOException $e) {
            echo "❌ Error checking $table: " . $e->getMessage() . "\n";
        }
    }

    echo "\n🎯 Data population completed!\n";

} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 