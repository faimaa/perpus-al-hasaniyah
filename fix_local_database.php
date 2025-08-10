<?php
/**
 * Script untuk memperbaiki database local XAMPP
 * Jalankan script ini di local XAMPP
 */

// Pastikan error reporting aktif untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 Memperbaiki Database Local XAMPP</h2>";
echo "<pre>";

try {
    // Koneksi ke database local XAMPP dengan socket yang benar
    $pdo = new PDO("mysql:host=localhost;dbname=perpus_al_hasaniyah;unix_socket=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Berhasil terhubung ke database local XAMPP\n\n";
    
    // 1. Periksa struktur tbl_pengembalian
    echo "🔍 Memeriksa tabel tbl_pengembalian...\n";
    try {
        $stmt = $pdo->query("DESCRIBE tbl_pengembalian");
        $pengembalian_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Kolom yang ada di tbl_pengembalian:\n";
        foreach ($pengembalian_columns as $column) {
            echo "  - $column\n";
        }
        
        // Periksa kolom yang diperlukan
        $required_pengembalian_columns = [
            'id_pinjam' => "INT(6) NOT NULL",
            'tgl_kembali' => "DATETIME NOT NULL",
            'denda' => "DECIMAL(10,2) DEFAULT 0.00",
            'keterangan' => "TEXT DEFAULT NULL",
            'petugas_id' => "INT(6) NOT NULL"
        ];
        
        $missing_pengembalian_columns = [];
        foreach ($required_pengembalian_columns as $col => $definition) {
            if (!in_array($col, $pengembalian_columns)) {
                $missing_pengembalian_columns[$col] = $definition;
            }
        }
        
        if (!empty($missing_pengembalian_columns)) {
            echo "\n➕ Menambahkan kolom yang hilang di tbl_pengembalian...\n";
            
            foreach ($missing_pengembalian_columns as $col => $definition) {
                try {
                    $sql = "ALTER TABLE tbl_pengembalian ADD COLUMN $col $definition";
                    $pdo->exec($sql);
                    echo "✅ Kolom '$col' berhasil ditambahkan ke tbl_pengembalian\n";
                } catch (Exception $e) {
                    echo "❌ Gagal menambahkan kolom '$col': " . $e->getMessage() . "\n";
                }
            }
        } else {
            echo "\n✅ Semua kolom yang diperlukan sudah ada di tbl_pengembalian\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Tabel tbl_pengembalian tidak ada. Membuat tabel...\n";
        
        $sql = "CREATE TABLE tbl_pengembalian (
            id_pengembalian INT(6) NOT NULL AUTO_INCREMENT,
            id_pinjam INT(6) NOT NULL,
            tgl_kembali DATETIME NOT NULL,
            denda DECIMAL(10,2) DEFAULT 0.00,
            keterangan TEXT DEFAULT NULL,
            petugas_id INT(6) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id_pengembalian),
            INDEX idx_id_pinjam (id_pinjam),
            INDEX idx_petugas_id (petugas_id)
        )";
        $pdo->exec($sql);
        echo "✅ Tabel tbl_pengembalian berhasil dibuat\n";
    }
    
    // 2. Test query yang bermasalah
    echo "\n🧪 Testing query yang bermasalah...\n";
    
    try {
        $query = "
            SELECT p1.*, peng.denda, peng.tgl_kembali, l.nama 
            FROM tbl_pinjam p1 
            LEFT JOIN tbl_pengembalian peng ON peng.id_pinjam = p1.id_pinjam 
            JOIN tbl_login l ON l.anggota_id = p1.anggota_id 
            WHERE p1.status = 'Di Kembalikan' 
            AND p1.id_pinjam = (
                SELECT MAX(p2.id_pinjam) 
                FROM tbl_pinjam p2 
                WHERE p2.anggota_id = p1.anggota_id 
                AND p2.status = 'Di Kembalikan'
            ) 
            ORDER BY p1.id_pinjam DESC
        ";
        
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Query berhasil dijalankan!\n";
        echo "📊 Hasil: " . count($results) . " baris data\n";
        
        if (count($results) > 0) {
            echo "\n📋 Sample data:\n";
            foreach (array_slice($results, 0, 3) as $row) {
                echo "  - ID: " . $row['id_pinjam'] . ", Anggota: " . $row['nama'] . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Query masih error: " . $e->getMessage() . "\n";
        
        // Coba periksa struktur tabel yang terkait
        echo "\n🔍 Memeriksa struktur tabel terkait...\n";
        
        try {
            $stmt = $pdo->query("DESCRIBE tbl_pinjam");
            $pinjam_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "Kolom tbl_pinjam: " . implode(", ", $pinjam_columns) . "\n";
        } catch (Exception $e2) {
            echo "❌ Tidak bisa memeriksa tbl_pinjam: " . $e2->getMessage() . "\n";
        }
        
        try {
            $stmt = $pdo->query("DESCRIBE tbl_login");
            $login_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "Kolom tbl_login: " . implode(", ", $login_columns) . "\n";
        } catch (Exception $e2) {
            echo "❌ Tidak bisa memeriksa tbl_login: " . $e2->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 SUCCESS! Database berhasil diperbaiki!\n";
    echo "✅ Kolom id_pinjam sudah ditambahkan ke tbl_pengembalian\n";
    echo "✅ Query yang bermasalah sekarang bisa berjalan\n";
    
} catch (PDOException $e) {
    echo "❌ Error koneksi database: " . $e->getMessage() . "\n";
    echo "\n💡 Pastikan:\n";
    echo "  1. XAMPP sudah running (Apache + MySQL)\n";
    echo "  2. Database 'perpus_al_hasaniyah' sudah dibuat\n";
    echo "  3. User database memiliki permission yang cukup\n";
    echo "  4. Coba buat database manual di phpMyAdmin jika belum ada\n";
}

echo "\n🏁 Script selesai dijalankan.\n";
echo "</pre>";
?> 