<?php
// Debug file untuk menguji koneksi database dan proses tambah buku
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Debug Tambah Buku - Railway</h2>";

// Test koneksi database
try {
    $host = 'ballast.proxy.rlwy.net';
    $username = 'root';
    $password = 'bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv';
    $database = 'railway';
    $port = '15609';
    
    echo "<h3>Testing Database Connection...</h3>";
    
    $mysqli = new mysqli($host, $username, $password, $database, $port);
    
    if ($mysqli->connect_error) {
        echo "<p style='color: red;'>Connection failed: " . $mysqli->connect_error . "</p>";
    } else {
        echo "<p style='color: green;'>Database connection successful!</p>";
        
        // Test query sederhana
        $result = $mysqli->query("SELECT COUNT(*) as total FROM tbl_buku");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>Total buku dalam database: " . $row['total'] . "</p>";
        } else {
            echo "<p style='color: red;'>Error querying tbl_buku: " . $mysqli->error . "</p>";
        }
        
        // Test struktur tabel
        echo "<h3>Testing Table Structure...</h3>";
        $result = $mysqli->query("DESCRIBE tbl_buku");
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
        } else {
            echo "<p style='color: red;'>Error describing tbl_buku: " . $mysqli->error . "</p>";
        }
        
        // Test insert sederhana
        echo "<h3>Testing Simple Insert...</h3>";
        $test_data = array(
            'id_kategori' => 1,
            'id_rak' => 1,
            'isbn' => 'TEST-123',
            'judul_buku' => 'Test Book Debug',
            'pengarang' => 'Test Author',
            'penerbit' => 'Test Publisher',
            'thn_buku' => '2024',
            'isi' => 'Test content',
            'jml' => 1,
            'status' => 'Tersedia',
            'tgl_masuk' => date('Y-m-d H:i:s')
        );
        
        $sql = "INSERT INTO tbl_buku (id_kategori, id_rak, isbn, judul_buku, pengarang, penerbit, thn_buku, isi, jml, status, tgl_masuk) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("iissssssss", 
                $test_data['id_kategori'],
                $test_data['id_rak'],
                $test_data['isbn'],
                $test_data['judul_buku'],
                $test_data['pengarang'],
                $test_data['penerbit'],
                $test_data['thn_buku'],
                $test_data['isi'],
                $test_data['jml'],
                $test_data['status'],
                $test_data['tgl_masuk']
            );
            
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Test insert successful! Insert ID: " . $stmt->insert_id . "</p>";
                
                // Hapus data test
                $delete_sql = "DELETE FROM tbl_buku WHERE isbn = 'TEST-123'";
                if ($mysqli->query($delete_sql)) {
                    echo "<p style='color: green;'>Test data cleaned up successfully</p>";
                } else {
                    echo "<p style='color: orange;'>Warning: Could not clean up test data: " . $mysqli->error . "</p>";
                }
            } else {
                echo "<p style='color: red;'>Test insert failed: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: red;'>Error preparing statement: " . $mysqli->error . "</p>";
        }
        
        $mysqli->close();
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
}

// Test environment variables
echo "<h3>Environment Information...</h3>";
echo "<p>HTTP_HOST: " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'Not set') . "</p>";
echo "<p>Is Railway: " . (isset($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'railway') !== false ? 'Yes' : 'No') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>MySQL Extension: " . (extension_loaded('mysqli') ? 'Loaded' : 'Not loaded') . "</p>";

// Test file permissions
echo "<h3>File Permissions Test...</h3>";
$test_paths = array(
    '/tmp/',
    './assets_style/image/buku/',
    './application/logs/'
);

foreach ($test_paths as $path) {
    if (is_dir($path)) {
        echo "<p>$path: Directory exists</p>";
        if (is_writable($path)) {
            echo "<p style='color: green;'>$path: Writable</p>";
        } else {
            echo "<p style='color: red;'>$path: Not writable</p>";
        }
    } else {
        echo "<p style='color: orange;'>$path: Directory does not exist</p>";
    }
}
?> 