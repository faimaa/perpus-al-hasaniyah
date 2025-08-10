<?php
// Test file untuk menguji koneksi database Railway
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Test Koneksi Database Railway</h2>";

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
        
        // Test data yang ada
        echo "<h3>Existing Data...</h3>";
        $result = $mysqli->query("SELECT * FROM tbl_buku LIMIT 5");
        if ($result) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            if ($result->num_rows > 0) {
                $first = true;
                while ($row = $result->fetch_assoc()) {
                    if ($first) {
                        echo "<tr>";
                        foreach ($row as $key => $value) {
                            echo "<th>" . $key . "</th>";
                        }
                        echo "</tr>";
                        $first = false;
                    }
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . (strlen($value) > 50 ? substr($value, 0, 50) . '...' : $value) . "</td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No data found</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: red;'>Error querying data: " . $mysqli->error . "</p>";
        }
        
        // Test kategori dan rak
        echo "<h3>Testing Kategori and Rak...</h3>";
        $result = $mysqli->query("SELECT COUNT(*) as total FROM tbl_kategori");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>Total kategori: " . $row['total'] . "</p>";
        }
        
        $result = $mysqli->query("SELECT COUNT(*) as total FROM tbl_rak");
        if ($result) {
            $row = $result->fetch_assoc();
            echo "<p>Total rak: " . $row['total'] . "</p>";
        }
        
        // Test insert sederhana
        echo "<h3>Testing Simple Insert...</h3>";
        $test_data = array(
            'buku_id' => 'BK999',
            'id_kategori' => 2,
            'id_rak' => 1,
            'isbn' => 'TEST-999',
            'title' => 'Test Book Debug',
            'pengarang' => 'Test Author',
            'penerbit' => 'Test Publisher',
            'thn_buku' => '2024',
            'isi' => 'Test content',
            'jml' => 1,
            'tgl_masuk' => date('Y-m-d H:i:s')
        );
        
        $sql = "INSERT INTO tbl_buku (buku_id, id_kategori, id_rak, isbn, title, pengarang, penerbit, thn_buku, isi, jml, tgl_masuk) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $mysqli->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("siisssssss", 
                $test_data['buku_id'],
                $test_data['id_kategori'],
                $test_data['id_rak'],
                $test_data['isbn'],
                $test_data['title'],
                $test_data['pengarang'],
                $test_data['penerbit'],
                $test_data['thn_buku'],
                $test_data['isi'],
                $test_data['jml'],
                $test_data['tgl_masuk']
            );
            
            if ($stmt->execute()) {
                echo "<p style='color: green;'>Test insert successful! Insert ID: " . $stmt->insert_id . "</p>";
                
                // Hapus data test
                $delete_sql = "DELETE FROM tbl_buku WHERE buku_id = 'BK999'";
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
?> 