<?php
/**
 * Script untuk memperbaiki struktur database Railway
 * Menambahkan kolom yang hilang dan memastikan struktur tabel sama dengan perpus_new.sql
 */

// Konfigurasi database Railway (sesuaikan dengan kredensial Anda)
$host = 'ballast.proxy.rlwy.net'; // Host database Railway
$username = 'root'; // Username database
$password = 'bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv'; // Password database
$database = 'railway'; // Nama database
$port = 15609; // Port database

try {
    // Koneksi ke database
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Berhasil terhubung ke database Railway\n";
    
    // 1. Periksa struktur tabel tbl_history
    echo "\n🔍 Memeriksa struktur tabel tbl_history...\n";
    $stmt = $pdo->query("DESCRIBE tbl_history");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Kolom yang ada di tbl_history:\n";
    foreach ($columns as $column) {
        echo "  - $column\n";
    }
    
    // 2. Periksa apakah kolom kode_transaksi sudah ada
    if (!in_array('kode_transaksi', $columns)) {
        echo "\n❌ Kolom 'kode_transaksi' tidak ditemukan di tbl_history\n";
        echo "🔧 Menambahkan kolom 'kode_transaksi'...\n";
        
        $sql = "ALTER TABLE tbl_history ADD COLUMN kode_transaksi VARCHAR(6) DEFAULT NULL AFTER tipe_transaksi";
        $pdo->exec($sql);
        echo "✅ Kolom 'kode_transaksi' berhasil ditambahkan\n";
    } else {
        echo "\n✅ Kolom 'kode_transaksi' sudah ada\n";
    }
    
    // 3. Periksa struktur tabel tbl_denda
    echo "\n🔍 Memeriksa struktur tabel tbl_denda...\n";
    $stmt = $pdo->query("DESCRIBE tbl_denda");
    $denda_columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Kolom yang ada di tbl_denda:\n";
    foreach ($denda_columns as $column) {
        echo "  - $column\n";
    }
    
    // 4. Periksa apakah tabel tbl_denda memiliki data
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM tbl_denda");
    $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    echo "\n📊 Jumlah data di tbl_denda: $count\n";
    
    if ($count == 0) {
        echo "⚠️  Tabel tbl_denda kosong, perlu diisi dengan data\n";
    }
    
    // 5. Periksa struktur tabel lainnya yang penting
    $important_tables = ['tbl_buku', 'tbl_login', 'tbl_pinjam', 'tbl_kategori', 'tbl_rak'];
    
    foreach ($important_tables as $table) {
        echo "\n🔍 Memeriksa tabel $table...\n";
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            echo "  📊 Jumlah data: $count\n";
            
            if ($count == 0) {
                echo "  ⚠️  Tabel $table kosong\n";
            }
        } catch (Exception $e) {
            echo "  ❌ Error: " . $e->getMessage() . "\n";
        }
    }
    
    // 6. Test query yang bermasalah
    echo "\n🧪 Testing query yang bermasalah...\n";
    try {
        $query = "
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
        
        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "✅ Query berhasil dijalankan\n";
        echo "📊 Hasil query (5 baris pertama):\n";
        foreach ($results as $i => $row) {
            echo "  Baris " . ($i + 1) . ": " . ($row['tipe_transaksi'] ?? 'N/A') . " - " . ($row['kode_transaksi'] ?? 'N/A') . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Query masih error: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Pemeriksaan database selesai!\n";

    // 7. Langsung perbaiki database jika ada masalah
    echo "\n🔧 Memperbaiki database secara otomatis...\n";
    
    // Tambahkan kolom kode_transaksi jika belum ada
    if (!in_array('kode_transaksi', $columns)) {
        echo "➕ Menambahkan kolom 'kode_transaksi' ke tbl_history...\n";
        $sql = "ALTER TABLE tbl_history ADD COLUMN kode_transaksi VARCHAR(6) DEFAULT NULL AFTER tipe_transaksi";
        $pdo->exec($sql);
        echo "✅ Kolom 'kode_transaksi' berhasil ditambahkan\n";
    }
    
    // Tambahkan index untuk performa
    echo "🔍 Menambahkan index untuk performa...\n";
    try {
        $pdo->exec("ALTER TABLE tbl_history ADD INDEX IF NOT EXISTS idx_kode_transaksi (kode_transaksi)");
        $pdo->exec("ALTER TABLE tbl_history ADD INDEX IF NOT EXISTS idx_tanggal (tanggal)");
        echo "✅ Index berhasil ditambahkan\n";
    } catch (Exception $e) {
        echo "ℹ️  Index sudah ada atau tidak bisa ditambahkan: " . $e->getMessage() . "\n";
    }
    
    // Test query yang bermasalah
    echo "\n🧪 Testing query yang bermasalah...\n";
    try {
        $query = "
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

        $stmt = $pdo->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "✅ Query berhasil dijalankan!\n";
        echo "📊 Hasil query (5 baris pertama):\n";
        foreach ($results as $i => $row) {
            echo "  Baris " . ($i + 1) . ": " . ($row['tipe_transaksi'] ?? 'N/A') . " - " . ($row['kode_transaksi'] ?? 'N/A') . "\n";
        }
        
        echo "\n🎉 Database berhasil diperbaiki! Query yang bermasalah sekarang bisa berjalan.\n";

    } catch (Exception $e) {
        echo "❌ Query masih error: " . $e->getMessage() . "\n";
        echo "🔧 Mencoba perbaikan tambahan...\n";
        
        // Coba buat tabel jika tidak ada
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS tbl_denda (
                id_denda int(6) NOT NULL AUTO_INCREMENT,
                pinjam_id varchar(6) NOT NULL,
                denda int(11) NOT NULL,
                lama_waktu int(2) NOT NULL,
                tgl_denda date NOT NULL,
                PRIMARY KEY (id_denda)
            )");
            echo "✅ Tabel tbl_denda berhasil dibuat\n";
        } catch (Exception $e2) {
            echo "❌ Gagal membuat tabel tbl_denda: " . $e2->getMessage() . "\n";
        }
    }

} catch (PDOException $e) {
    echo "❌ Error koneksi database: " . $e->getMessage() . "\n";
    echo "\n💡 Pastikan:\n";
    echo "  1. Kredensial database Railway sudah benar\n";
    echo "  2. Database 'perpus_new' sudah dibuat\n";
    echo "  3. Host database dapat diakses\n";
}
?> 