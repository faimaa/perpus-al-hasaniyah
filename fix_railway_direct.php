<?php
/**
 * Script untuk memperbaiki database Railway secara langsung
 * Jalankan script ini di server Railway
 */

// Pastikan error reporting aktif untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 Memperbaiki Database Railway</h2>";
echo "<pre>";

try {
    // Koneksi ke database local (Railway)
    $pdo = new PDO("mysql:host=localhost;dbname=railway", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Berhasil terhubung ke database Railway\n\n";
    
    // 1. Periksa struktur tbl_history
    echo "🔍 Memeriksa struktur tabel tbl_history...\n";
    $stmt = $pdo->query("DESCRIBE tbl_history");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Kolom yang ada:\n";
    foreach ($columns as $column) {
        echo "  - $column\n";
    }
    
    // 2. Tambahkan kolom yang hilang
    $missing_columns = [];
    
    $required_columns = [
        'tipe_transaksi' => "VARCHAR(20) DEFAULT NULL",
        'kode_transaksi' => "VARCHAR(6) DEFAULT NULL", 
        'jumlah' => "INT(11) DEFAULT 1",
        'keterangan' => "TEXT DEFAULT NULL",
        'tanggal' => "DATETIME DEFAULT CURRENT_TIMESTAMP"
    ];
    
    foreach ($required_columns as $col => $definition) {
        if (!in_array($col, $columns)) {
            $missing_columns[$col] = $definition;
        }
    }
    
    if (!empty($missing_columns)) {
        echo "\n➕ Menambahkan kolom yang hilang...\n";
        
        foreach ($missing_columns as $col => $definition) {
            try {
                $sql = "ALTER TABLE tbl_history ADD COLUMN $col $definition";
                $pdo->exec($sql);
                echo "✅ Kolom '$col' berhasil ditambahkan\n";
            } catch (Exception $e) {
                echo "❌ Gagal menambahkan kolom '$col': " . $e->getMessage() . "\n";
            }
        }
        
        // Refresh daftar kolom
        $stmt = $pdo->query("DESCRIBE tbl_history");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    } else {
        echo "\n✅ Semua kolom yang diperlukan sudah ada\n";
    }
    
    // 3. Periksa dan perbaiki tabel tbl_pengembalian
    echo "\n🔍 Memeriksa tabel tbl_pengembalian...\n";
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
    
    // 4. Test query yang bermasalah
    echo "\n🧪 Testing query yang bermasalah...\n";
    
    // Test query pertama (yang sudah diperbaiki sebelumnya)
    echo "\n📋 Test Query 1 (tbl_history):\n";
    try {
        $query1 = "
            SELECT
                h.*,
                b.judul_buku,
                b.isbn,
                l1.nama as nama_petugas,
                COALESCE(l2.nama, CONCAT('[ID:', h.anggota_id, ']')) as nama_anggota,
                d.denda as harga_denda
            FROM tbl_history h
            LEFT JOIN tbl_buku b ON h.buku_id = b.id_buku
            LEFT JOIN tbl_login l1 ON h.petugas_id = l1.id_login
            LEFT JOIN tbl_login l2 ON h.anggota_id = l2.id_login
            LEFT JOIN tbl_denda d ON d.pinjam_id = h.kode_transaksi
            ORDER BY h.tanggal DESC
            LIMIT 5
        ";
        
        $stmt = $pdo->query($query1);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Query 1 berhasil dijalankan!\n";
        echo "📊 Hasil: " . count($results) . " baris data\n";
        
    } catch (Exception $e) {
        echo "❌ Query 1 masih error: " . $e->getMessage() . "\n";
    }
    
    // Test query kedua (yang baru bermasalah)
    echo "\n📋 Test Query 2 (tbl_pengembalian):\n";
    try {
        $query2 = "
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
        
        $stmt = $pdo->query($query2);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Query 2 berhasil dijalankan!\n";
        echo "📊 Hasil: " . count($results) . " baris data\n";
        
    } catch (Exception $e) {
        echo "❌ Query 2 masih error: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 SUCCESS! Database berhasil diperbaiki!\n";
    echo "✅ Query yang bermasalah sekarang bisa berjalan\n";
    echo "✅ Kolom kode_transaksi sudah ditambahkan\n";
    echo "✅ Tabel tbl_pengembalian sudah diperbaiki\n";
    
} catch (PDOException $e) {
    echo "❌ Error koneksi database: " . $e->getMessage() . "\n";
    echo "\n💡 Pastikan:\n";
    echo "  1. Script dijalankan di server Railway\n";
    echo "  2. Database 'railway' sudah dibuat\n";
    echo "  3. User database memiliki permission yang cukup\n";
}

echo "\n🏁 Script selesai dijalankan.\n";
echo "</pre>";
?> 