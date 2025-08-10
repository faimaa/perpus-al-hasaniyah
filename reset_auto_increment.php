<?php
// Reset Auto Increment for All Tables
// File: reset_auto_increment.php

echo "🔧 Resetting Auto Increment for All Tables...\n\n";

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

    // List of tables to reset
    $tables = ['tbl_buku_hilang', 'tbl_buku_rusak', 'tbl_history', 'tbl_pengembalian', 'tbl_denda', 'tbl_pinjam', 'tbl_buku', 'tbl_kategori', 'tbl_rak', 'tbl_biaya_denda', 'tbl_login'];

    foreach ($tables as $table) {
        try {
            // Check if table has auto increment
            $stmt = $pdo->query("SHOW CREATE TABLE $table");
            $createTable = $stmt->fetch(PDO::FETCH_ASSOC)['Create Table'];
            
            if (strpos($createTable, 'AUTO_INCREMENT') !== false) {
                $pdo->exec("ALTER TABLE $table AUTO_INCREMENT = 1");
                echo "   ✅ Reset AUTO_INCREMENT for $table\n";
            } else {
                echo "   ℹ️  $table has no AUTO_INCREMENT\n";
            }
        } catch (PDOException $e) {
            echo "   ❌ Error resetting $table: " . $e->getMessage() . "\n";
        }
    }

    echo "\n🎯 Auto increment reset completed!\n";

} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 