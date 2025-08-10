<?php
// Fix Table Structures to Match perpus_new.sql
// File: fix_table_structures.php

echo "🔧 Fixing Table Structures in Railway Database...\n\n";

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

    // Fix tbl_buku structure
    echo "🔧 Fixing tbl_buku structure...\n";
    
    // Check if judul_buku column exists
    $stmt = $pdo->query("DESCRIBE tbl_buku");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    if (!in_array('judul_buku', $columnNames)) {
        echo "   📝 Adding judul_buku column...\n";
        try {
            $pdo->exec("ALTER TABLE tbl_buku ADD COLUMN judul_buku varchar(20) DEFAULT NULL AFTER isbn");
            echo "   ✅ Added judul_buku column successfully\n";
        } catch (PDOException $e) {
            echo "   ❌ Error adding judul_buku: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ✅ judul_buku column already exists\n";
    }
    
    // Check if status column exists
    if (!in_array('status', $columnNames)) {
        echo "   📝 Adding status column...\n";
        try {
            $pdo->exec("ALTER TABLE tbl_buku ADD COLUMN status enum('Tersedia','Rusak') DEFAULT 'Tersedia' AFTER jml");
            echo "   ✅ Added status column successfully\n";
        } catch (PDOException $e) {
            echo "   ❌ Error adding status: " . $e->getMessage() . "\n";
        }
    } else {
        echo "   ✅ status column already exists\n";
    }
    
    // Fix tbl_denda structure
    echo "\n🔧 Fixing tbl_denda structure...\n";
    
    $stmt = $pdo->query("DESCRIBE tbl_denda");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    // Add missing columns
    $missingColumns = [
        'tgl_pinjam' => 'date NOT NULL',
        'tgl_kembali' => 'date NOT NULL',
        'tgl_dikembalikan' => 'date DEFAULT NULL',
        'status' => "enum('Belum Lunas','Lunas') NOT NULL DEFAULT 'Belum Lunas'",
        'tgl_bayar' => 'date DEFAULT NULL',
        'petugas_id' => 'int(6) NOT NULL'
    ];
    
    foreach ($missingColumns as $column => $definition) {
        if (!in_array($column, $columnNames)) {
            echo "   📝 Adding $column column...\n";
            try {
                $pdo->exec("ALTER TABLE tbl_denda ADD COLUMN $column $definition");
                echo "   ✅ Added $column column successfully\n";
            } catch (PDOException $e) {
                echo "   ❌ Error adding $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✅ $column column already exists\n";
        }
    }
    
    // Fix tbl_pinjam structure
    echo "\n🔧 Fixing tbl_pinjam structure...\n";
    
    $stmt = $pdo->query("DESCRIBE tbl_pinjam");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    // Add missing columns
    $missingColumns = [
        'tgl_pinjam' => 'date NOT NULL',
        'tgl_kembali' => 'date NOT NULL',
        'status' => "enum('Dipinjam','Dikembalikan','Hilang','Rusak') NOT NULL DEFAULT 'Dipinjam'",
        'petugas_id' => 'int(6) NOT NULL'
    ];
    
    foreach ($missingColumns as $column => $definition) {
        if (!in_array($column, $columnNames)) {
            echo "   📝 Adding $column column...\n";
            try {
                $pdo->exec("ALTER TABLE tbl_pinjam ADD COLUMN $column $definition");
                echo "   ✅ Added $column column successfully\n";
            } catch (PDOException $e) {
                echo "   ❌ Error adding $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✅ $column column already exists\n";
        }
    }
    
    // Fix tbl_biaya_denda structure
    echo "\n🔧 Fixing tbl_biaya_denda structure...\n";
    
    $stmt = $pdo->query("DESCRIBE tbl_biaya_denda");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $columnNames = array_column($columns, 'Field');
    
    // Add missing columns
    $missingColumns = [
        'stat' => "enum('Aktif','Tidak Aktif') NOT NULL",
        'tgl_tetap' => 'date NOT NULL'
    ];
    
    foreach ($missingColumns as $column => $definition) {
        if (!in_array($column, $columnNames)) {
            echo "   📝 Adding $column column...\n";
            try {
                $pdo->exec("ALTER TABLE tbl_biaya_denda ADD COLUMN $column $definition");
                echo "   ✅ Added $column column successfully\n";
            } catch (PDOException $e) {
                echo "   ❌ Error adding $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "   ✅ $column column already exists\n";
        }
    }
    
    // Show final table structures
    echo "\n🔍 Final table structures:\n";
    $tables = ['tbl_buku', 'tbl_denda', 'tbl_pinjam', 'tbl_biaya_denda'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("DESCRIBE $table");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "📋 Table $table: " . count($columns) . " columns\n";
            foreach ($columns as $col) {
                echo "   - {$col['Field']}: {$col['Type']}\n";
            }
            echo "\n";
        } catch (PDOException $e) {
            echo "❌ Error checking $table: " . $e->getMessage() . "\n";
        }
    }

    echo "🎯 Table structure fixing completed!\n";

} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 