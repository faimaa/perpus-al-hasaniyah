<?php
// Script cepat untuk memperbaiki masalah tbl_history di Railway
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Quick Fix for tbl_history Table</h2>";

try {
    $host = 'ballast.proxy.rlwy.net';
    $username = 'root';
    $password = 'bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv';
    $database = 'railway';
    $port = '15609';
    
    echo "<h3>Connecting to Railway Database...</h3>";
    
    $mysqli = new mysqli($host, $username, $password, $database, $port);
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>Connection failed: " . $mysqli->error . "</p>";
        exit;
    }
    
    echo "<p style='color: green;'>Connected successfully!</p>";
    
    // Check if tbl_history exists
    echo "<h3>Checking tbl_history table...</h3>";
    $result = $mysqli->query("SHOW TABLES LIKE 'tbl_history'");
    
    if ($result->num_rows == 0) {
        echo "<p style='color: red;'>Table tbl_history does not exist. Creating it...</p>";
        
        $create_sql = "CREATE TABLE `tbl_history` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `tipe_transaksi` enum('Peminjaman','Pengembalian','Buku Rusak','Perbaikan Buku','Buku Hilang','Mengganti Buku Baru') NOT NULL,
            `kode_transaksi` varchar(50) DEFAULT NULL,
            `buku_id` int(11) DEFAULT NULL,
            `anggota_id` int(11) DEFAULT NULL,
            `petugas_id` int(11) DEFAULT NULL,
            `jumlah` int(11) NOT NULL DEFAULT 1,
            `keterangan` text DEFAULT NULL,
            `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($mysqli->query($create_sql)) {
            echo "<p style='color: green;'>Table tbl_history created successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error creating table: " . $mysqli->error . "</p>";
            exit;
        }
    } else {
        echo "<p style='color: green;'>Table tbl_history exists</p>";
    }
    
    // Check current structure
    echo "<h3>Current table structure:</h3>";
    $result = $mysqli->query("DESCRIBE tbl_history");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Fix anggota_id column if needed
    echo "<h3>Fixing anggota_id column...</h3>";
    $result = $mysqli->query("DESCRIBE tbl_history");
    $columns = array();
    while ($row = $result->fetch_assoc()) {
        $columns[$row['Field']] = $row;
    }
    
    if (isset($columns['anggota_id'])) {
        if ($columns['anggota_id']['Null'] == 'NO') {
            echo "<p style='color: orange;'>Column anggota_id does not allow NULL. Fixing...</p>";
            
            $alter_sql = "ALTER TABLE tbl_history MODIFY COLUMN `anggota_id` int(11) DEFAULT NULL";
            if ($mysqli->query($alter_sql)) {
                echo "<p style='color: green;'>Column anggota_id fixed successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error fixing column: " . $mysqli->error . "</p>";
            }
        } else {
            echo "<p style='color: green;'>Column anggota_id already allows NULL</p>";
        }
    } else {
        echo "<p style='color: red;'>Column anggota_id does not exist. Adding it...</p>";
        
        $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `anggota_id` int(11) DEFAULT NULL AFTER `buku_id`";
        if ($mysqli->query($alter_sql)) {
            echo "<p style='color: green;'>Column anggota_id added successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error adding column: " . $mysqli->error . "</p>";
        }
    }
    
    // Test insert without anggota_id
    echo "<h3>Testing insert without anggota_id...</h3>";
    $test_sql = "INSERT INTO tbl_history (tipe_transaksi, kode_transaksi, buku_id, petugas_id, jumlah, keterangan) 
                  VALUES ('Buku Rusak', 'TEST" . date('YmdHis') . "', 1, 1, 1, 'Test insert')";
    
    if ($mysqli->query($test_sql)) {
        echo "<p style='color: green;'>Test insert successful! Insert ID: " . $mysqli->insert_id . "</p>";
        
        // Clean up test data
        $delete_sql = "DELETE FROM tbl_history WHERE id = " . $mysqli->insert_id;
        if ($mysqli->query($delete_sql)) {
            echo "<p style='color: green;'>Test data cleaned up</p>";
        }
    } else {
        echo "<p style='color: red;'>Test insert failed: " . $mysqli->error . "</p>";
    }
    
    // Final structure check
    echo "<h3>Final table structure:</h3>";
    $result = $mysqli->query("DESCRIBE tbl_history");
    if ($result) {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    $mysqli->close();
    echo "<p style='color: green;'><strong>Fix completed! The tbl_history table should now work correctly.</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
}
?> 