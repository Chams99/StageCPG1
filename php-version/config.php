<?php
// Load environment variables from .env file if it exists
if (file_exists('.env')) {
    $env = parse_ini_file('.env');
    foreach ($env as $key => $value) {
        $_ENV[$key] = $value;
    }
}

// Database configuration with environment variables
$host = $_ENV['DB_HOST'] ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? '5432';
$dbname = $_ENV['DB_NAME'] ?? 'stageurs_db';
$username = $_ENV['DB_USERNAME'] ?? 'postgres';
$password = $_ENV['DB_PASSWORD'] ?? 'cpg1';

// Create PDO connection
try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$username;password=$password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session for authentication
session_start();

// Helper functions
function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
?> 