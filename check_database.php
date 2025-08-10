<?php
// Check Railway Database
// File: check_database.php

echo "🔍 Checking Railway Database...\n\n";

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
    
    // Check tables
    $tables = ['tbl_login', 'tbl_kategori', 'tbl_rak', 'tbl_buku', 'tbl_biaya_denda', 'tbl_pinjam', 'tbl_denda'];
    
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "📊 Table $table: $count records\n";
            
            // Show sample data for login table
            if ($table == 'tbl_login') {
                $stmt = $pdo->query("SELECT user, level, nama FROM $table LIMIT 3");
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($users as $user) {
                    echo "   👤 {$user['user']} ({$user['level']}) - {$user['nama']}\n";
                }
            }
        } catch (PDOException $e) {
            echo "❌ Error checking $table: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎯 Database check completed!\n";
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 