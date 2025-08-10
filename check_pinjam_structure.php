<?php
// Check tbl_pinjam structure on Railway
// File: check_pinjam_structure.php

echo "🔍 Checking tbl_pinjam structure on Railway...\n\n";

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

    // Check tbl_pinjam structure
    echo "📋 Table tbl_pinjam structure:\n";
    $stmt = $pdo->query("DESCRIBE tbl_pinjam");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $col) {
        echo "   - {$col['Field']}: {$col['Type']}";
        if ($col['Null'] == 'NO') echo " NOT NULL";
        if ($col['Default'] !== null) echo " DEFAULT '{$col['Default']}'";
        if ($col['Key'] == 'PRI') echo " PRIMARY KEY";
        if ($col['Extra'] == 'auto_increment') echo " AUTO_INCREMENT";
        echo "\n";
    }

} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 