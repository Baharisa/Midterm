<?php

// Load Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Prefer .env.local if it exists (for local testing)
$envPath = file_exists(__DIR__ . '/../.env.local') ? '.env.local' : '.env';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', $envPath);
$dotenv->load();

class Database {
    private $host;
    private $port;
    private $dbname;
    private $username;
    private $password;

    public $conn;

    public function __construct() {
        $this->host = $_ENV['DB_HOST'];
        $this->port = $_ENV['DB_PORT'] ?? '5432'; // fallback to 5432
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
            echo json_encode(['error' => 'Connection error: ' . $e->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
