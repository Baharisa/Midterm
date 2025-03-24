<?php

// Load Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Prefer .env.local for local testing, otherwise try .env
$envPath = file_exists(__DIR__ . '/../.env.local') ? '.env.local' : '.env';

// Only load dotenv if the file exists (on Render, it will not exist)
if (file_exists(__DIR__ . '/../' . $envPath)) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', $envPath);
    $dotenv->load();
}

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    public $conn;

    public function __construct() {
        // Set values from environment variables (from .env or Render dashboard)
        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'] ?? 5432; // fallback if not set (Render omits port)
        $this->dbname = $_ENV['DB_NAME'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASS'];
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}
