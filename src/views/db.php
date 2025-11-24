<?php

/**
 * Basic PDO bootstrap shared across views.
 * Update the fallback values to match your local DB or
 * provide DB_* environment variables in Apache/Nginx config.
 */
$host = getenv('DB_HOST') ?: '127.0.0.1';
$name = getenv('DB_NAME') ?: 'ecommerce';
$user = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '';

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $host, $name);

try {
    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $exception) {
    die('Connection error: ' . $exception->getMessage());
}