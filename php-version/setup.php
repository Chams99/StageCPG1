<?php
require_once 'config.php';

echo "<h2>StageursApp PHP Setup</h2>";

// Check if admin exists
$stmt = $pdo->query("SELECT COUNT(*) as count FROM admins");
$admin_count = $stmt->fetch()['count'];

if ($admin_count == 0) {
    // Create default admin using environment variables
    $username = $_ENV['ADMIN_USERNAME'] ?? 'admin';
    $password = $_ENV['ADMIN_PASSWORD'] ?? 'admin123';
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO admins (username, password, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$username, $hashed_password]);
    
    echo "<div style='color: green;'>✅ Default admin created successfully!</div>";
    echo "<p><strong>Username:</strong> $username</p>";
    echo "<p><strong>Password:</strong> $password</p>";
    echo "<p><a href='login.php'>Go to Login</a></p>";
} else {
    echo "<div style='color: blue;'>ℹ️ Admin user already exists.</div>";
    echo "<p><a href='login.php'>Go to Login</a></p>";
}

echo "<hr>";
echo "<h3>Database Tables Check:</h3>";

// Check if tables exist
$tables = ['etudiants', 'faculties', 'sections', 'encadreurs', 'admins'];
foreach ($tables as $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $stmt->fetch()['count'];
        echo "<div style='color: green;'>✅ Table '$table' exists ($count records)</div>";
    } catch (Exception $e) {
        echo "<div style='color: red;'>❌ Table '$table' missing or error: " . $e->getMessage() . "</div>";
    }
}

echo "<hr>";
echo "<p><a href='index.php'>Go to Home Page</a></p>";
?> 