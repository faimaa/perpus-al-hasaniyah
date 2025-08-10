<?php
/**
 * Railway Database Setup Script
 * Run this script to setup your database on Railway
 */

// Get database credentials from Railway environment variables
$db_host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: 'mysql.railway.internal';
$db_port = getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: '3306';
$db_name = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'railway';
$db_user = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root';
$db_pass = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: 'bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv';

echo "=== Railway Database Setup ===\n";
echo "Host: " . ($db_host ?: 'NOT SET') . "\n";
echo "Port: " . ($db_port ?: 'NOT SET') . "\n";
echo "Database: " . ($db_name ?: 'NOT SET') . "\n";
echo "User: " . ($db_user ?: 'NOT SET') . "\n";
echo "Password: " . ($db_pass ? 'SET' : 'NOT SET') . "\n\n";

if (!$db_host || !$db_name || !$db_user) {
    echo "❌ Error: Database credentials not found in environment variables!\n";
    echo "Please set the following environment variables in Railway:\n";
    echo "- MYSQLHOST or MYSQL_HOST\n";
    echo "- MYSQLDATABASE or MYSQL_DATABASE\n";
    echo "- MYSQLUSER or MYSQL_USER\n";
    echo "- MYSQLPASSWORD or MYSQL_PASSWORD\n";
    exit(1);
}

try {
    // Connect to database
    $pdo = new PDO(
        "mysql:host=$db_host;port=$db_port;charset=utf8",
        $db_user,
        $db_pass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to MySQL server successfully!\n";
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8 COLLATE utf8_general_ci");
    echo "✅ Database '$db_name' created/verified successfully!\n";
    
    // Select database
    $pdo->exec("USE `$db_name`");
    echo "✅ Database '$db_name' selected successfully!\n";
    
    // Import database schema
    $sql_file = 'projek_perpus.sql';
    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);
        
        // Split SQL by semicolon and execute each statement
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement);
                } catch (PDOException $e) {
                    // Skip errors for existing tables
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        echo "⚠️  Warning: " . $e->getMessage() . "\n";
                    }
                }
            }
        }
        echo "✅ Database schema imported successfully!\n";
    } else {
        echo "⚠️  Warning: SQL file '$sql_file' not found. Please import manually.\n";
    }
    
    echo "\n🎉 Railway Database Setup Complete!\n";
    echo "Your CodeIgniter application should now be able to connect to the database.\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
    echo "\nTroubleshooting:\n";
    echo "1. Check if MySQL service is running in Railway\n";
    echo "2. Verify database credentials in Railway environment variables\n";
    echo "3. Ensure MySQL port is accessible\n";
    exit(1);
}
?> 