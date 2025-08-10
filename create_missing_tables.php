<?php
// Create Missing Tables in Railway Database
// File: create_missing_tables.php

echo "🔧 Creating Missing Tables in Railway Database...\n\n";

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

    // Check existing tables
    $stmt = $pdo->query("SHOW TABLES");
    $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Existing tables: " . implode(', ', $existingTables) . "\n\n";

    // Tables that should exist according to perpus_new.sql
    $requiredTables = [
        'tbl_biaya_denda',
        'tbl_buku', 
        'tbl_buku_hilang',
        'tbl_buku_rusak',
        'tbl_denda',
        'tbl_history',
        'tbl_kategori',
        'tbl_login',
        'tbl_pengembalian',
        'tbl_pinjam',
        'tbl_rak'
    ];

    // Find missing tables
    $missingTables = array_diff($requiredTables, $existingTables);
    
    if (empty($missingTables)) {
        echo "🎉 All required tables already exist!\n";
    } else {
        echo "📝 Missing tables: " . implode(', ', $missingTables) . "\n\n";
        
        // Create missing tables
        foreach ($missingTables as $table) {
            echo "🔨 Creating table: $table\n";
            
            switch ($table) {
                case 'tbl_buku_hilang':
                    $sql = "CREATE TABLE `tbl_buku_hilang` (
                        `id` int(6) NOT NULL,
                        `buku_id` varchar(5) NOT NULL,
                        `anggota_id` varchar(6) NOT NULL,
                        `keterangan` text DEFAULT NULL,
                        `tgl_hilang` datetime NOT NULL,
                        `petugas_id` int(6) NOT NULL,
                        `pinjam_id` varchar(6) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
                    break;
                    
                case 'tbl_buku_rusak':
                    $sql = "CREATE TABLE `tbl_buku_rusak` (
                        `id` int(6) NOT NULL,
                        `buku_id` varchar(5) NOT NULL,
                        `anggota_id` varchar(6) NOT NULL,
                        `keterangan` text DEFAULT NULL,
                        `tgl_rusak` datetime NOT NULL,
                        `petugas_id` int(6) NOT NULL,
                        `pinjam_id` varchar(6) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
                    break;
                    
                case 'tbl_denda':
                    $sql = "CREATE TABLE `tbl_denda` (
                        `id_denda` int(6) NOT NULL,
                        `pinjam_id` varchar(6) NOT NULL,
                        `anggota_id` varchar(6) NOT NULL,
                        `buku_id` varchar(5) NOT NULL,
                        `tgl_pinjam` date NOT NULL,
                        `tgl_kembali` date NOT NULL,
                        `tgl_dikembalikan` date DEFAULT NULL,
                        `denda` int(6) NOT NULL,
                        `status` enum('Belum Lunas','Lunas') NOT NULL DEFAULT 'Belum Lunas',
                        `tgl_bayar` date DEFAULT NULL,
                        `petugas_id` int(6) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
                    break;
                    
                case 'tbl_history':
                    $sql = "CREATE TABLE `tbl_history` (
                        `id` int(6) NOT NULL,
                        `pinjam_id` varchar(6) NOT NULL,
                        `anggota_id` varchar(6) NOT NULL,
                        `buku_id` varchar(5) NOT NULL,
                        `tgl_pinjam` date NOT NULL,
                        `tgl_kembali` date NOT NULL,
                        `tgl_dikembalikan` date DEFAULT NULL,
                        `denda` int(6) NOT NULL,
                        `status` enum('Belum Lunas','Lunas') NOT NULL DEFAULT 'Belum Lunas',
                        `tgl_bayar` date DEFAULT NULL,
                        `petugas_id` int(6) NOT NULL,
                        `tgl_history` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
                    break;
                    
                case 'tbl_pengembalian':
                    $sql = "CREATE TABLE `tbl_pengembalian` (
                        `id` int(6) NOT NULL,
                        `pinjam_id` varchar(6) NOT NULL,
                        `anggota_id` varchar(6) NOT NULL,
                        `buku_id` varchar(5) NOT NULL,
                        `tgl_pinjam` date NOT NULL,
                        `tgl_kembali` date NOT NULL,
                        `tgl_dikembalikan` date NOT NULL,
                        `denda` int(6) NOT NULL,
                        `status` enum('Belum Lunas','Lunas') NOT NULL DEFAULT 'Belum Lunas',
                        `tgl_bayar` date DEFAULT NULL,
                        `petugas_id` int(6) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
                    break;
                    
                case 'tbl_pinjam':
                    $sql = "CREATE TABLE `tbl_pinjam` (
                        `id` int(6) NOT NULL,
                        `pinjam_id` varchar(6) NOT NULL,
                        `anggota_id` varchar(6) NOT NULL,
                        `buku_id` varchar(5) NOT NULL,
                        `tgl_pinjam` date NOT NULL,
                        `tgl_kembali` date NOT NULL,
                        `status` enum('Dipinjam','Dikembalikan','Hilang','Rusak') NOT NULL DEFAULT 'Dipinjam',
                        `petugas_id` int(6) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
                    break;
                    
                default:
                    echo "   ⚠️  Table $table not configured for creation\n";
                    continue 2;
            }
            
            try {
                $pdo->exec($sql);
                echo "   ✅ Created table $table successfully\n";
            } catch (PDOException $e) {
                echo "   ❌ Error creating table $table: " . $e->getMessage() . "\n";
            }
        }
    }

    // Check table structures
    echo "\n🔍 Checking table structures...\n";
    foreach ($requiredTables as $table) {
        if (in_array($table, $existingTables)) {
            try {
                $stmt = $pdo->query("DESCRIBE $table");
                $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "📋 Table $table: " . count($columns) . " columns\n";
            } catch (PDOException $e) {
                echo "❌ Error checking $table: " . $e->getMessage() . "\n";
            }
        }
    }

    echo "\n🎯 Table creation completed!\n";

} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 