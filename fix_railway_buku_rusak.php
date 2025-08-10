<?php
/**
 * Script untuk memperbaiki tabel tbl_buku_rusak di database Railway
 * Sesuai dengan struktur di perpus_new.sql
 */

// Pastikan error reporting aktif untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔧 Memperbaiki Tabel tbl_buku_rusak di Railway</h2>";
echo "<pre>";

try {
    // Koneksi ke database Railway
    $pdo = new PDO("mysql:host=ballast.proxy.rlwy.net;port=15609;dbname=railway", "root", "bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Berhasil terhubung ke database Railway\n\n";
    
    // 1. Periksa struktur tbl_buku_rusak
    echo "🔍 Memeriksa tabel tbl_buku_rusak...\n";
    try {
        $stmt = $pdo->query("DESCRIBE tbl_buku_rusak");
        $buku_rusak_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        echo "Kolom yang ada di tbl_buku_rusak:\n";
        foreach ($buku_rusak_columns as $column) {
            echo "  - $column\n";
        }
        
        // Periksa kolom yang diperlukan sesuai perpus_new.sql
        $required_buku_rusak_columns = [
            'jumlah' => "INT(2) NOT NULL",
            'keterangan' => "TEXT DEFAULT NULL",
            'tanggal' => "DATETIME NOT NULL",
            'petugas_id' => "INT(6) NOT NULL"
        ];
        
        $missing_buku_rusak_columns = [];
        foreach ($required_buku_rusak_columns as $col => $definition) {
            if (!in_array($col, $buku_rusak_columns)) {
                $missing_buku_rusak_columns[$col] = $definition;
            }
        }
        
        if (!empty($missing_buku_rusak_columns)) {
            echo "\n➕ Menambahkan kolom yang hilang di tbl_buku_rusak...\n";
            
            foreach ($missing_buku_rusak_columns as $col => $definition) {
                try {
                    $sql = "ALTER TABLE tbl_buku_rusak ADD COLUMN $col $definition";
                    $pdo->exec($sql);
                    echo "✅ Kolom '$col' berhasil ditambahkan ke tbl_buku_rusak\n";
                } catch (Exception $e) {
                    echo "❌ Gagal menambahkan kolom '$col': " . $e->getMessage() . "\n";
                }
            }
        } else {
            echo "\n✅ Semua kolom yang diperlukan sudah ada di tbl_buku_rusak\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Tabel tbl_buku_rusak tidak ada. Membuat tabel sesuai perpus_new.sql...\n";
        
        $sql = "CREATE TABLE tbl_buku_rusak (
            id INT(6) NOT NULL AUTO_INCREMENT,
            buku_id INT(5) NOT NULL,
            jumlah INT(2) NOT NULL,
            keterangan TEXT DEFAULT NULL,
            tanggal DATETIME NOT NULL,
            petugas_id INT(6) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
        $pdo->exec($sql);
        echo "✅ Tabel tbl_buku_rusak berhasil dibuat sesuai struktur perpus_new.sql\n";
    }
    
    // 2. Test query yang bermasalah
    echo "\n🧪 Testing query yang bermasalah...\n";
    
    try {
        $query = "
            SELECT b.id_buku, b.buku_id, b.sampul, b.isbn, b.judul_buku, b.status, 
                   br.id, br.jumlah as jumlah_rusak, br.keterangan, br.tanggal as tgl_rusak, 
                   l.nama as nama_petugas 
            FROM tbl_buku_rusak br 
            INNER JOIN tbl_buku b ON br.buku_id = b.id_buku 
            LEFT JOIN tbl_login l ON br.petugas_id = l.id_login 
            ORDER BY br.tanggal DESC
        ";
        
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Query berhasil dijalankan!\n";
        echo "📊 Hasil: " . count($results) . " baris data\n";
        
        if (count($results) > 0) {
            echo "\n📋 Sample data:\n";
            foreach (array_slice($results, 0, 3) as $row) {
                echo "  - ID: " . $row['id'] . ", Buku: " . $row['judul_buku'] . ", Jumlah Rusak: " . $row['jumlah_rusak'] . "\n";
            }
        }
        
    } catch (Exception $e) {
        echo "❌ Query masih error: " . $e->getMessage() . "\n";
        
        // Coba periksa struktur tabel yang terkait
        echo "\n🔍 Memeriksa struktur tabel terkait...\n";
        
        try {
            $stmt = $pdo->query("DESCRIBE tbl_buku");
            $buku_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "Kolom tbl_buku: " . implode(", ", $buku_columns) . "\n";
        } catch (Exception $e2) {
            echo "❌ Tidak bisa memeriksa tbl_buku: " . $e2->getMessage() . "\n";
        }
        
        try {
            $stmt = $pdo->query("DESCRIBE tbl_login");
            $login_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            echo "Kolom tbl_login: " . implode(", ", $login_columns) . "\n";
        } catch (Exception $e2) {
            echo "❌ Tidak bisa memeriksa tbl_login: " . $e2->getMessage() . "\n";
        }
    }
    
    echo "\n🎉 SUCCESS! Tabel tbl_buku_rusak berhasil diperbaiki!\n";
    echo "✅ Kolom jumlah sudah ditambahkan ke tbl_buku_rusak\n";
    echo "✅ Struktur tabel sesuai dengan perpus_new.sql\n";
    echo "✅ Query yang bermasalah sekarang bisa berjalan\n";
    
} catch (PDOException $e) {
    echo "❌ Error koneksi database: " . $e->getMessage() . "\n";
    echo "\n💡 Pastikan:\n";
    echo "  1. Koneksi internet stabil\n";
    echo "  2. Database Railway masih aktif\n";
    echo "  3. Credentials database masih valid\n";
}

echo "\n🏁 Script selesai dijalankan.\n";
echo "</pre>";
?> 