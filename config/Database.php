<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Try to load environment variables if file exists (only in local dev)
$localEnvPath = __DIR__ . '/../.env.local';
$envPath = __DIR__ . '/../.env';

if (file_exists($localEnvPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', '.env.local');
    $dotenv->load();
} elseif (file_exists($envPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
}
// ✅ No error if no .env — Render uses environment tab for env vars

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    public $conn;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'] ?? 'localhost';
        $this->port = $_ENV['DB_PORT'] ?? '5432';
        $this->dbname = $_ENV['DB_NAME'] ?? 'quotesdb1';
        $this->username = $_ENV['DB_USER'] ?? 'postgres';
        $this->password = $_ENV['DB_PASS'] ?? '';
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode([
                "message" => "Database connection error",
                "error" => $e->getMessage()
            ]);
            exit;
        }

        return $this->conn;
    }
}
