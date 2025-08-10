<?php
// Insert Login Data to Railway MySQL
// File: insert_login_data.php

echo "🔑 Inserting Login Data to Railway...\n\n";

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
    
    // Insert login data
    $loginData = [
        [
            'id_login' => 1,
            'anggota_id' => 'AG001',
            'user' => 'anang',
            'pass' => '202cb962ac59075b964b07152d234b70', // password: 123
            'level' => 'Petugas',
            'nama' => 'Anang',
            'tempat_lahir' => 'Bekasi',
            'tgl_lahir' => '1999-04-05',
            'jenkel' => 'Laki-Laki',
            'alamat' => 'Ujung Harapan',
            'telepon' => '089618173609',
            'email' => 'fauzan1892@codekop.com',
            'tgl_bergabung' => '2019-11-20',
            'foto' => 'user_1567327491.png'
        ],
        [
            'id_login' => 2,
            'anggota_id' => 'AG002',
            'user' => 'fauzan',
            'pass' => '202cb962ac59075b964b07152d234b70', // password: 123
            'level' => 'Anggota',
            'nama' => 'Fauzan',
            'tempat_lahir' => 'Bekasi',
            'tgl_lahir' => '1998-11-18',
            'jenkel' => 'Laki-Laki',
            'alamat' => 'Bekasi Barat',
            'telepon' => '08123123185',
            'email' => 'fauzanfalah21@gmail.com',
            'tgl_bergabung' => '2019-11-21',
            'foto' => 'user_1589911243.png'
        ]
    ];
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($loginData as $data) {
        try {
            $sql = "INSERT INTO tbl_login (id_login, anggota_id, user, pass, level, nama, tempat_lahir, tgl_lahir, jenkel, alamat, telepon, email, tgl_bergabung, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['id_login'],
                $data['anggota_id'],
                $data['user'],
                $data['pass'],
                $data['level'],
                $data['nama'],
                $data['tempat_lahir'],
                $data['tgl_lahir'],
                $data['jenkel'],
                $data['alamat'],
                $data['telepon'],
                $data['email'],
                $data['tgl_bergabung'],
                $data['foto']
            ]);
            
            $successCount++;
            echo "✅ Inserted user: {$data['user']} ({$data['level']})\n";
            
        } catch (PDOException $e) {
            $errorCount++;
            echo "❌ Error inserting {$data['user']}: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n🎯 Insert Summary:\n";
    echo "✅ Successful: $successCount\n";
    echo "❌ Errors: $errorCount\n";
    
    if ($successCount > 0) {
        echo "\n🎉 Login data inserted successfully!\n";
        echo "🔑 You can now login with:\n";
        echo "   Username: anang (Petugas)\n";
        echo "   Username: fauzan (Anggota)\n";
        echo "   Password: 123\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage() . "\n";
}

echo "\n✨ Script completed!\n";
?> 