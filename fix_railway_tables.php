<?php
// File untuk memperbaiki struktur tabel database Railway
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Fix Railway Database Tables</h2>";

// Test koneksi database
try {
    $host = 'ballast.proxy.rlwy.net';
    $username = 'root';
    $password = 'bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv';
    $database = 'railway';
    $port = '15609';
    
    echo "<h3>Connecting to Database...</h3>";
    
    $mysqli = new mysqli($host, $username, $password, $database, $port);
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>Connection failed: " . $mysqli->error . "</p>";
        exit;
    } else {
        echo "<p style='color: green;'>Database connection successful!</p>";
        
        // Check if tbl_buku exists and has correct structure
        echo "<h3>Checking tbl_buku structure...</h3>";
        $result = $mysqli->query("SHOW TABLES LIKE 'tbl_buku'");
        if ($result->num_rows == 0) {
            echo "<p style='color: red;'>Table tbl_buku does not exist!</p>";
            
            // Create table
            $create_sql = "CREATE TABLE `tbl_buku` (
                `id_buku` int(11) NOT NULL AUTO_INCREMENT,
                `buku_id` varchar(255) NOT NULL,
                `id_kategori` int(11) NOT NULL,
                `id_rak` int(11) NOT NULL,
                `sampul` varchar(255) DEFAULT NULL,
                `isbn` varchar(255) DEFAULT NULL,
                `lampiran` varchar(255) DEFAULT NULL,
                `title` varchar(255) DEFAULT NULL,
                `penerbit` varchar(255) DEFAULT NULL,
                `pengarang` varchar(255) DEFAULT NULL,
                `thn_buku` varchar(255) DEFAULT NULL,
                `isi` text DEFAULT NULL,
                `jml` int(11) DEFAULT NULL,
                `tgl_masuk` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id_buku`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            if ($mysqli->query($create_sql)) {
                echo "<p style='color: green;'>Table tbl_buku created successfully!</p>";
            } else {
                echo "<p style='color: red;'>Error creating table: " . $mysqli->error . "</p>";
            }
        } else {
            echo "<p style='color: green;'>Table tbl_buku exists</p>";
            
            // Check if required columns exist
            $result = $mysqli->query("DESCRIBE tbl_buku");
            $columns = array();
            while ($row = $result->fetch_assoc()) {
                $columns[] = $row['Field'];
            }
            
            $required_columns = array('id_buku', 'buku_id', 'id_kategori', 'id_rak', 'title', 'pengarang', 'penerbit', 'thn_buku', 'isi', 'jml', 'tgl_masuk');
            $missing_columns = array_diff($required_columns, $columns);
            
            if (!empty($missing_columns)) {
                echo "<p style='color: orange;'>Missing columns: " . implode(', ', $missing_columns) . "</p>";
                
                // Add missing columns
                foreach ($missing_columns as $column) {
                    $alter_sql = "";
                    switch ($column) {
                        case 'id_buku':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN id_buku int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
                            break;
                        case 'buku_id':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN buku_id varchar(255) NOT NULL AFTER id_buku";
                            break;
                        case 'title':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN title varchar(255) DEFAULT NULL AFTER lampiran";
                            break;
                        case 'pengarang':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN pengarang varchar(255) DEFAULT NULL AFTER title";
                            break;
                        case 'penerbit':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN penerbit varchar(255) DEFAULT NULL AFTER pengarang";
                            break;
                        case 'thn_buku':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN thn_buku varchar(255) DEFAULT NULL AFTER penerbit";
                            break;
                        case 'isi':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN isi text DEFAULT NULL AFTER thn_buku";
                            break;
                        case 'jml':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN jml int(11) DEFAULT NULL AFTER isi";
                            break;
                        case 'tgl_masuk':
                            $alter_sql = "ALTER TABLE tbl_buku ADD COLUMN tgl_masuk varchar(255) DEFAULT NULL AFTER jml";
                            break;
                    }
                    
                    if ($alter_sql) {
                        if ($mysqli->query($alter_sql)) {
                            echo "<p style='color: green;'>Column $column added successfully</p>";
                        } else {
                            echo "<p style='color: red;'>Error adding column $column: " . $mysqli->error . "</p>";
                        }
                    }
                }
            } else {
                echo "<p style='color: green;'>All required columns exist</p>";
            }
        }
        
        // Check if tbl_kategori exists
        echo "<h3>Checking tbl_kategori...</h3>";
        $result = $mysqli->query("SHOW TABLES LIKE 'tbl_kategori'");
        if ($result->num_rows == 0) {
            echo "<p style='color: red;'>Table tbl_kategori does not exist!</p>";
            
            $create_sql = "CREATE TABLE `tbl_kategori` (
                `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
                `nama_kategori` varchar(255) NOT NULL,
                PRIMARY KEY (`id_kategori`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            if ($mysqli->query($create_sql)) {
                echo "<p style='color: green;'>Table tbl_kategori created successfully!</p>";
                
                // Insert sample data
                $insert_sql = "INSERT INTO tbl_kategori (nama_kategori) VALUES ('Pemrograman'), ('Novel'), ('Pendidikan')";
                if ($mysqli->query($insert_sql)) {
                    echo "<p style='color: green;'>Sample kategori data inserted</p>";
                }
            }
        } else {
            echo "<p style='color: green;'>Table tbl_kategori exists</p>";
        }
        
        // Check if tbl_rak exists
        echo "<h3>Checking tbl_rak...</h3>";
        $result = $mysqli->query("SHOW TABLES LIKE 'tbl_rak'");
        if ($result->num_rows == 0) {
            echo "<p style='color: red;'>Table tbl_rak does not exist!</p>";
            
            $create_sql = "CREATE TABLE `tbl_rak` (
                `id_rak` int(11) NOT NULL AUTO_INCREMENT,
                `nama_rak` varchar(255) NOT NULL,
                PRIMARY KEY (`id_rak`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            if ($mysqli->query($create_sql)) {
                echo "<p style='color: green;'>Table tbl_rak created successfully!</p>";
                
                // Insert sample data
                $insert_sql = "INSERT INTO tbl_rak (nama_rak) VALUES ('Rak Buku 1'), ('Rak Buku 2'), ('Rak Buku 3')";
                if ($mysqli->query($insert_sql)) {
                    echo "<p style='color: green;'>Sample rak data inserted</p>";
                }
            }
        } else {
            echo "<p style='color: green;'>Table tbl_rak exists</p>";
        }
        
        // CRITICAL: Check and fix tbl_history structure
        echo "<h3>Checking tbl_history structure...</h3>";
        $result = $mysqli->query("SHOW TABLES LIKE 'tbl_history'");
        if ($result->num_rows == 0) {
            echo "<p style='color: red;'>Table tbl_history does not exist!</p>";
            
            // Create table with correct structure
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
            }
        } else {
            echo "<p style='color: green;'>Table tbl_history exists</p>";
            
            // Check if anggota_id column allows NULL
            $result = $mysqli->query("DESCRIBE tbl_history");
            $columns = array();
            while ($row = $result->fetch_assoc()) {
                $columns[$row['Field']] = $row;
            }
            
            if (isset($columns['anggota_id'])) {
                if ($columns['anggota_id']['Null'] == 'NO') {
                    echo "<p style='color: red;'>Column anggota_id does not allow NULL - this will cause errors!</p>";
                    
                    // Fix the column to allow NULL
                    $alter_sql = "ALTER TABLE tbl_history MODIFY COLUMN `anggota_id` int(11) DEFAULT NULL";
                    if ($mysqli->query($alter_sql)) {
                        echo "<p style='color: green;'>Column anggota_id fixed to allow NULL</p>";
                    } else {
                        echo "<p style='color: red;'>Error fixing anggota_id column: " . $mysqli->error . "</p>";
                    }
                } else {
                    echo "<p style='color: green;'>Column anggota_id allows NULL (correct)</p>";
                }
            } else {
                echo "<p style='color: red;'>Column anggota_id does not exist!</p>";
                
                // Add the column
                $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `anggota_id` int(11) DEFAULT NULL AFTER `buku_id`";
                if ($mysqli->query($alter_sql)) {
                    echo "<p style='color: green;'>Column anggota_id added successfully</p>";
                } else {
                    echo "<p style='color: red;'>Error adding anggota_id column: " . $mysqli->error . "</p>";
                }
            }
            
            // Check other required columns
            $required_columns = array('id', 'tipe_transaksi', 'kode_transaksi', 'buku_id', 'petugas_id', 'jumlah', 'tanggal');
            foreach ($required_columns as $column) {
                if (!isset($columns[$column])) {
                    echo "<p style='color: red;'>Missing required column: $column</p>";
                    
                    // Add missing column based on type
                    $alter_sql = "";
                    switch ($column) {
                        case 'id':
                            $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
                            break;
                        case 'tipe_transaksi':
                            $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `tipe_transaksi` enum('Peminjaman','Pengembalian','Buku Rusak','Perbaikan Buku','Buku Hilang','Mengganti Buku Baru') NOT NULL AFTER `id`";
                            break;
                        case 'kode_transaksi':
                            $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `kode_transaksi` varchar(50) DEFAULT NULL AFTER `tipe_transaksi`";
                            break;
                        case 'buku_id':
                            $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `buku_id` int(11) DEFAULT NULL AFTER `kode_transaksi`";
                            break;
                        case 'petugas_id':
                            $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `petugas_id` int(11) DEFAULT NULL AFTER `anggota_id`";
                            break;
                        case 'jumlah':
                            $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `jumlah` int(11) NOT NULL DEFAULT 1 AFTER `petugas_id`";
                            break;
                        case 'tanggal':
                            $alter_sql = "ALTER TABLE tbl_history ADD COLUMN `tanggal` datetime DEFAULT CURRENT_TIMESTAMP AFTER `keterangan`";
                            break;
                    }
                    
                    if ($alter_sql) {
                        if ($mysqli->query($alter_sql)) {
                            echo "<p style='color: green;'>Column $column added successfully</p>";
                        } else {
                            echo "<p style='color: red;'>Error adding column $column: " . $mysqli->error . "</p>";
                        }
                    }
                }
            }
        }
        
        // Final structure check
        echo "<h3>Final Table Structure...</h3>";
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
        echo "<p style='color: green;'>Database check completed!</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
}
?> 